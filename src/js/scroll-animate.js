/**
 * Scroll-linked entrance animations using GSAP ScrollTrigger (loaded
 * globally — see inc/enqueue.php). No-ops entirely if GSAP failed to load
 * or the user prefers reduced motion, leaving everything in its normal,
 * already-visible CSS state.
 */
export function initScrollAnimate() {
	if (
		!window.gsap ||
		!window.ScrollTrigger ||
		window.matchMedia('(prefers-reduced-motion: reduce)').matches
	) {
		return;
	}

	window.gsap.registerPlugin(window.ScrollTrigger);

	staggerFadeUpGrid('.cb-icon-card-grid__cards', '.cb-icon-card-grid__card');
	staggerFadeUpGrid('.cb-button-cards__cards', '.cb-button-cards__card');
	staggerFadeUpGrid('.cb-contact-cards__cards', '.cb-contact-cards__card');
	initSplitFeatureList();
	initTextImage();
	initCta();
}

/**
 * Shared "grid of cards" entrance — cards stagger fade up when the grid is
 * ~25% into the viewport. Used by CB Icon Card Grid, CB Button Cards, and
 * CB Contact Cards, which all share this exact layout shape.
 *
 * @param {string} gridSelector Selector for the grid container (the ScrollTrigger).
 * @param {string} cardSelector Selector for the individual cards within it (the stagger targets).
 */
function staggerFadeUpGrid(gridSelector, cardSelector) {
	document.querySelectorAll(gridSelector).forEach((grid) => {
		const cards = grid.querySelectorAll(cardSelector);

		if (!cards.length) return;

		window.gsap.from(cards, {
			opacity: 0,
			y: 32,
			duration: 0.6,
			ease: 'power2.out',
			stagger: 0.12,
			scrollTrigger: {
				trigger: grid,
				start: 'top 75%',
				once: true,
			},
		});
	});
}

/**
 * CB Split Feature List — left intro column fades up, right-hand points
 * stagger fade in from the right, both when ~25% into the viewport.
 */
function initSplitFeatureList() {
	document.querySelectorAll('.cb-split-feature-list').forEach((section) => {
		const intro = section.querySelector('.cb-split-feature-list__intro');
		const points = section.querySelectorAll('.cb-split-feature-list__point');

		if (intro) {
			window.gsap.from(intro, {
				opacity: 0,
				y: 32,
				duration: 0.6,
				ease: 'power2.out',
				scrollTrigger: {
					trigger: intro,
					start: 'top 75%',
					once: true,
				},
			});
		}

		if (points.length) {
			window.gsap.from(points, {
				opacity: 0,
				x: 32,
				duration: 0.6,
				ease: 'power2.out',
				stagger: 0.12,
				scrollTrigger: {
					trigger: section.querySelector('.cb-split-feature-list__points') || section,
					start: 'top 75%',
					once: true,
				},
			});
		}
	});
}

/**
 * CB Text Image — text column fades in from the left, image column fades
 * in from the right, both when ~25% into the viewport.
 */
function initTextImage() {
	document.querySelectorAll('.cb-text-image__grid').forEach((grid) => {
		const text = grid.querySelector('.cb-text-image__text');
		const image = grid.querySelector('.cb-text-image__image');

		const scrollTrigger = {
			trigger: grid,
			start: 'top 75%',
			once: true,
		};

		if (text) {
			window.gsap.from(text, {
				opacity: 0,
				x: -32,
				duration: 0.6,
				ease: 'power2.out',
				scrollTrigger,
			});
		}

		if (image) {
			window.gsap.from(image, {
				opacity: 0,
				x: 32,
				duration: 0.6,
				ease: 'power2.out',
				scrollTrigger,
			});
		}
	});
}

/**
 * CB CTA — the bordered content box fades up when ~25% into the viewport.
 */
function initCta() {
	document.querySelectorAll('.cb-cta .row').forEach((box) => {
		window.gsap.from(box, {
			opacity: 0,
			y: 32,
			duration: 0.6,
			ease: 'power2.out',
			scrollTrigger: {
				trigger: box,
				start: 'top 75%',
				once: true,
			},
		});
	});
}
