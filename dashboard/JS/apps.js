/**
 * ═══════════════════════════════════════════════════════════════════════════
 * APPS PAGE JAVASCRIPT
 * Rabbit Notification Service — Connected Apps Management
 * ═══════════════════════════════════════════════════════════════════════════
 */

(function() {
    'use strict';

    /* ─────────────────────────────────────────────────────────────────
       API Endpoints
       ───────────────────────────────────────────────────────────────── */
    const API = {
        getApps: '/rabbit/API/read/apps.php',
        createApp: '/rabbit/API/create/app.php',
        updateApp: '/rabbit/API/update/app.php',
        deleteApp: '/rabbit/API/delete/app.php',
        clearNotifications: '/rabbit/API/delete/app-notifications.php'
    };

    /* ─────────────────────────────────────────────────────────────────
       State Management
       ───────────────────────────────────────────────────────────────── */
    const State = {
        apps: [],
        filteredApps: [],
        searchQuery: '',
        currentSort: 'notifications',
        editingApp: null,
        pendingAction: null,
        isLoading: false
    };

    /* ─────────────────────────────────────────────────────────────────
       DOM Elements
       ───────────────────────────────────────────────────────────────── */
    let DOM = {};

    function initDOM() {
        DOM = {
            // Counts
            appCount: document.getElementById('appCount'),
            
            // Search
            searchInput: document.getElementById('searchInput'),
            searchClear: document.getElementById('searchClear'),
            
            // Sort
            sortTrigger: document.getElementById('sortTrigger'),
            sortLabel: document.getElementById('sortLabel'),
            sortMenu: document.getElementById('sortMenu'),
            
            // Grid
            appsGrid: document.getElementById('appsGrid'),
            emptyState: document.getElementById('emptyState'),
            emptyStateText: document.getElementById('emptyStateText'),
            emptyAddBtn: document.getElementById('emptyAddBtn'),
            loadingState: document.getElementById('loadingState'),
            
            // Add button
            addAppBtn: document.getElementById('addAppBtn'),
            
            // App Modal
            appModal: document.getElementById('appModal'),
            modalTitle: document.getElementById('modalTitle'),
            modalClose: document.getElementById('modalClose'),
            modalCancelBtn: document.getElementById('modalCancelBtn'),
            modalSaveBtn: document.getElementById('modalSaveBtn'),
            saveBtnText: document.getElementById('saveBtnText'),
            appForm: document.getElementById('appForm'),
            
            // Form fields
            iconPreview: document.getElementById('iconPreview'),
            iconLetter: document.getElementById('iconLetter'),
            colorPicker: document.getElementById('colorPicker'),
            appName: document.getElementById('appName'),
            appSlug: document.getElementById('appSlug'),
            appWebhook: document.getElementById('appWebhook'),
            copyWebhookBtn: document.getElementById('copyWebhookBtn'),
            appDescription: document.getElementById('appDescription'),
            
            // Edit mode sections
            appStats: document.getElementById('appStats'),
            statNotifications: document.getElementById('statNotifications'),
            statConnected: document.getElementById('statConnected'),
            statLastUsed: document.getElementById('statLastUsed'),
            dangerZone: document.getElementById('dangerZone'),
            clearNotificationsBtn: document.getElementById('clearNotificationsBtn'),
            disconnectAppBtn: document.getElementById('disconnectAppBtn'),
            
            // Confirmation Modal
            confirmModal: document.getElementById('confirmModal'),
            confirmTitle: document.getElementById('confirmTitle'),
            confirmClose: document.getElementById('confirmClose'),
            confirmIcon: document.getElementById('confirmIcon'),
            confirmMessage: document.getElementById('confirmMessage'),
            confirmWarning: document.getElementById('confirmWarning'),
            confirmCancelBtn: document.getElementById('confirmCancelBtn'),
            confirmActionBtn: document.getElementById('confirmActionBtn'),
            confirmActionText: document.getElementById('confirmActionText'),
            
            // Toast
            toastContainer: document.getElementById('toastContainer')
        };
    }

    /* ─────────────────────────────────────────────────────────────────
       Utility Functions
       ───────────────────────────────────────────────────────────────── */
    function formatTimeAgo(dateString) {
        if (!dateString) return 'Never';
        
        const timestamp = new Date(dateString).getTime();
        const seconds = Math.floor((Date.now() - timestamp) / 1000);
        
        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
        if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
        if (seconds < 172800) return 'Yesterday';
        if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;
        if (seconds < 2592000) return `${Math.floor(seconds / 604800)}w ago`;
        
        return new Date(timestamp).toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric',
            year: 'numeric'
        });
    }

    function formatDate(dateString) {
        if (!dateString) return '—';
        return new Date(dateString).toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric',
            year: 'numeric'
        });
    }

    function slugify(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function debounce(fn, delay) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    function getWebhookUrl(slug) {
        return `https://api.rabbit.io/webhook/${slug}`;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /* ─────────────────────────────────────────────────────────────────
       Toast Notifications
       ───────────────────────────────────────────────────────────────── */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        
        const iconPath = type === 'success' 
            ? '<polyline points="20 6 9 17 4 12"/>'
            : '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
        
        toast.innerHTML = `
            <svg class="toast__icon" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                ${iconPath}
            </svg>
            <span>${escapeHtml(message)}</span>
        `;
        
        DOM.toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('is-leaving');
            setTimeout(() => toast.remove(), 200);
        }, 3000);
    }

    /* ─────────────────────────────────────────────────────────────────
       API Functions
       ───────────────────────────────────────────────────────────────── */
    async function fetchApps() {
        try {
            State.isLoading = true;
            DOM.loadingState.style.display = 'flex';
            
            const response = await fetch(`${API.getApps}?limit=50`, {
                credentials: 'include'
            });
            
            const result = await response.json();
            
            if (result.success) {
                State.apps = result.data.apps;
                filterAndSortApps();
            } else {
                showToast(result.message || 'Failed to load apps', 'error');
            }
        } catch (error) {
            console.error('Failed to fetch apps:', error);
            showToast('Failed to load apps', 'error');
        } finally {
            State.isLoading = false;
            DOM.loadingState.style.display = 'none';
        }
    }

    async function createApp(appData) {
        try {
            DOM.modalSaveBtn.disabled = true;
            DOM.saveBtnText.textContent = 'Creating...';
            
            const response = await fetch(API.createApp, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(appData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                State.apps.unshift(result.data.app);
                filterAndSortApps();
                closeAppModal();
                showToast('App created successfully');
            } else {
                showToast(result.message || 'Failed to create app', 'error');
            }
        } catch (error) {
            console.error('Failed to create app:', error);
            showToast('Failed to create app', 'error');
        } finally {
            DOM.modalSaveBtn.disabled = false;
            DOM.saveBtnText.textContent = 'Add App';
        }
    }

    async function updateApp(appId, appData) {
        try {
            DOM.modalSaveBtn.disabled = true;
            DOM.saveBtnText.textContent = 'Saving...';
            
            const response = await fetch(API.updateApp, {
                method: 'PUT',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    app_id: appId,
                    ...appData
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Update local state
                const index = State.apps.findIndex(a => a.id === appId);
                if (index !== -1) {
                    State.apps[index] = result.data.app;
                }
                filterAndSortApps();
                closeAppModal();
                showToast('App updated successfully');
            } else {
                showToast(result.message || 'Failed to update app', 'error');
            }
        } catch (error) {
            console.error('Failed to update app:', error);
            showToast('Failed to update app', 'error');
        } finally {
            DOM.modalSaveBtn.disabled = false;
            DOM.saveBtnText.textContent = 'Save Changes';
        }
    }

    async function deleteApp(appId) {
        try {
            const response = await fetch(`${API.deleteApp}?app_id=${appId}`, {
                method: 'DELETE',
                credentials: 'include'
            });
            
            const result = await response.json();
            
            if (result.success) {
                State.apps = State.apps.filter(a => a.id !== appId);
                filterAndSortApps();
                closeConfirmModal();
                closeAppModal();
                showToast('App disconnected successfully');
            } else {
                showToast(result.message || 'Failed to delete app', 'error');
            }
        } catch (error) {
            console.error('Failed to delete app:', error);
            showToast('Failed to delete app', 'error');
        }
    }

    async function clearAppNotifications(appId) {
        try {
            const response = await fetch(`${API.clearNotifications}?app_id=${appId}`, {
                method: 'DELETE',
                credentials: 'include'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Update local state
                const app = State.apps.find(a => a.id === appId);
                if (app) {
                    app.notification_count = 0;
                }
                DOM.statNotifications.textContent = '0';
                filterAndSortApps();
                closeConfirmModal();
                showToast(`Cleared ${result.data.deleted_count} notifications`);
            } else {
                showToast(result.message || 'Failed to clear notifications', 'error');
            }
        } catch (error) {
            console.error('Failed to clear notifications:', error);
            showToast('Failed to clear notifications', 'error');
        }
    }

    /* ─────────────────────────────────────────────────────────────────
       Filtering & Sorting
       ───────────────────────────────────────────────────────────────── */
    function filterAndSortApps() {
        let filtered = [...State.apps];
        
        // Filter by search query
        if (State.searchQuery) {
            const query = State.searchQuery.toLowerCase();
            filtered = filtered.filter(app => 
                app.name.toLowerCase().includes(query) ||
                app.slug.toLowerCase().includes(query) ||
                (app.description && app.description.toLowerCase().includes(query))
            );
        }
        
        // Sort
        switch (State.currentSort) {
            case 'notifications':
                filtered.sort((a, b) => b.notification_count - a.notification_count);
                break;
            case 'recent':
                filtered.sort((a, b) => {
                    const aTime = a.last_notification_at ? new Date(a.last_notification_at).getTime() : 0;
                    const bTime = b.last_notification_at ? new Date(b.last_notification_at).getTime() : 0;
                    return bTime - aTime;
                });
                break;
            case 'name':
                filtered.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'oldest':
                filtered.sort((a, b) => {
                    const aTime = new Date(a.created_at).getTime();
                    const bTime = new Date(b.created_at).getTime();
                    return aTime - bTime;
                });
                break;
        }
        
        State.filteredApps = filtered;
        renderApps();
        updateCounts();
    }

    function updateCounts() {
        DOM.appCount.textContent = State.apps.length;
    }

    /* ─────────────────────────────────────────────────────────────────
       Rendering
       ───────────────────────────────────────────────────────────────── */
    function renderApps() {
        // Hide loading
        DOM.loadingState.style.display = 'none';
        
        // Check empty state
        if (State.filteredApps.length === 0) {
            DOM.appsGrid.innerHTML = '';
            DOM.emptyState.hidden = false;
            
            if (State.searchQuery) {
                DOM.emptyStateText.textContent = `No apps found matching "${State.searchQuery}"`;
                DOM.emptyAddBtn.style.display = 'none';
            } else {
                DOM.emptyStateText.textContent = 'Connect your first app to start receiving notifications';
                DOM.emptyAddBtn.style.display = '';
            }
            return;
        }
        
        DOM.emptyState.hidden = true;
        
        // Render app cards
        let html = '';
        State.filteredApps.forEach(app => {
            const letter = app.name.charAt(0).toUpperCase();
            const statusClass = app.is_active ? '' : 'app-card__status--inactive';
            const lastActivity = app.last_notification_at ? formatTimeAgo(app.last_notification_at) : 'No activity';
            
            html += `
                <article class="app-card" data-app-id="${app.id}" style="--app-color: ${app.color || '#5C6B55'}">
                    <div class="app-card__status ${statusClass}" title="${app.is_active ? 'Active' : 'Inactive'}"></div>
                    <div class="app-card__header">
                        <div class="app-card__icon" style="background: ${app.color || '#5C6B55'}">${letter}</div>
                        <div class="app-card__info">
                            <h3 class="app-card__name">${escapeHtml(app.name)}</h3>
                            <span class="app-card__slug">${escapeHtml(app.slug)}</span>
                        </div>
                    </div>
                    ${app.description ? `<p class="app-card__description">${escapeHtml(app.description)}</p>` : ''}
                    <div class="app-card__stats">
                        <div class="app-card__stat">
                            <span class="app-card__stat-value">${(app.notification_count || 0).toLocaleString()}</span>
                            <span class="app-card__stat-label">Notifications</span>
                        </div>
                        <div class="app-card__stat">
                            <span class="app-card__stat-value">${lastActivity}</span>
                            <span class="app-card__stat-label">Last Activity</span>
                        </div>
                    </div>
                </article>
            `;
        });
        
        DOM.appsGrid.innerHTML = html;
        
        // Add click handlers to cards
        DOM.appsGrid.querySelectorAll('.app-card').forEach(card => {
            card.addEventListener('click', () => {
                const appId = card.dataset.appId;
                const app = State.apps.find(a => a.id === appId);
                if (app) {
                    openEditModal(app);
                }
            });
        });
    }

    /* ─────────────────────────────────────────────────────────────────
       Modal Management
       ───────────────────────────────────────────────────────────────── */
    function openAddModal() {
        State.editingApp = null;
        
        // Reset form
        DOM.appForm.reset();
        DOM.modalTitle.textContent = 'Add New App';
        DOM.saveBtnText.textContent = 'Add App';
        
        // Reset icon preview
        DOM.iconLetter.textContent = 'A';
        DOM.iconPreview.style.setProperty('--preview-color', '#635BFF');
        
        // Reset color picker
        DOM.colorPicker.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.classList.toggle('is-selected', swatch.dataset.color === '#635BFF');
        });
        
        // Hide edit-only sections
        DOM.appStats.hidden = true;
        DOM.dangerZone.hidden = true;
        
        // Generate webhook URL placeholder
        DOM.appWebhook.value = 'Will be generated after saving';
        
        // Make slug editable
        DOM.appSlug.readOnly = false;
        DOM.appSlug.dataset.userEdited = '';
        
        DOM.appModal.classList.add('is-open');
        DOM.appName.focus();
    }

    function openEditModal(app) {
        State.editingApp = app;
        
        // Populate form
        DOM.modalTitle.textContent = 'Edit App';
        DOM.saveBtnText.textContent = 'Save Changes';
        
        DOM.appName.value = app.name;
        DOM.appSlug.value = app.slug;
        DOM.appSlug.readOnly = true; // Slug cannot be changed after creation
        DOM.appWebhook.value = getWebhookUrl(app.slug);
        DOM.appDescription.value = app.description || '';
        
        // Set icon preview
        DOM.iconLetter.textContent = app.name.charAt(0).toUpperCase();
        DOM.iconPreview.style.setProperty('--preview-color', app.color || '#5C6B55');
        
        // Set color picker
        const appColor = app.color || '#5C6B55';
        DOM.colorPicker.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.classList.toggle('is-selected', swatch.dataset.color === appColor);
        });
        
        // Populate stats
        DOM.statNotifications.textContent = (app.notification_count || 0).toLocaleString();
        DOM.statConnected.textContent = formatDate(app.created_at);
        DOM.statLastUsed.textContent = app.last_notification_at ? formatTimeAgo(app.last_notification_at) : 'Never';
        
        // Show edit-only sections
        DOM.appStats.hidden = false;
        DOM.dangerZone.hidden = false;
        
        DOM.appModal.classList.add('is-open');
    }

    function closeAppModal() {
        DOM.appModal.classList.remove('is-open');
        State.editingApp = null;
    }

    function openConfirmModal(config) {
        State.pendingAction = config.action;
        
        DOM.confirmTitle.textContent = config.title || 'Confirm Action';
        DOM.confirmMessage.textContent = config.message;
        DOM.confirmWarning.textContent = config.warning || 'This action cannot be undone.';
        DOM.confirmActionText.textContent = config.actionText || 'Confirm';
        
        // Update button style
        DOM.confirmActionBtn.className = 'btn btn--danger';
        
        DOM.confirmModal.classList.add('is-open');
    }

    function closeConfirmModal() {
        DOM.confirmModal.classList.remove('is-open');
        State.pendingAction = null;
    }

    /* ─────────────────────────────────────────────────────────────────
       Form Handling
       ───────────────────────────────────────────────────────────────── */
    function handleFormSubmit(e) {
        e.preventDefault();
        
        const name = DOM.appName.value.trim();
        const slug = DOM.appSlug.value.trim();
        const description = DOM.appDescription.value.trim();
        const color = DOM.colorPicker.querySelector('.color-swatch.is-selected')?.dataset.color || '#635BFF';
        
        // Validation
        if (!name) {
            showToast('Please enter an app name', 'error');
            DOM.appName.focus();
            return;
        }
        
        if (!slug) {
            showToast('Please enter an app identifier', 'error');
            DOM.appSlug.focus();
            return;
        }
        
        // Check for duplicate slug (only for new apps)
        if (!State.editingApp) {
            const existingApp = State.apps.find(a => a.slug === slug);
            if (existingApp) {
                showToast('An app with this identifier already exists', 'error');
                DOM.appSlug.focus();
                return;
            }
            
            // Create new app
            createApp({ name, slug, description, color });
        } else {
            // Update existing app
            updateApp(State.editingApp.id, { name, description, color });
        }
    }

    function handleClearNotifications() {
        if (!State.editingApp) return;
        
        openConfirmModal({
            title: 'Clear Notifications',
            message: `Are you sure you want to delete all notifications from ${State.editingApp.name}?`,
            warning: 'This will permanently remove all notification history for this app.',
            actionText: 'Clear All',
            action: () => {
                clearAppNotifications(State.editingApp.id);
            }
        });
    }

    function handleDisconnectApp() {
        if (!State.editingApp) return;
        
        openConfirmModal({
            title: 'Disconnect App',
            message: `Are you sure you want to disconnect ${State.editingApp.name}?`,
            warning: 'This will permanently remove this app and all its notification history.',
            actionText: 'Disconnect',
            action: () => {
                deleteApp(State.editingApp.id);
            }
        });
    }

    function copyWebhookUrl() {
        const url = DOM.appWebhook.value;
        if (url && url !== 'Will be generated after saving') {
            navigator.clipboard.writeText(url).then(() => {
                showToast('Webhook URL copied to clipboard');
            }).catch(() => {
                showToast('Failed to copy URL', 'error');
            });
        }
    }

    /* ─────────────────────────────────────────────────────────────────
       Event Listeners
       ───────────────────────────────────────────────────────────────── */
    function initEventListeners() {
        // Search
        DOM.searchInput.addEventListener('input', debounce((e) => {
            State.searchQuery = e.target.value.trim();
            DOM.searchClear.hidden = !State.searchQuery;
            filterAndSortApps();
        }, 300));
        
        DOM.searchClear.addEventListener('click', () => {
            DOM.searchInput.value = '';
            State.searchQuery = '';
            DOM.searchClear.hidden = true;
            filterAndSortApps();
        });
        
        // Sort dropdown
        DOM.sortTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            DOM.sortTrigger.parentElement.classList.toggle('is-open');
        });
        
        DOM.sortMenu.querySelectorAll('.filter-dropdown__item').forEach(item => {
            item.addEventListener('click', () => {
                State.currentSort = item.dataset.sort;
                DOM.sortLabel.textContent = item.textContent.trim();
                
                DOM.sortMenu.querySelectorAll('.filter-dropdown__item').forEach(i => i.classList.remove('is-active'));
                item.classList.add('is-active');
                
                DOM.sortTrigger.parentElement.classList.remove('is-open');
                filterAndSortApps();
            });
        });
        
        // Close dropdown on outside click
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('is-open'));
            }
        });
        
        // Add app buttons
        DOM.addAppBtn.addEventListener('click', openAddModal);
        DOM.emptyAddBtn.addEventListener('click', openAddModal);
        
        // App modal
        DOM.modalClose.addEventListener('click', closeAppModal);
        DOM.modalCancelBtn.addEventListener('click', closeAppModal);
        DOM.appModal.querySelector('.modal__backdrop').addEventListener('click', closeAppModal);
        
        // Form submission
        DOM.appForm.addEventListener('submit', handleFormSubmit);
        
        // Name input - auto-generate slug and update preview
        DOM.appName.addEventListener('input', (e) => {
            const name = e.target.value;
            DOM.iconLetter.textContent = name ? name.charAt(0).toUpperCase() : 'A';
            
            // Auto-generate slug only for new apps
            if (!State.editingApp && !DOM.appSlug.dataset.userEdited) {
                DOM.appSlug.value = slugify(name);
            }
        });
        
        // Track if user manually edited slug
        DOM.appSlug.addEventListener('input', () => {
            DOM.appSlug.dataset.userEdited = 'true';
        });
        
        // Color picker
        DOM.colorPicker.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.addEventListener('click', () => {
                DOM.colorPicker.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('is-selected'));
                swatch.classList.add('is-selected');
                DOM.iconPreview.style.setProperty('--preview-color', swatch.dataset.color);
            });
        });
        
        // Copy webhook URL
        DOM.copyWebhookBtn.addEventListener('click', copyWebhookUrl);
        
        // Danger zone actions
        DOM.clearNotificationsBtn.addEventListener('click', handleClearNotifications);
        DOM.disconnectAppBtn.addEventListener('click', handleDisconnectApp);
        
        // Confirmation modal
        DOM.confirmClose.addEventListener('click', closeConfirmModal);
        DOM.confirmCancelBtn.addEventListener('click', closeConfirmModal);
        DOM.confirmModal.querySelector('.modal__backdrop').addEventListener('click', closeConfirmModal);
        
        DOM.confirmActionBtn.addEventListener('click', () => {
            if (State.pendingAction) {
                State.pendingAction();
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (DOM.confirmModal.classList.contains('is-open')) {
                    closeConfirmModal();
                } else if (DOM.appModal.classList.contains('is-open')) {
                    closeAppModal();
                }
            }
        });
    }

    /* ─────────────────────────────────────────────────────────────────
       Initialize
       ───────────────────────────────────────────────────────────────── */
    function init() {
        initDOM();
        initEventListeners();
        fetchApps();
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
