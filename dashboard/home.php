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
            padding: var(--space-10) var(--space-8);
            background: var(--bg-primary);
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .home__header {
            margin-bottom: var(--space-12);
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
            margin-bottom: var(--space-12);
            padding: var(--space-8) 0;
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
        
        /* Clean Two Column Layout */
        .home__content {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: var(--space-8);
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
            margin-bottom: var(--space-4);
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
                padding: var(--space-6) var(--space-4);
            }
            
            .home__header {
                margin-bottom: var(--space-8);
            }
            
            .home__greeting {
                font-size: var(--text-2xl);
            }
            
            .stats-row {
                flex-wrap: wrap;
                gap: var(--space-6);
                padding: var(--space-6) 0;
                margin-bottom: var(--space-8);
            }
            
            .stat-item {
                flex: 1;
                min-width: 100px;
            }
            
            .stat-item__value {
                font-size: var(--text-2xl);
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
            <h1 class="home__greeting">Welcome back, John</h1>
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
            <div class="stat-item">
                <div class="stat-item__value">48ms</div>
                <div class="stat-item__label">Avg. Latency</div>
            </div>
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
                <!-- API Usage -->
                <div class="panel">
                    <div class="api-card">
                        <div class="api-card__label">Your API Key</div>
                        <div class="api-card__key">
                            <span class="api-card__key-value">rb_live_••••••••4f2a</span>
                            <button class="api-card__key-copy" onclick="copyApiKey()">Copy</button>
                        </div>
                        <div class="api-card__usage">
                            <span class="api-card__usage-label">Monthly Usage</span>
                            <span class="api-card__usage-value">2,847 / 10,000</span>
                        </div>
                        <div class="api-card__bar">
                            <div class="api-card__bar-fill" style="width: 28.47%;"></div>
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
        // Copy API Key function
        function copyApiKey() {
            const fullKey = 'rb_live_a8f3k2m9x7p4q1w5e6r3t8y2u0i4f2a';
            navigator.clipboard.writeText(fullKey).then(() => {
                const btn = document.querySelector('.api-card__key-copy');
                const originalText = btn.textContent;
                btn.textContent = 'Copied!';
                setTimeout(() => {
                    btn.textContent = originalText;
                }, 2000);
            });
        }

        // Dynamic greeting based on time
        (function() {
            const hour = new Date().getHours();
            const greeting = document.querySelector('.home__greeting');
            if (greeting) {
                let greetingText = 'Good morning';
                if (hour >= 12 && hour < 17) {
                    greetingText = 'Good afternoon';
                } else if (hour >= 17) {
                    greetingText = 'Good evening';
                }
                greeting.textContent = greetingText + ', John';
            }
        })();

        // Listen for theme changes from parent
        window.addEventListener('message', (e) => {
            if (e.data && e.data.type === 'themechange') {
                document.documentElement.setAttribute('data-theme', e.data.theme);
            }
        });
    </script>
</body>
</html>
