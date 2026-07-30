/**
 * Lenis smooth scroll (loaded globally — see inc/enqueue.php). Driven by
 * GSAP's own ticker rather than a separate requestAnimationFrame loop, and
 * synced to ScrollTrigger via lenis.on('scroll', ScrollTrigger.update) —
 * without this, ScrollTrigger's trigger points drift out of sync with
 * Lenis's smoothed scroll position. Falls back to a plain raf loop if GSAP
 * isn't available. No-ops entirely if Lenis failed to load or the user
 * prefers reduced motion.
 */
export function initSmoothScroll() {
	if (
		typeof window.Lenis === 'undefined' ||
		window.matchMedia('(prefers-reduced-motion: reduce)').matches
	) {
		return;
	}

	const lenis = new window.Lenis();

	window.lenis = lenis;

	if (window.gsap && window.ScrollTrigger) {
		lenis.on('scroll', window.ScrollTrigger.update);
		window.gsap.ticker.add((time) => {
			lenis.raf(time * 1000);
		});
		window.gsap.ticker.lagSmoothing(0);
		return;
	}

	requestAnimationFrame(function raf(time) {
		lenis.raf(time);
		requestAnimationFrame(raf);
	});
}
