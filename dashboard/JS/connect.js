/**
 * ═══════════════════════════════════════════════════════════════════════════
 * CONNECT PAGE JAVASCRIPT
 * Rabbit Notification Service — Integration Guide
 * ═══════════════════════════════════════════════════════════════════════════
 */

(function() {
    'use strict';

    /* ─────────────────────────────────────────────────────────────────
       Mock API Key
       ───────────────────────────────────────────────────────────────── */
    let currentApiKey = 'rb_live_sk7xK9mP2nQ4wR8tY6uI3oL5jH1gF0zA';

    /* ─────────────────────────────────────────────────────────────────
       Code Examples
       ───────────────────────────────────────────────────────────────── */
    const CODE_EXAMPLES = {
        javascript: `const sendNotification = async () => {
  const response = await fetch('https://api.rabbit.io/v1/notify', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer YOUR_API_KEY'
    },
    body: JSON.stringify({
      app: 'your-app-slug',
      title: 'New Payment Received',
      body: 'You received a payment of $99.00 from john@example.com',
      tag: 'payment'  // optional: 'alert', 'info', 'success', etc.
    })
  });
  
  const data = await response.json();
  console.log('Notification sent:', data);
};

sendNotification();`,

        nodejs: `// Using node-fetch or built-in fetch (Node 18+)
const RABBIT_API_KEY = process.env.RABBIT_API_KEY;

async function sendNotification(notification) {
  const response = await fetch('https://api.rabbit.io/v1/notify', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': \`Bearer \${RABBIT_API_KEY}\`
    },
    body: JSON.stringify({
      app: notification.app,
      title: notification.title,
      body: notification.body,
      tag: notification.tag || null
    })
  });

  if (!response.ok) {
    throw new Error(\`Rabbit API error: \${response.status}\`);
  }

  return response.json();
}

// Example usage
sendNotification({
  app: 'stripe',
  title: 'New Subscription',
  body: 'Customer upgraded to Pro plan ($29/mo)',
  tag: 'success'
}).then(result => console.log(result));`,

        curl: `curl -X POST https://api.rabbit.io/v1/notify \\
  -H "Content-Type: application/json" \\
  -H "Authorization: Bearer YOUR_API_KEY" \\
  -d '{
    "app": "your-app-slug",
    "title": "New Payment Received",
    "body": "You received a payment of $99.00",
    "tag": "payment"
  }'`
    };

    /* ─────────────────────────────────────────────────────────────────
       DOM Elements
       ───────────────────────────────────────────────────────────────── */
    const DOM = {
        // API Key
        apiKeyDisplay: document.getElementById('apiKeyDisplay'),
        copyApiKey: document.getElementById('copyApiKey'),
        regenerateKey: document.getElementById('regenerateKey'),
        
        // Code tabs
        codeTabs: document.querySelectorAll('.code-tab'),
        codeBlocks: document.querySelectorAll('.code-block[data-lang]'),
        
        // Copy buttons
        copyButtons: document.querySelectorAll('.btn-copy[data-copy]'),
        
        // Modal
        confirmModal: document.getElementById('confirmModal'),
        confirmClose: document.getElementById('confirmClose'),
        confirmCancelBtn: document.getElementById('confirmCancelBtn'),
        confirmActionBtn: document.getElementById('confirmActionBtn'),
        
        // Toast
        toastContainer: document.getElementById('toastContainer')
    };

    /* ─────────────────────────────────────────────────────────────────
       Utility Functions
       ───────────────────────────────────────────────────────────────── */
    function generateApiKey() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let key = 'rb_live_';
        for (let i = 0; i < 32; i++) {
            key += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return key;
    }

    function maskApiKey(key) {
        if (key.length <= 16) return key;
        return key.substring(0, 12) + '••••••••••••••••••••' + key.substring(key.length - 4);
    }

    /* ─────────────────────────────────────────────────────────────────
       Toast Notifications
       ───────────────────────────────────────────────────────────────── */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        
        const iconPath = type === 'success' 
            ? '<polyline points="20 6 9 17 4 12"/>'
            : '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
        
        toast.innerHTML = `
            <svg class="toast__icon" viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                ${iconPath}
            </svg>
            <span>${message}</span>
        `;
        
        DOM.toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('is-leaving');
            setTimeout(() => toast.remove(), 200);
        }, 3000);
    }

    /* ─────────────────────────────────────────────────────────────────
       Copy to Clipboard
       ───────────────────────────────────────────────────────────────── */
    async function copyToClipboard(text, button) {
        try {
            await navigator.clipboard.writeText(text);
            
            // Visual feedback
            button.classList.add('is-copied');
            const originalHTML = button.innerHTML;
            
            if (button.querySelector('svg')) {
                button.innerHTML = `
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    ${button.textContent.includes('Copy') ? 'Copied!' : ''}
                `;
            }
            
            setTimeout(() => {
                button.classList.remove('is-copied');
                button.innerHTML = originalHTML;
            }, 2000);
            
            return true;
        } catch (err) {
            showToast('Failed to copy', 'error');
            return false;
        }
    }

    /* ─────────────────────────────────────────────────────────────────
       Modal Management
       ───────────────────────────────────────────────────────────────── */
    function openConfirmModal() {
        DOM.confirmModal.classList.add('is-open');
    }

    function closeConfirmModal() {
        DOM.confirmModal.classList.remove('is-open');
    }

    /* ─────────────────────────────────────────────────────────────────
       Tab Switching
       ───────────────────────────────────────────────────────────────── */
    function switchTab(lang) {
        // Update tabs
        DOM.codeTabs.forEach(tab => {
            tab.classList.toggle('is-active', tab.dataset.lang === lang);
        });
        
        // Update code blocks
        DOM.codeBlocks.forEach(block => {
            block.hidden = block.dataset.lang !== lang;
        });
    }

    /* ─────────────────────────────────────────────────────────────────
       Event Listeners
       ───────────────────────────────────────────────────────────────── */
    function initEventListeners() {
        // Copy API key
        DOM.copyApiKey.addEventListener('click', () => {
            copyToClipboard(currentApiKey, DOM.copyApiKey);
            showToast('API key copied to clipboard');
        });
        
        // Regenerate key button
        DOM.regenerateKey.addEventListener('click', openConfirmModal);
        
        // Tab switching
        DOM.codeTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                switchTab(tab.dataset.lang);
            });
        });
        
        // Copy code buttons
        DOM.copyButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const lang = btn.dataset.copy;
                const code = CODE_EXAMPLES[lang];
                if (code) {
                    copyToClipboard(code, btn);
                    showToast('Code copied to clipboard');
                }
            });
        });
        
        // Modal close
        DOM.confirmClose.addEventListener('click', closeConfirmModal);
        DOM.confirmCancelBtn.addEventListener('click', closeConfirmModal);
        DOM.confirmModal.querySelector('.modal__backdrop').addEventListener('click', closeConfirmModal);
        
        // Confirm regenerate
        DOM.confirmActionBtn.addEventListener('click', () => {
            currentApiKey = generateApiKey();
            DOM.apiKeyDisplay.textContent = maskApiKey(currentApiKey);
            closeConfirmModal();
            showToast('API key regenerated successfully');
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && DOM.confirmModal.classList.contains('is-open')) {
                closeConfirmModal();
            }
        });
        
        // Handle app link navigation (for parent iframe communication)
        document.querySelectorAll('[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = link.dataset.page;
                // Try to communicate with parent dashboard
                if (window.parent !== window) {
                    window.parent.postMessage({ type: 'navigate', page }, '*');
                }
            });
        });
    }

    /* ─────────────────────────────────────────────────────────────────
       Initialize
       ───────────────────────────────────────────────────────────────── */
    function init() {
        // Display masked API key
        DOM.apiKeyDisplay.textContent = maskApiKey(currentApiKey);
        
        // Initialize event listeners
        initEventListeners();
    }

    // Start
    init();

})();
