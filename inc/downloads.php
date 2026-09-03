<?php
/**
 * Legacy WP Download Manager (`wpdmpro`) migration — 39 non-policy
 * documents now live as `download` CPT posts (see inc/posttypes.php for
 * registration, acf-json/group_cb_downloads.json for the file field).
 *
 * Each item's legacy numeric ID still needs to keep working as a direct
 * file-stream link (https://.../?wpdmdl={id}), because external sites and
 * embeds link straight to that URL expecting an instant download, not a
 * click-through landing page — this mirrors the original plugin's own
 * behaviour exactly.
 *
 * The 4 policy documents (1972, 2010, 2021, 24566) are NOT handled here —
 * they already have their own ?wpdmdl= redirect in inc/policies.php
 * (cb_global42026_legacy_policy_redirect(), reading its own
 * cb_legacy_policy_downloads() map). Both this handler and that one hook
 * template_redirect and both read $_GET['wpdmdl'], but they act on
 * disjoint ID sets and each is a no-op if the ID isn't in its own map, so
 * they coexist safely regardless of hook order.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

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
 * 302, not 301: the destination changes whenever someone replaces the
 * uploaded file, and a 301 would get a stale target cached indefinitely by
 * browsers/proxies. (Contrast with cb_global42026_legacy_policy_redirect()'s
 * 301 — that one points at a URL, /policies/{slug}/, that itself never
 * changes; this one points straight at the file, which does.)
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

	wp_redirect( $download_file['url'], 302 ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- matches cb_global42026_policy_redirect()'s own reasoning; a future item could point at an externally hosted file.
	exit;
}
add_action( 'template_redirect', 'cb_global42026_legacy_download_redirect' );
