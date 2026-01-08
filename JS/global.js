/* ═══════════════════════════════════════════════════════════════
   RABBIT — Global JavaScript Utilities
   Theme management, animations, and core functionality
   ═══════════════════════════════════════════════════════════════ */

(function() {
    'use strict';

    /* ─────────────────────────────────────────────────────────────────
       Theme Management
       ───────────────────────────────────────────────────────────────── */
    const ThemeManager = {
        STORAGE_KEY: 'rabbit-theme',
        THEMES: ['light', 'dark'],
        
        init() {
            const savedTheme = localStorage.getItem(this.STORAGE_KEY);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            
            this.setTheme(theme, false);
            this.bindSystemPreference();
        },
        
        setTheme(theme, save = true) {
            if (!this.THEMES.includes(theme)) return;
            
            document.documentElement.setAttribute('data-theme', theme);
            
            if (save) {
                localStorage.setItem(this.STORAGE_KEY, theme);
            }
            
            // Dispatch custom event
            window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
        },
        
        getTheme() {
            return document.documentElement.getAttribute('data-theme') || 'light';
        },
        
        toggle() {
            const current = this.getTheme();
            const next = current === 'light' ? 'dark' : 'light';
            this.setTheme(next);
            return next;
        },
        
        bindSystemPreference() {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem(this.STORAGE_KEY)) {
                    this.setTheme(e.matches ? 'dark' : 'light', false);
                }
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Scroll Animation Observer
       ───────────────────────────────────────────────────────────────── */
    const ScrollAnimator = {
        init() {
            this.observeElements();
        },
        
        observeElements() {
            const options = {
                root: null,
                rootMargin: '0px 0px -80px 0px',
                threshold: 0.1
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        
                        // Add staggered delay for children if specified
                        const staggerChildren = entry.target.querySelectorAll('[data-stagger]');
                        staggerChildren.forEach((child, index) => {
                            child.style.animationDelay = `${index * 100}ms`;
                            child.classList.add('is-visible');
                        });
                        
                        // Optionally unobserve after animation
                        if (!entry.target.hasAttribute('data-animate-repeat')) {
                            observer.unobserve(entry.target);
                        }
                    } else if (entry.target.hasAttribute('data-animate-repeat')) {
                        entry.target.classList.remove('is-visible');
                    }
                });
            }, options);
            
            // Observe all elements with data-animate attribute
            document.querySelectorAll('[data-animate]').forEach(el => {
                observer.observe(el);
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Smooth Scroll Handler
       ───────────────────────────────────────────────────────────────── */
    const SmoothScroll = {
        init() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', (e) => {
                    const href = anchor.getAttribute('href');
                    if (href === '#') return;
                    
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        this.scrollTo(target);
                    }
                });
            });
        },
        
        scrollTo(element, offset = 80) {
            const top = element.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({
                top,
                behavior: 'smooth'
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Utility Functions
       ───────────────────────────────────────────────────────────────── */
    const Utils = {
        // Debounce function
        debounce(func, wait = 100) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // Throttle function
        throttle(func, limit = 100) {
            let inThrottle;
            return function(...args) {
                if (!inThrottle) {
                    func.apply(this, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },
        
        // Copy to clipboard
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                return true;
            } catch (err) {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                return true;
            }
        },
        
        // Format date
        formatDate(date, options = {}) {
            const defaults = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return new Date(date).toLocaleDateString('en-US', { ...defaults, ...options });
        },
        
        // Generate unique ID
        generateId(prefix = 'rabbit') {
            return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
        },
        
        // Check if element is in viewport
        isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Toast Notifications
       ───────────────────────────────────────────────────────────────── */
    const Toast = {
        container: null,
        
        init() {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            this.container.setAttribute('aria-live', 'polite');
            this.container.setAttribute('aria-atomic', 'true');
            document.body.appendChild(this.container);
        },
        
        show(message, type = 'info', duration = 4000) {
            if (!this.container) this.init();
            
            const toast = document.createElement('div');
            toast.className = `toast toast--${type}`;
            toast.innerHTML = `
                <span class="toast__message">${message}</span>
                <button class="toast__close" aria-label="Close notification">&times;</button>
            `;
            
            // Close button handler
            toast.querySelector('.toast__close').addEventListener('click', () => {
                this.dismiss(toast);
            });
            
            this.container.appendChild(toast);
            
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.add('toast--visible');
            });
            
            // Auto dismiss
            if (duration > 0) {
                setTimeout(() => this.dismiss(toast), duration);
            }
            
            return toast;
        },
        
        dismiss(toast) {
            toast.classList.remove('toast--visible');
            toast.addEventListener('transitionend', () => {
                toast.remove();
            }, { once: true });
        },
        
        success(message, duration) {
            return this.show(message, 'success', duration);
        },
        
        error(message, duration) {
            return this.show(message, 'error', duration);
        },
        
        warning(message, duration) {
            return this.show(message, 'warning', duration);
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Loading State Manager
       ───────────────────────────────────────────────────────────────── */
    const LoadingState = {
        setLoading(element, isLoading, text = 'Loading...') {
            if (isLoading) {
                element.setAttribute('data-loading', 'true');
                element.disabled = true;
                element.dataset.originalText = element.textContent;
                element.innerHTML = `<span class="loading-spinner"></span>${text}`;
            } else {
                element.removeAttribute('data-loading');
                element.disabled = false;
                element.textContent = element.dataset.originalText || element.textContent;
                delete element.dataset.originalText;
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Page Loader — Mesmerizing Loading Animation
       ───────────────────────────────────────────────────────────────── */
    const PageLoader = {
        loader: null,
        dotsInterval: null,
        minLoadTime: 1000, // Minimum time to show loader (ms)
        startTime: null,
        enabled: true,
        
        init() {
            // Skip loader for dashboard pages (iframe content and dashboard shell)
            const isDashboard = window.location.pathname.includes('/dashboard/') ||
                               window.location.pathname.includes('\\dashboard\\') ||
                               window.self !== window.top; // Inside iframe
            
            if (isDashboard) {
                this.enabled = false;
                return;
            }
            
            // Check if loader already exists (created by inline script)
            this.loader = document.getElementById('pageLoader');
            if (this.loader) {
                this.startTime = window.loaderStartTime || Date.now();
            } else {
                this.startTime = Date.now();
                this.create();
                this.animateDots();
            }
        },
        
        create() {
            // Create loader HTML
            const loaderHTML = `
                <div class="page-loader" id="pageLoader">
                    <div class="loader">
                        <div class="loader__orbit">
                            <div class="loader__square"></div>
                        </div>
                        <div class="loader__orbit">
                            <div class="loader__square"></div>
                        </div>
                        <div class="loader__orbit">
                            <div class="loader__square"></div>
                        </div>
                        <div class="loader__center">
                            <div class="loader__rabbit">
                                <div class="loader__ear loader__ear--left"></div>
                                <div class="loader__ear loader__ear--right"></div>
                                <div class="loader__head">
                                    <div class="loader__eye"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loader__text">Loading<span class="loader__dots"></span></div>
                </div>
            `;
            
            // Insert at beginning of body
            document.body.insertAdjacentHTML('afterbegin', loaderHTML);
            this.loader = document.getElementById('pageLoader');
        },
        
        animateDots() {
            const dotsEl = document.querySelector('.loader__dots');
            if (!dotsEl) return;
            
            let dots = 0;
            this.dotsInterval = setInterval(() => {
                dots = (dots + 1) % 4;
                dotsEl.textContent = '.'.repeat(dots);
            }, 400);
        },
        
        hide() {
            if (!this.enabled) return;
            
            const elapsed = Date.now() - this.startTime;
            const remaining = Math.max(0, this.minLoadTime - elapsed);
            
            setTimeout(() => {
                if (this.loader) {
                    this.loader.classList.add('is-hidden');
                    
                    // Remove from DOM after transition
                    setTimeout(() => {
                        if (this.loader && this.loader.parentNode) {
                            this.loader.parentNode.removeChild(this.loader);
                        }
                    }, 400);
                }
                
                if (this.dotsInterval) {
                    clearInterval(this.dotsInterval);
                }
            }, remaining);
        },
        
        // Manual show (for navigation/AJAX)
        show() {
            if (!this.enabled) return;
            
            this.startTime = Date.now();
            if (!this.loader || !document.getElementById('pageLoader')) {
                this.create();
                this.animateDots();
            } else {
                this.loader.classList.remove('is-hidden');
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       DOM Ready Handler
       ───────────────────────────────────────────────────────────────── */
    function onDOMReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }

    /* ─────────────────────────────────────────────────────────────────
       Initialize Global Features
       ───────────────────────────────────────────────────────────────── */
    
    // Initialize loader as soon as body is available
    if (document.body) {
        PageLoader.init();
    } else {
        // Wait for body to be available
        document.addEventListener('DOMContentLoaded', () => {
            PageLoader.init();
        });
    }
    
    // Hide loader when page is fully loaded
    window.addEventListener('load', () => {
        PageLoader.hide();
    });
    
    onDOMReady(() => {
        ThemeManager.init();
        ScrollAnimator.init();
        SmoothScroll.init();
        Toast.init();
        
        // Remove loading class from body when ready
        document.body.classList.remove('is-loading');
        document.body.classList.add('is-ready');
    });

    /* ─────────────────────────────────────────────────────────────────
       Export to Global Scope
       ───────────────────────────────────────────────────────────────── */
    window.Rabbit = {
        Theme: ThemeManager,
        ScrollAnimator,
        SmoothScroll,
        Utils,
        Toast,
        LoadingState,
        PageLoader,
        onDOMReady
    };

})();
