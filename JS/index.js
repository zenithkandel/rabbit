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
            
            // Simulate API call
            const submitBtn = document.querySelector('#signin-form .btn--primary');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing in...';
            
            setTimeout(() => {
                Toast.success('Welcome back! Redirecting to dashboard...');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Sign In';
                this.close();
            }, 1500);
        },
        
        async handleSignUp(formData) {
            const email = formData.get('email');
            const password = formData.get('password');
            
            // Simulate API call
            const submitBtn = document.querySelector('#signup-form .btn--primary');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating account...';
            
            setTimeout(() => {
                Toast.success('Account created! Check your email for verification.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Account';
                this.close();
            }, 1500);
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
