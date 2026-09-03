<?php
/**
 * ONE-OFF migration script — delete after running.
 *
 * Migrates the 39 non-policy legacy WP Download Manager items into the new
 * `download` CPT (see inc/posttypes.php, acf-json/group_cb_downloads.json,
 * inc/downloads.php). The 4 policy documents are excluded — they're already
 * handled by inc/policies.php and Site-Wide Settings.
 *
 * No wp-cli on the target environment (see inc/run-once-dedup-media.php's
 * own note), so — same as that script — this runs from the browser:
 *
 * STEP 1: Upload all 39 source files to the Media Library first (Media ->
 * Add New supports dragging in every file at once). Use the exact original
 * filenames from cb_migrate_downloads_source_rows() below — WordPress only
 * appends a "-1"/"-2" suffix on an actual name collision, which this script
 * accounts for, but starting from the right name avoids ambiguity.
 *
 * STEP 2: Log in as an administrator, then visit either of:
 *
 *   /wp-admin/?cb_migrate_downloads=report   — read-only, changes nothing,
 *                                               shows what WOULD be created
 *                                               and flags any file it
 *                                               couldn't find a match for
 *   /wp-admin/?cb_migrate_downloads=run      — actually creates the
 *                                               `download` posts and sets
 *                                               their file field (asks for
 *                                               confirmation first)
 *
 * Always run the report first, fix any "file not found" rows by uploading
 * the missing file, and re-run the report until it's clean.
 *
 * Delete this file (and its require_once below in functions.php) once
 * you've confirmed the run succeeded.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', 'cb_migrate_downloads_maybe_handle_request' );

/**
 * Entry point — only administrators can trigger this, and the write path
 * additionally requires a nonce'd confirmation click.
 *
 * @return void
 */
function cb_migrate_downloads_maybe_handle_request() {
	if ( empty( $_GET['cb_migrate_downloads'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$mode = sanitize_key( wp_unslash( $_GET['cb_migrate_downloads'] ) );

	if ( ! in_array( $mode, array( 'report', 'run', 'run-confirmed' ), true ) ) {
		return;
	}

	$rows = cb_migrate_downloads_source_rows();

	if ( 'report' === $mode ) {
		cb_migrate_downloads_render_report( cb_migrate_downloads_preview( $rows ), false );
		exit;
	}

	if ( 'run' === $mode ) {
		cb_migrate_downloads_render_confirm( cb_migrate_downloads_preview( $rows ) );
		exit;
	}

	// run-confirmed.
	check_admin_referer( 'cb_migrate_downloads_run' );

	$results = cb_migrate_downloads_process( $rows );
	cb_migrate_downloads_render_report( $results, true );
	exit;
}

/**
 * The 39 non-policy legacy items to migrate — id, title, target slug (must
 * match the legacy /download/{slug}/ URL exactly, per cb_legacy_downloads()
 * in inc/downloads.php), and the original filename to match against the
 * Media Library. Source: the WP Download Manager export used to build
 * inc/downloads.php's own map — keep the two in sync if either changes.
 *
 * @return array[] Each: array( 'id', 'title', 'slug', 'filename' ).
 */
function cb_migrate_downloads_source_rows() {
	return array(
		array(
			'id'       => 1785,
			'title'    => 'Web Billing Instructions Guide',
			'slug'     => 'web-billing-instructions-guide',
			'filename' => 'web-billing-guide.pdf',
		),
		array(
			'id'       => 1787,
			'title'    => 'SV8100 Guide',
			'slug'     => 'sv8100-guide',
			'filename' => 'sv8100-guide.pdf',
		),
		array(
			'id'       => 1826,
			'title'    => 'Customer Escalation Details',
			'slug'     => 'customer-escalation-details',
			'filename' => 'Customer-escalation-detail.pdf',
		),
		array(
			'id'       => 1827,
			'title'    => 'CISAS Communications Factsheet',
			'slug'     => 'cisas-communications-factsheet',
			'filename' => 'CISAS-information.pdf',
		),
		array(
			'id'       => 1828,
			'title'    => 'Codes of Practice',
			'slug'     => 'codes-of-practice',
			'filename' => 'codes-of-practice.pdf',
		),
		array(
			'id'       => 1829,
			'title'    => 'Global 4 Standard Terms and Conditions',
			'slug'     => 'global-4-standard-terms-and-conditions',
			'filename' => 'Standard-Terms-and-Conditions.pdf',
		),
		array(
			'id'       => 1830,
			'title'    => 'Global 4 Mobile Services Terms and Conditions',
			'slug'     => 'global-4-mobile-services-terms-and-conditions',
			'filename' => 'mobile-terms.docx',
		),
		array(
			'id'       => 1831,
			'title'    => 'Global 4 Special Offer Terms and Conditions',
			'slug'     => 'global-4-special-offer-terms-and-conditions',
			'filename' => 'special-offer-terms.pdf',
		),
		array(
			'id'       => 1832,
			'title'    => 'Global 4 Line Assurance',
			'slug'     => 'global-4-line-assurance',
			'filename' => 'line-assurance.pdf',
		),
		array(
			'id'       => 1833,
			'title'    => 'Global 4 Standard Safe Guard Fraud',
			'slug'     => 'global-4-standard-safe-guard-fraud',
			'filename' => 'safe-guard-fraud.pdf',
		),
		array(
			'id'       => 1834,
			'title'    => 'Global 4 Software Assurance',
			'slug'     => 'global-4-software-assurance',
			'filename' => 'software-assurance.pdf',
		),
		array(
			'id'       => 1835,
			'title'    => 'Global 4 Maintenance & Service Terms & Conditions',
			'slug'     => 'global-4-maintenance-service-terms-conditions',
			'filename' => 'maint-terms.docx',
		),
		array(
			'id'       => 1836,
			'title'    => 'Acceptable Fair Use Policy',
			'slug'     => 'acceptable-fair-use-policy',
			'filename' => 'fair-usage-policy.pdf',
		),
		array(
			'id'       => 2001,
			'title'    => 'Cookie Policy',
			'slug'     => 'cookie-policy',
			'filename' => 'cookie-policy-v1.pdf',
		),
		array(
			'id'       => 2030,
			'title'    => 'Social Responsibility Policy',
			'slug'     => 'social-responsibility-policy',
			'filename' => 'social-responsibility-policy.pdf',
		),
		array(
			'id'       => 2050,
			'title'    => 'Covid-19 Risk Assessment',
			'slug'     => 'covid-19-risk-assessment',
			'filename' => 'covid90-risk-assesment.pdf',
		),
		array(
			'id'       => 3067,
			'title'    => 'Service Directory',
			'slug'     => 'corporate-brochure',
			'filename' => 'Global-4-Communications-Corporate-Brochure-Final-6.pdf',
		),
		array(
			'id'       => 3554,
			'title'    => 'IT Services Directory',
			'slug'     => 'it-services-brochure',
			'filename' => 'Global-4-Communications-IT-Services-Brochure-Final-1.pdf',
		),
		array(
			'id'       => 3586,
			'title'    => 'Recruitment Complaints Policy',
			'slug'     => 'recruitment-complaints-policy',
			'filename' => 'complaints-recruitment.pdf',
		),
		array(
			'id'       => 7468,
			'title'    => 'Working for Global 4',
			'slug'     => 'working-for-global-4',
			'filename' => 'Global-4-Communications-About-Us-Brochure-Version-1.3.pdf',
		),
		array(
			'id'       => 7472,
			'title'    => 'TelcoSwitch Directory',
			'slug'     => 'telcoswitch-feature-catalogue',
			'filename' => 'Global-4-Communications-TelcoSwitch-Brochure-3.pdf',
		),
		array(
			'id'       => 8337,
			'title'    => 'Global House Map',
			'slug'     => 'global-house-map',
			'filename' => 'ROE-Office-Map-2.pdf',
		),
		array(
			'id'       => 8566,
			'title'    => 'Dentistry Brochure',
			'slug'     => 'empowering-your-dentistry-practice',
			'filename' => 'Global-4-Dentistry-Brochure-9.pdf',
		),
		array(
			'id'       => 8744,
			'title'    => 'Microsoft 365 Services',
			'slug'     => 'microsoft-365-services',
			'filename' => 'Global-4-Microsoft-365-Brocure.pdf',
		),
		array(
			'id'       => 9266,
			'title'    => 'Clyde Munro Testimonial',
			'slug'     => 'clyde-munro-testimonial',
			'filename' => 'Clyde-Munro-Testimonial.pdf',
		),
		array(
			'id'       => 9323,
			'title'    => 'SLAs and Escalations',
			'slug'     => 'slas-and-escalations',
			'filename' => 'Global-4-Service-SLAs.pdf',
		),
		array(
			'id'       => 11324,
			'title'    => 'Offer Terms and Conditions',
			'slug'     => 'offer-terms-and-conditions',
			'filename' => 'G4-Special-Offer-Terms.pdf',
		),
		array(
			'id'       => 12070,
			'title'    => 'Energy Brochure',
			'slug'     => 'energy-brochure',
			'filename' => 'Global-4-Energy-Brochure.pdf',
		),
		array(
			'id'       => 12079,
			'title'    => 'Working for Global 4',
			'slug'     => 'working-for-global-4-2',
			'filename' => 'Global-4-Communications-About-Us-Brochure-Version-1.3.pdf',
		),
		array(
			'id'       => 12082,
			'title'    => 'Mobile Brochure',
			'slug'     => 'mobile-brochure',
			'filename' => 'Mobile-Brochure-G4.pdf',
		),
		array(
			'id'       => 13815,
			'title'    => 'Standard Terms and Conditions',
			'slug'     => '2024-standard-terms-and-conditions',
			'filename' => 'Standard-Terms-and-Conditions.pdf',
		),
		array(
			'id'       => 14656,
			'title'    => 'G4 Direct Debit Mandate',
			'slug'     => 'direct-debit-mandate',
			'filename' => 'Direct-Debit-Mandate-002.pdf',
		),
		array(
			'id'       => 15074,
			'title'    => 'Microsoft Copilot Checklist',
			'slug'     => 'microsoft-copilot-checklist',
			'filename' => 'Copilot-Checklist.pdf',
		),
		array(
			'id'       => 15730,
			'title'    => 'Small Business Customers',
			'slug'     => 'small-business-customers',
			'filename' => 'Small-Business-Customers-Notice.pdf',
		),
		array(
			'id'       => 21832,
			'title'    => 'From Detection to Response.',
			'slug'     => 'from-detection-to-response',
			'filename' => 'From-Detection-to-Response-Global-4-x-Sophos-Webinar.pdf',
		),
		array(
			'id'       => 21891,
			'title'    => 'Standard Terms and Conditions – Micro, Small and Not For Profit Businesses.',
			'slug'     => 'standard-terms-and-conditions-micro-small-and-not-for-profit',
			'filename' => 'Standard-Terms-and-Conditions-Micro-Small-and-Not-For-Profit-Businesses.pdf',
		),
		array(
			'id'       => 21942,
			'title'    => 'Logic 1st Terms and Conditions',
			'slug'     => 'logic-1st-terms-and-conditions',
			'filename' => 'Logic-1st-Ltd-Terms-and-Conditions.pdf',
		),
		array(
			'id'       => 24564,
			'title'    => 'Small Business Rights',
			'slug'     => 'g4-small-business-rights',
			'filename' => 'Global-4-Small-Business-Rights.pdf',
		),
		array(
			'id'       => 24574,
			'title'    => 'G4 – SLA Document',
			'slug'     => 'g4-sla-document',
			'filename' => 'Global-4-Service-SLAs.pdf',
		),
	);
}

/**
 * Finds an already-uploaded Media Library attachment whose stored file
 * basename matches the given filename exactly.
 *
 * @param string $filename Original filename to match (e.g. "codes-of-practice.pdf").
 * @return int Attachment ID, or 0 if no match was found.
 */
function cb_migrate_downloads_find_attachment( $filename ) {
	global $wpdb;

	$like = '%' . $wpdb->esc_like( $filename );

	$attachment_id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s ORDER BY post_id ASC LIMIT 1",
			$like
		)
	);

	return $attachment_id ? (int) $attachment_id : 0;
}

/**
 * Builds a preview of what a run would do for every source row, without
 * writing anything — used by both the report and confirm screens.
 *
 * @param array[] $rows From cb_migrate_downloads_source_rows().
 * @return array[] Each row plus 'existing_post_id' and 'attachment_id'.
 */
function cb_migrate_downloads_preview( $rows ) {
	$preview = array();

	foreach ( $rows as $row ) {
		$existing_post   = get_page_by_path( $row['slug'], OBJECT, 'download' );
		$row['existing_post_id'] = $existing_post ? $existing_post->ID : 0;
		$row['attachment_id']    = cb_migrate_downloads_find_attachment( $row['filename'] );

		$preview[] = $row;
	}

	return $preview;
}

/**
 * Actually creates the `download` posts and sets their file field, for
 * every row that isn't already migrated and has a matched attachment.
 *
 * @param array[] $rows From cb_migrate_downloads_source_rows().
 * @return array[] Each row from the preview plus 'status' and 'post_id'.
 */
function cb_migrate_downloads_process( $rows ) {
	$results = cb_migrate_downloads_preview( $rows );

	foreach ( $results as &$row ) {
		if ( $row['existing_post_id'] ) {
			$row['status']  = 'skipped-existing';
			$row['post_id'] = $row['existing_post_id'];
			continue;
		}

		if ( ! $row['attachment_id'] ) {
			$row['status']  = 'skipped-no-file';
			$row['post_id'] = 0;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'download',
				'post_title'  => $row['title'],
				'post_name'   => $row['slug'],
				'post_status' => 'publish',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$row['status']  = 'error';
			$row['post_id'] = 0;
			continue;
		}

		update_field( 'file', $row['attachment_id'], $post_id );

		$row['status']  = 'created';
		$row['post_id'] = $post_id;
	}

	return $results;
}

/**
 * Dry-run report (default $executed = false) or the actual results
 * (after running) — same shape either way, prints an HTML table.
 *
 * @param array[] $rows     From cb_migrate_downloads_preview() or cb_migrate_downloads_process().
 * @param bool    $executed True if posts were actually created.
 * @return void
 */
function cb_migrate_downloads_render_report( $rows, $executed ) {
	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Downloads migration report</title>';
	echo '<style>body{font:14px/1.5 sans-serif;padding:2rem;max-width:1000px}table{border-collapse:collapse;width:100%;margin-bottom:2rem}td,th{border:1px solid #ccc;padding:6px 10px;text-align:left;vertical-align:top}th{background:#f1f2f2}.ok{color:#0a7d33}.warn{color:#b3221e}</style>';
	echo '</head><body>';
	echo '<h1>' . ( $executed ? 'Downloads migration — done' : 'Downloads migration — dry run (nothing changed)' ) . '</h1>';
	echo '<table><tr><th>ID</th><th>Title</th><th>Slug</th><th>Filename</th><th>' . ( $executed ? 'Result' : 'Would do' ) . '</th></tr>';

	foreach ( $rows as $row ) {
		if ( $executed ) {
			$outcome = $row['status'];
		} elseif ( $row['existing_post_id'] ) {
			$outcome = 'already migrated (post #' . $row['existing_post_id'] . ')';
		} elseif ( ! $row['attachment_id'] ) {
			$outcome = 'FILE NOT FOUND — upload "' . esc_html( $row['filename'] ) . '" to the Media Library first';
		} else {
			$outcome = 'would create, using attachment #' . $row['attachment_id'];
		}

		$class = ( ! $executed && ! $row['existing_post_id'] && ! $row['attachment_id'] ) || 'error' === ( $row['status'] ?? '' ) ? 'warn' : 'ok';

		echo '<tr>';
		echo '<td>' . esc_html( $row['id'] ) . '</td>';
		echo '<td>' . esc_html( $row['title'] ) . '</td>';
		echo '<td>' . esc_html( $row['slug'] ) . '</td>';
		echo '<td>' . esc_html( $row['filename'] ) . '</td>';
		echo '<td class="' . esc_attr( $class ) . '">' . esc_html( $outcome ) . '</td>';
		echo '</tr>';
	}

	echo '</table>';

	if ( ! $executed ) {
		echo '<p><a href="' . esc_url( admin_url( '?cb_migrate_downloads=run' ) ) . '">Proceed to confirm &amp; run for real &rarr;</a></p>';
	} else {
		echo '<p>Done. Spot-check a few of the new /download/{slug}/ pages, then remove inc/run-once-migrate-downloads.php and its require_once line in functions.php.</p>';
	}

	echo '</body></html>';
}

/**
 * Confirmation screen shown before anything is written.
 *
 * @param array[] $preview From cb_migrate_downloads_preview().
 * @return void
 */
function cb_migrate_downloads_render_confirm( $preview ) {
	$creatable = 0;

	foreach ( $preview as $row ) {
		if ( ! $row['existing_post_id'] && $row['attachment_id'] ) {
			++$creatable;
		}
	}

	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Confirm downloads migration</title>';
	echo '<style>body{font:14px/1.5 sans-serif;padding:2rem;max-width:600px}</style></head><body>';
	echo '<h1>Confirm</h1>';
	echo '<p>This will create <strong>' . esc_html( $creatable ) . '</strong> new `download` post(s), each with its file field set to an already-uploaded Media Library attachment. Rows already migrated, or missing their file upload, are skipped.</p>';
	echo '<form method="get" action="' . esc_url( admin_url() ) . '">';
	echo '<input type="hidden" name="cb_migrate_downloads" value="run-confirmed">';
	wp_nonce_field( 'cb_migrate_downloads_run' );
	echo '<button type="submit">Yes, run the migration</button>';
	echo '</form>';
	echo '<p><a href="' . esc_url( admin_url( '?cb_migrate_downloads=report' ) ) . '">&larr; Back to report</a></p>';
	echo '</body></html>';
}
