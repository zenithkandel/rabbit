<?php 
require_once __DIR__ . '/../API/config/auth.php';

// Get first name for greeting
$firstName = explode(' ', trim($currentUser['name'] ?? 'User'))[0];
$firstName = htmlspecialchars($firstName);
$userId = $currentUser['id'];
$hasApiKey = $currentUser['has_api_key'] ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home — Rabbit Dashboard</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="CSS/dashboard.css">
    <style>
        /* Alert Banner */
        .alert-banner {
            display: flex;
            align-items: center;
            gap: var(--space-4);
            padding: var(--space-4) var(--space-5);
            background: #FEF3C7;
            border: 2px solid #F59E0B;
            margin-bottom: var(--space-6);
        }
        
        [data-theme="dark"] .alert-banner {
            background: rgba(245, 158, 11, 0.15);
            border-color: #F59E0B;
        }
        
        .alert-banner__icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            color: #D97706;
        }
        
        .alert-banner__content {
            flex: 1;
        }
        
        .alert-banner__title {
            font-weight: 600;
            color: #92400E;
            margin-bottom: var(--space-1);
        }
        
        [data-theme="dark"] .alert-banner__title {
            color: #FCD34D;
        }
        
        .alert-banner__text {
            font-size: var(--text-sm);
            color: #A16207;
        }
        
        [data-theme="dark"] .alert-banner__text {
            color: #FDE68A;
        }
        
        .alert-banner__action {
            padding: var(--space-2) var(--space-4);
            background: #D97706;
            color: white;
            font-size: var(--text-sm);
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background var(--duration-fast);
        }
        
        .alert-banner__action:hover {
            background: #B45309;
        }
        
        /* Cleaner Home Layout */
        .home {
            min-height: 100vh;
            padding: var(--space-6) var(--space-6);
            background: var(--bg-primary);
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .home__header {
            margin-bottom: var(--space-8);
            text-align: center;
        }
        
        .home__greeting {
            font-size: var(--text-4xl);
            margin-bottom: var(--space-3);
        }
        
        .home__subtitle {
            font-size: var(--text-lg);
            color: var(--text-muted);
            font-weight: 400;
        }
        
        /* Compact Stats Row */
        .stats-row {
            display: flex;
            justify-content: center;
            gap: var(--space-12);
            margin-bottom: var(--space-8);
            padding: var(--space-6) 0;
            border-top: var(--border-thin) solid var(--border-light);
            border-bottom: var(--border-thin) solid var(--border-light);
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-item__value {
            font-family: var(--font-display);
            font-size: var(--text-3xl);
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            margin-bottom: var(--space-2);
        }
        
        .stat-item__label {
            font-size: var(--text-sm);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: var(--tracking-wide);
        }
        
        .stat-loading {
            color: var(--text-muted);
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }
        
        /* Loading Placeholders */
        .loading-placeholder {
            height: 60px;
            background: linear-gradient(90deg, var(--bg-elevated) 25%, var(--bg-primary) 50%, var(--bg-elevated) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            margin: var(--space-3) var(--space-5);
        }
        
        .loading-placeholder--sm {
            height: 40px;
            margin: var(--space-2) var(--space-3);
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Empty States */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: var(--space-8) var(--space-4);
            text-align: center;
            color: var(--text-muted);
        }
        
        .empty-state__icon {
            width: 48px;
            height: 48px;
            margin-bottom: var(--space-4);
            opacity: 0.5;
        }
        
        .empty-state__title {
            font-size: var(--text-sm);
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: var(--space-1);
        }
        
        .empty-state__text {
            font-size: var(--text-xs);
            color: var(--text-muted);
        }
        
        /* Quick Nav Cards */
        .quick-nav {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-4);
            margin-bottom: var(--space-8);
        }
        
        .quick-nav__card {
            display: flex;
            align-items: center;
            gap: var(--space-4);
            padding: var(--space-5);
            background: var(--bg-secondary);
            border: var(--border-medium) solid var(--border-color);
            text-decoration: none;
            color: var(--text-primary);
            transition: border-color var(--duration-fast), background var(--duration-fast);
            cursor: pointer;
        }
        
        .quick-nav__card:hover {
            border-color: var(--text-primary);
            background: var(--bg-elevated);
        }
        
        .quick-nav__icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            border: var(--border-thin) solid var(--border-light);
            flex-shrink: 0;
        }
        
        .quick-nav__icon svg {
            width: 20px;
            height: 20px;
            stroke: var(--text-primary);
            stroke-width: 1.5;
            fill: none;
        }
        
        .quick-nav__icon--accent { background: var(--accent-primary); border-color: var(--accent-primary); }
        .quick-nav__icon--accent svg { stroke: var(--color-paper); }
        
        .quick-nav__icon--sage { background: var(--accent-secondary); border-color: var(--accent-secondary); }
        .quick-nav__icon--sage svg { stroke: var(--color-paper); }
        
        .quick-nav__text {
            flex: 1;
            min-width: 0;
        }
        
        .quick-nav__title {
            font-size: var(--text-sm);
            font-weight: 600;
            margin-bottom: var(--space-1);
        }
        
        .quick-nav__desc {
            font-size: var(--text-xs);
            color: var(--text-muted);
        }
        
        /* Clean Two Column Layout */
        .home__content {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: var(--space-6);
            align-items: start;
        }
        
        /* Simplified Panel */
        .panel {
            background: var(--bg-secondary);
            border: var(--border-medium) solid var(--border-color);
        }
        
        .panel__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--space-5) var(--space-6);
            border-bottom: var(--border-thin) solid var(--border-light);
        }
        
        .panel__title {
            font-family: var(--font-display);
            font-size: var(--text-lg);
            font-weight: 600;
        }
        
        /* Cleaner Notification List */
        .notification-list {
            max-height: 480px;
            overflow-y: auto;
        }
        
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: var(--space-4);
            padding: var(--space-5) var(--space-6);
            border-bottom: var(--border-thin) solid var(--border-light);
            transition: background var(--duration-fast) var(--ease-out);
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item:hover {
            background: var(--bg-elevated);
        }
        
        .notification-item__icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--text-sm);
            font-weight: 700;
            background: var(--accent-primary);
            color: var(--color-paper);
            flex-shrink: 0;
        }
        
        .notification-item__icon--sage { background: var(--accent-secondary); }
        .notification-item__icon--ink { background: var(--text-primary); }
        
        .notification-item__content {
            flex: 1;
            min-width: 0;
        }
        
        .notification-item__title {
            font-size: var(--text-base);
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: var(--space-1);
            line-height: var(--leading-snug);
        }
        
        .notification-item__meta {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            font-size: var(--text-sm);
            color: var(--text-muted);
        }
        
        .notification-item__app {
            font-weight: 500;
            color: var(--text-secondary);
        }
        
        .notification-item__time {
            font-family: var(--font-mono);
            font-size: var(--text-xs);
        }
        
        /* Sidebar Panels */
        .sidebar-stack {
            display: flex;
            flex-direction: column;
            gap: var(--space-6);
        }
        
        /* Quick API Card */
        .api-card {
            padding: var(--space-6);
        }
        
        .api-card__label {
            font-size: var(--text-xs);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: var(--tracking-wide);
            color: var(--text-muted);
            margin-bottom: var(--space-3);
        }
        
        .api-status {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-4);
            font-weight: 500;
            margin-bottom: var(--space-3);
        }
        
        .api-status--active {
            background: rgba(34, 197, 94, 0.1);
            color: #16A34A;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .api-status--inactive {
            background: rgba(245, 158, 11, 0.1);
            color: #D97706;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .api-card__hint {
            font-size: var(--text-sm);
            color: var(--text-muted);
            margin-bottom: var(--space-4);
            line-height: var(--leading-relaxed);
        }
        
        .api-card__btn {
            width: 100%;
            padding: var(--space-3) var(--space-4);
            background: var(--bg-primary);
            border: var(--border-medium) solid var(--border-color);
            font-size: var(--text-sm);
            font-weight: 500;
            color: var(--text-primary);
            cursor: pointer;
            transition: border-color var(--duration-fast), background var(--duration-fast);
        }
        
        .api-card__btn:hover {
            border-color: var(--text-primary);
            background: var(--bg-elevated);
        }
        
        /* Apps Mini List */
        .apps-mini {
            padding: var(--space-4);
        }
        
        .apps-mini__item {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3);
            transition: background var(--duration-fast);
        }
        
        .apps-mini__item:hover {
            background: var(--bg-elevated);
        }
        
        .apps-mini__icon {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--text-xs);
            font-weight: 700;
            background: var(--accent-primary);
            color: var(--color-paper);
        }
        
        .apps-mini__icon--sage { background: var(--accent-secondary); }
        .apps-mini__icon--ink { background: var(--text-primary); }
        
        .apps-mini__name {
            flex: 1;
            font-size: var(--text-sm);
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .apps-mini__status {
            width: 6px;
            height: 6px;
            background: var(--accent-secondary);
            animation: pulse 2s ease-in-out infinite;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .quick-nav {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .home__content {
                grid-template-columns: 1fr;
            }
            
            .sidebar-stack {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .home {
                padding: var(--space-4) var(--space-4);
            }
            
            .home__header {
                margin-bottom: var(--space-6);
            }
            
            .home__greeting {
                font-size: var(--text-2xl);
            }
            
            .stats-row {
                flex-wrap: wrap;
                gap: var(--space-6);
                padding: var(--space-5) 0;
                margin-bottom: var(--space-6);
            }
            
            .stat-item {
                flex: 1;
                min-width: 80px;
            }
            
            .stat-item__value {
                font-size: var(--text-xl);
            }
            
            .quick-nav {
                grid-template-columns: 1fr 1fr;
                gap: var(--space-3);
                margin-bottom: var(--space-6);
            }
            
            .quick-nav__card {
                padding: var(--space-4);
            }
            
            .quick-nav__icon {
                width: 32px;
                height: 32px;
            }
            
            .quick-nav__icon svg {
                width: 16px;
                height: 16px;
            }
            
            .quick-nav__desc {
                display: none;
            }
            
            .sidebar-stack {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <script>
        // Sync theme with parent
        (function() {
            try {
                const parentTheme = window.parent.document.documentElement.getAttribute('data-theme');
                if (parentTheme) {
                    document.documentElement.setAttribute('data-theme', parentTheme);
                }
            } catch(e) {}
        })();
    </script>
</head>
<body>
    <div class="home">
        <?php if (!$hasApiKey): ?>
        <!-- API Key Alert Banner -->
        <div class="alert-banner" id="apiKeyAlert">
            <svg class="alert-banner__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <div class="alert-banner__content">
                <div class="alert-banner__title">API Key Required</div>
                <div class="alert-banner__text">You haven't generated an API key yet. Create one to start sending notifications to Rabbit.</div>
            </div>
            <button class="alert-banner__action" onclick="window.parent.Dashboard.navigate('settings.php');">
                Generate API Key
            </button>
        </div>
        <?php endif; ?>

        <!-- Welcome Header -->
        <header class="home__header">
            <h1 class="home__greeting" data-user-name="<?php echo $firstName; ?>">Welcome back, <?php echo $firstName; ?></h1>
            <p class="home__subtitle">Here's what's happening with your notifications</p>
        </header>

        <!-- Key Stats Row -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-item__value" data-stat="total-notifications">
                    <span class="stat-loading">—</span>
                </div>
                <div class="stat-item__label">Total Notifications</div>
            </div>
            <div class="stat-item">
                <div class="stat-item__value" data-stat="connected-apps">
                    <span class="stat-loading">—</span>
                </div>
                <div class="stat-item__label">Connected Apps</div>
            </div>
            <div class="stat-item">
                <div class="stat-item__value" data-stat="today-notifications">
                    <span class="stat-loading">—</span>
                </div>
                <div class="stat-item__label">Today</div>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="quick-nav">
            <a href="#" class="quick-nav__card" onclick="window.parent.Dashboard.navigate('notifications.php'); return false;">
                <div class="quick-nav__icon quick-nav__icon--accent">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <div class="quick-nav__text">
                    <div class="quick-nav__title">Notifications</div>
                    <div class="quick-nav__desc">View all activity</div>
                </div>
            </a>
            <a href="#" class="quick-nav__card" onclick="window.parent.Dashboard.navigate('apps.php'); return false;">
                <div class="quick-nav__icon quick-nav__icon--sage">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                </div>
                <div class="quick-nav__text">
                    <div class="quick-nav__title">Apps</div>
                    <div class="quick-nav__desc">Manage connections</div>
                </div>
            </a>
            <a href="#" class="quick-nav__card" onclick="window.parent.Dashboard.navigate('api-keys.php'); return false;">
                <div class="quick-nav__icon">
                    <svg viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                </div>
                <div class="quick-nav__text">
                    <div class="quick-nav__title">API Keys</div>
                    <div class="quick-nav__desc">Manage credentials</div>
                </div>
            </a>
            <a href="#" class="quick-nav__card" onclick="window.parent.Dashboard.navigate('settings.php'); return false;">
                <div class="quick-nav__icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </div>
                <div class="quick-nav__text">
                    <div class="quick-nav__title">Settings</div>
                    <div class="quick-nav__desc">Preferences</div>
                </div>
            </a>
        </div>

        <!-- Main Content -->
        <div class="home__content">
            <!-- Recent Notifications -->
            <div class="panel">
                <div class="panel__header">
                    <h2 class="panel__title">Recent Notifications</h2>
                    <a href="#" class="panel__action" onclick="window.parent.Dashboard.navigate('notifications.php'); return false;">View All</a>
                </div>
                <div class="notification-list" id="notificationList">
                    <!-- Loading state -->
                    <div class="notification-list__loading">
                        <div class="loading-placeholder"></div>
                        <div class="loading-placeholder"></div>
                        <div class="loading-placeholder"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar-stack">
                <!-- API Key Status -->
                <div class="panel">
                    <div class="api-card">
                        <div class="api-card__label">API Key Status</div>
                        <?php if ($hasApiKey): ?>
                        <div class="api-status api-status--active">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            <span>API Key Active</span>
                        </div>
                        <p class="api-card__hint">Your API key is set up and ready to use. Manage it in Settings.</p>
                        <?php else: ?>
                        <div class="api-status api-status--inactive">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span>No API Key</span>
                        </div>
                        <p class="api-card__hint">Generate an API key in Settings to start integrating.</p>
                        <?php endif; ?>
                        <button class="api-card__btn" onclick="window.parent.Dashboard.navigate('settings.php');">
                            <?php echo $hasApiKey ? 'Manage API Key' : 'Generate API Key'; ?>
                        </button>
                    </div>
                </div>

                <!-- Connected Apps -->
                <div class="panel">
                    <div class="panel__header">
                        <h2 class="panel__title">Apps</h2>
                        <a href="#" class="panel__action" onclick="window.parent.Dashboard.navigate('apps.php'); return false;">Manage</a>
                    </div>
                    <div class="apps-mini" id="appsList">
                        <!-- Loading state -->
                        <div class="apps-mini__loading">
                            <div class="loading-placeholder loading-placeholder--sm"></div>
                            <div class="loading-placeholder loading-placeholder--sm"></div>
                            <div class="loading-placeholder loading-placeholder--sm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dynamic greeting based on time
        (function() {
            const hour = new Date().getHours();
            const greeting = document.querySelector('.home__greeting');
            const userName = greeting?.dataset.userName || 'there';
            
            if (greeting) {
                let greetingText = 'Good morning';
                if (hour >= 12 && hour < 17) {
                    greetingText = 'Good afternoon';
                } else if (hour >= 17) {
                    greetingText = 'Good evening';
                }
                greeting.textContent = greetingText + ', ' + userName;
            }
        })();

        // Load dashboard stats
        async function loadDashboardStats() {
            try {
                const response = await fetch('/rabbit/API/read/stats.php', {
                    credentials: 'include'
                });
                const result = await response.json();
                
                if (result.success) {
                    const stats = result.data;
                    
                    // Update total notifications
                    const totalNotifs = document.querySelector('[data-stat="total-notifications"]');
                    if (totalNotifs) {
                        totalNotifs.textContent = stats.notifications.total.toLocaleString();
                    }
                    
                    // Update connected apps
                    const connectedApps = document.querySelector('[data-stat="connected-apps"]');
                    if (connectedApps) {
                        connectedApps.textContent = stats.apps.total.toLocaleString();
                    }
                    
                    // Update today's notifications
                    const todayNotifs = document.querySelector('[data-stat="today-notifications"]');
                    if (todayNotifs) {
                        todayNotifs.textContent = stats.notifications.today.toLocaleString();
                    }
                }
            } catch (error) {
                console.error('Failed to load stats:', error);
                // Show error state
                document.querySelectorAll('[data-stat]').forEach(el => {
                    el.textContent = '—';
                });
            }
        }
        
        // Load stats on page load
        loadDashboardStats();
        
        // Load recent notifications
        async function loadRecentNotifications() {
            const container = document.getElementById('notificationList');
            if (!container) return;
            
            try {
                const response = await fetch('/rabbit/API/read/notifications.php?limit=6', {
                    credentials: 'include'
                });
                const result = await response.json();
                
                if (result.success && result.data.notifications.length > 0) {
                    container.innerHTML = result.data.notifications.map(notif => `
                        <div class="notification-item">
                            <div class="notification-item__icon" style="background-color: ${notif.app_color || 'var(--accent-primary)'}">
                                ${notif.app_name.charAt(0).toUpperCase()}
                            </div>
                            <div class="notification-item__content">
                                <div class="notification-item__title">${escapeHtml(notif.title)}</div>
                                <div class="notification-item__meta">
                                    <span class="notification-item__app">${escapeHtml(notif.app_name)}</span>
                                    <span class="notification-item__time">${notif.created_at_relative}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="empty-state">
                            <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            <div class="empty-state__title">No notifications yet</div>
                            <div class="empty-state__text">Connect an app to start receiving notifications</div>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Failed to load notifications:', error);
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state__title">Failed to load</div>
                        <div class="empty-state__text">Please refresh the page</div>
                    </div>
                `;
            }
        }
        
        // Load connected apps
        async function loadConnectedApps() {
            const container = document.getElementById('appsList');
            if (!container) return;
            
            try {
                const response = await fetch('/rabbit/API/read/apps.php?limit=5&active=1', {
                    credentials: 'include'
                });
                const result = await response.json();
                
                if (result.success && result.data.apps.length > 0) {
                    container.innerHTML = result.data.apps.map(app => `
                        <div class="apps-mini__item">
                            <div class="apps-mini__icon" style="background-color: ${app.color || 'var(--accent-primary)'}">
                                ${app.name.charAt(0).toUpperCase()}
                            </div>
                            <span class="apps-mini__name">${escapeHtml(app.name)}</span>
                            ${app.is_active ? '<span class="apps-mini__status"></span>' : ''}
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="empty-state" style="padding: var(--space-6) var(--space-4);">
                            <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 32px; height: 32px;">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="14" y="14" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                            </svg>
                            <div class="empty-state__title">No apps connected</div>
                            <div class="empty-state__text">Add your first app</div>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Failed to load apps:', error);
                container.innerHTML = `
                    <div class="empty-state" style="padding: var(--space-6) var(--space-4);">
                        <div class="empty-state__title">Failed to load</div>
                    </div>
                `;
            }
        }
        
        // Utility: escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Load all dashboard data
        loadRecentNotifications();
        loadConnectedApps();

        // Listen for theme changes from parent
        window.addEventListener('message', (e) => {
            if (e.data && e.data.type === 'themechange') {
                document.documentElement.setAttribute('data-theme', e.data.theme);
            }
        });
    </script>
</body>
</html>
