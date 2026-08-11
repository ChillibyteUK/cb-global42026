/**
 * Counts up any [data-counter] element from 0 to its target value once it
 * scrolls into view. Content that must always display correctly, so
 * reduced-motion just sets the final value instantly rather than skipping
 * it outright (unlike the purely decorative GSAP entrances in
 * scroll-animate.js).
 */
export function initCounters() {
	const counters = document.querySelectorAll('[data-counter]');

	if (!counters.length) {
		return;
	}

	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	const duration = 1500;

	const animate = (el) => {
		const target = parseFloat(el.dataset.counter);
		const decimals = (el.dataset.counter.split('.')[1] || '').length;
		const format = (value) =>
			value.toLocaleString(undefined, {
				minimumFractionDigits: decimals,
				maximumFractionDigits: decimals,
			});

		if (!target || prefersReducedMotion) {
			el.textContent = format(target);
			return;
		}

		const start = performance.now();

		const tick = (now) => {
			const progress = Math.min((now - start) / duration, 1);
			el.textContent = format(target * progress);

			if (progress < 1) {
				requestAnimationFrame(tick);
			}
		};

		requestAnimationFrame(tick);
	};

	const observer = new IntersectionObserver((entries, obs) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				animate(entry.target);
				obs.unobserve(entry.target);
			}
		});
	}, { threshold: 0.5 });

	counters.forEach((el) => observer.observe(el));
}
