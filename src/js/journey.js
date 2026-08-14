/**
 * Records the visitor's page-by-page trail and the campaign (UTM) parameters
 * they arrived with in sessionStorage, and uses both to fill the hidden fields
 * CF7 renders on every form (see inc/cf7.php).
 *
 * Why sessionStorage rather than the obvious alternatives:
 *  - Server-side HTTP_REFERER is useless here: CF7 submits by AJAX *from* the
 *    page hosting the form, so at submit time the referer is that same page.
 *  - document.referrer only ever gives a URL, never the previous page's
 *    <title> — the browser doesn't expose it. Recording each page as we go is
 *    the only way to have the title of the page they came from.
 *  - UTM parameters only exist on the URL they landed on. One click through to
 *    another page and they're gone, so they have to be stashed on arrival to
 *    still be available at the point of submitting a form.
 *
 * The full trail (not just the previous page) is kept deliberately, so this can
 * be extended for user-journey analysis later without revisiting how the data
 * is captured. Read it with getJourney().
 *
 * Cleared automatically when the tab closes — it's sessionStorage, not a
 * cookie, so nothing is sent to the server on every request and there's no
 * consent banner implication.
 */

const JOURNEY_KEY = 'cb-journey';
const CAMPAIGN_KEY = 'cb-campaign';

// Enough for a meaningful trail without letting a long browsing session grow
// the entry unbounded. Oldest entries are dropped first.
const MAX_ENTRIES = 20;

// Query parameters captured on arrival. Field names are derived from these
// by swapping underscores for hyphens and prefixing "cb-" (utm_source ->
// cb-utm-source, gclid -> cb-gclid), so adding one here only needs a matching
// entry in cb_global42026_cf7_tracking_fields() in inc/cf7.php.
//
// The second group is ad click identifiers rather than UTMs — Google Ads
// (gclid, and gbraid/wbraid for iOS/web-to-app journeys), Microsoft Ads
// (msclkid) and Meta (fbclid). GTM itself doesn't add query parameters; these
// are what it and the ad platforms actually pass through.
const TRACKED_PARAMS = [
	'utm_source',
	'utm_medium',
	'utm_campaign',
	'utm_term',
	'utm_content',
	'utm_id',
	'gclid',
	'gbraid',
	'wbraid',
	'msclkid',
	'fbclid',
];

/**
 * Reads and JSON-parses a sessionStorage key. Always returns the fallback on a
 * disabled/private-mode/corrupt store rather than throwing.
 *
 * @param {string} key      Storage key.
 * @param {*}      fallback Value to return when nothing usable is stored.
 */
function readStore(key, fallback) {
	try {
		const raw = window.sessionStorage.getItem(key);

		return raw ? JSON.parse(raw) : fallback;
	} catch (e) {
		return fallback;
	}
}

function writeStore(key, value) {
	try {
		window.sessionStorage.setItem(key, JSON.stringify(value));
	} catch (e) {
		// Private mode, quota, or storage disabled — the data simply doesn't
		// persist. Never worth breaking the page over.
	}
}

/**
 * The recorded trail for this tab, oldest first.
 *
 * @returns {Array<{url: string, title: string, at: number}>}
 */
export function getJourney() {
	const journey = readStore(JOURNEY_KEY, []);

	return Array.isArray(journey) ? journey : [];
}

/**
 * The campaign parameters this session arrived with, if any.
 *
 * @returns {Object<string, string>}
 */
export function getCampaign() {
	const utm = readStore(CAMPAIGN_KEY, {});

	return utm && 'object' === typeof utm ? utm : {};
}

/**
 * Fills CF7's hidden inputs. Targeted by name because
 * wpcf7_form_hidden_fields (inc/cf7.php) only lets us set a name and value, not
 * arbitrary attributes — so these names are a deliberate coupling with that
 * file. Anything left empty is reported as "unset" by the mail tag, so there's
 * nothing to write here for a missing value.
 *
 * @param {string} name  Input name.
 * @param {string} value Value to set.
 */
function fillField(name, value) {
	if (!value) {
		return;
	}

	document.querySelectorAll(`input[name="${name}"]`).forEach((input) => {
		input.value = value;
	});
}

/**
 * Campaign params off the current URL if it has any, otherwise whatever was
 * stashed earlier in the session. A fresh set of params replaces the stored
 * ones, so clicking a second ad mid-session re-attributes correctly.
 *
 * @returns {Object<string, string>}
 */
function resolveCampaign() {
	const params = new URLSearchParams(window.location.search);
	const found = {};

	TRACKED_PARAMS.forEach((param) => {
		const value = params.get(param);

		if (value) {
			found[param] = value;
		}
	});

	if (Object.keys(found).length) {
		writeStore(CAMPAIGN_KEY, found);

		return found;
	}

	return getCampaign();
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

	if (previous) {
		fillField('cb-referrer-url', previous.url);
		fillField('cb-referrer-title', previous.title);
	}

	const campaign = resolveCampaign();

	TRACKED_PARAMS.forEach((param) => {
		fillField(`cb-${param.replace(/_/g, '-')}`, campaign[param]);
	});

	const last = journey[journey.length - 1];

	if (!last || last.url !== current.url) {
		journey.push(current);
	}

	writeStore(JOURNEY_KEY, journey.slice(-MAX_ENTRIES));
}
