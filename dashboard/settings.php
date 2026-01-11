<?php 
require_once __DIR__ . '/../API/config/auth.php';

$userName = htmlspecialchars($currentUser['name'] ?? '');
$userEmail = htmlspecialchars($currentUser['email'] ?? '');
$hasApiKey = $currentUser['has_api_key'] ?? false;
?>
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
                                   name="name" 
                                   value="<?php echo $userName; ?>"
                                   placeholder="Enter your full name">
                            <span class="form-hint">This is how you'll appear across Rabbit</span>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" 
                                   class="form-input" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo $userEmail; ?>"
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

            <!-- API Key Section -->
            <section class="settings-section">
                <div class="settings-section__header">
                    <div class="settings-section__icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                        </svg>
                    </div>
                    <div class="settings-section__title-group">
                        <h2 class="settings-section__title">API Key</h2>
                        <p class="settings-section__desc">Manage your API authentication key</p>
                    </div>
                </div>
                
                <div class="settings-section__content">
                    <?php if ($hasApiKey): ?>
                    <!-- Has API Key - Show status and regenerate option -->
                    <div class="api-key-status">
                        <div class="api-key-status__badge api-key-status__badge--active">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            API Key Active
                        </div>
                        <p class="api-key-status__desc">Your API key is configured and ready to use. If you need to regenerate it, your old key will be immediately invalidated.</p>
                        <div class="api-key-status__warning">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span>Remember: API keys can only be viewed once when generated. Make sure to save it securely.</span>
                        </div>
                        <button type="button" class="btn btn--secondary" id="regenerateKeyBtn">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <polyline points="23 4 23 10 17 10"/>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                            </svg>
                            <span>Regenerate API Key</span>
                        </button>
                    </div>
                    <?php else: ?>
                    <!-- No API Key - Show generate prompt -->
                    <div class="api-key-status">
                        <div class="api-key-status__badge api-key-status__badge--inactive">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            No API Key
                        </div>
                        <p class="api-key-status__desc">You haven't generated an API key yet. Create one to start sending notifications from your applications.</p>
                        <div class="api-key-status__warning">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span>Important: Your API key will only be shown ONCE after generation. Make sure to copy and save it securely!</span>
                        </div>
                        <button type="button" class="btn btn--primary" id="generateKeyBtn">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                            </svg>
                            <span>Generate API Key</span>
                        </button>
                    </div>
                    <?php endif; ?>
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

    <!-- API Key Success Modal -->
    <div class="modal-overlay" id="apiKeyModal">
        <div class="modal modal--success">
            <div class="modal__header">
                <div class="modal__icon modal__icon--success">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3 class="modal__title">API Key Generated!</h3>
                <p class="modal__desc">Your new API key has been created. Copy it now — you won't be able to see it again.</p>
            </div>
            
            <div class="modal__body">
                <div class="api-key-reveal">
                    <label class="form-label">Your API Key</label>
                    <div class="api-key-reveal__box">
                        <code class="api-key-reveal__value" id="newApiKeyValue">rb_live_xxxx...</code>
                        <button type="button" class="btn btn--primary btn--sm" id="copyNewKeyBtn">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            Copy
                        </button>
                    </div>
                    <div class="api-key-reveal__warning">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <strong>This is the only time you'll see this key!</strong> Store it in a secure location like a password manager or environment variable.
                    </div>
                </div>
            </div>
            
            <div class="modal__footer">
                <button class="btn btn--secondary" id="apiKeyModalClose">I've Saved My Key</button>
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
