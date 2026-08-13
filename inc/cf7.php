<?php
/**
 * Contact Form 7 integration — referrer capture.
 *
 * Answers "which page were they on before they hit the form?" (e.g. they were
 * reading Cloud Telephony, clicked Contact, and landed on the form page).
 *
 * Plain CF7 has no [hidden] form tag, and no CF7 add-ons are installed, so the
 * two fields are injected into every form server-side here and filled
 * client-side by src/js/journey.js. Doing it that way round means the inputs
 * always exist in the posted data (empty at worst) rather than depending on JS
 * to create them.
 *
 * Deliberately NOT read from $_SERVER['HTTP_REFERER']: CF7 submits by AJAX
 * from the page hosting the form, so at submit time the referer is that same
 * page, never the one they came from.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field names, shared by the two functions below and hardcoded to match the
 * selectors in src/js/journey.js — keep the three in sync.
 */
const CB_CF7_REFERRER_FIELDS = array(
	'cb-referrer-url',
	'cb-referrer-title',
);

/**
 * Adds the referrer inputs to every CF7 form as hidden fields, empty. CF7
 * renders these as plain <input type="hidden">, which is why journey.js has to
 * target them by name — this filter can't set data attributes or classes.
 *
 * @param array $fields Existing hidden fields, name => value.
 * @return array
 */
function cb_global42026_cf7_hidden_fields( $fields ) {
	foreach ( CB_CF7_REFERRER_FIELDS as $name ) {
		$fields[ $name ] = '';
	}

	return $fields;
}
add_filter( 'wpcf7_form_hidden_fields', 'cb_global42026_cf7_hidden_fields' );

/**
 * Exposes the captured values to the Mail tab as [_cb_referrer_url] and
 * [_cb_referrer_title].
 *
 * They need to be special mail tags rather than ordinary ones because they
 * aren't CF7 form tags — nothing in the form editor declares them, so
 * [cb-referrer-url] alone would render literally.
 *
 * $name arrives without a leading underscore in some CF7 versions and with one
 * in others, so it's normalised before matching rather than assuming either.
 *
 * @param string $output   Value resolved so far.
 * @param string $name     Mail tag name.
 * @param bool   $html     Whether the mail is HTML — unused, values are plain text.
 * @param object $mail_tag Mail tag object — unused.
 * @return string
 */
function cb_global42026_cf7_special_mail_tags( $output, $name, $html, $mail_tag ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	$normalised = ltrim( $name, '_' );

	$map = array(
		'cb_referrer_url'   => 'cb-referrer-url',
		'cb_referrer_title' => 'cb-referrer-title',
	);

	if ( ! isset( $map[ $normalised ] ) ) {
		return $output;
	}

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return $output;
	}

	$posted = $submission->get_posted_data();
	$key    = $map[ $normalised ];

	if ( empty( $posted[ $key ] ) ) {
		return $output;
	}

	return sanitize_text_field( $posted[ $key ] );
}
add_filter( 'wpcf7_special_mail_tags', 'cb_global42026_cf7_special_mail_tags', 10, 4 );
