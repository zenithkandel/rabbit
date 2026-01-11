<?php require_once __DIR__ . '/../API/config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — Rabbit</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="CSS/settings.css">
</head>
<body>
    <div class="settings-page">
        <!-- Page Header -->
        <header class="page-header">
            <h1 class="page-header__title">Settings</h1>
            <p class="page-header__subtitle">Manage your account and preferences</p>
        </header>

        <!-- Settings Sections -->
        <div class="settings-content">
            <!-- Profile Section -->
            <section class="settings-section">
                <div class="settings-section__header">
                    <div class="settings-section__icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="settings-section__title-group">
                        <h2 class="settings-section__title">Profile Information</h2>
                        <p class="settings-section__desc">Update your personal details</p>
                    </div>
                </div>
                
                <div class="settings-section__content">
                    <form class="settings-form" id="profileForm">
                        <div class="form-group">
                            <label class="form-label" for="fullName">Full Name</label>
                            <input type="text" 
                                   class="form-input" 
                                   id="fullName" 
                                   name="fullName" 
                                   value="John Doe"
                                   placeholder="Enter your full name">
                            <span class="form-hint">This is how you'll appear across Rabbit</span>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" 
                                   class="form-input" 
                                   id="email" 
                                   name="email" 
                                   value="john@example.com"
                                   placeholder="Enter your email">
                            <span class="form-hint">Used for account notifications and recovery</span>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn--primary" id="saveProfileBtn">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Danger Zone Section -->
            <section class="settings-section settings-section--danger">
                <div class="settings-section__header">
                    <div class="settings-section__icon settings-section__icon--danger">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="settings-section__title-group">
                        <h2 class="settings-section__title">Danger Zone</h2>
                        <p class="settings-section__desc">Irreversible and destructive actions</p>
                    </div>
                </div>
                
                <div class="settings-section__content">
                    <div class="danger-actions">
                        <!-- Reset Data -->
                        <div class="danger-action">
                            <div class="danger-action__info">
                                <h3 class="danger-action__title">Reset All Data</h3>
                                <p class="danger-action__desc">Delete all notifications and apps. Your account will remain intact.</p>
                            </div>
                            <button class="btn btn--danger-outline" id="resetDataBtn">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                    <polyline points="1 4 1 10 7 10"/>
                                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                                </svg>
                                <span>Reset Data</span>
                            </button>
                        </div>
                        
                        <!-- Delete Account -->
                        <div class="danger-action danger-action--critical">
                            <div class="danger-action__info">
                                <h3 class="danger-action__title">Delete Account</h3>
                                <p class="danger-action__desc">Permanently delete your account and all associated data. This cannot be undone.</p>
                            </div>
                            <button class="btn btn--danger" id="deleteAccountBtn">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                                <span>Delete Account</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal">
            <div class="modal__header">
                <div class="modal__icon modal__icon--danger">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <h3 class="modal__title" id="modalTitle">Confirm Action</h3>
                <p class="modal__desc" id="modalDesc">Are you sure you want to proceed?</p>
            </div>
            
            <div class="modal__body">
                <div class="confirm-input-group" id="confirmInputGroup">
                    <label class="form-label" for="confirmInput">
                        Type <strong id="confirmPhrase">DELETE</strong> to confirm
                    </label>
                    <input type="text" 
                           class="form-input form-input--danger" 
                           id="confirmInput" 
                           placeholder="Type here to confirm"
                           autocomplete="off">
                </div>
            </div>
            
            <div class="modal__footer">
                <button class="btn btn--secondary" id="modalCancelBtn">Cancel</button>
                <button class="btn btn--danger" id="modalConfirmBtn" disabled>
                    <span id="modalConfirmText">Confirm</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Scripts -->
    <script src="../JS/global.js"></script>
    <script src="JS/settings.js"></script>
</body>
</html>
