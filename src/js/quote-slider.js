/**
 * CB Quote Slider — autoplay-only crossfade between repeater rows. No nav,
 * no pagination, no drag/swipe, so a full carousel library (Swiper etc.)
 * isn't justified here — this is the entire behaviour needed. Non-active
 * slides are already hidden via CSS (opacity: 0 on .cb-quote-slider__slide,
 * see src/blocks/cb-quote-slider.css) — this just cycles which one carries
 * .is-active, on a timer.
 */
export function initQuoteSliders() {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	document.querySelectorAll('.cb-quote-slider__track').forEach((track) => {
		const slides = track.querySelectorAll('.cb-quote-slider__slide');

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
		}, 6000);
	});
}
