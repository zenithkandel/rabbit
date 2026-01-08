// Page-specific interactions
(function () {
	const { copyText, observeReveal } = window.Signalstack || {};

	// Add reveal class and observe
	const revealables = Array.from(document.querySelectorAll('section, .grid-card, .pricing-card, .pillar'));
	revealables.forEach((el) => el.classList.add('fade-rise'));
	if (observeReveal) {
		observeReveal(revealables);
	}

	// Copy snippet
	document.querySelectorAll('.copy-btn').forEach((btn) => {
		btn.addEventListener('click', async () => {
			const payload = btn.getAttribute('data-copy');
			const ok = copyText ? await copyText(payload) : false;
			const original = btn.textContent;
			btn.textContent = ok ? 'Copied' : 'Copy failed';
			setTimeout(() => (btn.textContent = original), 1600);
		});
	});

	// Accordions
	document.querySelectorAll('[data-accordion]').forEach((wrap) => {
		const trigger = wrap.querySelector('.accordion-trigger');
		const panel = wrap.querySelector('.accordion-panel');
		if (!trigger || !panel) return;
		trigger.addEventListener('click', () => {
			const isOpen = trigger.classList.contains('open');
			document.querySelectorAll('.accordion-trigger').forEach((t) => t.classList.remove('open'));
			document.querySelectorAll('.accordion-panel').forEach((p) => p.classList.remove('open'));
			if (!isOpen) {
				trigger.classList.add('open');
				panel.classList.add('open');
			}
		});
	});

	// CTA form intercept
	const form = document.querySelector('.cta-form');
	if (form) {
		form.addEventListener('submit', (event) => {
			event.preventDefault();
			const submitBtn = form.querySelector('button[type="submit"]');
			if (submitBtn) {
				submitBtn.textContent = 'Key issued';
				submitBtn.disabled = true;
			}
		});
	}

	// Simulated trace updates in hero
	const traces = document.querySelectorAll('.trace-row .trace-meta');
	const statuses = ['delivered', 'queued', 'fanout', 'retrying'];
	if (traces.length) {
		setInterval(() => {
			traces.forEach((meta) => {
				const next = statuses[Math.floor(Math.random() * statuses.length)];
				const latency = Math.floor(Math.random() * 80) + 32;
				meta.textContent = `${next} · ${latency}ms`;
			});
		}, 2400);
	}
})();
