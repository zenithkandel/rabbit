/* ═══════════════════════════════════════════════════════════════
   RABBIT — Settings Page JavaScript
   Profile management, API key, and danger zone actions
   ═══════════════════════════════════════════════════════════════ */

(function() {
    'use strict';

    const { Theme, Toast } = window.Rabbit;
    
    // Store for API key
    let currentApiKey = null;

    /* ─────────────────────────────────────────────────────────────────
       API Key Controller
       ───────────────────────────────────────────────────────────────── */
    const ApiKeyManager = {
        displayEl: null,
        copyBtn: null,
        regenerateBtn: null,
        
        init() {
            this.displayEl = document.getElementById('currentApiKey');
            this.copyBtn = document.getElementById('copyApiKeyBtn');
            this.regenerateBtn = document.getElementById('regenerateKeyBtn');
            
            if (!this.displayEl) return;
            
            this.loadApiKey();
            this.bindEvents();
        },
        
        async loadApiKey() {
            try {
                // Check for new key from signup
                const newKey = sessionStorage.getItem('rabbit_new_api_key');
                if (newKey) {
                    currentApiKey = newKey;
                    this.displayEl.textContent = this.maskKey(newKey);
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
                    this.displayEl.textContent = this.maskKey(currentApiKey);
                } else if (result.data.masked) {
                    this.displayEl.textContent = result.data.masked;
                } else {
                    this.displayEl.textContent = 'No API key generated';
                }
            } catch (error) {
                console.error('Failed to load API key:', error);
                this.displayEl.textContent = 'Error loading key';
            }
        },
        
        maskKey(key) {
            if (!key || key.length <= 10) return key || 'N/A';
            return key.substring(0, 6) + '••••••••••••' + key.substring(key.length - 4);
        },
        
        bindEvents() {
            if (this.copyBtn) {
                this.copyBtn.addEventListener('click', () => this.copyKey());
            }
            
            if (this.regenerateBtn) {
                this.regenerateBtn.addEventListener('click', () => this.confirmRegenerate());
            }
        },
        
        async copyKey() {
            if (!currentApiKey) {
                Toast.error('No API key to copy');
                return;
            }
            
            try {
                await navigator.clipboard.writeText(currentApiKey);
                Toast.success('API key copied to clipboard');
            } catch (err) {
                Toast.error('Failed to copy API key');
            }
        },
        
        confirmRegenerate() {
            ConfirmModal.open({
                title: 'Regenerate API Key',
                description: 'Your current API key will be invalidated immediately. Any apps using the old key will stop working.',
                phrase: 'REGENERATE',
                confirmText: 'Regenerate Key',
                action: () => this.regenerateKey()
            });
        },
        
        async regenerateKey() {
            try {
                const response = await fetch('/rabbit/API/update/apikey.php', {
                    method: 'POST',
                    credentials: 'include'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    currentApiKey = result.data.api_key;
                    this.displayEl.textContent = this.maskKey(currentApiKey);
                    Toast.success('API key regenerated successfully');
                } else {
                    Toast.error(result.message || 'Failed to regenerate API key');
                }
            } catch (error) {
                console.error('Regenerate error:', error);
                Toast.error('Connection error. Please try again.');
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Profile Form Controller
       ───────────────────────────────────────────────────────────────── */
    const ProfileForm = {
        form: null,
        saveBtn: null,
        
        init() {
            this.form = document.getElementById('profileForm');
            this.saveBtn = document.getElementById('saveProfileBtn');
            
            if (!this.form) return;
            
            this.bindEvents();
        },
        
        bindEvents() {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.save();
            });
        },
        
        async save() {
            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            
            // Basic validation
            if (!fullName || fullName.length < 2) {
                Toast.error('Name must be at least 2 characters');
                return;
            }
            
            if (!email || !this.isValidEmail(email)) {
                Toast.error('Please enter a valid email address');
                return;
            }
            
            // Disable button
            this.saveBtn.disabled = true;
            const originalHTML = this.saveBtn.innerHTML;
            this.saveBtn.innerHTML = '<span>Saving...</span>';
            
            try {
                const response = await fetch('/rabbit/API/update/user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        name: fullName,
                        email: email
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Toast.success('Profile updated successfully');
                    
                    // Update parent frame if exists
                    if (window.parent !== window) {
                        window.parent.postMessage({ 
                            type: 'userUpdated', 
                            user: result.data.user 
                        }, '*');
                    }
                } else {
                    if (result.errors) {
                        const firstError = Object.values(result.errors)[0];
                        Toast.error(firstError);
                    } else {
                        Toast.error(result.message || 'Failed to update profile');
                    }
                }
            } catch (error) {
                console.error('Save error:', error);
                Toast.error('Connection error. Please try again.');
            } finally {
                this.saveBtn.disabled = false;
                this.saveBtn.innerHTML = originalHTML;
            }
        },
        
        isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Confirmation Modal Controller
       ───────────────────────────────────────────────────────────────── */
    const ConfirmModal = {
        overlay: null,
        titleEl: null,
        descEl: null,
        phraseEl: null,
        inputEl: null,
        confirmBtn: null,
        cancelBtn: null,
        currentAction: null,
        requiredPhrase: '',
        
        init() {
            this.overlay = document.getElementById('confirmModal');
            this.titleEl = document.getElementById('modalTitle');
            this.descEl = document.getElementById('modalDesc');
            this.phraseEl = document.getElementById('confirmPhrase');
            this.inputEl = document.getElementById('confirmInput');
            this.confirmBtn = document.getElementById('modalConfirmBtn');
            this.cancelBtn = document.getElementById('modalCancelBtn');
            
            if (!this.overlay) return;
            
            this.bindEvents();
        },
        
        bindEvents() {
            this.cancelBtn.addEventListener('click', () => this.close());
            
            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.close();
                }
            });
            
            this.inputEl.addEventListener('input', () => {
                this.validateInput();
            });
            
            this.confirmBtn.addEventListener('click', () => {
                if (this.currentAction) {
                    this.currentAction();
                }
                this.close();
            });
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.overlay.classList.contains('is-open')) {
                    this.close();
                }
            });
        },
        
        open(options) {
            const { title, description, phrase, confirmText, action } = options;
            
            this.titleEl.textContent = title;
            this.descEl.textContent = description;
            this.phraseEl.textContent = phrase;
            this.requiredPhrase = phrase;
            this.currentAction = action;
            
            document.getElementById('modalConfirmText').textContent = confirmText || 'Confirm';
            
            this.inputEl.value = '';
            this.confirmBtn.disabled = true;
            
            this.overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                this.inputEl.focus();
            }, 100);
        },
        
        close() {
            this.overlay.classList.remove('is-open');
            document.body.style.overflow = '';
            this.currentAction = null;
            this.inputEl.value = '';
        },
        
        validateInput() {
            const isValid = this.inputEl.value === this.requiredPhrase;
            this.confirmBtn.disabled = !isValid;
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Danger Zone Controller
       ───────────────────────────────────────────────────────────────── */
    const DangerZone = {
        resetBtn: null,
        deleteBtn: null,
        
        init() {
            this.resetBtn = document.getElementById('resetDataBtn');
            this.deleteBtn = document.getElementById('deleteAccountBtn');
            
            this.bindEvents();
        },
        
        bindEvents() {
            if (this.resetBtn) {
                this.resetBtn.addEventListener('click', () => this.confirmReset());
            }
            
            if (this.deleteBtn) {
                this.deleteBtn.addEventListener('click', () => this.confirmDelete());
            }
        },
        
        confirmReset() {
            ConfirmModal.open({
                title: 'Reset All Data',
                description: 'This will permanently delete all your notifications and connected apps. Your account settings will remain intact.',
                phrase: 'RESET',
                confirmText: 'Reset Everything',
                action: () => this.resetData()
            });
        },
        
        confirmDelete() {
            ConfirmModal.open({
                title: 'Delete Account',
                description: 'This will permanently delete your entire account, including all notifications, apps, and personal data. This action cannot be undone.',
                phrase: 'DELETE',
                confirmText: 'Delete My Account',
                action: () => this.deleteAccount()
            });
        },
        
        resetData() {
            this.doResetData();
        },
        
        async doResetData() {
            try {
                const response = await fetch('/rabbit/API/delete/userdata.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        confirmation: 'RESET'
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Toast.success('All data has been reset successfully');
                } else {
                    Toast.error(result.message || 'Failed to reset data');
                }
            } catch (error) {
                console.error('Reset error:', error);
                Toast.error('Connection error. Please try again.');
            }
        },
        
        deleteAccount() {
            this.doDeleteAccount();
        },
        
        async doDeleteAccount() {
            try {
                const response = await fetch('/rabbit/API/delete/user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        confirmation: 'DELETE'
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Toast.success('Account deleted. Redirecting...');
                    
                    // Clear local storage
                    localStorage.clear();
                    sessionStorage.clear();
                    
                    // Redirect to landing page
                    setTimeout(() => {
                        window.top.location.href = '/rabbit/index.php';
                    }, 1500);
                } else {
                    Toast.error(result.message || 'Failed to delete account');
                }
            } catch (error) {
                console.error('Delete error:', error);
                Toast.error('Connection error. Please try again.');
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Initialize
       ───────────────────────────────────────────────────────────────── */
    function init() {
        Theme.init();
        ApiKeyManager.init();
        ProfileForm.init();
        ConfirmModal.init();
        DangerZone.init();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
