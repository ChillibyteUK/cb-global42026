/*!
 * cb-global42026 v1.0.0 (https://github.com/LamcatUK/cb-global42026)
 * Copyright 2026 LamcatUK
 * Licensed under GPL-3.0
 */
(function () {
	'use strict';

	/**
	 * Mobile nav toggle. Wires any button with aria-controls pointing at a
	 * .navbar-collapse to show/hide it and keep aria-expanded in sync — this is
	 * the entire replacement for Bootstrap's Collapse component for this use case.
	 */
	function initNavToggle() {
	  document.querySelectorAll('.navbar-toggler[aria-controls]').forEach(toggler => {
	    const target = document.getElementById(toggler.getAttribute('aria-controls'));
	    if (!target) return;
	    toggler.addEventListener('click', () => {
	      const isOpen = target.classList.toggle('is-open');
	      toggler.setAttribute('aria-expanded', String(isOpen));
	    });

	    // Close after choosing a link — expected mobile nav behaviour.
	    target.querySelectorAll('a').forEach(link => {
	      link.addEventListener('click', () => {
	        target.classList.remove('is-open');
	        toggler.setAttribute('aria-expanded', 'false');
	      });
	    });
	  });
	}

	/**
	 * Click-to-open nav dropdowns. Each dropdown-toggle button shows/hides its
	 * linked .dropdown-menu and keeps aria-expanded in sync. Clicking elsewhere,
	 * or pressing Escape, closes whatever is open — this is the entire
	 * replacement for hover-based submenus.
	 */
	function initNavDropdowns() {
	  const toggles = document.querySelectorAll('.dropdown-toggle[aria-controls]');
	  function close(toggle) {
	    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
	    if (!menu) return;
	    menu.classList.remove('is-open');
	    toggle.setAttribute('aria-expanded', 'false');
	  }
	  function closeAllExcept(except) {
	    toggles.forEach(toggle => {
	      if (toggle !== except) close(toggle);
	    });
	  }
	  toggles.forEach(toggle => {
	    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
	    if (!menu) return;
	    toggle.addEventListener('click', event => {
	      event.stopPropagation();
	      const isOpen = menu.classList.toggle('is-open');
	      toggle.setAttribute('aria-expanded', String(isOpen));
	      closeAllExcept(toggle);
	    });
	  });
	  document.addEventListener('click', event => {
	    if (event.target.closest('.dropdown-menu')) return;
	    closeAllExcept();
	  });
	  document.addEventListener('keydown', event => {
	    if (event.key !== 'Escape') return;
	    const openToggle = Array.from(toggles).find(toggle => toggle.getAttribute('aria-expanded') === 'true');
	    closeAllExcept();
	    if (openToggle) openToggle.focus();
	  });
	}

	/**
	 * Toggles a `.scrolled` class on the header once the page has scrolled past
	 * its own height, or whenever the mobile nav panel is open (its own
	 * background is opaque, so the header needs its "scrolled" text/logo colours
	 * regardless of scroll position). No hide/reveal-on-scroll-direction
	 * behaviour — background/logo-swap styling for `.scrolled` is left entirely
	 * to CSS. Watches the panel via MutationObserver rather than importing
	 * nav-toggle.js, so the two stay independent.
	 */
	function initNavScroll() {
	  const header = document.getElementById('masthead');
	  if (!header) return;
	  const mobileMenu = document.getElementById('primary-menu');
	  function checkScroll() {
	    const menuOpen = Boolean(mobileMenu && mobileMenu.classList.contains('is-open'));
	    header.classList.toggle('scrolled', menuOpen || window.scrollY > header.offsetHeight);
	  }
	  window.addEventListener('scroll', checkScroll, {
	    passive: true
	  });
	  if (mobileMenu) {
	    new MutationObserver(checkScroll).observe(mobileMenu, {
	      attributes: true,
	      attributeFilter: ['class']
	    });
	  }
	  checkScroll();
	}

	/**
	 * Native <dialog> wiring — replaces Bootstrap's Modal component entirely.
	 * showModal()/close() do the heavy lifting (focus trap, Escape-to-close,
	 * ::backdrop); this just connects trigger/close buttons to a target dialog.
	 *
	 * Markup:
	 *   <button data-dialog-target="my-dialog">Open</button>
	 *   <dialog id="my-dialog">
	 *     <button data-dialog-close>Close</button>
	 *     ...
	 *   </dialog>
	 */
	function initDialogs() {
	  document.querySelectorAll('[data-dialog-target]').forEach(trigger => {
	    const dialog = document.getElementById(trigger.getAttribute('data-dialog-target'));
	    if (!(dialog instanceof HTMLDialogElement)) return;
	    trigger.addEventListener('click', () => dialog.showModal());
	    dialog.querySelectorAll('[data-dialog-close]').forEach(closeBtn => {
	      closeBtn.addEventListener('click', () => dialog.close());
	    });

	    // Click on the backdrop (the dialog element itself, outside its content) closes it.
	    dialog.addEventListener('click', event => {
	      if (event.target === dialog) dialog.close();
	    });
	  });
	}

	/**
	 * Lenis smooth scroll (loaded globally — see inc/enqueue.php). Driven by
	 * GSAP's own ticker rather than a separate requestAnimationFrame loop, and
	 * synced to ScrollTrigger via lenis.on('scroll', ScrollTrigger.update) —
	 * without this, ScrollTrigger's trigger points drift out of sync with
	 * Lenis's smoothed scroll position. Falls back to a plain raf loop if GSAP
	 * isn't available. No-ops entirely if Lenis failed to load or the user
	 * prefers reduced motion.
	 */
	function initSmoothScroll() {
	  if (typeof window.Lenis === 'undefined' || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
	    return;
	  }
	  const lenis = new window.Lenis();
	  window.lenis = lenis;
	  if (window.gsap && window.ScrollTrigger) {
	    lenis.on('scroll', window.ScrollTrigger.update);
	    window.gsap.ticker.add(time => {
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

	/**
	 * Scroll-linked entrance animations using GSAP ScrollTrigger (loaded
	 * globally — see inc/enqueue.php). No-ops entirely if GSAP failed to load
	 * or the user prefers reduced motion, leaving everything in its normal,
	 * already-visible CSS state.
	 */
	function initScrollAnimate() {
	  if (!window.gsap || !window.ScrollTrigger || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
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
	  document.querySelectorAll(gridSelector).forEach(grid => {
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
	        once: true
	      }
	    });
	  });
	}

	/**
	 * CB Split Feature List — left intro column fades up, right-hand points
	 * stagger fade in from the right, both when ~25% into the viewport.
	 */
	function initSplitFeatureList() {
	  document.querySelectorAll('.cb-split-feature-list').forEach(section => {
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
	          once: true
	        }
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
	          once: true
	        }
	      });
	    }
	  });
	}

	/**
	 * CB Text Image — text column fades in from the left, image column fades
	 * in from the right, both when ~25% into the viewport.
	 */
	function initTextImage() {
	  document.querySelectorAll('.cb-text-image__grid').forEach(grid => {
	    const text = grid.querySelector('.cb-text-image__text');
	    const image = grid.querySelector('.cb-text-image__image');
	    const scrollTrigger = {
	      trigger: grid,
	      start: 'top 75%',
	      once: true
	    };
	    if (text) {
	      window.gsap.from(text, {
	        opacity: 0,
	        x: -32,
	        duration: 0.6,
	        ease: 'power2.out',
	        scrollTrigger
	      });
	    }
	    if (image) {
	      window.gsap.from(image, {
	        opacity: 0,
	        x: 32,
	        duration: 0.6,
	        ease: 'power2.out',
	        scrollTrigger
	      });
	    }
	  });
	}

	/**
	 * CB CTA — the bordered content box fades up when ~25% into the viewport.
	 */
	function initCta() {
	  document.querySelectorAll('.cb-cta .row').forEach(box => {
	    window.gsap.from(box, {
	      opacity: 0,
	      y: 32,
	      duration: 0.6,
	      ease: 'power2.out',
	      scrollTrigger: {
	        trigger: box,
	        start: 'top 75%',
	        once: true
	      }
	    });
	  });
	}

	document.addEventListener('DOMContentLoaded', () => {
	  initNavToggle();
	  initNavDropdowns();
	  initNavScroll();
	  initDialogs();
	  initSmoothScroll();
	  initScrollAnimate();
	});

})();
//# sourceMappingURL=theme.js.map
