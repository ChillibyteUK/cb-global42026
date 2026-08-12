/**
 * Autoplay-only crossfade slider. No nav, no pagination, no drag/swipe, so a
 * full carousel library (Swiper etc.) isn't justified — this is the entire
 * behaviour needed. Non-active slides are already hidden via CSS (opacity: 0
 * on the slide class), so this just cycles which one carries .is-active, on a
 * timer.
 *
 * Shared by CB Quote Slider and CB Text Stat Slider, which need identical
 * behaviour against different class names — same shape as
 * staggerFadeUpGrid() in src/js/scroll-animate.js.
 *
 * @param {string} trackSelector Selector for the track wrapping the slides.
 * @param {string} slideSelector Selector for the individual slides within it.
 * @param {number} [interval=6000] Milliseconds each slide stays visible.
 */
export function initCrossfadeSlider(trackSelector, slideSelector, interval = 6000) {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	document.querySelectorAll(trackSelector).forEach((track) => {
		const slides = track.querySelectorAll(slideSelector);

		if (slides.length < 2) {
			return;
		}

		let index = 0;

		setInterval(() => {
			slides[index].classList.remove('is-active');
			slides[index].setAttribute('aria-hidden', 'true');
			index = (index + 1) % slides.length;
			slides[index].classList.add('is-active');
			slides[index].removeAttribute('aria-hidden');
		}, interval);
	});
}
