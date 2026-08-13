import { initNavToggle } from './nav-toggle';
import { initNavDropdowns } from './nav-dropdown';
import { initNavScroll } from './nav-scroll';
import { initDialogs } from './dialog';
import { initSmoothScroll } from './smooth-scroll';
import { initScrollAnimate } from './scroll-animate';
import { initTabbedContent } from './tabbed-content';
import { initCrossfadeSlider } from './crossfade-slider';
import { initPostIndex } from './post-index';
import { initWebinars } from './webinars';
import { initCounters } from './counter';
import { initJourney } from './journey';

document.addEventListener('DOMContentLoaded', () => {
	initNavToggle();
	initNavDropdowns();
	initNavScroll();
	initDialogs();
	initSmoothScroll();
	initScrollAnimate();
	initTabbedContent();
	initCrossfadeSlider('.cb-quote-slider__track', '.cb-quote-slider__slide');
	initCrossfadeSlider('.cb-text-stat-slider__track', '.cb-text-stat-slider__slide', 4000);
	initPostIndex();
	initWebinars();
	initCounters();
	initJourney();
});
