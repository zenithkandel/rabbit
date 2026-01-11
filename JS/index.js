/* ═══════════════════════════════════════════════════════════════
   RABBIT — Landing Page JavaScript
   Interactions, animations, and page-specific functionality
   ═══════════════════════════════════════════════════════════════ */

(function() {
    'use strict';

    const { Theme, Utils, Toast } = window.Rabbit;

    /* ─────────────────────────────────────────────────────────────────
       Navigation Controller
       ───────────────────────────────────────────────────────────────── */
    const Navigation = {
        nav: null,
        lastScrollY: 0,
        scrollThreshold: 100,
        
        init() {
            this.nav = document.querySelector('.nav');
            if (!this.nav) return;
            
            this.bindScrollBehavior();
        },
        
        bindScrollBehavior() {
            window.addEventListener('scroll', Utils.throttle(() => {
                const currentScrollY = window.scrollY;
                
                if (currentScrollY > this.lastScrollY && currentScrollY > this.scrollThreshold) {
                    this.nav.classList.add('nav--hidden');
                } else {
                    this.nav.classList.remove('nav--hidden');
                }
                
                this.lastScrollY = currentScrollY;
            }, 100));
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Theme Toggle
       ───────────────────────────────────────────────────────────────── */
    const ThemeToggle = {
        init() {
            const toggleBtn = document.querySelector('.theme-toggle');
            if (!toggleBtn) return;
            
            toggleBtn.addEventListener('click', () => {
                Theme.toggle();
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Auth Modal Controller
       ───────────────────────────────────────────────────────────────── */
    const AuthModal = {
        overlay: null,
        modal: null,
        activeTab: 'signin',
        
        init() {
            this.overlay = document.querySelector('.modal-overlay');
            this.modal = document.querySelector('.modal');
            if (!this.overlay || !this.modal) return;
            
            this.bindTriggers();
            this.bindCloseHandlers();
            this.bindTabs();
            this.bindForms();
            this.bindPasswordToggles();
        },
        
        bindTriggers() {
            document.querySelectorAll('[data-auth-trigger]').forEach(trigger => {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    const tab = trigger.dataset.authTrigger || 'signin';
                    this.open(tab);
                });
            });
        },
        
        bindCloseHandlers() {
            // Close button
            const closeBtn = this.modal.querySelector('.modal__close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
            }
            
            // Click outside
            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.close();
                }
            });
            
            // Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen()) {
                    this.close();
                }
            });
        },
        
        bindTabs() {
            const tabs = this.modal.querySelectorAll('.modal__tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const targetTab = tab.dataset.tab;
                    this.switchTab(targetTab);
                });
            });
            
            // Switch form links
            document.querySelectorAll('[data-switch-tab]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetTab = link.dataset.switchTab;
                    this.switchTab(targetTab);
                });
            });
        },
        
        bindForms() {
            const signinForm = document.getElementById('signin-form');
            const signupForm = document.getElementById('signup-form');
            
            if (signinForm) {
                signinForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleSignIn(new FormData(signinForm));
                });
            }
            
            if (signupForm) {
                signupForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleSignUp(new FormData(signupForm));
                });
            }
        },
        
        bindPasswordToggles() {
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const targetId = toggle.dataset.target;
                    const input = document.getElementById(targetId);
                    
                    if (input) {
                        const isPassword = input.type === 'password';
                        input.type = isPassword ? 'text' : 'password';
                        toggle.classList.toggle('is-visible', isPassword);
                    }
                });
            });
        },
        
        open(tab = 'signin') {
            this.switchTab(tab);
            this.overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
            
            // Focus first input
            setTimeout(() => {
                const firstInput = this.modal.querySelector('.auth-form.is-active input');
                if (firstInput) firstInput.focus();
            }, 300);
        },
        
        close() {
            this.overlay.classList.remove('is-active');
            document.body.style.overflow = '';
        },
        
        isOpen() {
            return this.overlay.classList.contains('is-active');
        },
        
        switchTab(tab) {
            this.activeTab = tab;
            
            // Update tab buttons
            this.modal.querySelectorAll('.modal__tab').forEach(tabBtn => {
                tabBtn.classList.toggle('is-active', tabBtn.dataset.tab === tab);
            });
            
            // Update forms
            this.modal.querySelectorAll('.auth-form').forEach(form => {
                form.classList.toggle('is-active', form.dataset.form === tab);
            });
        },
        
        async handleSignIn(formData) {
            const email = formData.get('email');
            const password = formData.get('password');
            
            const submitBtn = document.querySelector('#signin-form .btn--primary');
            const originalText = submitBtn.textContent;
            
            // Clear previous errors
            this.clearFormErrors('signin-form');
            
            // Client-side validation
            const errors = {};
            
            if (!email || !this.isValidEmail(email)) {
                errors.email = 'Please enter a valid email address';
            }
            
            if (!password) {
                errors.password = 'Password is required';
            }
            
            if (Object.keys(errors).length > 0) {
                this.showFormErrors('signin-form', errors);
                return;
            }
            
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing in...';
            
            try {
                const response = await fetch('/rabbit/API/signin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        email: email.trim(),
                        password: password
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Toast.success('Welcome back! Redirecting to dashboard...');
                    this.close();
                    
                    // Redirect to dashboard
                    setTimeout(() => {
                        window.location.href = result.data.redirect || '/rabbit/dashboard/';
                    }, 1000);
                } else {
                    // Show validation errors from server
                    if (result.errors) {
                        this.showFormErrors('signin-form', result.errors);
                    }
                    Toast.error(result.message || 'Signin failed. Please try again.');
                }
            } catch (error) {
                console.error('Signin error:', error);
                Toast.error('Connection error. Please check your internet and try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        },
        
        async handleSignUp(formData) {
            const name = formData.get('name');
            const email = formData.get('email');
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            
            const submitBtn = document.querySelector('#signup-form .btn--primary');
            const originalText = submitBtn.textContent;
            
            // Clear previous errors
            this.clearFormErrors('signup-form');
            
            // Client-side validation
            const errors = {};
            
            if (!name || name.trim().length < 2) {
                errors.name = 'Name must be at least 2 characters';
            }
            
            if (!email || !this.isValidEmail(email)) {
                errors.email = 'Please enter a valid email address';
            }
            
            if (!password || password.length < 8) {
                errors.password = 'Password must be at least 8 characters';
            }
            
            if (password !== confirmPassword) {
                errors.confirm_password = 'Passwords do not match';
            }
            
            if (Object.keys(errors).length > 0) {
                this.showFormErrors('signup-form', errors);
                return;
            }
            
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating account...';
            
            try {
                const response = await fetch('/rabbit/API/signup.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name.trim(),
                        email: email.trim(),
                        password: password,
                        confirm_password: confirmPassword
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Store API key temporarily for display (shown only once)
                    sessionStorage.setItem('rabbit_new_api_key', result.data.api_key);
                    sessionStorage.setItem('rabbit_user_name', result.data.user.name);
                    
                    Toast.success('Account created! Signing you in...');
                    
                    // Now sign in the user via signin API to create session
                    await this.autoSignIn(email.trim(), password);
                } else {
                    // Show validation errors from server
                    if (result.errors) {
                        this.showFormErrors('signup-form', result.errors);
                    }
                    Toast.error(result.message || 'Signup failed. Please try again.');
                }
            } catch (error) {
                console.error('Signup error:', error);
                Toast.error('Connection error. Please check your internet and try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        },
        
        async autoSignIn(email, password) {
            try {
                const response = await fetch('/rabbit/API/signin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({ email, password })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.close();
                    
                    // Redirect to dashboard
                    setTimeout(() => {
                        window.location.href = result.data.redirect || '/rabbit/dashboard/';
                    }, 1000);
                } else {
                    // Fallback: redirect to signin if auto-signin fails
                    Toast.info('Please sign in with your new account.');
                    this.switchTab('signin');
                }
            } catch (error) {
                console.error('Auto signin error:', error);
                Toast.info('Please sign in with your new account.');
                this.switchTab('signin');
            }
        },
        
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        clearFormErrors(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            form.querySelectorAll('.form-error').forEach(el => el.remove());
            form.querySelectorAll('.input--error').forEach(el => el.classList.remove('input--error'));
        },
        
        showFormErrors(formId, errors) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            Object.entries(errors).forEach(([field, message]) => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('input--error');
                    
                    // Create error message element
                    const errorEl = document.createElement('span');
                    errorEl.className = 'form-error';
                    errorEl.textContent = message;
                    
                    // Insert after input wrapper (for password fields) or input's parent
                    const wrapper = input.closest('.input-password-wrapper');
                    if (wrapper) {
                        wrapper.parentNode.appendChild(errorEl);
                    } else {
                        input.parentNode.appendChild(errorEl);
                    }
                }
            });
            
            // Focus first error field
            const firstError = form.querySelector('.input--error');
            if (firstError) {
                firstError.focus();
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Notification Cards Animation
       ───────────────────────────────────────────────────────────────── */
    const NotificationAnimation = {
        cards: [],
        
        init() {
            this.cards = document.querySelectorAll('.notification-card');
            if (this.cards.length === 0) return;
            
            this.animateCards();
            this.startCycle();
        },
        
        animateCards() {
            this.cards.forEach(card => {
                card.classList.add('is-visible');
            });
        },
        
        startCycle() {
            // Periodically refresh cards to simulate live notifications
            setInterval(() => {
                this.pulseCard();
            }, 5000);
        },
        
        pulseCard() {
            const randomCard = this.cards[Math.floor(Math.random() * this.cards.length)];
            randomCard.style.transform = 'scale(1.02)';
            
            setTimeout(() => {
                randomCard.style.transform = '';
            }, 200);
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Code Preview Copy
       ───────────────────────────────────────────────────────────────── */
    const CodeCopy = {
        init() {
            const copyBtn = document.querySelector('.code-copy-btn');
            const codeBlock = document.querySelector('.code-preview__body code');
            
            if (!copyBtn || !codeBlock) return;
            
            copyBtn.addEventListener('click', async () => {
                const code = codeBlock.textContent;
                const success = await Utils.copyToClipboard(code);
                
                if (success) {
                    const originalText = copyBtn.textContent;
                    copyBtn.textContent = 'Copied!';
                    
                    setTimeout(() => {
                        copyBtn.textContent = originalText;
                    }, 2000);
                }
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Typing Animation for Hero
       ───────────────────────────────────────────────────────────────── */
    const TypeWriter = {
        element: null,
        words: ['apps', 'services', 'systems', 'APIs'],
        currentWordIndex: 0,
        currentCharIndex: 0,
        isDeleting: false,
        typeSpeed: 100,
        deleteSpeed: 50,
        pauseDuration: 2000,
        
        init() {
            this.element = document.querySelector('.type-target');
            if (!this.element) return;
            
            this.type();
        },
        
        type() {
            const currentWord = this.words[this.currentWordIndex];
            
            if (this.isDeleting) {
                this.currentCharIndex--;
            } else {
                this.currentCharIndex++;
            }
            
            const text = currentWord.substring(0, this.currentCharIndex);
            this.element.innerHTML = text + '<span class="type-cursor"></span>';
            
            let delay = this.isDeleting ? this.deleteSpeed : this.typeSpeed;
            
            if (!this.isDeleting && this.currentCharIndex === currentWord.length) {
                delay = this.pauseDuration;
                this.isDeleting = true;
            } else if (this.isDeleting && this.currentCharIndex === 0) {
                this.isDeleting = false;
                this.currentWordIndex = (this.currentWordIndex + 1) % this.words.length;
                delay = 500;
            }
            
            setTimeout(() => this.type(), delay);
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Cursor Follower Effect
       ───────────────────────────────────────────────────────────────── */
    const CursorEffect = {
        cursor: null,
        
        init() {
            // Only on desktop
            if (window.matchMedia('(pointer: coarse)').matches) return;
            
            this.createCursor();
            this.bindEvents();
        },
        
        createCursor() {
            this.cursor = document.createElement('div');
            this.cursor.className = 'cursor-follower';
            this.cursor.innerHTML = '<div class="cursor-follower__inner"></div>';
            document.body.appendChild(this.cursor);
            
            // Add styles
            const style = document.createElement('style');
            style.textContent = `
                .cursor-follower {
                    position: fixed;
                    pointer-events: none;
                    z-index: 9999;
                    mix-blend-mode: difference;
                    opacity: 0;
                    transition: opacity 0.3s;
                }
                .cursor-follower.is-visible {
                    opacity: 1;
                }
                .cursor-follower__inner {
                    width: 8px;
                    height: 8px;
                    background: var(--color-cream);
                    transform: translate(-50%, -50%);
                    transition: transform 0.15s var(--ease-out);
                }
                .cursor-follower.is-hovering .cursor-follower__inner {
                    transform: translate(-50%, -50%) scale(4);
                }
            `;
            document.head.appendChild(style);
        },
        
        bindEvents() {
            document.addEventListener('mousemove', (e) => {
                this.cursor.style.left = e.clientX + 'px';
                this.cursor.style.top = e.clientY + 'px';
                this.cursor.classList.add('is-visible');
            });
            
            document.addEventListener('mouseleave', () => {
                this.cursor.classList.remove('is-visible');
            });
            
            // Hover effect on interactive elements
            const interactiveElements = document.querySelectorAll('a, button, .btn, input, .notification-card');
            interactiveElements.forEach(el => {
                el.addEventListener('mouseenter', () => {
                    this.cursor.classList.add('is-hovering');
                });
                el.addEventListener('mouseleave', () => {
                    this.cursor.classList.remove('is-hovering');
                });
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Page Load Handler
       ───────────────────────────────────────────────────────────────── */
    const PageLoader = {
        init() {
            document.body.classList.add('is-loading');
            
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.body.classList.remove('is-loading');
                    document.body.classList.add('is-ready');
                }, 500);
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Initialize All Modules
       ───────────────────────────────────────────────────────────────── */
    function init() {
        PageLoader.init();
        
        window.Rabbit.onDOMReady(() => {
            Navigation.init();
            ThemeToggle.init();
            AuthModal.init();
            NotificationAnimation.init();
            CodeCopy.init();
            TypeWriter.init();
            CursorEffect.init();
        });
    }

    init();

})();
