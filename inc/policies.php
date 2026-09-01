<?php
/**
 * Policy documents — canonical URLs for the PDFs held in Site-Wide Settings.
 *
 * Each policy gets a permanent public URL of the form /policies/{slug}/ which
 * 302s to whatever file the matching Site-Wide Settings field currently points
 * at. That indirection is the whole point: replacing a PDF changes the uploads
 * URL (new date folder, or a "-1" suffix on the filename), which would silently
 * break every bookmark, email footer and inbound link if the file were linked
 * directly. The canonical URL never changes.
 *
 * A "policies/" prefix rather than bare slugs deliberately — it's very unlikely
 * a page will ever want that path, so there's no collision risk with the
 * /privacy-policy/ style page slugs.
 *
 * Requires a permalink flush (Settings -> Permalinks, just hit Save) after
 * deployment, or the rewrite rule below won't be registered and the URLs 404.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL segment the policy endpoints live under.
 */
const CB_POLICY_BASE = 'policies';

/**
 * Canonical slug => Site-Wide Settings field name.
 *
 * Single source of truth: the rewrite rule, the redirect handler, the footer
 * links and the admin hints below all read this, so adding a policy is one
 * entry here plus its two ACF fields ({name} file + {name}_url fallback).
 *
 * @return array<string, string>
 */
function cb_policies() {
	return array(
		'privacy-policy'        => 'privacy_policy',
		'anti-bribery-policy'   => 'anti_bribery_policy',
		'modern-slavery-policy' => 'modern_slavery_policy',
		'acceptable-use-policy' => 'acceptable_use_policy',
	);
}

/**
 * The canonical public URL for a policy — what to link to, everywhere.
 *
 * @param string $slug Policy slug, as keyed in cb_policies().
 * @return string
 */
function cb_policy_url( $slug ) {
	return home_url( '/' . CB_POLICY_BASE . '/' . $slug . '/' );
}

/**
 * Where a policy's canonical URL should actually send people.
 *
 * The uploaded file wins. Failing that, the field's "{name}_url" sibling is
 * used, which exists for documents hosted elsewhere and for a launch
 * transition — pointing at the old live download URL (e.g. a ?wpdmdl=1972
 * style link) until the PDF has been migrated across.
 *
 * @param string $slug Policy slug, as keyed in cb_policies().
 * @return string Absolute URL, or '' when neither is set.
 */
function cb_policy_target( $slug ) {
	$policies = cb_policies();

	if ( ! isset( $policies[ $slug ] ) ) {
		return '';
	}

	$field = $policies[ $slug ];
	$file  = get_field( $field, 'option' );

	if ( ! empty( $file['url'] ) ) {
		return $file['url'];
	}

	$fallback = get_field( $field . '_url', 'option' );

	return $fallback ? $fallback : '';
}

/**
 * Whether a policy resolves to anything at all. Used by the footer so it
 * doesn't link an endpoint that would only 404.
 *
 * @param string $slug Policy slug, as keyed in cb_policies().
 * @return bool
 */
function cb_policy_has_target( $slug ) {
	return '' !== cb_policy_target( $slug );
}

/**
 * Registers /policies/{slug}/ and its query var.
 *
 * 'top' so it's matched before WordPress's own page/attachment rules get a
 * chance to interpret the path.
 *
 * @return void
 */
function cb_global42026_policy_rewrite() {
	add_rewrite_rule(
		'^' . CB_POLICY_BASE . '/([^/]+)/?$',
		'index.php?cb_policy=$matches[1]',
		'top'
	);
}
add_action( 'init', 'cb_global42026_policy_rewrite' );

/**
 * Makes cb_policy a recognised query var, so get_query_var() below can read it.
 *
 * @param array $vars Registered public query vars.
 * @return array
 */
function cb_global42026_policy_query_var( $vars ) {
	$vars[] = 'cb_policy';

	return $vars;
}
add_filter( 'query_vars', 'cb_global42026_policy_query_var' );

/**
 * Sends /policies/{slug}/ on to the current file, or its fallback URL.
 *
 * 302, NOT 301: the target changes every time a policy is reissued, and a
 * permanent redirect would be cached by browsers and proxies against the old
 * file more or less forever.
 *
 * An unknown slug, or a known one with neither a file nor a fallback URL set,
 * renders a normal 404 rather than redirecting somewhere arbitrary.
 *
 * @return void
 */
function cb_global42026_policy_redirect() {
	$slug = get_query_var( 'cb_policy' );

	if ( ! $slug ) {
		return;
	}

	$target = cb_policy_target( $slug );

	if ( '' === $target ) {
		global $wp_query;

		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();

		return;
	}

	// wp_redirect rather than wp_safe_redirect: the value is set by an admin in
	// Site-Wide Settings, and this leaves the door open to pointing a policy at
	// an externally hosted document without the redirect being silently
	// rejected as an off-site host.
	wp_redirect( $target, 302 ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
	exit;
}
add_action( 'template_redirect', 'cb_global42026_policy_redirect' );

/**
 * Legacy Download Monitor download ID => canonical policy slug.
 *
 * The old live site served these PDFs through a download manager plugin, as
 * query-string URLs on the homepage (e.g.
 * https://www.global4.co.uk/?wpdmdl=1972). That plugin isn't installed here, so
 * without this those URLs would silently serve the homepage — a soft 404 for
 * anything still linking to them: bookmarks, contracts, email footers.
 *
 * Handled in the theme rather than a redirection plugin partly because it's a
 * fixed, known set, and partly because a plugin rule would have to be
 * configured for exact query-string matching — the source path is "/", so a
 * path-only rule would redirect every homepage hit.
 *
 * @return array<int, string>
 */
function cb_legacy_policy_downloads() {
	return array(
		1972  => 'privacy-policy',
		2010  => 'anti-bribery-policy',
		2021  => 'modern-slavery-policy',
		24566 => 'acceptable-use-policy',
	);
}

/**
 * 301s the old ?wpdmdl={id} download URLs to the canonical policy endpoint.
 *
 * 301 here, unlike the 302 above: the old URL scheme is genuinely gone for
 * good, so it's safe (and better for SEO) to let this one be cached. The
 * canonical URL it lands on is what stays flexible.
 *
 * Runs regardless of what the rest of the query looks like, so it catches the
 * parameter on any path rather than assuming the homepage.
 *
 * @return void
 */
function cb_global42026_legacy_policy_redirect() {
	if ( is_admin() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- reading a public legacy URL parameter, not processing a form.
	$download_id = isset( $_GET['wpdmdl'] ) ? (int) $_GET['wpdmdl'] : 0;

	if ( ! $download_id ) {
		return;
	}

	$downloads = cb_legacy_policy_downloads();

	if ( ! isset( $downloads[ $download_id ] ) ) {
		return;
	}

	wp_safe_redirect( cb_policy_url( $downloads[ $download_id ] ), 301 );
	exit;
}
add_action( 'template_redirect', 'cb_global42026_legacy_policy_redirect' );

/**
 * Shows the canonical URL against each upload field in Site-Wide Settings, so
 * whoever swaps a PDF can see (and test) the public URL without needing to
 * know the rewrite rule exists.
 *
 * Appended to the field's own instructions via acf/load_field, keyed off
 * cb_policies() so it can't drift from the actual routing.
 *
 * @param array $field ACF field array.
 * @return array
 */
function cb_global42026_policy_field_instructions( $field ) {
	$slug = array_search( $field['name'], cb_policies(), true );

	if ( ! $slug ) {
		return $field;
	}

	$url = cb_policy_url( $slug );

	$field['instructions'] = trim(
		$field['instructions'] . sprintf(
			'<br><strong>Public URL:</strong> <a href="%1$s" target="_blank" rel="noopener">%2$s</a><br>Always link to that, never the file itself — it stays the same when you upload a replacement.',
			esc_url( $url ),
			esc_html( $url )
		)
	);

	return $field;
}
add_filter( 'acf/load_field/type=file', 'cb_global42026_policy_field_instructions' );
