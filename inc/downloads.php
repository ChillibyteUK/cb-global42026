<?php
/**
 * `download` CPT redirects — 39 legacy WP Download Manager documents now
 * live as `download` CPT posts (see inc/posttypes.php for registration,
 * acf-json/group_cb_downloads.json for the file field). Both a post's own
 * permalink (/download/{slug}/) and the old numeric query link
 * (?wpdmdl={id}) redirect straight to the file — an instant download, not
 * a click-through landing page, matching the original plugin's behaviour
 * exactly. single-download.php only renders when there's no file to
 * redirect to.
 *
 * The 4 policy documents (1972, 2010, 2021, 24566) are NOT handled here —
 * they already have their own ?wpdmdl= redirect in inc/policies.php
 * (cb_global42026_legacy_policy_redirect(), reading its own
 * cb_legacy_policy_downloads() map). Both that handler and
 * cb_global42026_legacy_download_redirect() below hook template_redirect
 * and both read $_GET['wpdmdl'], but they act on disjoint ID sets and each
 * is a no-op if the ID isn't in its own map, so they coexist safely
 * regardless of hook order.
 *
 * The `download` post type itself is kept out of search results entirely:
 * both redirect handlers below send X-Robots-Tag: noindex ahead of their
 * 302 (see the comment on each function for why it stays 302, not 301),
 * and cb_global42026_download_yoast_robots() / _core_robots() below
 * noindex, and exclude from the sitemap, the rare case where
 * single-download.php actually renders (a `download` post published
 * before its file was uploaded).
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * noindex via Yoast — see cb_global42026_landing_page_yoast_robots() in
 * inc/landing-pages.php for why this (rather than relying on the redirect
 * alone) is needed: it's belt-and-braces for the one case where a
 * `download` post actually renders instead of redirecting —
 * single-download.php's "no file uploaded yet" fallback.
 *
 * @param array $robots Robots directives Yoast is about to output.
 * @return array
 */
function cb_global42026_download_yoast_robots( $robots ) {
	if ( is_singular( 'download' ) ) {
		$robots['index'] = 'noindex';
	}

	return $robots;
}
add_filter( 'wpseo_robots_array', 'cb_global42026_download_yoast_robots' );

/**
 * Same as above through core's own robots API, so the noindex survives
 * Yoast being deactivated or swapped out.
 *
 * @param array $robots Robots directives core is about to output.
 * @return array
 */
function cb_global42026_download_core_robots( $robots ) {
	if ( is_singular( 'download' ) ) {
		$robots['noindex'] = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'cb_global42026_download_core_robots' );

/**
 * Keeps `download` posts out of Yoast's XML sitemap — a noindexed post type
 * has no business being listed there (same reasoning as the landing_page
 * exclusion in inc/landing-pages.php).
 *
 * @param bool   $excluded  Whether the post type is already excluded.
 * @param string $post_type Post type being considered.
 * @return bool
 */
function cb_global42026_download_exclude_yoast_sitemap( $excluded, $post_type ) {
	if ( 'download' === $post_type ) {
		return true;
	}

	return $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'cb_global42026_download_exclude_yoast_sitemap', 10, 2 );

/**
 * Same exclusion for core's built-in sitemaps.
 *
 * @param array $post_types Post type objects keyed by name.
 * @return array
 */
function cb_global42026_download_exclude_core_sitemap( $post_types ) {
	unset( $post_types['download'] );

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'cb_global42026_download_exclude_core_sitemap' );

/**
 * Legacy WP Download Manager ID => `download` CPT post slug.
 *
 * Generated from the WP Download Manager export (id/title/slug/filename for
 * all 43 legacy items), with the 4 policy IDs excluded — see inc/policies.php.
 * Regenerate from that same source data if this list ever needs editing,
 * rather than hand-maintaining it.
 *
 * @return array<int, string>
 */
function cb_legacy_downloads() {
	return array(
		1785  => 'web-billing-instructions-guide',
		1787  => 'sv8100-guide',
		1826  => 'customer-escalation-details',
		1827  => 'cisas-communications-factsheet',
		1828  => 'codes-of-practice',
		1829  => 'global-4-standard-terms-and-conditions',
		1830  => 'global-4-mobile-services-terms-and-conditions',
		1831  => 'global-4-special-offer-terms-and-conditions',
		1832  => 'global-4-line-assurance',
		1833  => 'global-4-standard-safe-guard-fraud',
		1834  => 'global-4-software-assurance',
		1835  => 'global-4-maintenance-service-terms-conditions',
		1836  => 'acceptable-fair-use-policy',
		2001  => 'cookie-policy',
		2030  => 'social-responsibility-policy',
		2050  => 'covid-19-risk-assessment',
		3067  => 'corporate-brochure',
		3554  => 'it-services-brochure',
		3586  => 'recruitment-complaints-policy',
		7468  => 'working-for-global-4',
		7472  => 'telcoswitch-feature-catalogue',
		8337  => 'global-house-map',
		8566  => 'empowering-your-dentistry-practice',
		8744  => 'microsoft-365-services',
		9266  => 'clyde-munro-testimonial',
		9323  => 'slas-and-escalations',
		11324 => 'offer-terms-and-conditions',
		12070 => 'energy-brochure',
		12079 => 'working-for-global-4-2',
		12082 => 'mobile-brochure',
		13815 => '2024-standard-terms-and-conditions',
		14656 => 'direct-debit-mandate',
		15074 => 'microsoft-copilot-checklist',
		15730 => 'small-business-customers',
		21832 => 'from-detection-to-response',
		21891 => 'standard-terms-and-conditions-micro-small-and-not-for-profit',
		21942 => 'logic-1st-terms-and-conditions',
		24564 => 'g4-small-business-rights',
		24574 => 'g4-sla-document',
	);
}

/**
 * 302s the old ?wpdmdl={id} download URLs straight to the file currently
 * attached to the matching `download` post — a direct file stream, not the
 * landing page, matching the original plugin's instant-download behaviour.
 *
 * 302, NOT 301, deliberately: the destination changes whenever the file
 * attached to the post is replaced, and a 301 tells search engines to
 * permanently consolidate on that specific file URL rather than treat this
 * legacy link (or the post's own /download/{slug}/ permalink) as the
 * canonical, stable one — so a reissue can leave a dead file URL indexed
 * until the redirect is re-crawled. 302 keeps re-checking behaviour, which
 * is the reason this indirection exists at all. (Briefly changed to 301 at
 * an SEO stakeholder's request and reverted.)
 *
 * @return void
 */
function cb_global42026_legacy_download_redirect() {
	if ( is_admin() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- reading a public legacy URL parameter, not processing a form.
	$download_id = isset( $_GET['wpdmdl'] ) ? (int) $_GET['wpdmdl'] : 0;

	if ( ! $download_id ) {
		return;
	}

	$downloads = cb_legacy_downloads();

	if ( ! isset( $downloads[ $download_id ] ) ) {
		return;
	}

	$download_post = get_page_by_path( $downloads[ $download_id ], OBJECT, 'download' );

	if ( ! $download_post ) {
		return;
	}

	$download_file = get_field( 'file', $download_post->ID );

	if ( empty( $download_file['url'] ) ) {
		return;
	}

	header( 'X-Robots-Tag: noindex' );
	wp_redirect( $download_file['url'], 302 ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- matches cb_global42026_policy_redirect()'s own reasoning; a future item could point at an externally hosted file.
	exit;
}
add_action( 'template_redirect', 'cb_global42026_legacy_download_redirect' );

/**
 * Redirects a `download` post's own permalink (/download/{slug}/) straight
 * to its current file — an instant download rather than a click-through
 * landing page, matching the legacy plugin's behaviour for both the pretty
 * URL and the old numeric ?wpdmdl= links.
 *
 * single-download.php only ever renders when there's no file to redirect
 * to (a `download` post published before its file was uploaded), showing a
 * "not available" message instead of redirecting to nothing.
 *
 * 302 for the same reason as cb_global42026_legacy_download_redirect() above.
 *
 * @return void
 */
function cb_global42026_download_redirect() {
	if ( ! is_singular( 'download' ) ) {
		return;
	}

	$download_file = get_field( 'file', get_the_ID() );

	if ( empty( $download_file['url'] ) ) {
		return;
	}

	header( 'X-Robots-Tag: noindex' );
	wp_redirect( $download_file['url'], 302 ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- matches cb_global42026_legacy_download_redirect()'s own reasoning; file may not be same-host.
	exit;
}
add_action( 'template_redirect', 'cb_global42026_download_redirect' );
