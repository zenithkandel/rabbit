<?php 
require_once __DIR__ . '/../API/config/auth.php';

// Get first name for greeting
$firstName = explode(' ', trim($currentUser['name'] ?? 'User'))[0];
$firstName = htmlspecialchars($firstName);
$userId = $currentUser['id'];
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
        
        .api-card__key {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-4);
            background: var(--bg-primary);
            border: var(--border-thin) solid var(--border-light);
        }
        
        .api-card__key-value {
            flex: 1;
            font-family: var(--font-mono);
            font-size: var(--text-sm);
            color: var(--text-primary);
        }
        
        .api-card__key-copy {
            padding: var(--space-1) var(--space-2);
            font-family: var(--font-mono);
            font-size: var(--text-xs);
            color: var(--text-muted);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: color var(--duration-fast);
        }
        
        .api-card__key-copy:hover {
            color: var(--text-primary);
        }
        
        .api-card__usage {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: var(--text-sm);
            margin-bottom: var(--space-2);
        }
        
        .api-card__usage-label { color: var(--text-muted); }
        .api-card__usage-value { 
            font-family: var(--font-mono);
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .api-card__bar {
            height: 4px;
            background: var(--bg-primary);
            border: var(--border-thin) solid var(--border-light);
        }
        
        .api-card__bar-fill {
            height: 100%;
            background: var(--accent-secondary);
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
        <!-- Welcome Header -->
        <header class="home__header">
            <h1 class="home__greeting" data-user-name="<?php echo $firstName; ?>">Welcome back, <?php echo $firstName; ?></h1>
            <p class="home__subtitle">Here's what's happening with your notifications</p>
        </header>

        <!-- Key Stats Row -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-item__value">2,847</div>
                <div class="stat-item__label">Total Notifications</div>
            </div>
            <div class="stat-item">
                <div class="stat-item__value">5</div>
                <div class="stat-item__label">Connected Apps</div>
            </div>
            <div class="stat-item">
                <div class="stat-item__value">342</div>
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
                <div class="notification-list">
                    <div class="notification-item">
                        <div class="notification-item__icon">S</div>
                        <div class="notification-item__content">
                            <div class="notification-item__title">Payment Received — Invoice #4521 paid by Acme Corp</div>
                            <div class="notification-item__meta">
                                <span class="notification-item__app">Stripe</span>
                                <span class="notification-item__time">2 min ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-item__icon notification-item__icon--ink">A</div>
                        <div class="notification-item__content">
                            <div class="notification-item__title">New user registered: sarah@acme.co</div>
                            <div class="notification-item__meta">
                                <span class="notification-item__app">Auth0</span>
                                <span class="notification-item__time">6 min ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-item__icon notification-item__icon--ink">V</div>
                        <div class="notification-item__content">
                            <div class="notification-item__title">Deploy successful: production v2.4.1</div>
                            <div class="notification-item__meta">
                                <span class="notification-item__app">Vercel</span>
                                <span class="notification-item__time">27 min ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-item__icon notification-item__icon--sage">G</div>
                        <div class="notification-item__content">
                            <div class="notification-item__title">PR merged: feat/webhook-retry #287</div>
                            <div class="notification-item__meta">
                                <span class="notification-item__app">GitHub</span>
                                <span class="notification-item__time">1 hour ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-item__icon">D</div>
                        <div class="notification-item__content">
                            <div class="notification-item__title">CPU usage alert: server-prod-01 at 85%</div>
                            <div class="notification-item__meta">
                                <span class="notification-item__app">Datadog</span>
                                <span class="notification-item__time">2 hours ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-item__icon">S</div>
                        <div class="notification-item__content">
                            <div class="notification-item__title">Subscription renewed: Pro Plan</div>
                            <div class="notification-item__meta">
                                <span class="notification-item__app">Stripe</span>
                                <span class="notification-item__time">Yesterday</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar-stack">
                <!-- API Key -->
                <div class="panel">
                    <div class="api-card">
                        <div class="api-card__label">Your API Key</div>
                        <div class="api-card__key">
                            <span class="api-card__key-value" id="apiKeyDisplay">Loading...</span>
                            <button class="api-card__key-copy" id="copyApiKeyBtn">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Connected Apps -->
                <div class="panel">
                    <div class="panel__header">
                        <h2 class="panel__title">Apps</h2>
                        <a href="#" class="panel__action" onclick="window.parent.Dashboard.navigate('apps.php'); return false;">Manage</a>
                    </div>
                    <div class="apps-mini">
                        <div class="apps-mini__item">
                            <div class="apps-mini__icon">S</div>
                            <span class="apps-mini__name">Stripe</span>
                            <span class="apps-mini__status"></span>
                        </div>
                        <div class="apps-mini__item">
                            <div class="apps-mini__icon apps-mini__icon--sage">G</div>
                            <span class="apps-mini__name">GitHub</span>
                            <span class="apps-mini__status"></span>
                        </div>
                        <div class="apps-mini__item">
                            <div class="apps-mini__icon apps-mini__icon--ink">V</div>
                            <span class="apps-mini__name">Vercel</span>
                            <span class="apps-mini__status"></span>
                        </div>
                        <div class="apps-mini__item">
                            <div class="apps-mini__icon apps-mini__icon--ink">A</div>
                            <span class="apps-mini__name">Auth0</span>
                            <span class="apps-mini__status"></span>
                        </div>
                        <div class="apps-mini__item">
                            <div class="apps-mini__icon">D</div>
                            <span class="apps-mini__name">Datadog</span>
                            <span class="apps-mini__status"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store for API key (loaded from server)
        let currentApiKey = null;
        
        // Load API key on page load
        async function loadApiKey() {
            try {
                // Check if there's a new API key in sessionStorage (from signup)
                const newKey = sessionStorage.getItem('rabbit_new_api_key');
                if (newKey) {
                    currentApiKey = newKey;
                    displayApiKey(newKey);
                    // Clear from sessionStorage after displaying
                    sessionStorage.removeItem('rabbit_new_api_key');
                    sessionStorage.removeItem('rabbit_user_name');
                    return;
                }
                
                const response = await fetch('/rabbit/API/read/apikey.php', {
                    credentials: 'include'
                });
                const result = await response.json();
                
                if (result.success && result.data.api_key) {
                    currentApiKey = result.data.api_key;
                    displayApiKey(result.data.api_key);
                } else if (result.data.masked) {
                    document.getElementById('apiKeyDisplay').textContent = result.data.masked;
                } else {
                    document.getElementById('apiKeyDisplay').textContent = 'No API key';
                }
            } catch (error) {
                console.error('Failed to load API key:', error);
                document.getElementById('apiKeyDisplay').textContent = 'Error loading key';
            }
        }
        
        function displayApiKey(key) {
            const display = document.getElementById('apiKeyDisplay');
            if (key) {
                // Show masked version
                const masked = key.substring(0, 6) + '••••••••••••' + key.substring(key.length - 4);
                display.textContent = masked;
            }
        }
        
        // Copy API Key function
        document.getElementById('copyApiKeyBtn').addEventListener('click', async () => {
            const btn = document.getElementById('copyApiKeyBtn');
            
            if (currentApiKey) {
                try {
                    await navigator.clipboard.writeText(currentApiKey);
                    const originalText = btn.textContent;
                    btn.textContent = 'Copied!';
                    setTimeout(() => {
                        btn.textContent = originalText;
                    }, 2000);
                } catch (e) {
                    btn.textContent = 'Failed';
                    setTimeout(() => btn.textContent = 'Copy', 2000);
                }
            } else {
                btn.textContent = 'No key';
                setTimeout(() => btn.textContent = 'Copy', 2000);
            }
        });

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
        
        // Initialize on load
        loadApiKey();

        // Listen for theme changes from parent
        window.addEventListener('message', (e) => {
            if (e.data && e.data.type === 'themechange') {
                document.documentElement.setAttribute('data-theme', e.data.theme);
            }
        });
    </script>
</body>
</html>
