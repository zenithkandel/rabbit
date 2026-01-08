<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Signalstack — Notification API Hub</title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="CSS/global.css" />
	<link rel="stylesheet" href="CSS/index.css" />
</head>
<body>
	<div class="page-frame">
		<header class="site-header">
			<div class="brand mark">Signalstack</div>
			<nav class="nav">
				<a href="#how" class="nav-link">How it works</a>
				<a href="#integrations" class="nav-link">Integrations</a>
				<a href="#pricing" class="nav-link">Pricing</a>
				<a href="#faq" class="nav-link">FAQ</a>
			</nav>
			<div class="cta-row">
				<button class="ghost-btn" data-scroll-to="#how">Tour</button>
				<button class="solid-btn" data-scroll-to="#cta">Get API key</button>
			</div>
		</header>

		<main>
			<section class="hero" id="top">
				<div class="hero-copy">
					<div class="eyebrow">Retro-sharp notification rail</div>
					<h1>Pipe every app into one clear signal.</h1>
					<p class="lead">Signalstack issues a single API key that your apps post to. We fan the traffic into your channels and keep the trail auditable, encrypted, and fast.</p>
					<div class="hero-ctas">
						<button class="solid-btn" data-scroll-to="#cta">Start free</button>
						<button class="ghost-btn" data-scroll-to="#code">View payload</button>
					</div>
					<div class="hero-tags">
						<span class="tag">1.2M req/min sustained</span>
						<span class="tag">SLA-first</span>
						<span class="tag">Multi-app inbox</span>
					</div>
				</div>
				<div class="hero-panel">
					<div class="panel-header">Live route trace</div>
					<div class="panel-body">
						<div class="trace-row">
							<span class="trace-dot green"></span>
							<span class="trace-label">app.crm</span>
							<span class="trace-meta">delivered · 42ms</span>
						</div>
						<div class="trace-row">
							<span class="trace-dot amber"></span>
							<span class="trace-label">app.billing</span>
							<span class="trace-meta">queued · 88ms</span>
						</div>
						<div class="trace-row">
							<span class="trace-dot cream"></span>
							<span class="trace-label">app.support</span>
							<span class="trace-meta">fanout · 51ms</span>
						</div>
						<div class="divider"></div>
						<div class="mini-stat">
							<span>Fanout destinations</span>
							<strong>Slack · Webhook · Email · SMS</strong>
						</div>
					</div>
				</div>
			</section>

			<section class="grid-meta" id="how">
				<div class="grid-card">
					<div class="card-title">1 · Issue a key</div>
					<p>Provision a scoped API key per workspace or per app. Rotate in seconds without downtime.</p>
				</div>
				<div class="grid-card">
					<div class="card-title">2 · Send to one rail</div>
					<p>Your apps POST to a single endpoint. We normalize payloads, sign them, and route by rules.</p>
				</div>
				<div class="grid-card">
					<div class="card-title">3 · Fanout everywhere</div>
					<p>Dispatch into Slack, email, SMS, webhooks, or custom sockets. One place to manage delivery.</p>
				</div>
				<div class="grid-card">
					<div class="card-title">4 · Audit forever</div>
					<p>Time-ordered, hash-locked ledger of every notification, including retries and read receipts.</p>
				</div>
			</section>

			<section class="code-block" id="code">
				<div class="code-meta">
					<p class="eyebrow">Send a notification</p>
					<h2>Drop in with cURL or fetch.</h2>
					<p>Point any app to the rail and include your key. Fanout rules, retries, and receipts are automatic.</p>
				</div>
				<div class="code-window">
					<div class="code-bar">
						<span class="code-dot"></span>
						<span class="code-dot"></span>
						<span class="code-dot"></span>
						<div class="code-env">workspace: signalstack-prod</div>
					</div>
					<pre><code class="language-bash">curl -X POST https://api.signalstack.dev/notify \ 
  -H "Authorization: Bearer sk_live_x3J4..." \ 
  -H "Content-Type: application/json" \ 
  -d '{
	"source": "billing-app",
	"channel": "inbox+slack",
	"title": "Invoice paid",
	"body": "Acme Ltd settled INV-2048",
	"severity": "info"
  }'</code></pre>
					<button class="ghost-btn copy-btn" data-copy="curl -X POST https://api.signalstack.dev/notify -H 'Authorization: Bearer sk_live_x3J4...' -H 'Content-Type: application/json' -d '{\"source\":\"billing-app\",\"channel\":\"inbox+slack\",\"title\":\"Invoice paid\",\"body\":\"Acme Ltd settled INV-2048\",\"severity\":\"info\"}'">Copy snippet</button>
				</div>
			</section>

			<section class="pillars" id="integrations">
				<div class="pillar">
					<h3>Connect every app</h3>
					<p>One rail for CRMs, billing, support, infra alerts, and custom stacks. Bring order to noisy systems.</p>
					<ul class="list">
						<li>Slack, Teams, Email, SMS fanout</li>
						<li>Webhook retries with signed payloads</li>
						<li>Latency-aware routing and batching</li>
					</ul>
				</div>
				<div class="pillar">
					<h3>Trust the ledger</h3>
					<p>Every notification is hashed into a ledger. Search, export, and reconcile receipts with a click.</p>
					<ul class="list">
						<li>SHA-256 signed envelopes</li>
						<li>Replay-resistant tokens</li>
						<li>Granular access per app</li>
					</ul>
				</div>
				<div class="pillar">
					<h3>Design with clarity</h3>
					<p>Retro-sharp UI keeps signal clean: bold borders, steady motion, and quiet gradients for hierarchy.</p>
					<ul class="list">
						<li>Grid-aligned layout</li>
						<li>Readable mono overlays</li>
						<li>Subtle hover telemetry</li>
					</ul>
				</div>
			</section>

			<section class="pricing" id="pricing">
				<div class="pricing-card">
					<div class="pricing-label">Launch</div>
					<div class="pricing-value">Free</div>
					<p>Up to 50K notifications monthly, 3 destinations, ledgers retained 30 days.</p>
					<button class="solid-btn" data-scroll-to="#cta">Start</button>
				</div>
				<div class="pricing-card featured">
					<div class="pricing-label">SLA</div>
					<div class="pricing-value">$79</div>
					<p>2M notifications, 15 destinations, priority queues, and SLO dashboards.</p>
					<button class="solid-btn" data-scroll-to="#cta">Choose</button>
				</div>
				<div class="pricing-card">
					<div class="pricing-label">Scale</div>
					<div class="pricing-value">Talk</div>
					<p>Custom contracts, on-prem hooks, multi-region delivery, and red-team onboarding.</p>
					<button class="ghost-btn" data-scroll-to="#cta">Contact</button>
				</div>
			</section>

			<section class="faq" id="faq">
				<h2>Questions we hear most</h2>
				<div class="accordion" data-accordion>
					<button class="accordion-trigger">How fast is delivery?</button>
					<div class="accordion-panel">P99 under 120ms for in-region, under 250ms cross-region. Retries with exponential backoff.</div>
				</div>
				<div class="accordion" data-accordion>
					<button class="accordion-trigger">Can I rotate keys without downtime?</button>
					<div class="accordion-panel">Yes. Create a new key, mark dual-mode, migrate traffic, then revoke. No dropped messages.</div>
				</div>
				<div class="accordion" data-accordion>
					<button class="accordion-trigger">How do you secure fanout destinations?</button>
					<div class="accordion-panel">Signed webhooks, IP allowlists, per-destination secrets, and optional mutual TLS for private relays.</div>
				</div>
				<div class="accordion" data-accordion>
					<button class="accordion-trigger">Do you support on-prem?</button>
					<div class="accordion-panel">Dedicated clusters with private networking, hardware-backed keys, and offline ledgers.</div>
				</div>
			</section>

			<section class="cta" id="cta">
				<div class="cta-copy">
					<h2>Ready to centralize your signals?</h2>
					<p>Get an API key, plug your apps in, and keep your team focused on the signal, not the noise.</p>
				</div>
				<form class="cta-form">
					<label for="email">Work email</label>
					<input id="email" name="email" type="email" placeholder="you@product.com" required />
					<label for="workspace">Workspace name</label>
					<input id="workspace" name="workspace" type="text" placeholder="acme-prod" required />
					<button type="submit" class="solid-btn">Issue my key</button>
					<small class="form-note">No credit card. We email a scoped key and setup script.</small>
				</form>
			</section>
		</main>

		<footer class="footer">
			<div class="brand mark">Signalstack</div>
			<div class="footer-links">
				<a href="#top">Top</a>
				<a href="#how">How</a>
				<a href="#pricing">Pricing</a>
				<a href="#faq">FAQ</a>
			</div>
			<p class="footer-note">Built for developers who want sharp signals, not chatter.</p>
		</footer>
	</div>
	<script src="JS/global.js"></script>
	<script src="JS/index.js"></script>
</body>
</html>
