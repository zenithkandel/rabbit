<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rabbit — Unified notification hub for developers. Connect all your apps and receive notifications in one place.">
    <meta name="theme-color" content="#F7F4EE">
    
    <title>Rabbit — Unified Notification Hub for Developers</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <!-- Loader (injected immediately to show before content) -->
    <script>
        (function() {
            // Skip for dashboard pages
            if (window.location.pathname.includes('/dashboard/')) return;
            
            window.loaderStartTime = Date.now();
            
            var loader = document.createElement('div');
            loader.id = 'pageLoader';
            loader.className = 'page-loader';
            loader.innerHTML = '<div class="loader"><div class="loader__orbit"><div class="loader__square"></div></div><div class="loader__orbit"><div class="loader__square"></div></div><div class="loader__orbit"><div class="loader__square"></div></div><div class="loader__center"><div class="loader__rabbit"><div class="loader__ear loader__ear--left"></div><div class="loader__ear loader__ear--right"></div><div class="loader__head"><div class="loader__eye"></div></div></div></div></div><div class="loader__text">Loading<span class="loader__dots"></span></div>';
            document.body.appendChild(loader);
            
            // Animate dots
            var dots = 0;
            var dotsEl = loader.querySelector('.loader__dots');
            setInterval(function() {
                dots = (dots + 1) % 4;
                dotsEl.textContent = '.'.repeat(dots);
            }, 400);
        })();
    </script>

    <!-- Navigation -->
    <nav class="nav">
        <div class="container">
            <div class="nav__inner">
                <a href="/" class="nav__brand">
                    <div class="nav__logo">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="nav__wordmark">Rabbit</span>
                </a>
                
                <div class="nav__actions">
                    <a href="#features" class="nav__link">Features</a>
                    <a href="#how-it-works" class="nav__link">How It Works</a>
                    
                    <button class="theme-toggle" aria-label="Toggle theme">
                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                    
                    <button class="btn btn--ghost" data-auth-trigger="signin">Sign In</button>
                    <button class="btn btn--secondary" data-auth-trigger="signup">Get Started</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__content">
            <span class="hero__eyebrow">Notification Infrastructure</span>
            <h1 class="hero__title">
                <span class="hero__title-line">All your</span>
                <span class="hero__title-line"><span class="hero__title-accent type-target">apps</span></span>
                <span class="hero__title-line">one inbox.</span>
            </h1>
            <p class="hero__subtitle">
                Connect your applications, services, and APIs to a single notification hub. 
                No more context switching between dashboards.
            </p>
            <div class="hero__cta">
                <button class="btn btn--primary btn--lg" data-auth-trigger="signup">Start Free</button>
                <button class="btn btn--outline btn--lg" data-auth-trigger="signin">Sign In</button>
            </div>
        </div>
        
        <div class="hero__visual">
            <div class="notification-stack">
                <div class="live-indicator">
                    <span class="live-indicator__dot"></span>
                    Live Preview
                </div>
                
                <div class="notification-card">
                    <div class="notification-card__header">
                        <div class="notification-card__app">
                            <div class="notification-card__icon">S</div>
                            <span class="notification-card__name">Stripe</span>
                        </div>
                        <span class="notification-card__time">2m ago</span>
                    </div>
                    <div class="notification-card__title">Payment Received</div>
                    <div class="notification-card__body">Invoice #4521 paid — $2,400.00 from Acme Corp</div>
                </div>
                
                <div class="notification-card">
                    <div class="notification-card__header">
                        <div class="notification-card__app">
                            <div class="notification-card__icon notification-card__icon--sage">G</div>
                            <span class="notification-card__name">GitHub</span>
                        </div>
                        <span class="notification-card__time">5m ago</span>
                    </div>
                    <div class="notification-card__title">Pull Request Merged</div>
                    <div class="notification-card__body">feat: add webhook retry logic #287 merged to main</div>
                </div>
                
                <div class="notification-card">
                    <div class="notification-card__header">
                        <div class="notification-card__app">
                            <div class="notification-card__icon notification-card__icon--ink">V</div>
                            <span class="notification-card__name">Vercel</span>
                        </div>
                        <span class="notification-card__time">12m ago</span>
                    </div>
                    <div class="notification-card__title">Deploy Complete</div>
                    <div class="notification-card__body">Production deployment successful — rabbit.app</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header section-header--center" data-animate="fade-up">
                <span class="section-eyebrow">Capabilities</span>
                <h2 class="section-title">Built for developers,<br>designed for clarity</h2>
                <p class="section-subtitle">
                    A notification system that respects your attention and workflow.
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card" data-animate="fade-up">
                    <span class="feature-card__number">01</span>
                    <div class="feature-card__icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">One API Key</h3>
                    <p class="feature-card__desc">
                        A single key to authenticate all your applications. 
                        Rotate, revoke, and manage access from one dashboard.
                    </p>
                </div>
                
                <div class="feature-card" data-animate="fade-up">
                    <span class="feature-card__number">02</span>
                    <div class="feature-card__icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">Real-time Delivery</h3>
                    <p class="feature-card__desc">
                        WebSocket connections ensure instant delivery. 
                        No polling, no delays, no missed alerts.
                    </p>
                </div>
                
                <div class="feature-card" data-animate="fade-up">
                    <span class="feature-card__number">03</span>
                    <div class="feature-card__icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <line x1="3" y1="9" x2="21" y2="9"/>
                            <line x1="9" y1="21" x2="9" y2="9"/>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">Multi-App Support</h3>
                    <p class="feature-card__desc">
                        Connect unlimited applications to your account. 
                        Tag, filter, and organize by project or team.
                    </p>
                </div>
                
                <div class="feature-card" data-animate="fade-up">
                    <span class="feature-card__number">04</span>
                    <div class="feature-card__icon">
                        <svg viewBox="0 0 24 24">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">Webhooks & REST</h3>
                    <p class="feature-card__desc">
                        Send notifications via REST API or receive them through webhooks. 
                        Full flexibility for any architecture.
                    </p>
                </div>
                
                <div class="feature-card" data-animate="fade-up">
                    <span class="feature-card__number">05</span>
                    <div class="feature-card__icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">Smart Filtering</h3>
                    <p class="feature-card__desc">
                        Set rules to mute, highlight, or route notifications. 
                        Stay focused on what matters most.
                    </p>
                </div>
                
                <div class="feature-card" data-animate="fade-up">
                    <span class="feature-card__number">06</span>
                    <div class="feature-card__icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">Complete Logs</h3>
                    <p class="feature-card__desc">
                        Full history of every notification sent. 
                        Search, export, and debug with ease.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header section-header--center" data-animate="fade-up">
                <span class="section-eyebrow">Integration</span>
                <h2 class="section-title">Three steps to unified notifications</h2>
            </div>
            
            <div class="steps">
                <div class="step" data-animate="fade-up">
                    <h3 class="step__title">Create Your Account</h3>
                    <p class="step__desc">
                        Sign up and get your API key instantly. No credit card required. 
                        Free tier includes 10,000 notifications per month.
                    </p>
                </div>
                
                <div class="step" data-animate="fade-up">
                    <h3 class="step__title">Integrate Your Apps</h3>
                    <p class="step__desc">
                        Add a single API call to your applications. 
                        SDKs available for Node.js, Python, Go, and more.
                    </p>
                </div>
                
                <div class="step" data-animate="fade-up">
                    <h3 class="step__title">Receive Everything</h3>
                    <p class="step__desc">
                        All notifications flow to your unified inbox. 
                        Access via web, mobile, or desktop apps.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Code Preview Section -->
    <section class="code-section">
        <div class="container">
            <div class="code-section__grid">
                <div class="section-header" data-animate="fade-right">
                    <span class="section-eyebrow">Developer Experience</span>
                    <h2 class="section-title">Simple API,<br>powerful results</h2>
                    <p class="section-subtitle">
                        Send your first notification in under a minute. 
                        Our API is designed to be intuitive and well-documented.
                    </p>
                </div>
                
                <div class="code-preview" data-animate="fade-left">
                    <div class="code-preview__header">
                        <div class="code-preview__dots">
                            <span class="code-preview__dot"></span>
                            <span class="code-preview__dot"></span>
                            <span class="code-preview__dot"></span>
                        </div>
                        <span class="code-preview__lang">JavaScript</span>
                    </div>
                    <button class="code-copy-btn">Copy</button>
                    <div class="code-preview__body">
                        <pre><code><span class="token-keyword">import</span> { Rabbit } <span class="token-keyword">from</span> <span class="token-string">'@rabbit/sdk'</span>;

<span class="token-keyword">const</span> rabbit = <span class="token-keyword">new</span> <span class="token-method">Rabbit</span>(<span class="token-string">'your_api_key'</span>);

<span class="token-keyword">await</span> rabbit.<span class="token-method">send</span>({
  <span class="token-property">app</span>: <span class="token-string">'my-saas'</span>,
  <span class="token-property">title</span>: <span class="token-string">'New User Signup'</span>,
  <span class="token-property">body</span>: <span class="token-string">'john@example.com just created an account'</span>,
  <span class="token-property">tags</span>: [<span class="token-string">'users'</span>, <span class="token-string">'growth'</span>]
});</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="section-header section-header--center" data-animate="fade-up">
                <span class="section-eyebrow">Get Started</span>
                <h2 class="section-title">Ready to simplify<br>your notifications?</h2>
                <p class="section-subtitle">
                    Join developers who've consolidated their notification chaos.
                </p>
                <div class="cta-actions">
                    <button class="btn btn--primary btn--lg" data-auth-trigger="signup">Create Free Account</button>
                    <button class="btn btn--outline btn--lg" data-auth-trigger="signin">Sign In</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__inner">
                <div class="footer__brand">
                    <div class="footer__logo">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="footer__copy">&copy; 2026 Rabbit. All rights reserved.</span>
                </div>
                
                <div class="footer__links">
                    <a href="#" class="footer__link">Documentation</a>
                    <a href="#" class="footer__link">Privacy</a>
                    <a href="#" class="footer__link">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div class="modal-overlay">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="modal__header">
                <h2 class="modal__title" id="modal-title">Welcome</h2>
                <button class="modal__close" aria-label="Close modal">&times;</button>
            </div>
            <div class="modal__body">
                <div class="modal__tabs">
                    <button class="modal__tab is-active" data-tab="signin">Sign In</button>
                    <button class="modal__tab" data-tab="signup">Sign Up</button>
                </div>
                
                <!-- Sign In Form -->
                <form class="auth-form is-active" data-form="signin" id="signin-form">
                    <div class="form-group">
                        <label class="label" for="signin-email">Email</label>
                        <input type="email" class="input" id="signin-email" name="email" placeholder="you@company.com" required>
                    </div>
                    <div class="form-group">
                        <label class="label" for="signin-password">Password</label>
                        <input type="password" class="input" id="signin-password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary" style="width: 100%;">Sign In</button>
                    </div>
                    
                    <div class="form-divider">or continue with</div>
                    
                    <button type="button" class="social-btn">
                        <svg viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Continue with Google
                    </button>
                    
                    <div class="form-footer">
                        <p class="form-footer__text">
                            Don't have an account? 
                            <span class="form-footer__link" data-switch-tab="signup">Sign up</span>
                        </p>
                    </div>
                </form>
                
                <!-- Sign Up Form -->
                <form class="auth-form" data-form="signup" id="signup-form">
                    <div class="form-group">
                        <label class="label" for="signup-name">Full Name</label>
                        <input type="text" class="input" id="signup-name" name="name" placeholder="John Doe" required minlength="2">
                    </div>
                    <div class="form-group">
                        <label class="label" for="signup-email">Email</label>
                        <input type="email" class="input" id="signup-email" name="email" placeholder="you@company.com" required>
                    </div>
                    <div class="form-group">
                        <label class="label" for="signup-password">Password</label>
                        <input type="password" class="input" id="signup-password" name="password" placeholder="Min. 8 characters" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label class="label" for="signup-confirm-password">Confirm Password</label>
                        <input type="password" class="input" id="signup-confirm-password" name="confirm_password" placeholder="Re-enter password" required minlength="8">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary" style="width: 100%;">Create Account</button>
                    </div>
                    
                    <p class="form-terms">
                        By creating an account, you agree to our 
                        <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                    </p>
                    
                    <div class="form-footer">
                        <p class="form-footer__text">
                            Already have an account? 
                            <span class="form-footer__link" data-switch-tab="signin">Sign in</span>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="JS/global.js"></script>
    <script src="JS/index.js"></script>
</body>
</html>
