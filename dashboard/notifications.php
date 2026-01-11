<?php require_once __DIR__ . '/../API/config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications — Rabbit Dashboard</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="CSS/notifications.css">
    
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
    <div class="notifications-page">
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-header__top">
                <div class="page-header__title-group">
                    <h1 class="page-header__title">Notifications</h1>
                    <p class="page-header__subtitle">
                        <span id="totalCount">0</span> total notifications
                    </p>
                </div>
                <div class="page-header__actions">
                    <button class="btn btn--ghost" id="selectModeToggle">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                            <polyline points="9 11 12 14 22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        <span>Select</span>
                    </button>
                    <button class="btn btn--ghost" id="markAllReadBtn" title="Mark all as read">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>Mark All Read</span>
                    </button>
                </div>
            </div>
            
            <!-- Filter Bar -->
            <div class="filter-bar">
                <!-- Search -->
                <div class="search-box">
                    <svg class="search-box__icon" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" class="search-box__input" id="searchInput" placeholder="Search notifications...">
                    <button class="search-box__clear" id="searchClear" hidden>
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Filter Options Row -->
                <div class="filter-options">
                    <!-- Date Range Filter -->
                    <div class="filter-dropdown" id="dateFilterDropdown">
                        <button class="filter-dropdown__trigger" id="dateFilterTrigger">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span id="dateFilterLabel">All Time</span>
                            <svg class="filter-dropdown__chevron" viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="filter-dropdown__menu filter-dropdown__menu--date" id="dateFilterMenu">
                            <div class="filter-dropdown__header">Filter by Date</div>
                            <div class="date-filter__presets">
                                <button class="date-preset is-active" data-preset="all">All Time</button>
                                <button class="date-preset" data-preset="today">Today</button>
                                <button class="date-preset" data-preset="week">This Week</button>
                                <button class="date-preset" data-preset="month">This Month</button>
                                <button class="date-preset" data-preset="custom">Custom Range</button>
                            </div>
                            <div class="date-filter__custom" id="dateFilterCustom" hidden>
                                <div class="date-filter__row">
                                    <label class="date-filter__label">From</label>
                                    <input type="date" class="date-filter__input" id="dateFrom">
                                </div>
                                <div class="date-filter__row">
                                    <label class="date-filter__label">To</label>
                                    <input type="date" class="date-filter__input" id="dateTo">
                                </div>
                                <button class="btn btn--sm btn--primary date-filter__apply" id="dateApplyBtn">Apply Range</button>
                            </div>
                            <button class="date-filter__clear" id="dateClearBtn" hidden>Clear Date Filter</button>
                        </div>
                    </div>
                    
                    <!-- App Filter Dropdown -->
                    <div class="filter-dropdown" id="appFilterDropdown">
                        <button class="filter-dropdown__trigger" id="appFilterTrigger">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                            </svg>
                            <span id="appFilterLabel">All Apps</span>
                            <svg class="filter-dropdown__chevron" viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="filter-dropdown__menu filter-dropdown__menu--apps" id="appFilterMenu">
                            <div class="filter-dropdown__header">Filter by App</div>
                            <div class="filter-dropdown__list" id="appFilterList">
                                <!-- App options will be dynamically inserted -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="filter-dropdown">
                        <button class="filter-dropdown__trigger" id="sortTrigger">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <line x1="4" y1="6" x2="20" y2="6"/>
                                <line x1="4" y1="12" x2="14" y2="12"/>
                                <line x1="4" y1="18" x2="8" y2="18"/>
                            </svg>
                            <span id="sortLabel">Newest First</span>
                            <svg class="filter-dropdown__chevron" viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="filter-dropdown__menu" id="sortMenu">
                            <button class="filter-dropdown__item is-active" data-sort="newest">Newest First</button>
                            <button class="filter-dropdown__item" data-sort="oldest">Oldest First</button>
                            <button class="filter-dropdown__item" data-sort="app">Group by App</button>
                            <button class="filter-dropdown__item" data-sort="unread">Unread First</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Selection Bar (hidden by default) -->
        <div class="selection-bar" id="selectionBar">
            <div class="selection-bar__info">
                <button class="selection-bar__checkbox" id="selectAllCheckbox">
                    <svg class="icon-check" viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="3" fill="none">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </button>
                <span class="selection-bar__count">
                    <span id="selectedCount">0</span> selected
                </span>
            </div>
            <div class="selection-bar__actions">
                <button class="btn btn--ghost btn--sm" id="markSelectedReadBtn">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Mark Read
                </button>
                <button class="btn btn--ghost btn--sm btn--danger" id="deleteSelectedBtn">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                    Delete
                </button>
                <button class="btn btn--ghost btn--sm" id="cancelSelectionBtn">
                    Cancel
                </button>
            </div>
        </div>

        <!-- Notifications Container -->
        <main class="notifications-container" id="notificationsContainer">
            <!-- Notification groups will be rendered here -->
            <div class="notifications-loading" id="loadingState">
                <div class="notifications-loading__spinner"></div>
                <p>Loading notifications...</p>
            </div>
        </main>

        <!-- Empty State -->
        <div class="empty-state" id="emptyState" hidden>
            <div class="empty-state__icon">
                <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
            <h3 class="empty-state__title">No notifications found</h3>
            <p class="empty-state__text" id="emptyStateText">Try adjusting your filters or search query</p>
        </div>

        <!-- Load More -->
        <div class="load-more" id="loadMore" hidden>
            <button class="btn btn--outline" id="loadMoreBtn">
                Load More Notifications
            </button>
            <p class="load-more__info">
                Showing <span id="showingCount">0</span> of <span id="filteredCount">0</span>
            </p>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal__backdrop"></div>
        <div class="modal__container">
            <div class="modal__header">
                <h2 class="modal__title">Delete Notifications</h2>
                <button class="modal__close" id="deleteModalClose">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal__body">
                <p>Are you sure you want to delete <strong id="deleteCount">0</strong> notification(s)?</p>
                <p class="modal__warning">This action cannot be undone.</p>
            </div>
            <div class="modal__footer">
                <button class="btn btn--ghost" id="deleteCancelBtn">Cancel</button>
                <button class="btn btn--danger" id="deleteConfirmBtn">Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="JS/notifications.js"></script>
    <script>
        // Listen for theme changes from parent
        window.addEventListener('message', (e) => {
            if (e.data && e.data.type === 'themechange') {
                document.documentElement.setAttribute('data-theme', e.data.theme);
            }
        });
    </script>
</body>
</html>
