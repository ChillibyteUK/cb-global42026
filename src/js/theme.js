import { initNavToggle } from './nav-toggle';
import { initNavDropdowns } from './nav-dropdown';
import { initNavScroll } from './nav-scroll';
import { initDialogs } from './dialog';
import { initSmoothScroll } from './smooth-scroll';
import { initScrollAnimate } from './scroll-animate';
import { initTabbedContent } from './tabbed-content';
import { initQuoteSliders } from './quote-slider';

document.addEventListener('DOMContentLoaded', () => {
	initNavToggle();
	initNavDropdowns();
	initNavScroll();
	initDialogs();
	initSmoothScroll();
	initScrollAnimate();
	initTabbedContent();
	initQuoteSliders();
});
