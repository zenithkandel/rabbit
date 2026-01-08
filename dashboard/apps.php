<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apps — Rabbit</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="CSS/apps.css">
</head>
<body>
    <div class="apps-page">
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-header__top">
                <div class="page-header__title-group">
                    <h1 class="page-header__title">Connected Apps</h1>
                    <p class="page-header__subtitle">
                        <span id="appCount">0</span> apps connected
                    </p>
                </div>
                <div class="page-header__actions">
                    <button class="btn btn--primary" id="addAppBtn">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <span>Add App</span>
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
                    <input type="text" class="search-box__input" id="searchInput" placeholder="Search apps...">
                    <button class="search-box__clear" id="searchClear" hidden>
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Sort Dropdown -->
                <div class="filter-options">
                    <div class="filter-dropdown">
                        <button class="filter-dropdown__trigger" id="sortTrigger">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <line x1="4" y1="6" x2="20" y2="6"/>
                                <line x1="4" y1="12" x2="14" y2="12"/>
                                <line x1="4" y1="18" x2="8" y2="18"/>
                            </svg>
                            <span id="sortLabel">Most Notifications</span>
                            <svg class="filter-dropdown__chevron" viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="filter-dropdown__menu" id="sortMenu">
                            <button class="filter-dropdown__item is-active" data-sort="notifications">Most Notifications</button>
                            <button class="filter-dropdown__item" data-sort="recent">Recently Used</button>
                            <button class="filter-dropdown__item" data-sort="name">Alphabetical</button>
                            <button class="filter-dropdown__item" data-sort="oldest">Oldest Connected</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Apps Grid -->
        <div class="apps-grid" id="appsGrid">
            <!-- App cards will be dynamically inserted -->
        </div>
        
        <!-- Empty State -->
        <div class="empty-state" id="emptyState" hidden>
            <div class="empty-state__icon">
                <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                </svg>
            </div>
            <h3 class="empty-state__title">No apps found</h3>
            <p class="empty-state__text" id="emptyStateText">Connect your first app to start receiving notifications</p>
            <button class="btn btn--primary" id="emptyAddBtn">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span>Add Your First App</span>
            </button>
        </div>

        <!-- Loading State -->
        <div class="loading-state" id="loadingState">
            <div class="loading-spinner"></div>
            <p>Loading apps...</p>
        </div>
    </div>

    <!-- Add/Edit App Modal -->
    <div class="modal" id="appModal">
        <div class="modal__backdrop"></div>
        <div class="modal__container modal__container--lg">
            <div class="modal__header">
                <h2 class="modal__title" id="modalTitle">Add New App</h2>
                <button class="modal__close" id="modalClose">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal__body">
                <form id="appForm" class="app-form">
                    <!-- App Icon Preview -->
                    <div class="app-form__icon-section">
                        <div class="app-form__icon-preview" id="iconPreview">
                            <span id="iconLetter">A</span>
                        </div>
                        <div class="app-form__icon-controls">
                            <label class="form-label">App Color</label>
                            <div class="color-picker" id="colorPicker">
                                <button type="button" class="color-swatch is-selected" data-color="#635BFF" style="background: #635BFF"></button>
                                <button type="button" class="color-swatch" data-color="#24292F" style="background: #24292F"></button>
                                <button type="button" class="color-swatch" data-color="#EB5424" style="background: #EB5424"></button>
                                <button type="button" class="color-swatch" data-color="#632CA6" style="background: #632CA6"></button>
                                <button type="button" class="color-swatch" data-color="#4A154B" style="background: #4A154B"></button>
                                <button type="button" class="color-swatch" data-color="#5865F2" style="background: #5865F2"></button>
                                <button type="button" class="color-swatch" data-color="#F22F46" style="background: #F22F46"></button>
                                <button type="button" class="color-swatch" data-color="#1A82E2" style="background: #1A82E2"></button>
                                <button type="button" class="color-swatch" data-color="#FF9900" style="background: #FF9900"></button>
                                <button type="button" class="color-swatch" data-color="#00A67E" style="background: #00A67E"></button>
                                <button type="button" class="color-swatch" data-color="#B94A2C" style="background: #B94A2C"></button>
                                <button type="button" class="color-swatch" data-color="#5C6B55" style="background: #5C6B55"></button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Fields -->
                    <div class="app-form__fields">
                        <div class="form-group">
                            <label class="form-label" for="appName">App Name <span class="required">*</span></label>
                            <input type="text" class="form-input" id="appName" name="appName" placeholder="e.g., Stripe, GitHub, Slack" required>
                            <p class="form-hint">The display name for this app</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="appSlug">App Identifier <span class="required">*</span></label>
                            <input type="text" class="form-input form-input--mono" id="appSlug" name="appSlug" placeholder="e.g., stripe, github, slack" required>
                            <p class="form-hint">Unique identifier used in API calls (lowercase, no spaces)</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="appWebhook">Webhook URL</label>
                            <div class="form-input-group">
                                <input type="text" class="form-input form-input--mono" id="appWebhook" name="appWebhook" readonly>
                                <button type="button" class="btn btn--ghost btn--icon" id="copyWebhookBtn" title="Copy webhook URL">
                                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="form-hint">Use this URL to send notifications from your app</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="appDescription">Description</label>
                            <textarea class="form-textarea" id="appDescription" name="appDescription" rows="3" placeholder="Optional description for this app..."></textarea>
                        </div>
                    </div>
                    
                    <!-- App Stats (Edit mode only) -->
                    <div class="app-form__stats" id="appStats" hidden>
                        <h3 class="app-form__section-title">Statistics</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-item__value" id="statNotifications">0</span>
                                <span class="stat-item__label">Notifications</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-item__value" id="statConnected">—</span>
                                <span class="stat-item__label">Connected</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-item__value" id="statLastUsed">—</span>
                                <span class="stat-item__label">Last Activity</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Danger Zone (Edit mode only) -->
                    <div class="app-form__danger" id="dangerZone" hidden>
                        <h3 class="app-form__section-title app-form__section-title--danger">Danger Zone</h3>
                        <div class="danger-actions">
                            <div class="danger-action">
                                <div class="danger-action__info">
                                    <h4 class="danger-action__title">Clear Notifications</h4>
                                    <p class="danger-action__desc">Remove all notifications from this app. This action cannot be undone.</p>
                                </div>
                                <button type="button" class="btn btn--outline btn--danger" id="clearNotificationsBtn">
                                    Clear All
                                </button>
                            </div>
                            <div class="danger-action">
                                <div class="danger-action__info">
                                    <h4 class="danger-action__title">Disconnect App</h4>
                                    <p class="danger-action__desc">Remove this app and all its data permanently. This action cannot be undone.</p>
                                </div>
                                <button type="button" class="btn btn--danger" id="disconnectAppBtn">
                                    Disconnect
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" id="modalCancelBtn">Cancel</button>
                <button type="submit" form="appForm" class="btn btn--primary" id="modalSaveBtn">
                    <span id="saveBtnText">Add App</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal modal--sm" id="confirmModal">
        <div class="modal__backdrop"></div>
        <div class="modal__container">
            <div class="modal__header">
                <h2 class="modal__title" id="confirmTitle">Confirm Action</h2>
                <button class="modal__close" id="confirmClose">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal__body">
                <div class="confirm-content">
                    <div class="confirm-icon" id="confirmIcon">
                        <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <p class="confirm-message" id="confirmMessage">Are you sure you want to proceed?</p>
                    <p class="confirm-warning" id="confirmWarning">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" id="confirmCancelBtn">Cancel</button>
                <button type="button" class="btn btn--danger" id="confirmActionBtn">
                    <span id="confirmActionText">Confirm</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="JS/apps.js"></script>
</body>
</html>
