/* ═══════════════════════════════════════════════════════════════
   RABBIT — Settings Page JavaScript
   Profile management and danger zone actions
   ═══════════════════════════════════════════════════════════════ */

(function() {
    'use strict';

    const { Theme, Toast } = window.Rabbit;

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
            
            this.loadSavedData();
            this.bindEvents();
        },
        
        loadSavedData() {
            // Load saved profile from localStorage
            const savedName = localStorage.getItem('rabbit_user_name');
            const savedEmail = localStorage.getItem('rabbit_user_email');
            
            if (savedName) {
                document.getElementById('fullName').value = savedName;
            }
            if (savedEmail) {
                document.getElementById('email').value = savedEmail;
            }
        },
        
        bindEvents() {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.save();
            });
        },
        
        save() {
            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            
            // Basic validation
            if (!fullName) {
                Toast.error('Please enter your full name');
                return;
            }
            
            if (!email || !this.isValidEmail(email)) {
                Toast.error('Please enter a valid email address');
                return;
            }
            
            // Save to localStorage
            localStorage.setItem('rabbit_user_name', fullName);
            localStorage.setItem('rabbit_user_email', email);
            
            // Show success
            Toast.success('Profile updated successfully');
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
            // Clear all app-related data from localStorage
            const keysToRemove = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('rabbit_app') || key.startsWith('rabbit_notif')) {
                    keysToRemove.push(key);
                }
            }
            keysToRemove.forEach(key => localStorage.removeItem(key));
            
            // Clear sessionStorage
            sessionStorage.clear();
            
            Toast.success('All data has been reset successfully');
        },
        
        deleteAccount() {
            // Clear all Rabbit data
            const keysToRemove = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('rabbit_')) {
                    keysToRemove.push(key);
                }
            }
            keysToRemove.forEach(key => localStorage.removeItem(key));
            
            // Clear sessionStorage
            sessionStorage.clear();
            
            Toast.success('Account deleted. Redirecting...');
            
            // Redirect to homepage after a brief delay
            setTimeout(() => {
                window.top.location.href = '../index.php';
            }, 1500);
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Initialize
       ───────────────────────────────────────────────────────────────── */
    function init() {
        Theme.init();
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
