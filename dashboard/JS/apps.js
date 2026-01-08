/**
 * ═══════════════════════════════════════════════════════════════════════════
 * APPS PAGE JAVASCRIPT
 * Rabbit Notification Service — Connected Apps Management
 * ═══════════════════════════════════════════════════════════════════════════
 */

(function() {
    'use strict';

    /* ─────────────────────────────────────────────────────────────────
       Mock Data
       ───────────────────────────────────────────────────────────────── */
    const MOCK_APPS = [
        {
            id: 'app_001',
            name: 'Stripe',
            slug: 'stripe',
            color: '#635BFF',
            description: 'Payment processing and subscription management notifications',
            notificationCount: 156,
            connectedAt: Date.now() - (90 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (2 * 60 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_002',
            name: 'GitHub',
            slug: 'github',
            color: '#24292F',
            description: 'Repository events, pull requests, and CI/CD notifications',
            notificationCount: 243,
            connectedAt: Date.now() - (120 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (30 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_003',
            name: 'Vercel',
            slug: 'vercel',
            color: '#000000',
            description: 'Deployment status and serverless function logs',
            notificationCount: 89,
            connectedAt: Date.now() - (60 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (4 * 60 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_004',
            name: 'Auth0',
            slug: 'auth0',
            color: '#EB5424',
            description: 'Authentication events and security alerts',
            notificationCount: 67,
            connectedAt: Date.now() - (45 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (1 * 24 * 60 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_005',
            name: 'Datadog',
            slug: 'datadog',
            color: '#632CA6',
            description: 'Infrastructure monitoring and APM alerts',
            notificationCount: 312,
            connectedAt: Date.now() - (180 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (15 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_006',
            name: 'Slack',
            slug: 'slack',
            color: '#4A154B',
            description: 'Team messaging and workflow notifications',
            notificationCount: 45,
            connectedAt: Date.now() - (30 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (6 * 60 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_007',
            name: 'Discord',
            slug: 'discord',
            color: '#5865F2',
            description: 'Bot events and server activity notifications',
            notificationCount: 28,
            connectedAt: Date.now() - (15 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (2 * 24 * 60 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_008',
            name: 'Twilio',
            slug: 'twilio',
            color: '#F22F46',
            description: 'SMS delivery status and voice call events',
            notificationCount: 134,
            connectedAt: Date.now() - (75 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (12 * 60 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_009',
            name: 'SendGrid',
            slug: 'sendgrid',
            color: '#1A82E2',
            description: 'Email delivery and engagement tracking',
            notificationCount: 198,
            connectedAt: Date.now() - (150 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (3 * 60 * 60 * 1000),
            isActive: true
        },
        {
            id: 'app_010',
            name: 'AWS',
            slug: 'aws',
            color: '#FF9900',
            description: 'CloudWatch alarms and service notifications',
            notificationCount: 276,
            connectedAt: Date.now() - (200 * 24 * 60 * 60 * 1000),
            lastUsed: Date.now() - (45 * 60 * 1000),
            isActive: true
        }
    ];

    /* ─────────────────────────────────────────────────────────────────
       State Management
       ───────────────────────────────────────────────────────────────── */
    const State = {
        apps: [...MOCK_APPS],
        filteredApps: [],
        searchQuery: '',
        currentSort: 'notifications',
        editingApp: null,
        pendingAction: null
    };

    /* ─────────────────────────────────────────────────────────────────
       DOM Elements
       ───────────────────────────────────────────────────────────────── */
    const DOM = {
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

    /* ─────────────────────────────────────────────────────────────────
       Utility Functions
       ───────────────────────────────────────────────────────────────── */
    function formatTimeAgo(timestamp) {
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

    function formatDate(timestamp) {
        return new Date(timestamp).toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric',
            year: 'numeric'
        });
    }

    function generateId() {
        return 'app_' + Math.random().toString(36).substr(2, 9);
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
            <span>${message}</span>
        `;
        
        DOM.toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('is-leaving');
            setTimeout(() => toast.remove(), 200);
        }, 3000);
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
                filtered.sort((a, b) => b.notificationCount - a.notificationCount);
                break;
            case 'recent':
                filtered.sort((a, b) => b.lastUsed - a.lastUsed);
                break;
            case 'name':
                filtered.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'oldest':
                filtered.sort((a, b) => a.connectedAt - b.connectedAt);
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
            const statusClass = app.isActive ? '' : 'app-card__status--inactive';
            
            html += `
                <article class="app-card" data-app-id="${app.id}" style="--app-color: ${app.color}">
                    <div class="app-card__status ${statusClass}" title="${app.isActive ? 'Active' : 'Inactive'}"></div>
                    <div class="app-card__header">
                        <div class="app-card__icon" style="background: ${app.color}">${letter}</div>
                        <div class="app-card__info">
                            <h3 class="app-card__name">${app.name}</h3>
                            <span class="app-card__slug">${app.slug}</span>
                        </div>
                    </div>
                    ${app.description ? `<p class="app-card__description">${app.description}</p>` : ''}
                    <div class="app-card__stats">
                        <div class="app-card__stat">
                            <span class="app-card__stat-value">${app.notificationCount.toLocaleString()}</span>
                            <span class="app-card__stat-label">Notifications</span>
                        </div>
                        <div class="app-card__stat">
                            <span class="app-card__stat-value">${formatTimeAgo(app.lastUsed)}</span>
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
        DOM.iconPreview.style.setProperty('--preview-color', app.color);
        
        // Set color picker
        DOM.colorPicker.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.classList.toggle('is-selected', swatch.dataset.color === app.color);
        });
        
        // Populate stats
        DOM.statNotifications.textContent = app.notificationCount.toLocaleString();
        DOM.statConnected.textContent = formatDate(app.connectedAt);
        DOM.statLastUsed.textContent = formatTimeAgo(app.lastUsed);
        
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
        }
        
        if (State.editingApp) {
            // Update existing app
            const app = State.apps.find(a => a.id === State.editingApp.id);
            if (app) {
                app.name = name;
                app.description = description;
                app.color = color;
                
                showToast('App updated successfully');
            }
        } else {
            // Create new app
            const newApp = {
                id: generateId(),
                name,
                slug,
                color,
                description,
                notificationCount: 0,
                connectedAt: Date.now(),
                lastUsed: Date.now(),
                isActive: true
            };
            
            State.apps.unshift(newApp);
            showToast('App added successfully');
        }
        
        closeAppModal();
        filterAndSortApps();
    }

    function handleClearNotifications() {
        if (!State.editingApp) return;
        
        openConfirmModal({
            title: 'Clear Notifications',
            message: `Are you sure you want to delete all notifications from ${State.editingApp.name}?`,
            warning: 'This will permanently remove all notification history for this app.',
            actionText: 'Clear All',
            action: () => {
                const app = State.apps.find(a => a.id === State.editingApp.id);
                if (app) {
                    app.notificationCount = 0;
                    DOM.statNotifications.textContent = '0';
                    showToast('Notifications cleared');
                }
                closeConfirmModal();
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
                State.apps = State.apps.filter(a => a.id !== State.editingApp.id);
                showToast(`${State.editingApp.name} has been disconnected`);
                closeConfirmModal();
                closeAppModal();
                filterAndSortApps();
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
        // Simulate loading delay
        setTimeout(() => {
            initEventListeners();
            filterAndSortApps();
        }, 500);
    }

    // Start
    init();

})();
