/**
 * Records the visitor's page-by-page trail through the site in sessionStorage,
 * and uses it to fill the hidden referrer fields CF7 renders on every form
 * (see inc/cf7.php).
 *
 * Why sessionStorage rather than the obvious alternatives:
 *  - Server-side HTTP_REFERER is useless here: CF7 submits by AJAX *from* the
 *    page hosting the form, so at submit time the referer is that same page.
 *  - document.referrer only ever gives a URL, never the previous page's
 *    <title> — the browser doesn't expose it. Recording each page as we go is
 *    the only way to have the title of the page they came from.
 *
 * The full trail (not just the previous page) is kept deliberately, so this
 * can be extended for user-journey analysis later without revisiting how the
 * data is captured. Read it with getJourney().
 *
 * Cleared automatically when the tab closes — it's sessionStorage, not a
 * cookie, so nothing is sent to the server on every request and there's no
 * consent banner implication.
 */

const STORAGE_KEY = 'cb-journey';

// Enough for a meaningful trail without letting a long browsing session grow
// the entry unbounded. Oldest entries are dropped first.
const MAX_ENTRIES = 20;

/**
 * The recorded trail for this tab, oldest first. Always an array — a
 * disabled/full/private-mode sessionStorage or corrupt JSON yields an empty
 * one rather than throwing.
 *
 * @returns {Array<{url: string, title: string, at: number}>}
 */
export function getJourney() {
	try {
		const raw = window.sessionStorage.getItem(STORAGE_KEY);
		const parsed = raw ? JSON.parse(raw) : [];

		return Array.isArray(parsed) ? parsed : [];
	} catch (e) {
		return [];
	}
}

function writeJourney(journey) {
	try {
		window.sessionStorage.setItem(STORAGE_KEY, JSON.stringify(journey));
	} catch (e) {
		// Private mode, quota, or storage disabled — the trail simply doesn't
		// persist. Never worth breaking the page over.
	}
}

/**
 * Fills CF7's hidden referrer inputs. Targeted by name because
 * wpcf7_form_hidden_fields (inc/cf7.php) only lets us set a name and value,
 * not arbitrary attributes — so these two strings are a deliberate coupling
 * with that file. Left empty when there's nothing to report.
 *
 * @param {{url: string, title: string}|null} previous Page they arrived from.
 */
function fillReferrerFields(previous) {
	if (!previous) {
		return;
	}

	document.querySelectorAll('input[name="cb-referrer-url"]').forEach((input) => {
		input.value = previous.url;
	});

	document.querySelectorAll('input[name="cb-referrer-title"]').forEach((input) => {
		input.value = previous.title;
	});
}

export function initJourney() {
	const journey = getJourney();
	const current = { url: window.location.href, title: document.title, at: Date.now() };

	// Walk back to the last entry that isn't this same URL, so a reload or an
	// in-page hash change doesn't report the current page as its own referrer.
	let previous = null;
	for (let i = journey.length - 1; i >= 0; i--) {
		if (journey[i].url !== current.url) {
			previous = journey[i];
			break;
		}
	}

	// First page of the session (or a fresh tab): no trail to draw on, so fall
	// back to the external referrer. URL only — see the note up top on why
	// there's no title available in this case.
	if (!previous && document.referrer) {
		previous = { url: document.referrer, title: '' };
	}

	fillReferrerFields(previous);

	const last = journey[journey.length - 1];

	if (!last || last.url !== current.url) {
		journey.push(current);
	}

	writeJourney(journey.slice(-MAX_ENTRIES));
}
