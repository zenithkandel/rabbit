<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect — Rabbit</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="CSS/connect.css">
</head>
<body>
    <div class="connect-page">
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-header__top">
                <div class="page-header__title-group">
                    <h1 class="page-header__title">Connect Your App</h1>
                    <p class="page-header__subtitle">Send notifications to Rabbit in minutes</p>
                </div>
            </div>
        </header>

        <!-- Quick Start Section -->
        <section class="guide-section">
            <div class="guide-section__header">
                <span class="guide-section__step">1</span>
                <div>
                    <h2 class="guide-section__title">Get Your API Key</h2>
                    <p class="guide-section__desc">Your API key authenticates requests to the Rabbit API</p>
                </div>
            </div>
            <div class="guide-section__content">
                <div class="api-key-box">
                    <div class="api-key-box__label">Your API Key</div>
                    <div class="api-key-box__value">
                        <code id="apiKeyDisplay">rb_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxx</code>
                        <button class="btn-copy" id="copyApiKey" title="Copy API Key">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                        </button>
                    </div>
                    <p class="api-key-box__hint">Keep this secret! Never expose it in client-side code.</p>
                </div>
                <button class="btn btn--ghost" id="regenerateKey">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                        <polyline points="23 4 23 10 17 10"/>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                    </svg>
                    Regenerate Key
                </button>
            </div>
        </section>

        <!-- App Identifier Section -->
        <section class="guide-section">
            <div class="guide-section__header">
                <span class="guide-section__step">2</span>
                <div>
                    <h2 class="guide-section__title">Find Your App Identifier</h2>
                    <p class="guide-section__desc">Each connected app has a unique identifier (slug)</p>
                </div>
            </div>
            <div class="guide-section__content">
                <p class="guide-text">
                    Go to the <a href="#" class="guide-link" data-page="apps.php">Apps</a> section to see your connected apps. 
                    Each app has an identifier like <code>stripe</code>, <code>github</code>, or <code>my-app</code>.
                </p>
                <div class="app-example">
                    <div class="app-example__icon" style="background: #635BFF">S</div>
                    <div class="app-example__info">
                        <span class="app-example__name">Stripe</span>
                        <code class="app-example__slug">stripe</code>
                    </div>
                </div>
            </div>
        </section>

        <!-- Code Example Section -->
        <section class="guide-section guide-section--large">
            <div class="guide-section__header">
                <span class="guide-section__step">3</span>
                <div>
                    <h2 class="guide-section__title">Send a Notification</h2>
                    <p class="guide-section__desc">Use fetch to send notifications from your backend</p>
                </div>
            </div>
            <div class="guide-section__content">
                <!-- Code Tabs -->
                <div class="code-tabs">
                    <button class="code-tab is-active" data-lang="javascript">JavaScript</button>
                    <button class="code-tab" data-lang="nodejs">Node.js</button>
                    <button class="code-tab" data-lang="curl">cURL</button>
                </div>
                
                <!-- JavaScript Example -->
                <div class="code-block" data-lang="javascript">
                    <div class="code-block__header">
                        <span class="code-block__lang">JavaScript (Backend)</span>
                        <button class="btn-copy" data-copy="javascript" title="Copy code">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            Copy
                        </button>
                    </div>
                    <pre class="code-block__content"><code><span class="token keyword">const</span> <span class="token function">sendNotification</span> <span class="token operator">=</span> <span class="token keyword">async</span> <span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=></span> <span class="token punctuation">{</span>
  <span class="token keyword">const</span> response <span class="token operator">=</span> <span class="token keyword">await</span> <span class="token function">fetch</span><span class="token punctuation">(</span><span class="token string">'https://api.rabbit.io/v1/notify'</span><span class="token punctuation">,</span> <span class="token punctuation">{</span>
    method<span class="token punctuation">:</span> <span class="token string">'POST'</span><span class="token punctuation">,</span>
    headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
      <span class="token string">'Content-Type'</span><span class="token punctuation">:</span> <span class="token string">'application/json'</span><span class="token punctuation">,</span>
      <span class="token string">'Authorization'</span><span class="token punctuation">:</span> <span class="token string">'Bearer YOUR_API_KEY'</span>
    <span class="token punctuation">}</span><span class="token punctuation">,</span>
    body<span class="token punctuation">:</span> <span class="token builtin">JSON</span><span class="token punctuation">.</span><span class="token function">stringify</span><span class="token punctuation">(</span><span class="token punctuation">{</span>
      app<span class="token punctuation">:</span> <span class="token string">'your-app-slug'</span><span class="token punctuation">,</span>
      title<span class="token punctuation">:</span> <span class="token string">'New Payment Received'</span><span class="token punctuation">,</span>
      body<span class="token punctuation">:</span> <span class="token string">'You received a payment of $99.00 from john@example.com'</span><span class="token punctuation">,</span>
      tag<span class="token punctuation">:</span> <span class="token string">'payment'</span>  <span class="token comment">// optional: 'alert', 'info', 'success', etc.</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span>
  <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
  
  <span class="token keyword">const</span> data <span class="token operator">=</span> <span class="token keyword">await</span> response<span class="token punctuation">.</span><span class="token function">json</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
  <span class="token builtin">console</span><span class="token punctuation">.</span><span class="token function">log</span><span class="token punctuation">(</span><span class="token string">'Notification sent:'</span><span class="token punctuation">,</span> data<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span><span class="token punctuation">;</span>

<span class="token function">sendNotification</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span></code></pre>
                </div>

                <!-- Node.js Example -->
                <div class="code-block" data-lang="nodejs" hidden>
                    <div class="code-block__header">
                        <span class="code-block__lang">Node.js</span>
                        <button class="btn-copy" data-copy="nodejs" title="Copy code">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            Copy
                        </button>
                    </div>
                    <pre class="code-block__content"><code><span class="token comment">// Using node-fetch or built-in fetch (Node 18+)</span>
<span class="token keyword">const</span> RABBIT_API_KEY <span class="token operator">=</span> process<span class="token punctuation">.</span>env<span class="token punctuation">.</span>RABBIT_API_KEY<span class="token punctuation">;</span>

<span class="token keyword">async function</span> <span class="token function">sendNotification</span><span class="token punctuation">(</span>notification<span class="token punctuation">)</span> <span class="token punctuation">{</span>
  <span class="token keyword">const</span> response <span class="token operator">=</span> <span class="token keyword">await</span> <span class="token function">fetch</span><span class="token punctuation">(</span><span class="token string">'https://api.rabbit.io/v1/notify'</span><span class="token punctuation">,</span> <span class="token punctuation">{</span>
    method<span class="token punctuation">:</span> <span class="token string">'POST'</span><span class="token punctuation">,</span>
    headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
      <span class="token string">'Content-Type'</span><span class="token punctuation">:</span> <span class="token string">'application/json'</span><span class="token punctuation">,</span>
      <span class="token string">'Authorization'</span><span class="token punctuation">:</span> <span class="token template-string">`Bearer <span class="token interpolation">${RABBIT_API_KEY}</span>`</span>
    <span class="token punctuation">}</span><span class="token punctuation">,</span>
    body<span class="token punctuation">:</span> <span class="token builtin">JSON</span><span class="token punctuation">.</span><span class="token function">stringify</span><span class="token punctuation">(</span><span class="token punctuation">{</span>
      app<span class="token punctuation">:</span> notification<span class="token punctuation">.</span>app<span class="token punctuation">,</span>
      title<span class="token punctuation">:</span> notification<span class="token punctuation">.</span>title<span class="token punctuation">,</span>
      body<span class="token punctuation">:</span> notification<span class="token punctuation">.</span>body<span class="token punctuation">,</span>
      tag<span class="token punctuation">:</span> notification<span class="token punctuation">.</span>tag <span class="token operator">||</span> <span class="token keyword">null</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span>
  <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>

  <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token operator">!</span>response<span class="token punctuation">.</span>ok<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    <span class="token keyword">throw new</span> <span class="token class-name">Error</span><span class="token punctuation">(</span><span class="token template-string">`Rabbit API error: <span class="token interpolation">${response.status}</span>`</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
  <span class="token punctuation">}</span>

  <span class="token keyword">return</span> response<span class="token punctuation">.</span><span class="token function">json</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>

<span class="token comment">// Example usage</span>
<span class="token function">sendNotification</span><span class="token punctuation">(</span><span class="token punctuation">{</span>
  app<span class="token punctuation">:</span> <span class="token string">'stripe'</span><span class="token punctuation">,</span>
  title<span class="token punctuation">:</span> <span class="token string">'New Subscription'</span><span class="token punctuation">,</span>
  body<span class="token punctuation">:</span> <span class="token string">'Customer upgraded to Pro plan ($29/mo)'</span><span class="token punctuation">,</span>
  tag<span class="token punctuation">:</span> <span class="token string">'success'</span>
<span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span>result <span class="token operator">=></span> <span class="token builtin">console</span><span class="token punctuation">.</span><span class="token function">log</span><span class="token punctuation">(</span>result<span class="token punctuation">)</span><span class="token punctuation">)</span><span class="token punctuation">;</span></code></pre>
                </div>

                <!-- cURL Example -->
                <div class="code-block" data-lang="curl" hidden>
                    <div class="code-block__header">
                        <span class="code-block__lang">cURL</span>
                        <button class="btn-copy" data-copy="curl" title="Copy code">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            Copy
                        </button>
                    </div>
                    <pre class="code-block__content"><code><span class="token function">curl</span> <span class="token flag">-X</span> POST <span class="token url">https://api.rabbit.io/v1/notify</span> <span class="token operator">\</span>
  <span class="token flag">-H</span> <span class="token string">"Content-Type: application/json"</span> <span class="token operator">\</span>
  <span class="token flag">-H</span> <span class="token string">"Authorization: Bearer YOUR_API_KEY"</span> <span class="token operator">\</span>
  <span class="token flag">-d</span> <span class="token string">'{
    "app": "your-app-slug",
    "title": "New Payment Received",
    "body": "You received a payment of $99.00",
    "tag": "payment"
  }'</span></code></pre>
                </div>
            </div>
        </section>

        <!-- Request Body Reference -->
        <section class="guide-section">
            <div class="guide-section__header">
                <span class="guide-section__step">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                </span>
                <div>
                    <h2 class="guide-section__title">Request Body Reference</h2>
                    <p class="guide-section__desc">All available fields for the notification payload</p>
                </div>
            </div>
            <div class="guide-section__content">
                <table class="params-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>app</code></td>
                            <td>string</td>
                            <td><span class="badge badge--required">Required</span></td>
                            <td>Your app identifier (slug) from the Apps section</td>
                        </tr>
                        <tr>
                            <td><code>title</code></td>
                            <td>string</td>
                            <td><span class="badge badge--required">Required</span></td>
                            <td>Notification title (max 100 characters)</td>
                        </tr>
                        <tr>
                            <td><code>body</code></td>
                            <td>string</td>
                            <td><span class="badge badge--required">Required</span></td>
                            <td>Notification message body (max 500 characters)</td>
                        </tr>
                        <tr>
                            <td><code>tag</code></td>
                            <td>string</td>
                            <td><span class="badge badge--optional">Optional</span></td>
                            <td>Category tag: <code>alert</code>, <code>info</code>, <code>success</code>, <code>warning</code>, or custom</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Response Section -->
        <section class="guide-section">
            <div class="guide-section__header">
                <span class="guide-section__step">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
                <div>
                    <h2 class="guide-section__title">Response</h2>
                    <p class="guide-section__desc">Successful response from the API</p>
                </div>
            </div>
            <div class="guide-section__content">
                <div class="code-block code-block--response">
                    <div class="code-block__header">
                        <span class="code-block__lang">
                            <span class="status-badge status-badge--success">200 OK</span>
                        </span>
                    </div>
                    <pre class="code-block__content"><code><span class="token punctuation">{</span>
  <span class="token property">"success"</span><span class="token punctuation">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">"id"</span><span class="token punctuation">:</span> <span class="token string">"notif_abc123xyz"</span><span class="token punctuation">,</span>
  <span class="token property">"message"</span><span class="token punctuation">:</span> <span class="token string">"Notification delivered successfully"</span>
<span class="token punctuation">}</span></code></pre>
                </div>
            </div>
        </section>

        <!-- Need Help Section -->
        <section class="help-section">
            <div class="help-section__icon">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div class="help-section__content">
                <h3 class="help-section__title">Need Help?</h3>
                <p class="help-section__text">
                    Check out our <a href="#" class="guide-link">documentation</a> for more examples, 
                    or reach out to <a href="mailto:support@rabbit.io" class="guide-link">support@rabbit.io</a>
                </p>
            </div>
        </section>
    </div>

    <!-- Confirm Modal for Regenerate Key -->
    <div class="modal modal--sm" id="confirmModal">
        <div class="modal__backdrop"></div>
        <div class="modal__container">
            <div class="modal__header">
                <h2 class="modal__title">Regenerate API Key</h2>
                <button class="modal__close" id="confirmClose">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal__body">
                <div class="confirm-content">
                    <div class="confirm-icon confirm-icon--warning">
                        <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <p class="confirm-message">Are you sure you want to regenerate your API key?</p>
                    <p class="confirm-warning">Your current key will be invalidated immediately. Any apps using the old key will stop working.</p>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" id="confirmCancelBtn">Cancel</button>
                <button type="button" class="btn btn--primary" id="confirmActionBtn">Regenerate</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="JS/connect.js"></script>
</body>
</html>
