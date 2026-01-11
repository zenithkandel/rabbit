/* ═══════════════════════════════════════════════════════════════
   RABBIT — Settings Page JavaScript
   Profile management, API key, and danger zone actions
   ═══════════════════════════════════════════════════════════════ */

(function() {
    'use strict';

    const { Theme, Toast } = window.Rabbit;

    /* ─────────────────────────────────────────────────────────────────
       API Key Controller (Show Once Model)
       ───────────────────────────────────────────────────────────────── */
    const ApiKeyManager = {
        generateBtn: null,
        regenerateBtn: null,
        apiKeyModal: null,
        newApiKeyValue: null,
        copyNewKeyBtn: null,
        apiKeyModalClose: null,
        
        init() {
            this.generateBtn = document.getElementById('generateKeyBtn');
            this.regenerateBtn = document.getElementById('regenerateKeyBtn');
            this.apiKeyModal = document.getElementById('apiKeyModal');
            this.newApiKeyValue = document.getElementById('newApiKeyValue');
            this.copyNewKeyBtn = document.getElementById('copyNewKeyBtn');
            this.apiKeyModalClose = document.getElementById('apiKeyModalClose');
            
            this.bindEvents();
        },
        
        bindEvents() {
            // Generate new key (first time)
            if (this.generateBtn) {
                this.generateBtn.addEventListener('click', () => this.generateKey());
            }
            
            // Regenerate existing key
            if (this.regenerateBtn) {
                this.regenerateBtn.addEventListener('click', () => this.confirmRegenerate());
            }
            
            // Copy new key from modal
            if (this.copyNewKeyBtn) {
                this.copyNewKeyBtn.addEventListener('click', () => this.copyNewKey());
            }
            
            // Close API key modal
            if (this.apiKeyModalClose) {
                this.apiKeyModalClose.addEventListener('click', () => this.closeApiKeyModal());
            }
            
            // Close modal on backdrop click
            if (this.apiKeyModal) {
                this.apiKeyModal.addEventListener('click', (e) => {
                    if (e.target === this.apiKeyModal) {
                        this.closeApiKeyModal();
                    }
                });
            }
        },
        
        async generateKey() {
            if (this.generateBtn) {
                this.generateBtn.disabled = true;
                this.generateBtn.innerHTML = '<span>Generating...</span>';
            }
            
            try {
                const response = await fetch('/rabbit/API/update/apikey.php', {
                    method: 'POST',
                    credentials: 'include'
                });
                
                const result = await response.json();
                
                if (result.success && result.data.api_key) {
                    // Show the API key in the success modal
                    this.showApiKeyModal(result.data.api_key);
                } else {
                    Toast.error(result.message || 'Failed to generate API key');
                    if (this.generateBtn) {
                        this.generateBtn.disabled = false;
                        this.generateBtn.innerHTML = `
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                            </svg>
                            <span>Generate API Key</span>
                        `;
                    }
                }
            } catch (error) {
                console.error('Generate error:', error);
                Toast.error('Connection error. Please try again.');
                if (this.generateBtn) {
                    this.generateBtn.disabled = false;
                    this.generateBtn.innerHTML = `
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                        </svg>
                        <span>Generate API Key</span>
                    `;
                }
            }
        },
        
        confirmRegenerate() {
            ConfirmModal.open({
                title: 'Regenerate API Key',
                description: 'Your current API key will be invalidated immediately. Any apps using the old key will stop working. The new key will only be shown once.',
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
                
                if (result.success && result.data.api_key) {
                    // Show the API key in the success modal
                    this.showApiKeyModal(result.data.api_key);
                } else {
                    Toast.error(result.message || 'Failed to regenerate API key');
                }
            } catch (error) {
                console.error('Regenerate error:', error);
                Toast.error('Connection error. Please try again.');
            }
        },
        
        showApiKeyModal(apiKey) {
            if (this.newApiKeyValue) {
                this.newApiKeyValue.textContent = apiKey;
            }
            if (this.apiKeyModal) {
                this.apiKeyModal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }
        },
        
        closeApiKeyModal() {
            if (this.apiKeyModal) {
                this.apiKeyModal.classList.remove('is-open');
                document.body.style.overflow = '';
            }
            // Reload page to show updated state
            window.location.reload();
        },
        
        async copyNewKey() {
            const apiKey = this.newApiKeyValue?.textContent;
            if (!apiKey) {
                Toast.error('No API key to copy');
                return;
            }
            
            try {
                await navigator.clipboard.writeText(apiKey);
                
                // Update button visual
                if (this.copyNewKeyBtn) {
                    const originalHTML = this.copyNewKeyBtn.innerHTML;
                    this.copyNewKeyBtn.innerHTML = `
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Copied!
                    `;
                    setTimeout(() => {
                        this.copyNewKeyBtn.innerHTML = originalHTML;
                    }, 2000);
                }
                
                Toast.success('API key copied to clipboard');
            } catch (err) {
                Toast.error('Failed to copy API key');
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
