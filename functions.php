<?php
/**
 * CB Global 4 — theme functions.
 *
 * Standalone theme, no parent theme. See style.css header for the
 * "BS-flavored naming, not Bootstrap" note and browser support baseline.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

define( 'CB_GLOBAL42026_DIR', get_template_directory() );

require_once CB_GLOBAL42026_DIR . '/inc/setup.php';
require_once CB_GLOBAL42026_DIR . '/inc/enqueue.php';
require_once CB_GLOBAL42026_DIR . '/inc/class-cb-global-4-nav-walker.php';
require_once CB_GLOBAL42026_DIR . '/inc/blocks.php';
require_once CB_GLOBAL42026_DIR . '/inc/editor.php';
require_once CB_GLOBAL42026_DIR . '/inc/options.php';
require_once CB_GLOBAL42026_DIR . '/inc/icon-upload.php';
require_once CB_GLOBAL42026_DIR . '/inc/head-tags.php';
require_once CB_GLOBAL42026_DIR . '/inc/block-usage.php';
require_once CB_GLOBAL42026_DIR . '/inc/utilities.php';
require_once CB_GLOBAL42026_DIR . '/inc/helpers.php';
require_once CB_GLOBAL42026_DIR . '/inc/posttypes.php';
require_once CB_GLOBAL42026_DIR . '/inc/landing-pages.php';
require_once CB_GLOBAL42026_DIR . '/inc/taxonomies.php';
require_once CB_GLOBAL42026_DIR . '/inc/post-index.php';
require_once CB_GLOBAL42026_DIR . '/inc/cf7.php';
require_once CB_GLOBAL42026_DIR . '/inc/policies.php';
require_once CB_GLOBAL42026_DIR . '/inc/downloads.php';

// TEMPORARY — one-off media dedup after a WP All Import run. Remove this
// line and delete inc/run-once-dedup-media.php once it's been run on the
// target site and the report confirms success.
require_once CB_GLOBAL42026_DIR . '/inc/run-once-dedup-media.php';

// TEMPORARY — one-off migration of the 39 non-policy legacy WP Download
// Manager items into the `download` CPT. Remove this line and delete
// inc/run-once-migrate-downloads.php once it's been run on the target site
// and the report confirms success.
require_once CB_GLOBAL42026_DIR . '/inc/run-once-migrate-downloads.php';
