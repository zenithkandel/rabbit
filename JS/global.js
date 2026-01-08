// Global utilities: scrolling, clipboard, and reveal helper
(function () {
	const scrollToTarget = (selector) => {
		const el = document.querySelector(selector);
		if (!el) return;
		el.scrollIntoView({ behavior: 'smooth', block: 'start' });
	};

	const copyText = async (text) => {
		if (!text) return false;
		try {
			await navigator.clipboard.writeText(text);
			return true;
		} catch (err) {
			console.warn('Clipboard not available', err);
			const textarea = document.createElement('textarea');
			textarea.value = text;
			document.body.appendChild(textarea);
			textarea.select();
			try {
				document.execCommand('copy');
				return true;
			} finally {
				document.body.removeChild(textarea);
			}
		}
	};

	const observeReveal = (nodes, options = {}) => {
		if (!nodes || !nodes.length) return () => {};
		const observer = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add('visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: options.threshold || 0.18 });

		nodes.forEach((node) => observer.observe(node));
		return () => observer.disconnect();
	};

	// Bind scroll buttons
	document.addEventListener('click', (event) => {
		const trigger = event.target.closest('[data-scroll-to]');
		if (!trigger) return;
		const target = trigger.getAttribute('data-scroll-to');
		if (target) {
			event.preventDefault();
			scrollToTarget(target);
		}
	});

	// Expose minimal API globally
	window.Signalstack = {
		scrollToTarget,
		copyText,
		observeReveal,
	};
})();
