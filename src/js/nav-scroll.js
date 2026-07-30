/**
 * Toggles a `.scrolled` class on the header once the page has scrolled past
 * its own height, or whenever the mobile nav panel is open (its own
 * background is opaque, so the header needs its "scrolled" text/logo colours
 * regardless of scroll position). No hide/reveal-on-scroll-direction
 * behaviour — background/logo-swap styling for `.scrolled` is left entirely
 * to CSS. Watches the panel via MutationObserver rather than importing
 * nav-toggle.js, so the two stay independent.
 */
export function initNavScroll() {
	const header = document.getElementById('masthead');
	if (!header) return;

	const mobileMenu = document.getElementById('primary-menu');

	function checkScroll() {
		const menuOpen = Boolean(mobileMenu && mobileMenu.classList.contains('is-open'));
		header.classList.toggle('scrolled', menuOpen || window.scrollY > header.offsetHeight);
	}

	window.addEventListener('scroll', checkScroll, { passive: true });

	if (mobileMenu) {
		new MutationObserver(checkScroll).observe(mobileMenu, { attributes: true, attributeFilter: ['class'] });
	}

	checkScroll();
}
