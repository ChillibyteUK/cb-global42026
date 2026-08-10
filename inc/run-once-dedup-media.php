<?php
/**
 * ONE-OFF maintenance script — delete after running.
 *
 * Cleans up exact-duplicate media library attachments left behind by a WP
 * All Import run (same source image sideloaded twice per post, byte-
 * identical files under slightly different filenames — see the "-1"/"-2"
 * suffixes WordPress's own filename-collision handling added).
 *
 * No wp-cli on the target environment, so this runs from the browser
 * instead: log in as an administrator, then visit either of:
 *
 *   /wp-admin/?cb_dedup_media=report   — read-only, changes nothing, shows
 *                                        what WOULD be merged/deleted
 *   /wp-admin/?cb_dedup_media=run      — actually repoints references and
 *                                        deletes the duplicate attachments
 *                                        (asks for confirmation first)
 *
 * Always run the report first and read it before running for real.
 *
 * Delete this file (and its require_once below in functions.php) once
 * you've confirmed the run succeeded.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', 'cb_dedup_media_maybe_handle_request' );

/**
 * Entry point — only administrators can trigger this, and the destructive
 * path additionally requires a nonce'd confirmation click.
 *
 * @return void
 */
function cb_dedup_media_maybe_handle_request() {
	if ( empty( $_GET['cb_dedup_media'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$mode = sanitize_key( wp_unslash( $_GET['cb_dedup_media'] ) );

	if ( ! in_array( $mode, array( 'report', 'run', 'run-confirmed' ), true ) ) {
		return;
	}

	$groups = cb_dedup_media_find_duplicate_groups();

	if ( 'report' === $mode ) {
		cb_dedup_media_render_report( $groups, false );
		exit;
	}

	if ( 'run' === $mode ) {
		cb_dedup_media_render_confirm( $groups );
		exit;
	}

	// run-confirmed.
	check_admin_referer( 'cb_dedup_media_run' );

	$results = cb_dedup_media_process( $groups );
	cb_dedup_media_render_report( $results, true );
	exit;
}

/**
 * Groups every media library attachment by the md5 hash of its file on
 * disk. Only hashes returned as groups of 2+ are actual duplicates.
 *
 * @return array<string, int[]> Hash => attachment IDs, lowest ID first.
 */
function cb_dedup_media_find_duplicate_groups() {
	$attachment_ids = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	$by_hash = array();

	foreach ( $attachment_ids as $id ) {
		$file = get_attached_file( $id );

		if ( ! $file || ! file_exists( $file ) ) {
			continue;
		}

		$hash = md5_file( $file );

		if ( ! isset( $by_hash[ $hash ] ) ) {
			$by_hash[ $hash ] = array();
		}

		$by_hash[ $hash ][] = $id;
	}

	return array_filter(
		$by_hash,
		function ( $ids ) {
			return count( $ids ) > 1;
		}
	);
}

/**
 * Every place a given attachment ID is referenced — featured image, any
 * other postmeta storing the raw ID (this covers ACF image/gallery fields
 * and repeater sub-fields: whatever the field's return format, ACF always
 * stores the plain attachment ID in postmeta), and inline <img> tags in
 * post_content (matched on the wp-image-{id} class and the file's own
 * basename, since the full URL can vary by size).
 *
 * @param int $attachment_id Attachment to find references to.
 * @return array[] Each: array( 'type' => ..., 'post_id' => ..., 'meta_id' => ... ).
 */
function cb_dedup_media_find_references( $attachment_id ) {
	global $wpdb;

	$refs = array();

	$thumb_post_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %d",
			$attachment_id
		)
	);

	foreach ( $thumb_post_ids as $post_id ) {
		$refs[] = array(
			'type'    => 'featured_image',
			'post_id' => (int) $post_id,
		);
	}

	$meta_rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT meta_id, post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_value = %d AND meta_key != '_thumbnail_id'",
			$attachment_id
		)
	);

	foreach ( $meta_rows as $row ) {
		$refs[] = array(
			'type'     => 'postmeta',
			'post_id'  => (int) $row->post_id,
			'meta_id'  => (int) $row->meta_id,
			'meta_key' => $row->meta_key,
		);
	}

	$file = get_attached_file( $attachment_id );
	$base = $file ? $wpdb->esc_like( basename( $file ) ) : '';

	if ( $base ) {
		$content_rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_content LIKE %s OR post_content LIKE %s",
				'%wp-image-' . $attachment_id . '%',
				'%' . $base . '%'
			)
		);

		foreach ( $content_rows as $row ) {
			$refs[] = array(
				'type'    => 'post_content',
				'post_id' => (int) $row->ID,
			);
		}
	}

	return $refs;
}

/**
 * Repoints every reference found by cb_dedup_media_find_references() from
 * the duplicate attachment to the keeper, then permanently deletes the
 * duplicate (post + all generated file sizes).
 *
 * @param int   $dupe_id   Duplicate attachment to remove.
 * @param int   $keeper_id Attachment being kept in its place.
 * @param array $refs      References, from cb_dedup_media_find_references().
 * @return void
 */
function cb_dedup_media_repoint_and_delete( $dupe_id, $keeper_id, $refs ) {
	$dupe_url   = wp_get_attachment_url( $dupe_id );
	$keeper_url = wp_get_attachment_url( $keeper_id );

	foreach ( $refs as $ref ) {
		if ( 'featured_image' === $ref['type'] ) {
			update_post_meta( $ref['post_id'], '_thumbnail_id', $keeper_id );
			continue;
		}

		if ( 'postmeta' === $ref['type'] ) {
			update_metadata_by_mid( 'post', $ref['meta_id'], $keeper_id );
			continue;
		}

		if ( 'post_content' === $ref['type'] ) {
			$post = get_post( $ref['post_id'] );

			if ( ! $post ) {
				continue;
			}

			$new_content = str_replace(
				array( 'wp-image-' . $dupe_id, $dupe_url ),
				array( 'wp-image-' . $keeper_id, $keeper_url ),
				$post->post_content
			);

			if ( $new_content !== $post->post_content ) {
				wp_update_post(
					array(
						'ID'           => $ref['post_id'],
						'post_content' => $new_content,
					)
				);
			}
		}
	}

	wp_delete_attachment( $dupe_id, true );
}

/**
 * Dry-run report (default $execute = false) or the actual repoint+delete
 * pass (called with the results already produced by cb_dedup_media_process()
 * when $execute is true) — same shape either way, just prints an HTML table
 * of what happened/would happen.
 *
 * @param array<string, int[]>|array $groups_or_results Either raw duplicate groups (dry run) or the array returned by cb_dedup_media_process() (after running).
 * @param bool                       $executed           True if changes were actually made.
 * @return void
 */
function cb_dedup_media_render_report( $groups_or_results, $executed ) {
	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Media dedup report</title>';
	echo '<style>body{font:14px/1.5 sans-serif;padding:2rem;max-width:900px}table{border-collapse:collapse;width:100%;margin-bottom:2rem}td,th{border:1px solid #ccc;padding:6px 10px;text-align:left;vertical-align:top}th{background:#f1f2f2}.keeper{color:#0a7d33}.dupe{color:#b3221e}</style>';
	echo '</head><body>';
	echo '<h1>' . ( $executed ? 'Media dedup — done' : 'Media dedup — dry run (nothing changed)' ) . '</h1>';

	if ( empty( $groups_or_results ) ) {
		echo '<p>No duplicate attachments found.</p></body></html>';
		return;
	}

	echo '<table><tr><th>Keep</th><th>Remove</th><th>References found</th></tr>';

	foreach ( $groups_or_results as $group ) {
		$keeper_id = is_array( $group ) && isset( $group['keeper'] ) ? $group['keeper'] : $group[0];
		$dupe_ids  = is_array( $group ) && isset( $group['dupes'] ) ? $group['dupes'] : array_slice( $group, 1 );

		foreach ( $dupe_ids as $dupe_id ) {
			$refs      = $executed ? array() : cb_dedup_media_find_references( $dupe_id );
			$ref_count = $executed ? '—' : count( $refs );

			echo '<tr>';
			echo '<td class="keeper">#' . esc_html( $keeper_id ) . '<br>' . esc_html( basename( get_attached_file( $keeper_id ) ) ) . '</td>';
			echo '<td class="dupe">#' . esc_html( $dupe_id ) . '<br>' . esc_html( basename( get_attached_file( $dupe_id ) ) ? get_attached_file( $dupe_id ) : '(deleted)' ) . '</td>';
			echo '<td>' . esc_html( $ref_count ) . '</td>';
			echo '</tr>';
		}
	}

	echo '</table>';

	if ( ! $executed ) {
		echo '<p><a href="' . esc_url( admin_url( '?cb_dedup_media=run' ) ) . '">Proceed to confirm &amp; run for real &rarr;</a></p>';
	} else {
		echo '<p>Done. Remove inc/run-once-dedup-media.php and its require_once line in functions.php now.</p>';
	}

	echo '</body></html>';
}

/**
 * Confirmation screen shown before anything destructive happens.
 *
 * @param array<string, int[]> $groups Duplicate groups.
 * @return void
 */
function cb_dedup_media_render_confirm( $groups ) {
	$dupe_count = 0;

	foreach ( $groups as $ids ) {
		$dupe_count += count( $ids ) - 1;
	}

	echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Confirm media dedup</title>';
	echo '<style>body{font:14px/1.5 sans-serif;padding:2rem;max-width:600px}</style></head><body>';
	echo '<h1>Confirm</h1>';
	echo '<p>This will permanently delete <strong>' . esc_html( $dupe_count ) . '</strong> duplicate attachment(s) and repoint any posts referencing them to the kept copy. This cannot be undone — make sure you have a backup.</p>';
	echo '<form method="get" action="' . esc_url( admin_url() ) . '">';
	echo '<input type="hidden" name="cb_dedup_media" value="run-confirmed">';
	wp_nonce_field( 'cb_dedup_media_run' );
	echo '<button type="submit">Yes, delete the duplicates</button>';
	echo '</form>';
	echo '<p><a href="' . esc_url( admin_url( '?cb_dedup_media=report' ) ) . '">&larr; Back to report</a></p>';
	echo '</body></html>';
}

/**
 * Actually repoints references and deletes duplicates for every group.
 *
 * @param array<string, int[]> $groups Duplicate groups.
 * @return array[] One entry per group: array( 'keeper' => id, 'dupes' => array of ids that were deleted ).
 */
function cb_dedup_media_process( $groups ) {
	$results = array();

	foreach ( $groups as $ids ) {
		$keeper_id = $ids[0];
		$dupe_ids  = array_slice( $ids, 1 );

		foreach ( $dupe_ids as $dupe_id ) {
			$refs = cb_dedup_media_find_references( $dupe_id );
			cb_dedup_media_repoint_and_delete( $dupe_id, $keeper_id, $refs );
		}

		$results[] = array(
			'keeper' => $keeper_id,
			'dupes'  => $dupe_ids,
		);
	}

	return $results;
}
