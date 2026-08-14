<?php
/**
 * Contact Form 7 integration — referrer and campaign (UTM) capture.
 *
 * Answers two questions about a submission:
 *  - which page were they on before they hit the form (e.g. reading Cloud
 *    Telephony, clicked Contact, landed on the form page);
 *  - which campaign brought them to the site, for PPC landing pages.
 *
 * Plain CF7 has no [hidden] form tag, and no CF7 add-ons are installed, so the
 * fields are injected into every form server-side here and filled client-side
 * by src/js/journey.js. Doing it that way round means the inputs always exist
 * in the posted data (empty at worst) rather than depending on JS to create
 * them.
 *
 * Deliberately NOT read from $_SERVER['HTTP_REFERER']: CF7 submits by AJAX
 * from the page hosting the form, so at submit time the referer is that same
 * page, never the one they came from.
 *
 * The page the form itself sits on doesn't need any of this — CF7's built-in
 * [_post_title] / [_post_url] already cover it.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * The tracking fields added to every CF7 form, keyed by the special mail tag
 * that exposes them.
 *
 * 'field' is the input name, and is a deliberate coupling with the selectors in
 * src/js/journey.js — keep the two in sync.
 *
 * 'label' is only used by the bundled [_cb_tracking] summary below.
 *
 * 'fallback' is what the mail tag renders when nothing was captured. The UTM
 * tags report "unset" so a campaign-less submission is unambiguous in the
 * email, rather than looking like a broken tag; the referrer tags stay empty,
 * since a direct arrival genuinely has no previous page.
 *
 * @return array<string, array{field: string, fallback: string, label: string}>
 */
function cb_global42026_cf7_tracking_fields() {
	return array(
		'cb_referrer_url'   => array(
			'field'    => 'cb-referrer-url',
			'fallback' => '',
			'label'    => 'Referrer URL',
		),
		'cb_referrer_title' => array(
			'field'    => 'cb-referrer-title',
			'fallback' => '',
			'label'    => 'Referring page',
		),
		'cb_utm_source'     => array(
			'field'    => 'cb-utm-source',
			'fallback' => 'unset',
			'label'    => 'Source',
		),
		'cb_utm_medium'     => array(
			'field'    => 'cb-utm-medium',
			'fallback' => 'unset',
			'label'    => 'Medium',
		),
		'cb_utm_campaign'   => array(
			'field'    => 'cb-utm-campaign',
			'fallback' => 'unset',
			'label'    => 'Campaign',
		),
		'cb_utm_term'       => array(
			'field'    => 'cb-utm-term',
			'fallback' => 'unset',
			'label'    => 'Term',
		),
		'cb_utm_content'    => array(
			'field'    => 'cb-utm-content',
			'fallback' => 'unset',
			'label'    => 'Content',
		),
		'cb_utm_id'         => array(
			'field'    => 'cb-utm-id',
			'fallback' => 'unset',
			'label'    => 'Campaign ID',
		),
		// Ad click identifiers rather than UTMs: Google Ads (gclid, plus
		// gbraid/wbraid for iOS and web-to-app journeys), Microsoft Ads
		// (msclkid) and Meta (fbclid). GTM itself adds no query parameters —
		// these are what it and the ad platforms actually pass through.
		'cb_gclid'          => array(
			'field'    => 'cb-gclid',
			'fallback' => 'unset',
			'label'    => 'Google click ID',
		),
		'cb_gbraid'         => array(
			'field'    => 'cb-gbraid',
			'fallback' => 'unset',
			'label'    => 'Google gbraid',
		),
		'cb_wbraid'         => array(
			'field'    => 'cb-wbraid',
			'fallback' => 'unset',
			'label'    => 'Google wbraid',
		),
		'cb_msclkid'        => array(
			'field'    => 'cb-msclkid',
			'fallback' => 'unset',
			'label'    => 'Microsoft click ID',
		),
		'cb_fbclid'         => array(
			'field'    => 'cb-fbclid',
			'fallback' => 'unset',
			'label'    => 'Meta click ID',
		),
	);
}

/**
 * Adds the tracking inputs to every CF7 form as hidden fields, empty. CF7
 * renders these as plain <input type="hidden">, which is why journey.js has to
 * target them by name — this filter can't set data attributes or classes.
 *
 * @param array $fields Existing hidden fields, name => value.
 * @return array
 */
function cb_global42026_cf7_hidden_fields( $fields ) {
	foreach ( cb_global42026_cf7_tracking_fields() as $tracking_field ) {
		$fields[ $tracking_field['field'] ] = '';
	}

	return $fields;
}
add_filter( 'wpcf7_form_hidden_fields', 'cb_global42026_cf7_hidden_fields' );

/**
 * Builds the bundled [_cb_tracking] summary: one block covering the page the
 * form sits on, where they came from, and whatever campaign parameters the
 * session arrived with.
 *
 * Unlike the individual tags, anything not captured is omitted entirely rather
 * than reported as "unset" — the point of the bundle is to show only what's
 * actually relevant to that submission, so a plain organic enquiry doesn't
 * carry ten empty campaign rows.
 *
 * With an HTML mail this emits a complete, self-contained <table> so the tag can
 * simply be dropped at the bottom of a submission email. Place it OUTSIDE any
 * existing table — a nested table between <tr>s would be invalid markup. Cell
 * styling matches the main submission table so the two read as one design.
 *
 * Plain-text mails get "Label: value" lines instead, so the same tag works with
 * either content type.
 *
 * @param bool $html Whether the mail is HTML.
 * @return string
 */
function cb_global42026_cf7_tracking_summary( $html ) {
	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return '';
	}

	$posted = $submission->get_posted_data();
	$rows   = array();

	// The page hosting the form. Same source CF7's own [_post_*] tags use —
	// included here so the bundle is self-contained, which matters on landing
	// pages where the form's own page is the useful attribution.
	$container_id = (int) $submission->get_meta( 'container_post_id' );

	if ( $container_id ) {
		$rows[] = array(
			'label' => 'Form page',
			'text'  => get_the_title( $container_id ),
			'url'   => get_permalink( $container_id ),
		);
	}

	// Referrer title and URL are one logical row, so they're pulled out of the
	// generic loop below and paired up.
	$referrer_title = ! empty( $posted['cb-referrer-title'] ) ? sanitize_text_field( $posted['cb-referrer-title'] ) : '';
	$referrer_url   = ! empty( $posted['cb-referrer-url'] ) ? sanitize_text_field( $posted['cb-referrer-url'] ) : '';

	if ( $referrer_title || $referrer_url ) {
		$rows[] = array(
			'label' => 'Referring page',
			'text'  => $referrer_title,
			'url'   => $referrer_url,
		);
	}

	foreach ( cb_global42026_cf7_tracking_fields() as $tracking_field ) {
		if ( 'cb-referrer-title' === $tracking_field['field'] || 'cb-referrer-url' === $tracking_field['field'] ) {
			continue;
		}

		if ( empty( $posted[ $tracking_field['field'] ] ) ) {
			continue;
		}

		$rows[] = array(
			'label' => $tracking_field['label'],
			'text'  => sanitize_text_field( $posted[ $tracking_field['field'] ] ),
			'url'   => '',
		);
	}

	if ( ! $rows ) {
		return '';
	}

	if ( ! $html ) {
		$lines = array();

		foreach ( $rows as $row ) {
			$value   = $row['text'];
			$value  .= $row['url'] ? ( $value ? ' (' . $row['url'] . ')' : $row['url'] ) : '';
			$lines[] = $row['label'] . ': ' . $value;
		}

		return implode( "\n", $lines );
	}

	$label_style = 'padding: 2px 14px 2px 0; font-weight: bold; color: #072a41; white-space: nowrap; vertical-align: top;';
	$value_style = 'padding: 2px 0; vertical-align: top;';
	$link_style  = 'color: #467cc6; font-size: 13px;';

	// margin-top separates it from whatever it's appended after, without
	// relying on a border (Outlook is unreliable with borders on tables).
	$output = '<table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; margin-top: 20px;">';

	foreach ( $rows as $row ) {
		$value = esc_html( $row['text'] );

		if ( $row['url'] ) {
			$link = '<a href="' . esc_url( $row['url'] ) . '" style="' . esc_attr( $link_style ) . '">' . esc_html( $row['url'] ) . '</a>';
			// A row with both gets the URL on its own line beneath the title.
			$value = $value ? $value . '<br>' . $link : $link;
		}

		$output .= '<tr>';
		$output .= '<td style="' . esc_attr( $label_style ) . '">' . esc_html( $row['label'] ) . '</td>';
		$output .= '<td style="' . esc_attr( $value_style ) . '">' . $value . '</td>';
		$output .= '</tr>';
	}

	$output .= '</table>';

	return $output;
}

/**
 * Exposes the captured values to the Mail tab as [_cb_referrer_url],
 * [_cb_utm_source] and so on, plus the bundled [_cb_tracking] summary.
 *
 * They need to be special mail tags rather than ordinary ones because they
 * aren't CF7 form tags — nothing in the form editor declares them, so
 * [cb-utm-source] alone would render literally.
 *
 * $name arrives without a leading underscore in some CF7 versions and with one
 * in others, so it's normalised before matching rather than assuming either.
 *
 * @param string $output   Value resolved so far.
 * @param string $name     Mail tag name.
 * @param bool   $html     Whether the mail is HTML — passed through to the bundled summary.
 * @param object $mail_tag Mail tag object — unused.
 * @return string
 */
function cb_global42026_cf7_special_mail_tags( $output, $name, $html, $mail_tag ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- $mail_tag is required by the filter signature.
	$normalised = ltrim( $name, '_' );

	if ( 'cb_tracking' === $normalised ) {
		return cb_global42026_cf7_tracking_summary( $html );
	}

	$map = cb_global42026_cf7_tracking_fields();

	if ( ! isset( $map[ $normalised ] ) ) {
		return $output;
	}

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return $output;
	}

	$posted = $submission->get_posted_data();
	$key    = $map[ $normalised ]['field'];

	if ( empty( $posted[ $key ] ) ) {
		return $map[ $normalised ]['fallback'];
	}

	return sanitize_text_field( $posted[ $key ] );
}
add_filter( 'wpcf7_special_mail_tags', 'cb_global42026_cf7_special_mail_tags', 10, 4 );
