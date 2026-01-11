/* ═══════════════════════════════════════════════════════════════
   NOTIFICATIONS PAGE — JavaScript
   ═══════════════════════════════════════════════════════════════ */

(function() {
    'use strict';

    /* ─────────────────────────────────────────────────────────────────
       Mock Data — Comprehensive Notification Dataset
       ───────────────────────────────────────────────────────────────── */
    const MOCK_NOTIFICATIONS = [
        // Today's notifications
        {
            id: 'n001',
            app: 'stripe',
            appName: 'Stripe',
            title: 'Payment Received',
            body: 'Invoice #4521 has been paid by Acme Corp. Amount: $2,450.00 USD',
            tag: 'payment',
            timestamp: Date.now() - (5 * 60 * 1000), // 5 mins ago
            isRead: false,
            target_link: 'https://dashboard.stripe.com/payments/pi_4521'
        },
        {
            id: 'n002',
            app: 'github',
            appName: 'GitHub',
            title: 'Pull Request Merged',
            body: 'PR #287 "feat/webhook-retry" has been merged into main by @johndoe',
            tag: 'deploy',
            timestamp: Date.now() - (18 * 60 * 1000), // 18 mins ago
            isRead: false,
            target_link: 'https://github.com/rabbit/app/pull/287'
        },
        {
            id: 'n003',
            app: 'auth0',
            appName: 'Auth0',
            title: 'New User Registration',
            body: 'A new user sarah@acme.co has registered via Google OAuth',
            tag: 'auth',
            timestamp: Date.now() - (35 * 60 * 1000), // 35 mins ago
            isRead: true,
            target_link: 'https://manage.auth0.com/users/sarah@acme.co'
        },
        {
            id: 'n004',
            app: 'vercel',
            appName: 'Vercel',
            title: 'Deployment Successful',
            body: 'Production deployment for rabbit-app completed successfully. Build time: 42s',
            tag: 'deploy',
            timestamp: Date.now() - (1 * 60 * 60 * 1000), // 1 hour ago
            isRead: true,
            target_link: 'https://vercel.com/rabbit/deployments/dpl_abc123'
        },
        {
            id: 'n005',
            app: 'datadog',
            appName: 'Datadog',
            title: 'CPU Alert Triggered',
            body: 'Server prod-web-01 CPU usage exceeded 85% threshold for 5 minutes',
            tag: 'alert',
            timestamp: Date.now() - (2 * 60 * 60 * 1000), // 2 hours ago
            isRead: false,
            target_link: 'https://app.datadoghq.com/monitors/12345'
        },
        {
            id: 'n006',
            app: 'slack',
            appName: 'Slack',
            title: 'New Message in #engineering',
            body: '@channel The API rate limits have been updated. Please review the new documentation.',
            tag: null,
            timestamp: Date.now() - (3 * 60 * 60 * 1000), // 3 hours ago
            isRead: true
        },
        {
            id: 'n007',
            app: 'stripe',
            appName: 'Stripe',
            title: 'Subscription Renewed',
            body: 'Customer cust_ABC123 renewed their Pro Plan subscription for $99.00/month',
            tag: 'payment',
            timestamp: Date.now() - (4 * 60 * 60 * 1000), // 4 hours ago
            isRead: true
        },
        {
            id: 'n008',
            app: 'discord',
            appName: 'Discord',
            title: 'New Support Ticket',
            body: 'User DragonSlayer99 opened ticket #1842: "Cannot connect webhook to channel"',
            tag: null,
            timestamp: Date.now() - (5 * 60 * 60 * 1000), // 5 hours ago
            isRead: false
        },
        
        // Yesterday's notifications
        {
            id: 'n009',
            app: 'github',
            appName: 'GitHub',
            title: 'Issue Opened',
            body: 'New issue #342 "Bug: Notifications not showing in dark mode" opened by @sarahdev',
            tag: 'alert',
            timestamp: Date.now() - (24 * 60 * 60 * 1000), // Yesterday
            isRead: true
        },
        {
            id: 'n010',
            app: 'auth0',
            appName: 'Auth0',
            title: 'Failed Login Attempt',
            body: 'Multiple failed login attempts detected for admin@example.com from IP 192.168.1.100',
            tag: 'alert',
            timestamp: Date.now() - (26 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n011',
            app: 'twilio',
            appName: 'Twilio',
            title: 'SMS Delivered',
            body: 'Verification code SMS successfully delivered to +1 (555) 123-4567',
            tag: null,
            timestamp: Date.now() - (28 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n012',
            app: 'stripe',
            appName: 'Stripe',
            title: 'Refund Processed',
            body: 'Refund of $49.99 processed for order #ORD-2024-1234. Reason: Customer request',
            tag: 'payment',
            timestamp: Date.now() - (30 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n013',
            app: 'vercel',
            appName: 'Vercel',
            title: 'Build Failed',
            body: 'Deployment failed for staging branch. Error: Module not found @/components/Header',
            tag: 'alert',
            timestamp: Date.now() - (32 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n014',
            app: 'sendgrid',
            appName: 'SendGrid',
            title: 'Email Bounced',
            body: 'Email to invalid@example.com bounced. Reason: Mailbox not found',
            tag: 'alert',
            timestamp: Date.now() - (34 * 60 * 60 * 1000),
            isRead: false
        },
        {
            id: 'n015',
            app: 'datadog',
            appName: 'Datadog',
            title: 'Memory Usage Normal',
            body: 'Server prod-web-01 memory usage returned to normal levels (65%)',
            tag: null,
            timestamp: Date.now() - (36 * 60 * 60 * 1000),
            isRead: true
        },
        
        // This Week
        {
            id: 'n016',
            app: 'aws',
            appName: 'AWS',
            title: 'S3 Bucket Access',
            body: 'New IAM policy attached to bucket rabbit-uploads. Review access permissions.',
            tag: 'alert',
            timestamp: Date.now() - (3 * 24 * 60 * 60 * 1000), // 3 days ago
            isRead: true
        },
        {
            id: 'n017',
            app: 'github',
            appName: 'GitHub',
            title: 'Security Alert',
            body: 'Dependabot found 2 high severity vulnerabilities in package-lock.json',
            tag: 'alert',
            timestamp: Date.now() - (3 * 24 * 60 * 60 * 1000),
            isRead: false
        },
        {
            id: 'n018',
            app: 'stripe',
            appName: 'Stripe',
            title: 'Payout Initiated',
            body: 'Weekly payout of $12,450.00 initiated to bank account ending in 4242',
            tag: 'payment',
            timestamp: Date.now() - (4 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n019',
            app: 'slack',
            appName: 'Slack',
            title: 'Channel Created',
            body: 'New channel #incident-2024-01 created by @oncall-bot',
            tag: null,
            timestamp: Date.now() - (4 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n020',
            app: 'auth0',
            appName: 'Auth0',
            title: 'API Key Rotated',
            body: 'API key ending in ...x4f2 was automatically rotated. Update your integrations.',
            tag: 'auth',
            timestamp: Date.now() - (5 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n021',
            app: 'discord',
            appName: 'Discord',
            title: 'Bot Went Offline',
            body: 'Your bot RabbitNotify went offline unexpectedly. Check server logs.',
            tag: 'alert',
            timestamp: Date.now() - (5 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n022',
            app: 'vercel',
            appName: 'Vercel',
            title: 'Domain Verified',
            body: 'Domain notifications.rabbit.app has been verified and is now active',
            tag: 'deploy',
            timestamp: Date.now() - (6 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        
        // Older
        {
            id: 'n023',
            app: 'stripe',
            appName: 'Stripe',
            title: 'New Customer',
            body: 'New customer "TechCorp Inc" signed up for the Enterprise plan',
            tag: 'payment',
            timestamp: Date.now() - (10 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n024',
            app: 'github',
            appName: 'GitHub',
            title: 'Repository Starred',
            body: 'Your repository rabbit-sdk received 50 new stars this week',
            tag: null,
            timestamp: Date.now() - (12 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n025',
            app: 'sendgrid',
            appName: 'SendGrid',
            title: 'Monthly Report',
            body: 'Your December email report is ready. 45,230 emails sent, 98.2% delivery rate',
            tag: null,
            timestamp: Date.now() - (14 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n026',
            app: 'aws',
            appName: 'AWS',
            title: 'Cost Alert',
            body: 'Your estimated AWS charges exceeded $500 this month. Current: $523.45',
            tag: 'alert',
            timestamp: Date.now() - (15 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n027',
            app: 'datadog',
            appName: 'Datadog',
            title: 'New Dashboard Created',
            body: 'Dashboard "API Performance Metrics" was created by team member @mike',
            tag: null,
            timestamp: Date.now() - (18 * 24 * 60 * 60 * 1000),
            isRead: true
        },
        {
            id: 'n028',
            app: 'twilio',
            appName: 'Twilio',
            title: 'Phone Number Added',
            body: 'New phone number +1 (555) 987-6543 added to your account',
            tag: null,
            timestamp: Date.now() - (20 * 24 * 60 * 60 * 1000),
            isRead: true
        }
    ];

    /* ─────────────────────────────────────────────────────────────────
       App Configuration
       ───────────────────────────────────────────────────────────────── */
    const APP_CONFIG = {
        stripe: { name: 'Stripe', icon: 'S', color: '#635BFF' },
        github: { name: 'GitHub', icon: 'G', color: '#24292F' },
        vercel: { name: 'Vercel', icon: 'V', color: '#000000' },
        auth0: { name: 'Auth0', icon: 'A', color: '#EB5424' },
        datadog: { name: 'Datadog', icon: 'D', color: '#632CA6' },
        slack: { name: 'Slack', icon: 'S', color: '#4A154B' },
        discord: { name: 'Discord', icon: 'D', color: '#5865F2' },
        twilio: { name: 'Twilio', icon: 'T', color: '#F22F46' },
        sendgrid: { name: 'SendGrid', icon: 'S', color: '#1A82E2' },
        aws: { name: 'AWS', icon: 'A', color: '#FF9900' }
    };

    /* ─────────────────────────────────────────────────────────────────
       State Management
       ───────────────────────────────────────────────────────────────── */
    const State = {
        notifications: [...MOCK_NOTIFICATIONS],
        filteredNotifications: [],
        selectedIds: new Set(),
        currentApp: 'all',
        currentSort: 'newest',
        searchQuery: '',
        datePreset: 'all',
        dateFrom: null,
        dateTo: null,
        isSelectionMode: false,
        itemsPerPage: 15,
        currentPage: 1
    };

    /* ─────────────────────────────────────────────────────────────────
       DOM Elements
       ───────────────────────────────────────────────────────────────── */
    const DOM = {
        // Counts
        totalCount: document.getElementById('totalCount'),
        selectedCount: document.getElementById('selectedCount'),
        showingCount: document.getElementById('showingCount'),
        filteredCount: document.getElementById('filteredCount'),
        deleteCount: document.getElementById('deleteCount'),
        
        // Search
        searchInput: document.getElementById('searchInput'),
        searchClear: document.getElementById('searchClear'),
        
        // Filters
        appFilterDropdown: document.getElementById('appFilterDropdown'),
        appFilterTrigger: document.getElementById('appFilterTrigger'),
        appFilterLabel: document.getElementById('appFilterLabel'),
        appFilterMenu: document.getElementById('appFilterMenu'),
        appFilterList: document.getElementById('appFilterList'),
        dateFilterDropdown: document.getElementById('dateFilterDropdown'),
        dateFilterTrigger: document.getElementById('dateFilterTrigger'),
        dateFilterLabel: document.getElementById('dateFilterLabel'),
        dateFilterMenu: document.getElementById('dateFilterMenu'),
        dateFilterCustom: document.getElementById('dateFilterCustom'),
        dateFrom: document.getElementById('dateFrom'),
        dateTo: document.getElementById('dateTo'),
        dateApplyBtn: document.getElementById('dateApplyBtn'),
        dateClearBtn: document.getElementById('dateClearBtn'),
        sortTrigger: document.getElementById('sortTrigger'),
        sortLabel: document.getElementById('sortLabel'),
        sortMenu: document.getElementById('sortMenu'),
        
        // Selection
        selectModeToggle: document.getElementById('selectModeToggle'),
        selectionBar: document.getElementById('selectionBar'),
        selectAllCheckbox: document.getElementById('selectAllCheckbox'),
        markSelectedReadBtn: document.getElementById('markSelectedReadBtn'),
        deleteSelectedBtn: document.getElementById('deleteSelectedBtn'),
        cancelSelectionBtn: document.getElementById('cancelSelectionBtn'),
        markAllReadBtn: document.getElementById('markAllReadBtn'),
        
        // Container
        notificationsContainer: document.getElementById('notificationsContainer'),
        loadingState: document.getElementById('loadingState'),
        emptyState: document.getElementById('emptyState'),
        emptyStateText: document.getElementById('emptyStateText'),
        loadMore: document.getElementById('loadMore'),
        loadMoreBtn: document.getElementById('loadMoreBtn'),
        
        // Modal
        deleteModal: document.getElementById('deleteModal'),
        deleteModalClose: document.getElementById('deleteModalClose'),
        deleteCancelBtn: document.getElementById('deleteCancelBtn'),
        deleteConfirmBtn: document.getElementById('deleteConfirmBtn'),
        
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
        
        return new Date(timestamp).toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric' 
        });
    }

    function getDateGroup(timestamp) {
        const now = new Date();
        const date = new Date(timestamp);
        const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0 && now.getDate() === date.getDate()) return 'Today';
        if (diffDays <= 1 || (diffDays === 0 && now.getDate() !== date.getDate())) return 'Yesterday';
        if (diffDays <= 7) return 'This Week';
        return 'Older';
    }

    function debounce(fn, delay) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => fn.apply(this, args), delay);
        };
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
    function filterNotifications() {
        let filtered = [...State.notifications];
        
        // Filter by app
        if (State.currentApp !== 'all') {
            filtered = filtered.filter(n => n.app === State.currentApp);
        }
        
        // Filter by search query
        if (State.searchQuery) {
            const query = State.searchQuery.toLowerCase();
            filtered = filtered.filter(n => 
                n.title.toLowerCase().includes(query) ||
                n.body.toLowerCase().includes(query) ||
                n.appName.toLowerCase().includes(query)
            );
        }
        
        // Filter by date range
        if (State.dateFrom || State.dateTo) {
            filtered = filtered.filter(n => {
                const notifDate = new Date(n.timestamp);
                notifDate.setHours(0, 0, 0, 0);
                
                if (State.dateFrom && notifDate < State.dateFrom) return false;
                if (State.dateTo) {
                    const endDate = new Date(State.dateTo);
                    endDate.setHours(23, 59, 59, 999);
                    if (notifDate > endDate) return false;
                }
                return true;
            });
        }
        
        // Sort
        switch (State.currentSort) {
            case 'newest':
                filtered.sort((a, b) => b.timestamp - a.timestamp);
                break;
            case 'oldest':
                filtered.sort((a, b) => a.timestamp - b.timestamp);
                break;
            case 'app':
                filtered.sort((a, b) => a.appName.localeCompare(b.appName));
                break;
            case 'unread':
                filtered.sort((a, b) => {
                    if (a.isRead === b.isRead) return b.timestamp - a.timestamp;
                    return a.isRead ? 1 : -1;
                });
                break;
        }
        
        State.filteredNotifications = filtered;
        State.currentPage = 1;
        
        renderNotifications();
        updateCounts();
    }

    /* ─────────────────────────────────────────────────────────────────
       Rendering
       ───────────────────────────────────────────────────────────────── */
    function renderAppFilters() {
        // Count notifications per app
        const appCounts = {};
        State.notifications.forEach(n => {
            appCounts[n.app] = (appCounts[n.app] || 0) + 1;
        });
        
        // Get unique apps sorted by count
        const apps = [...new Set(State.notifications.map(n => n.app))]
            .sort((a, b) => appCounts[b] - appCounts[a]);
        
        // Generate dropdown options
        let html = `
            <button class="app-option app-option--all ${State.currentApp === 'all' ? 'is-active' : ''}" data-app="all">
                <span class="app-option__icon">✦</span>
                <span class="app-option__name">All Apps</span>
                <span class="app-option__count">${State.notifications.length}</span>
            </button>
        `;
        
        apps.forEach(app => {
            const config = APP_CONFIG[app];
            html += `
                <button class="app-option ${State.currentApp === app ? 'is-active' : ''}" data-app="${app}">
                    <span class="app-option__icon" style="background: ${config.color}">${config.icon}</span>
                    <span class="app-option__name">${config.name}</span>
                    <span class="app-option__count">${appCounts[app]}</span>
                </button>
            `;
        });
        
        DOM.appFilterList.innerHTML = html;
        
        // Update trigger label
        if (State.currentApp === 'all') {
            DOM.appFilterLabel.textContent = 'All Apps';
            DOM.appFilterTrigger.classList.remove('has-filter');
        } else {
            DOM.appFilterLabel.textContent = APP_CONFIG[State.currentApp].name;
            DOM.appFilterTrigger.classList.add('has-filter');
        }
        
        // Add event listeners
        DOM.appFilterList.querySelectorAll('.app-option').forEach(btn => {
            btn.addEventListener('click', () => {
                State.currentApp = btn.dataset.app;
                filterNotifications();
                renderAppFilters();
                DOM.appFilterDropdown.classList.remove('is-open');
            });
        });
    }

    function renderNotifications() {
        const notifications = State.filteredNotifications;
        const displayCount = State.currentPage * State.itemsPerPage;
        const toShow = notifications.slice(0, displayCount);
        
        // Hide loading
        DOM.loadingState.style.display = 'none';
        
        // Check empty state
        if (notifications.length === 0) {
            DOM.notificationsContainer.innerHTML = '';
            DOM.emptyState.hidden = false;
            DOM.loadMore.hidden = true;
            
            if (State.searchQuery) {
                DOM.emptyStateText.textContent = `No results for "${State.searchQuery}"`;
            } else if (State.currentApp !== 'all') {
                DOM.emptyStateText.textContent = `No notifications from ${APP_CONFIG[State.currentApp].name}`;
            } else {
                DOM.emptyStateText.textContent = 'You have no notifications yet';
            }
            return;
        }
        
        DOM.emptyState.hidden = true;
        
        // Group by date (unless sorting by app)
        let html = '';
        
        if (State.currentSort === 'app') {
            // Group by app
            const grouped = {};
            toShow.forEach(n => {
                if (!grouped[n.app]) grouped[n.app] = [];
                grouped[n.app].push(n);
            });
            
            Object.keys(grouped).forEach(app => {
                const config = APP_CONFIG[app];
                html += `
                    <section class="notification-group">
                        <header class="notification-group__header">
                            <h2 class="notification-group__title">${config.name}</h2>
                            <span class="notification-group__count">${grouped[app].length}</span>
                        </header>
                        <div class="notification-group__list">
                            ${grouped[app].map(n => renderNotificationCard(n)).join('')}
                        </div>
                    </section>
                `;
            });
        } else {
            // Group by date
            const grouped = {};
            toShow.forEach(n => {
                const group = getDateGroup(n.timestamp);
                if (!grouped[group]) grouped[group] = [];
                grouped[group].push(n);
            });
            
            const groupOrder = ['Today', 'Yesterday', 'This Week', 'Older'];
            groupOrder.forEach(group => {
                if (grouped[group]) {
                    html += `
                        <section class="notification-group">
                            <header class="notification-group__header">
                                <h2 class="notification-group__title">${group}</h2>
                                <span class="notification-group__count">${grouped[group].length}</span>
                            </header>
                            <div class="notification-group__list">
                                ${grouped[group].map(n => renderNotificationCard(n)).join('')}
                            </div>
                        </section>
                    `;
                }
            });
        }
        
        DOM.notificationsContainer.innerHTML = html;
        
        // Show/hide load more
        if (displayCount < notifications.length) {
            DOM.loadMore.hidden = false;
            DOM.showingCount.textContent = toShow.length;
            DOM.filteredCount.textContent = notifications.length;
        } else {
            DOM.loadMore.hidden = true;
        }
        
        // Bind card events
        bindCardEvents();
    }

    function renderNotificationCard(notification) {
        const isSelected = State.selectedIds.has(notification.id);
        const config = APP_CONFIG[notification.app];
        
        let tagHtml = '';
        if (notification.tag) {
            tagHtml = `<span class="notification-card__tag notification-card__tag--${notification.tag}">${notification.tag}</span>`;
        }
        
        let targetLinkHtml = '';
        if (notification.target_link) {
            targetLinkHtml = `
                <a href="${notification.target_link}" class="notification-card__link" target="_blank" rel="noopener noreferrer" title="Open in new tab">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    <span>View Details</span>
                </a>
            `;
        }
        
        return `
            <article class="notification-card ${notification.isRead ? '' : 'is-unread'} ${isSelected ? 'is-selected' : ''}" 
                     data-id="${notification.id}">
                <button class="notification-card__checkbox ${isSelected ? 'is-checked' : ''}" data-id="${notification.id}">
                    <svg class="icon-check" viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="3" fill="none">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </button>
                <div class="notification-card__icon notification-card__icon--${notification.app}">${config.icon}</div>
                <div class="notification-card__content">
                    <header class="notification-card__header">
                        <h3 class="notification-card__title">${notification.title}</h3>
                        <time class="notification-card__time">${formatTimeAgo(notification.timestamp)}</time>
                    </header>
                    <p class="notification-card__body">${notification.body}</p>
                    <div class="notification-card__meta">
                        <span class="notification-card__app">
                            <span class="notification-card__app-dot" style="background: ${config.color}"></span>
                            ${notification.appName}
                        </span>
                        ${tagHtml}
                        ${targetLinkHtml}
                    </div>
                </div>
                <div class="notification-card__actions">
                    <button class="notification-card__action" data-action="read" data-id="${notification.id}" 
                            title="${notification.isRead ? 'Mark as unread' : 'Mark as read'}">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                            ${notification.isRead 
                                ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
                                : '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'
                            }
                        </svg>
                    </button>
                    <button class="notification-card__action notification-card__action--delete" data-action="delete" data-id="${notification.id}" title="Delete">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </div>
            </article>
        `;
    }

    function bindCardEvents() {
        // Checkbox clicks
        document.querySelectorAll('.notification-card__checkbox').forEach(checkbox => {
            checkbox.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSelection(checkbox.dataset.id);
            });
        });
        
        // Action button clicks
        document.querySelectorAll('.notification-card__action').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const action = btn.dataset.action;
                const id = btn.dataset.id;
                
                if (action === 'read') {
                    toggleRead(id);
                } else if (action === 'delete') {
                    showDeleteModal([id]);
                }
            });
        });
        
        // Card click (toggle selection in selection mode, otherwise mark as read)
        document.querySelectorAll('.notification-card').forEach(card => {
            card.addEventListener('click', () => {
                if (State.isSelectionMode) {
                    toggleSelection(card.dataset.id);
                } else {
                    markAsRead(card.dataset.id);
                }
            });
        });
    }

    function updateCounts() {
        DOM.totalCount.textContent = State.notifications.length.toLocaleString();
        DOM.selectedCount.textContent = State.selectedIds.size;
        
        // Update select all checkbox state
        const filteredIds = new Set(State.filteredNotifications.map(n => n.id));
        const selectedInView = [...State.selectedIds].filter(id => filteredIds.has(id)).length;
        
        if (selectedInView === 0) {
            DOM.selectAllCheckbox.classList.remove('is-checked', 'is-partial');
        } else if (selectedInView === State.filteredNotifications.length) {
            DOM.selectAllCheckbox.classList.add('is-checked');
            DOM.selectAllCheckbox.classList.remove('is-partial');
        } else {
            DOM.selectAllCheckbox.classList.add('is-partial');
            DOM.selectAllCheckbox.classList.remove('is-checked');
        }
    }

    /* ─────────────────────────────────────────────────────────────────
       Selection Mode
       ───────────────────────────────────────────────────────────────── */
    function toggleSelectionMode(enable) {
        State.isSelectionMode = enable;
        
        if (enable) {
            document.body.classList.add('selection-mode');
            DOM.selectionBar.classList.add('is-visible');
            DOM.selectModeToggle.innerHTML = `
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                <span>Cancel</span>
            `;
        } else {
            document.body.classList.remove('selection-mode');
            DOM.selectionBar.classList.remove('is-visible');
            State.selectedIds.clear();
            DOM.selectModeToggle.innerHTML = `
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                    <polyline points="9 11 12 14 22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <span>Select</span>
            `;
            renderNotifications();
        }
        
        updateCounts();
    }

    function toggleSelection(id) {
        if (State.selectedIds.has(id)) {
            State.selectedIds.delete(id);
        } else {
            State.selectedIds.add(id);
        }
        
        // Update card UI
        const card = document.querySelector(`.notification-card[data-id="${id}"]`);
        const checkbox = card?.querySelector('.notification-card__checkbox');
        
        if (card && checkbox) {
            card.classList.toggle('is-selected', State.selectedIds.has(id));
            checkbox.classList.toggle('is-checked', State.selectedIds.has(id));
        }
        
        updateCounts();
    }

    function selectAll() {
        const filteredIds = State.filteredNotifications.map(n => n.id);
        const allSelected = filteredIds.every(id => State.selectedIds.has(id));
        
        if (allSelected) {
            // Deselect all in current view
            filteredIds.forEach(id => State.selectedIds.delete(id));
        } else {
            // Select all in current view
            filteredIds.forEach(id => State.selectedIds.add(id));
        }
        
        renderNotifications();
        updateCounts();
    }

    /* ─────────────────────────────────────────────────────────────────
       Actions
       ───────────────────────────────────────────────────────────────── */
    function toggleRead(id) {
        const notification = State.notifications.find(n => n.id === id);
        if (notification) {
            notification.isRead = !notification.isRead;
            filterNotifications();
            showToast(notification.isRead ? 'Marked as read' : 'Marked as unread');
        }
    }

    function markAsRead(id) {
        const notification = State.notifications.find(n => n.id === id);
        if (notification && !notification.isRead) {
            notification.isRead = true;
            filterNotifications();
        }
    }

    function markSelectedAsRead() {
        let count = 0;
        State.selectedIds.forEach(id => {
            const notification = State.notifications.find(n => n.id === id);
            if (notification && !notification.isRead) {
                notification.isRead = true;
                count++;
            }
        });
        
        State.selectedIds.clear();
        toggleSelectionMode(false);
        filterNotifications();
        showToast(`Marked ${count} notification${count !== 1 ? 's' : ''} as read`);
    }

    function markAllAsRead() {
        let count = 0;
        State.notifications.forEach(n => {
            if (!n.isRead) {
                n.isRead = true;
                count++;
            }
        });
        
        filterNotifications();
        showToast(`Marked ${count} notification${count !== 1 ? 's' : ''} as read`);
    }

    function deleteNotifications(ids) {
        State.notifications = State.notifications.filter(n => !ids.includes(n.id));
        ids.forEach(id => State.selectedIds.delete(id));
        
        filterNotifications();
        renderAppFilters();
        closeDeleteModal();
        toggleSelectionMode(false);
        
        showToast(`Deleted ${ids.length} notification${ids.length !== 1 ? 's' : ''}`);
    }

    /* ─────────────────────────────────────────────────────────────────
       Modal
       ───────────────────────────────────────────────────────────────── */
    let pendingDeleteIds = [];

    function showDeleteModal(ids) {
        pendingDeleteIds = ids;
        DOM.deleteCount.textContent = ids.length;
        DOM.deleteModal.classList.add('is-open');
    }

    function closeDeleteModal() {
        DOM.deleteModal.classList.remove('is-open');
        pendingDeleteIds = [];
    }

    /* ─────────────────────────────────────────────────────────────────
       Event Listeners
       ───────────────────────────────────────────────────────────────── */
    function initEventListeners() {
        // Search
        DOM.searchInput.addEventListener('input', debounce((e) => {
            State.searchQuery = e.target.value.trim();
            DOM.searchClear.hidden = !State.searchQuery;
            filterNotifications();
        }, 300));
        
        DOM.searchClear.addEventListener('click', () => {
            DOM.searchInput.value = '';
            State.searchQuery = '';
            DOM.searchClear.hidden = true;
            filterNotifications();
        });
        
        // Sort dropdown
        DOM.sortTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            DOM.appFilterDropdown.classList.remove('is-open');
            DOM.dateFilterDropdown.classList.remove('is-open');
            DOM.sortTrigger.parentElement.classList.toggle('is-open');
        });
        
        DOM.sortMenu.querySelectorAll('.filter-dropdown__item').forEach(item => {
            item.addEventListener('click', () => {
                State.currentSort = item.dataset.sort;
                DOM.sortLabel.textContent = item.textContent.trim();
                
                DOM.sortMenu.querySelectorAll('.filter-dropdown__item').forEach(i => i.classList.remove('is-active'));
                item.classList.add('is-active');
                
                DOM.sortTrigger.parentElement.classList.remove('is-open');
                filterNotifications();
            });
        });
        
        // App filter dropdown
        DOM.appFilterTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            DOM.sortTrigger.parentElement.classList.remove('is-open');
            DOM.dateFilterDropdown.classList.remove('is-open');
            DOM.appFilterDropdown.classList.toggle('is-open');
        });
        
        // Date filter dropdown
        DOM.dateFilterTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            DOM.sortTrigger.parentElement.classList.remove('is-open');
            DOM.appFilterDropdown.classList.remove('is-open');
            DOM.dateFilterDropdown.classList.toggle('is-open');
        });
        
        // Date preset buttons
        DOM.dateFilterMenu.querySelectorAll('.date-preset').forEach(btn => {
            btn.addEventListener('click', () => {
                const preset = btn.dataset.preset;
                State.datePreset = preset;
                
                // Update active state
                DOM.dateFilterMenu.querySelectorAll('.date-preset').forEach(b => b.classList.remove('is-active'));
                btn.classList.add('is-active');
                
                // Show/hide custom inputs
                DOM.dateFilterCustom.hidden = preset !== 'custom';
                
                if (preset === 'custom') {
                    return; // Wait for apply button
                }
                
                // Calculate date range based on preset
                const now = new Date();
                now.setHours(0, 0, 0, 0);
                
                switch (preset) {
                    case 'all':
                        State.dateFrom = null;
                        State.dateTo = null;
                        DOM.dateFilterLabel.textContent = 'All Time';
                        DOM.dateFilterTrigger.classList.remove('has-filter');
                        DOM.dateClearBtn.hidden = true;
                        break;
                    case 'today':
                        State.dateFrom = new Date(now);
                        State.dateTo = new Date(now);
                        DOM.dateFilterLabel.textContent = 'Today';
                        DOM.dateFilterTrigger.classList.add('has-filter');
                        DOM.dateClearBtn.hidden = false;
                        break;
                    case 'week':
                        const weekStart = new Date(now);
                        weekStart.setDate(now.getDate() - now.getDay());
                        State.dateFrom = weekStart;
                        State.dateTo = new Date(now);
                        DOM.dateFilterLabel.textContent = 'This Week';
                        DOM.dateFilterTrigger.classList.add('has-filter');
                        DOM.dateClearBtn.hidden = false;
                        break;
                    case 'month':
                        const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                        State.dateFrom = monthStart;
                        State.dateTo = new Date(now);
                        DOM.dateFilterLabel.textContent = 'This Month';
                        DOM.dateFilterTrigger.classList.add('has-filter');
                        DOM.dateClearBtn.hidden = false;
                        break;
                }
                
                DOM.dateFilterDropdown.classList.remove('is-open');
                filterNotifications();
            });
        });
        
        // Apply custom date range
        DOM.dateApplyBtn.addEventListener('click', () => {
            const fromVal = DOM.dateFrom.value;
            const toVal = DOM.dateTo.value;
            
            if (!fromVal && !toVal) {
                showToast('Please select at least one date', 'error');
                return;
            }
            
            State.dateFrom = fromVal ? new Date(fromVal) : null;
            State.dateTo = toVal ? new Date(toVal) : null;
            
            // Format label
            const formatDate = (d) => d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            if (State.dateFrom && State.dateTo) {
                DOM.dateFilterLabel.textContent = `${formatDate(State.dateFrom)} - ${formatDate(State.dateTo)}`;
            } else if (State.dateFrom) {
                DOM.dateFilterLabel.textContent = `From ${formatDate(State.dateFrom)}`;
            } else {
                DOM.dateFilterLabel.textContent = `Until ${formatDate(State.dateTo)}`;
            }
            
            DOM.dateFilterTrigger.classList.add('has-filter');
            DOM.dateClearBtn.hidden = false;
            DOM.dateFilterDropdown.classList.remove('is-open');
            filterNotifications();
        });
        
        // Clear date filter
        DOM.dateClearBtn.addEventListener('click', () => {
            State.datePreset = 'all';
            State.dateFrom = null;
            State.dateTo = null;
            DOM.dateFrom.value = '';
            DOM.dateTo.value = '';
            DOM.dateFilterLabel.textContent = 'All Time';
            DOM.dateFilterTrigger.classList.remove('has-filter');
            DOM.dateClearBtn.hidden = true;
            DOM.dateFilterCustom.hidden = true;
            
            DOM.dateFilterMenu.querySelectorAll('.date-preset').forEach(b => b.classList.remove('is-active'));
            DOM.dateFilterMenu.querySelector('[data-preset="all"]').classList.add('is-active');
            
            DOM.dateFilterDropdown.classList.remove('is-open');
            filterNotifications();
        });
        
        // Close dropdown on outside click
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('is-open'));
            }
        });
        
        // Selection mode
        DOM.selectModeToggle.addEventListener('click', () => {
            toggleSelectionMode(!State.isSelectionMode);
        });
        
        DOM.cancelSelectionBtn.addEventListener('click', () => {
            toggleSelectionMode(false);
        });
        
        DOM.selectAllCheckbox.addEventListener('click', selectAll);
        
        // Bulk actions
        DOM.markSelectedReadBtn.addEventListener('click', markSelectedAsRead);
        DOM.markAllReadBtn.addEventListener('click', markAllAsRead);
        
        DOM.deleteSelectedBtn.addEventListener('click', () => {
            if (State.selectedIds.size > 0) {
                showDeleteModal([...State.selectedIds]);
            }
        });
        
        // Modal
        DOM.deleteModalClose.addEventListener('click', closeDeleteModal);
        DOM.deleteCancelBtn.addEventListener('click', closeDeleteModal);
        DOM.deleteModal.querySelector('.modal__backdrop').addEventListener('click', closeDeleteModal);
        
        DOM.deleteConfirmBtn.addEventListener('click', () => {
            deleteNotifications(pendingDeleteIds);
        });
        
        // Load more
        DOM.loadMoreBtn.addEventListener('click', () => {
            State.currentPage++;
            renderNotifications();
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (DOM.deleteModal.classList.contains('is-open')) {
                    closeDeleteModal();
                } else if (State.isSelectionMode) {
                    toggleSelectionMode(false);
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
            renderAppFilters();
            filterNotifications();
        }, 500);
    }

    // Start
    init();

})();
