<?php 
require_once __DIR__ . '/../API/config/auth.php';

// Helper function to get user initials
function getUserInitials($name) {
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach ($parts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper($part[0]);
        }
    }
    return substr($initials, 0, 2);
}

$userInitials = getUserInitials($currentUser['name'] ?? 'User');
$userName = htmlspecialchars($currentUser['name'] ?? 'User');
$userEmail = htmlspecialchars($currentUser['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rabbit Dashboard — Manage your notifications and connected apps">
    <meta name="theme-color" content="#F7F4EE">
    
    <title>Dashboard — Rabbit</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="CSS/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <!-- Taskbar -->
        <header class="taskbar">
            <div class="taskbar__main">
                <div class="taskbar__left">
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-nav-toggle" aria-label="Open menu">
                        <svg viewBox="0 0 24 24">
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                    
                    <!-- Brand -->
                    <a href="../index.php" class="taskbar__brand">
                        <div class="taskbar__logo">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <span class="taskbar__wordmark">Rabbit</span>
                    </a>
                    
                    <!-- Navigation -->
                    <nav class="taskbar__nav">
                        <a href="#" class="taskbar__link is-active" data-page="home.php">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            Home
                        </a>
                        <a href="#" class="taskbar__link" data-page="notifications.php">
                            <svg viewBox="0 0 24 24">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            Notifications
                        </a>
                        <a href="#" class="taskbar__link" data-page="apps.php">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="14" y="14" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                            </svg>
                            Apps
                        </a>
                        <a href="#" class="taskbar__link" data-page="connect.php">
                            <svg viewBox="0 0 24 24">
                                <polyline points="16 18 22 12 16 6"/>
                                <polyline points="8 6 2 12 8 18"/>
                            </svg>
                            Connect
                        </a>
                    </nav>
                </div>
                
                <div class="taskbar__right">
                    <!-- Quick Stats -->
                    <div class="taskbar__stats">
                        <div class="taskbar__stat">
                            <span class="taskbar__stat-dot"></span>
                            <span class="taskbar__stat-value" data-stat="notifications">2,847</span>
                            <span>notifications</span>
                        </div>
                        <div class="taskbar__stat">
                            <span class="taskbar__stat-value" data-stat="apps">5</span>
                            <span>apps</span>
                        </div>
                    </div>
                    
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" aria-label="Toggle theme">
                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="user-menu">
                        <button class="user-menu__trigger">
                            <div class="user-menu__avatar"><?php echo $userInitials; ?></div>
                            <div class="user-menu__info">
                                <div class="user-menu__name"><?php echo $userName; ?></div>
                                <div class="user-menu__email"><?php echo $userEmail; ?></div>
                            </div>
                            <svg class="user-menu__chevron" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        
                        <div class="user-menu__dropdown">
                            <button class="user-menu__item" data-action="settings">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                                Settings
                            </button>
                            <button class="user-menu__item" data-action="theme">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="5"/>
                                    <line x1="12" y1="1" x2="12" y2="3"/>
                                    <line x1="12" y1="21" x2="12" y2="23"/>
                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                    <line x1="1" y1="12" x2="3" y2="12"/>
                                    <line x1="21" y1="12" x2="23" y2="12"/>
                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                                </svg>
                                Toggle Theme
                            </button>
                            <div class="user-menu__divider"></div>
                            <button class="user-menu__item user-menu__item--danger" data-action="logout">
                                <svg viewBox="0 0 24 24">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                Sign Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <div class="mobile-menu__overlay"></div>
            <div class="mobile-menu__panel">
                <div class="mobile-menu__header">
                    <a href="../index.php" class="taskbar__brand">
                        <div class="taskbar__logo">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <span class="taskbar__wordmark">Rabbit</span>
                    </a>
                    <button class="mobile-menu__close" aria-label="Close menu">&times;</button>
                </div>
                <nav class="mobile-menu__nav">
                    <a href="home.php" class="mobile-menu__link is-active">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Home
                    </a>
                    <a href="notifications.php" class="mobile-menu__link">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        Notifications
                    </a>
                    <a href="apps.php" class="mobile-menu__link">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                        Apps
                    </a>
                    <a href="connect.php" class="mobile-menu__link">
                        <svg viewBox="0 0 24 24">
                            <polyline points="16 18 22 12 16 6"/>
                            <polyline points="8 6 2 12 8 18"/>
                        </svg>
                        Connect
                    </a>
                    <a href="settings.php" class="mobile-menu__link">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                        Settings
                    </a>
                </nav>
                <div class="mobile-menu__footer">
                    <button class="btn btn--outline" style="width: 100%;" onclick="window.Dashboard.logout();">
                        Sign Out
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content (Iframe) -->
        <main class="dashboard__content">
            <div class="dashboard__loader">
                <div class="loader-spinner"></div>
            </div>
            <iframe class="dashboard__iframe" src="home.php" title="Dashboard Content"></iframe>
        </main>
    </div>

    <!-- Scripts -->
    <script src="../JS/global.js"></script>
    <script src="JS/dashboard.js"></script>
</body>
</html>
