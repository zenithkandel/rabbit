/* ═══════════════════════════════════════════════════════════════
   RABBIT — Dashboard JavaScript
   Navigation, iframe management, and interactions
   ═══════════════════════════════════════════════════════════════ */

(function() {
    'use strict';

    const { Theme, Utils, Toast } = window.Rabbit;

    /* ─────────────────────────────────────────────────────────────────
       Taskbar Controller
       ───────────────────────────────────────────────────────────────── */
    const Taskbar = {
        element: null,
        
        init() {
            this.element = document.querySelector('.taskbar');
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       User Menu Controller
       ───────────────────────────────────────────────────────────────── */
    const UserMenu = {
        element: null,
        trigger: null,
        isOpen: false,
        
        init() {
            this.element = document.querySelector('.user-menu');
            this.trigger = document.querySelector('.user-menu__trigger');
            
            if (!this.element || !this.trigger) return;
            
            this.bindEvents();
        },
        
        bindEvents() {
            this.trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggle();
            });
            
            // Close on outside click
            document.addEventListener('click', (e) => {
                if (this.isOpen && !this.element.contains(e.target)) {
                    this.close();
                }
            });
            
            // Close on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });
            
            // Handle menu items
            this.element.querySelectorAll('.user-menu__item').forEach(item => {
                item.addEventListener('click', () => {
                    const action = item.dataset.action;
                    this.handleAction(action);
                });
            });
        },
        
        toggle() {
            this.isOpen = !this.isOpen;
            this.element.classList.toggle('is-open', this.isOpen);
        },
        
        close() {
            this.isOpen = false;
            this.element.classList.remove('is-open');
        },
        
        handleAction(action) {
            switch (action) {
                case 'settings':
                    IframeManager.navigate('settings.php');
                    break;
                case 'theme':
                    Theme.toggle();
                    break;
                case 'logout':
                    this.logout();
                    break;
            }
            this.close();
        },
        
        async logout() {
            try {
                const response = await fetch('../API/logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Toast.success('Logged out successfully');
                    setTimeout(() => {
                        window.location.href = data.redirect || '../index.php';
                    }, 500);
                } else {
                    // Even if logout fails, redirect to be safe
                    window.location.href = '../index.php';
                }
            } catch (error) {
                console.error('Logout error:', error);
                // Redirect anyway
                window.location.href = '../index.php';
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Mobile Menu Controller
       ───────────────────────────────────────────────────────────────── */
    const MobileMenu = {
        menu: null,
        toggleBtn: null,
        closeBtn: null,
        overlay: null,
        isOpen: false,
        
        init() {
            this.menu = document.querySelector('.mobile-menu');
            this.toggleBtn = document.querySelector('.mobile-nav-toggle');
            this.closeBtn = document.querySelector('.mobile-menu__close');
            this.overlay = document.querySelector('.mobile-menu__overlay');
            
            if (!this.menu) return;
            
            this.bindEvents();
        },
        
        bindEvents() {
            if (this.toggleBtn) {
                this.toggleBtn.addEventListener('click', () => this.open());
            }
            
            if (this.closeBtn) {
                this.closeBtn.addEventListener('click', () => this.close());
            }
            
            if (this.overlay) {
                this.overlay.addEventListener('click', () => this.close());
            }
            
            // Handle menu links
            this.menu.querySelectorAll('.mobile-menu__link').forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (href && href !== '#') {
                        e.preventDefault();
                        IframeManager.navigate(href);
                        this.close();
                    }
                });
            });
        },
        
        open() {
            this.isOpen = true;
            this.menu.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        },
        
        close() {
            this.isOpen = false;
            this.menu.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Iframe Manager
       ───────────────────────────────────────────────────────────────── */
    const IframeManager = {
        iframe: null,
        loader: null,
        navLinks: [],
        currentPage: 'home.php',
        storageKey: 'rabbit_dashboard_page',
        
        init() {
            this.iframe = document.querySelector('.dashboard__iframe');
            this.loader = document.querySelector('.dashboard__loader');
            this.navLinks = document.querySelectorAll('[data-page]');
            
            if (!this.iframe) return;
            
            // Restore saved page from sessionStorage
            const savedPage = sessionStorage.getItem(this.storageKey);
            if (savedPage) {
                this.currentPage = savedPage;
            }
            
            this.bindEvents();
            this.bindNavigation();
            
            // Load initial page
            this.navigate(this.currentPage, false);
        },
        
        bindEvents() {
            this.iframe.addEventListener('load', () => {
                this.hideLoader();
                this.syncTheme();
            });
        },
        
        bindNavigation() {
            this.navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const page = link.dataset.page;
                    if (page) {
                        this.navigate(page);
                    }
                });
            });
        },
        
        navigate(page, showLoader = true) {
            if (showLoader) {
                this.showLoader();
            }
            
            this.currentPage = page;
            this.iframe.src = page;
            this.updateActiveNav(page);
            
            // Save current page to sessionStorage
            sessionStorage.setItem(this.storageKey, page);
        },
        
        updateActiveNav(page) {
            this.navLinks.forEach(link => {
                const isActive = link.dataset.page === page;
                link.classList.toggle('is-active', isActive);
            });
            
            // Update mobile menu as well
            document.querySelectorAll('.mobile-menu__link').forEach(link => {
                const href = link.getAttribute('href');
                link.classList.toggle('is-active', href === page);
            });
        },
        
        showLoader() {
            if (this.loader) {
                this.loader.classList.add('is-loading');
            }
        },
        
        hideLoader() {
            if (this.loader) {
                this.loader.classList.remove('is-loading');
            }
        },
        
        syncTheme() {
            // Sync theme with iframe content
            try {
                const iframeDoc = this.iframe.contentDocument || this.iframe.contentWindow.document;
                const currentTheme = Theme.getTheme();
                iframeDoc.documentElement.setAttribute('data-theme', currentTheme);
            } catch (e) {
                // Cross-origin restrictions may prevent this
                console.log('Could not sync theme with iframe');
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Theme Sync
       ───────────────────────────────────────────────────────────────── */
    const ThemeSync = {
        init() {
            // Listen for theme changes and sync with iframe
            window.addEventListener('themechange', () => {
                IframeManager.syncTheme();
            });
            
            // Theme toggle button
            const themeToggle = document.querySelector('.theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    Theme.toggle();
                });
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Keyboard Shortcuts
       ───────────────────────────────────────────────────────────────── */
    const KeyboardShortcuts = {
        init() {
            document.addEventListener('keydown', (e) => {
                // Ctrl/Cmd + K - Quick search (placeholder)
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    Toast.show('Quick search coming soon...', 'info');
                }
                
                // Ctrl/Cmd + 1-5 - Quick navigation
                if ((e.ctrlKey || e.metaKey) && e.key >= '1' && e.key <= '5') {
                    e.preventDefault();
                    const pages = ['home.php', 'notifications.php', 'apps.php', 'api-keys.php', 'settings.php'];
                    const index = parseInt(e.key) - 1;
                    if (pages[index]) {
                        IframeManager.navigate(pages[index]);
                    }
                }
            });
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Live Stats Updates
       ───────────────────────────────────────────────────────────────── */
    const LiveStats = {
        init() {
            // Simulate live stat updates (replace with real WebSocket in production)
            this.startPolling();
        },
        
        startPolling() {
            // Update stats every 30 seconds
            setInterval(() => {
                this.fetchStats();
            }, 30000);
        },
        
        async fetchStats() {
            try {
                const response = await fetch('/rabbit/API/read/stats.php', {
                    credentials: 'include'
                });
                const result = await response.json();
                if (result.success && result.data) {
                    this.updateDisplay(result.data);
                }
            } catch (error) {
                console.error('Failed to fetch live stats:', error);
            }
        },

        updateDisplay(data) {
            const notifCount = document.querySelector('[data-stat="notifications"]');
            const appCount = document.querySelector('[data-stat="apps"]');
            if (notifCount && data.notifications && typeof data.notifications.total === 'number') {
                notifCount.textContent = data.notifications.total.toLocaleString();
            }
            if (appCount && data.apps && typeof data.apps.total === 'number') {
                appCount.textContent = data.apps.total.toLocaleString();
            }
        }
    };

    /* ─────────────────────────────────────────────────────────────────
       Initialize Dashboard
       ───────────────────────────────────────────────────────────────── */
    function init() {
        window.Rabbit.onDOMReady(() => {
            Taskbar.init();
            UserMenu.init();
            MobileMenu.init();
            IframeManager.init();
            ThemeSync.init();
            KeyboardShortcuts.init();
            LiveStats.init();
            // Initial stats fetch
            LiveStats.fetchStats();
        });
    }

    // Export for iframe communication
    window.Dashboard = {
        IframeManager,
        navigate: (page) => IframeManager.navigate(page),
        showToast: Toast.show.bind(Toast),
        logout: () => UserMenu.logout()
    };

    init();

})();
