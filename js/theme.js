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
	  staggerFadeUpGrid('.cb-accreditations__cards', '.cb-accreditations__card');
	  initSplitFeatureList();
	  initTextImage();
	  initTextUsps();
	  initCta();
	  initLogoGrid();
	  initAwards();
	  initHeroParallax();
	  initHeroContent();
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
	    window.gsap.fromTo(cards, {
	      opacity: 0,
	      y: 32
	    }, {
	      opacity: 1,
	      y: 0,
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
	      window.gsap.fromTo(intro, {
	        opacity: 0,
	        y: 32
	      }, {
	        opacity: 1,
	        y: 0,
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
	      window.gsap.fromTo(points, {
	        opacity: 0,
	        x: 32
	      }, {
	        opacity: 1,
	        x: 0,
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
	 * CB Text Image — whichever column sits visually on the left fades in from
	 * the left, whichever sits on the right fades in from the right, both when
	 * ~25% into the viewport. Direction follows the "Order" ACF field (via the
	 * .cb-text-image__grid--image-first modifier, see src/blocks/cb-text-image.css)
	 * rather than element type, since text/image swap visual position but not
	 * DOM order (DOM stays text-then-image for reading order/SEO regardless).
	 */
	function initTextImage() {
	  document.querySelectorAll('.cb-text-image__grid').forEach(grid => {
	    const text = grid.querySelector('.cb-text-image__text');
	    const image = grid.querySelector('.cb-text-image__image');
	    const imageFirst = grid.classList.contains('cb-text-image__grid--image-first');
	    const scrollTrigger = {
	      trigger: grid,
	      start: 'top 75%',
	      once: true
	    };
	    if (text) {
	      window.gsap.fromTo(text, {
	        opacity: 0,
	        x: imageFirst ? 32 : -32
	      }, {
	        opacity: 1,
	        x: 0,
	        duration: 0.6,
	        ease: 'power2.out',
	        scrollTrigger
	      });
	    }
	    if (image) {
	      window.gsap.fromTo(image, {
	        opacity: 0,
	        x: imageFirst ? -32 : 32
	      }, {
	        opacity: 1,
	        x: 0,
	        duration: 0.6,
	        ease: 'power2.out',
	        scrollTrigger
	      });
	    }
	  });
	}

	/**
	 * CB Text USPs — left text column slides in from the left, right-hand USP
	 * cards stagger fade in from the right, both when ~25% into the viewport.
	 */
	function initTextUsps() {
	  document.querySelectorAll('.cb-text-usps').forEach(section => {
	    const text = section.querySelector('.col-lg-7');
	    const usps = section.querySelectorAll('.cb-text-usps__usp');
	    const scrollTrigger = {
	      trigger: section,
	      start: 'top 75%',
	      once: true
	    };
	    if (text) {
	      window.gsap.fromTo(text, {
	        opacity: 0,
	        x: -32
	      }, {
	        opacity: 1,
	        x: 0,
	        duration: 0.6,
	        ease: 'power2.out',
	        scrollTrigger
	      });
	    }
	    if (usps.length) {
	      window.gsap.fromTo(usps, {
	        opacity: 0,
	        x: 32
	      }, {
	        opacity: 1,
	        x: 0,
	        duration: 0.6,
	        ease: 'power2.out',
	        stagger: 0.12,
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
	    window.gsap.fromTo(box, {
	      opacity: 0,
	      y: 32
	    }, {
	      opacity: 1,
	      y: 0,
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

	/**
	 * CB Logo Grid — heading, intro, and each logo stagger fade up together,
	 * in that DOM order, when ~25% into the viewport.
	 */
	function initLogoGrid() {
	  document.querySelectorAll('.cb-logo-grid').forEach(section => {
	    const heading = section.querySelector('.cb-logo-grid__heading');
	    const intro = section.querySelector('.cb-logo-grid__intro');
	    const logos = section.querySelectorAll('.cb-logo-grid__logo');
	    const targets = [heading, intro, ...logos].filter(Boolean);
	    if (!targets.length) return;
	    window.gsap.fromTo(targets, {
	      opacity: 0,
	      y: 32
	    }, {
	      opacity: 1,
	      y: 0,
	      duration: 0.6,
	      ease: 'power2.out',
	      stagger: 0.08,
	      scrollTrigger: {
	        trigger: section,
	        start: 'top 75%',
	        once: true
	      }
	    });
	  });
	}

	/**
	 * CB Hero — background photo and SVG mask holes (blocks/cb-hero.php) drift
	 * at different rates as the hero scrolls through the viewport, the mask
	 * holes moving noticeably further than the photo behind them. Both layers
	 * are deliberately oversized/bled past the hero's edges in CSS to leave
	 * room for this before anything crops.
	 *
	 * The logo group lives inside an SVG <mask>, which — like <clipPath> — is
	 * never actually rendered (it's only referenced for its effect), so a plain
	 * CSS transform on it is unreliable: browsers may skip style/animation
	 * updates on elements that aren't rendered. So the parallax is driven by
	 * writing the "transform" attribute directly on every tick instead, which
	 * always affects the mask geometry regardless of render status.
	 */
	function initHeroParallax() {
	  document.querySelectorAll('.cb-hero--has-bg-image').forEach(hero => {
	    const bg = hero.querySelector('.cb-hero__bg');
	    const clip = hero.querySelector('.cb-hero__clip-parallax');
	    const scrollTrigger = {
	      trigger: hero,
	      start: 'top bottom',
	      end: 'bottom top',
	      scrub: true
	    };
	    if (bg) {
	      window.gsap.fromTo(bg, {
	        yPercent: -6
	      }, {
	        yPercent: 6,
	        ease: 'none',
	        scrollTrigger
	      });
	    }
	    if (clip) {
	      const targetX = parseFloat(clip.dataset.targetX);
	      const targetY = parseFloat(clip.dataset.targetY);
	      const halfW = parseFloat(clip.dataset.halfW);
	      const halfH = parseFloat(clip.dataset.halfH);
	      const baseScale = parseFloat(clip.dataset.baseScale);
	      const proxy = {
	        offset: -70,
	        scaleFactor: 0.025
	      };
	      // Load-in reveal: grows from nothing up to whatever proxy.scaleFactor
	      // naturally is at the current scroll position (see below), then gets
	      // out of the way — reveal.progress just stays at 1 once its tween
	      // finishes, leaving the scrub tween as the only thing still driving
	      // the transform. Homepage only — replaying a "grow in from nothing"
	      // every time the mask scrolls into view on inner pages would get
	      // old fast, so everywhere else just starts at its natural size.
	      const isHome = hero.classList.contains('cb-hero--home');
	      const reveal = {
	        progress: isHome ? 0 : 1
	      };
	      const applyTransform = () => {
	        // Anchored on (targetX, targetY) rather than the raw path
	        // origin, so growing/shrinking reads as scaling in place
	        // instead of drifting toward the corner.
	        const s = baseScale * proxy.scaleFactor * reveal.progress;
	        const tx = targetX - s * halfW;
	        const ty = targetY - s * halfH + proxy.offset;
	        clip.setAttribute('transform', `translate(${tx}, ${ty}) scale(${s})`);
	      };
	      window.gsap.fromTo(proxy, {
	        offset: -70,
	        scaleFactor: 0.025
	      }, {
	        offset: 70,
	        scaleFactor: 1.2,
	        ease: 'none',
	        scrollTrigger,
	        onUpdate: applyTransform
	      });
	      if (isHome) {
	        window.gsap.to(reveal, {
	          progress: 1,
	          duration: 1,
	          ease: 'power2.out',
	          onUpdate: applyTransform
	        });
	      }
	    }
	  });
	}

	/**
	 * CB Hero — heading, intro copy, and CTA buttons stagger fade up together,
	 * in that order. The hero sits at the top of the page, so `start: 'top 75%'`
	 * is already satisfied the moment the page loads — this doubles as a load-in
	 * animation without needing a separate DOMContentLoaded-driven mechanism.
	 */
	function initHeroContent() {
	  document.querySelectorAll('.cb-hero').forEach(hero => {
	    const heading = hero.querySelector('h1');
	    const content = hero.querySelector('.cb-hero__content');
	    const cta = hero.querySelector('.cb-hero__cta');
	    const targets = [heading, content, cta].filter(Boolean);
	    if (!targets.length) return;
	    window.gsap.fromTo(targets, {
	      opacity: 0,
	      y: 32
	    }, {
	      opacity: 1,
	      y: 0,
	      duration: 0.6,
	      ease: 'power2.out',
	      stagger: 0.12,
	      scrollTrigger: {
	        trigger: hero,
	        start: 'top 75%',
	        once: true
	      }
	    });
	  });
	}

	/**
	 * CB Awards — cards fade up row-by-row as they actually scroll into view,
	 * not one long stagger across the whole grid. ScrollTrigger.batch groups
	 * cards that cross the trigger point around the same time (i.e. the same
	 * visual row, whatever the current column count) and animates each batch
	 * together — so scrolling fast straight to the bottom doesn't leave the
	 * last row waiting on a stagger chain that started back when the first
	 * row appeared.
	 *
	 * Cards are hidden with gsap.set() up front rather than relying on
	 * onEnter's gsap.from() to establish the hidden state — onEnter only runs
	 * once a batch actually crosses the trigger, so until then the cards would
	 * just sit there fully visible (a flash of visible content, not "no
	 * animation yet"). Pre-hiding matches how every other scroll-in animation
	 * in this file behaves: hidden from first paint, revealed on scroll.
	 */
	function initAwards() {
	  document.querySelectorAll('.cb-awards__cards').forEach(grid => {
	    const cards = grid.querySelectorAll('.cb-awards__card');
	    if (!cards.length) return;
	    window.gsap.set(cards, {
	      opacity: 0,
	      y: 32
	    });
	    window.ScrollTrigger.batch(cards, {
	      start: 'top 85%',
	      once: true,
	      onEnter: batch => window.gsap.to(batch, {
	        opacity: 1,
	        y: 0,
	        duration: 0.6,
	        ease: 'power2.out',
	        stagger: 0.08
	      })
	    });
	  });
	}

	// .cb-tabbed-content__display is server-rendered with the first item's
	// content already inside it (see blocks/cb-tabbed-content.php), so this is
	// pure progressive enhancement — copies whichever item's .panel content
	// into the shared display pane whenever a different one is opened. Below
	// "lg" .display is hidden entirely by CSS, so this listener is harmless
	// there — the native <details> accordion doesn't need it.
	function initTabbedContent() {
	  document.querySelectorAll('.cb-tabbed-content__items').forEach(items => {
	    const display = items.querySelector('.cb-tabbed-content__display');
	    if (!display) {
	      return;
	    }
	    items.querySelectorAll('.cb-tabbed-content__item').forEach(item => {
	      item.addEventListener('toggle', () => {
	        if (!item.open) {
	          return;
	        }
	        const panel = item.querySelector('.cb-tabbed-content__panel');
	        display.innerHTML = panel ? panel.innerHTML : '';
	      });
	    });
	  });
	}

	/**
	 * CB Quote Slider — autoplay-only crossfade between repeater rows. No nav,
	 * no pagination, no drag/swipe, so a full carousel library (Swiper etc.)
	 * isn't justified here — this is the entire behaviour needed. Non-active
	 * slides are already hidden via CSS (opacity: 0 on .cb-quote-slider__slide,
	 * see src/blocks/cb-quote-slider.css) — this just cycles which one carries
	 * .is-active, on a timer.
	 */
	function initQuoteSliders() {
	  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
	    return;
	  }
	  document.querySelectorAll('.cb-quote-slider__track').forEach(track => {
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

	/**
	 * CB Post Index — category/year filtering plus a debounced AJAX search
	 * (ported from cb-pluto2026's blocks/cb-insights-index.php, same logic:
	 * client-side filtering for category/year, server round-trip for text
	 * search since that needs a real WP_Query 's' match). The handler this
	 * calls is registered in inc/post-index.php.
	 */
	function initPostIndex() {
	  document.querySelectorAll('.cb-post-index').forEach(block => {
	    const results = block.querySelector('.cb-post-index__results');
	    const searchInput = block.querySelector('.cb-post-index__search-input');
	    const resetBtn = block.querySelector('.cb-post-index__reset');
	    const catFilters = block.querySelectorAll('.cb-post-index__filter[data-filter]');
	    const yearFilters = block.querySelectorAll('.cb-post-index__filter[data-year]');
	    if (!results) {
	      return;
	    }
	    const getActiveCat = () => {
	      const active = block.querySelector('.cb-post-index__filter--active[data-filter]');
	      return active ? active.dataset.filter : 'all';
	    };
	    const getActiveYear = () => {
	      const active = block.querySelector('.cb-post-index__filter--active[data-year]');
	      return active ? active.dataset.year : 'all';
	    };
	    const resetFilterButtons = () => {
	      catFilters.forEach(btn => btn.classList.remove('cb-post-index__filter--active'));
	      block.querySelector('.cb-post-index__filter[data-filter="all"]').classList.add('cb-post-index__filter--active');
	      yearFilters.forEach(btn => btn.classList.remove('cb-post-index__filter--active'));
	      block.querySelector('.cb-post-index__filter[data-year="all"]').classList.add('cb-post-index__filter--active');
	    };
	    const applyFilters = () => {
	      const cat = getActiveCat();
	      const year = getActiveYear();
	      results.querySelectorAll('.cb-post-index__year-group').forEach(group => {
	        let anyVisible = false;
	        group.querySelectorAll('.cb-post-index__item').forEach(item => {
	          const cats = (item.dataset.category || '').split(' ');
	          const matchesCat = cat === 'all' || cats.includes(cat);
	          const matchesYear = year === 'all' || item.dataset.year === year;
	          const matches = matchesCat && matchesYear;
	          item.style.display = matches ? '' : 'none';
	          if (matches) anyVisible = true;
	        });
	        group.style.display = anyVisible ? '' : 'none';
	      });
	    };
	    catFilters.forEach(btn => {
	      btn.addEventListener('click', () => {
	        catFilters.forEach(f => f.classList.remove('cb-post-index__filter--active'));
	        btn.classList.add('cb-post-index__filter--active');
	        applyFilters();
	      });
	    });
	    yearFilters.forEach(btn => {
	      btn.addEventListener('click', () => {
	        yearFilters.forEach(f => f.classList.remove('cb-post-index__filter--active'));
	        btn.classList.add('cb-post-index__filter--active');
	        applyFilters();
	      });
	    });

	    // Category archives redirect here with ?filter={slug} (see
	    // cb_redirect_category_archives() in inc/post-index.php) — land
	    // with that category already selected instead of on everything.
	    const requestedFilter = new URLSearchParams(window.location.search).get('filter');
	    if (requestedFilter) {
	      const matchingFilter = block.querySelector(`.cb-post-index__filter[data-filter="${CSS.escape(requestedFilter)}"]`);
	      if (matchingFilter) {
	        matchingFilter.click();
	      }
	    }
	    if (!searchInput) {
	      return;
	    }
	    let debounceTimer;
	    const runSearch = term => {
	      const data = new FormData();
	      data.append('action', 'cb_post_index_search');
	      data.append('nonce', results.dataset.nonce);
	      data.append('search_term', term);
	      data.append('category', getActiveCat());
	      fetch(results.dataset.ajaxUrl, {
	        method: 'POST',
	        body: data
	      }).then(response => response.json()).then(response => {
	        if (response.success) {
	          results.innerHTML = response.data.html;
	          resetFilterButtons();
	          applyFilters();
	        }
	      });
	    };
	    searchInput.addEventListener('input', () => {
	      clearTimeout(debounceTimer);
	      debounceTimer = setTimeout(() => runSearch(searchInput.value.trim()), 300);
	    });
	    if (resetBtn) {
	      resetBtn.addEventListener('click', () => {
	        searchInput.value = '';
	        resetFilterButtons();
	        runSearch('');
	      });
	    }
	  });
	}

	document.addEventListener('DOMContentLoaded', () => {
	  initNavToggle();
	  initNavDropdowns();
	  initNavScroll();
	  initDialogs();
	  initSmoothScroll();
	  initScrollAnimate();
	  initTabbedContent();
	  initQuoteSliders();
	  initPostIndex();
	});

})();
//# sourceMappingURL=theme.js.map
