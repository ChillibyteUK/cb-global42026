<?php
/**
 * CB Post Index — shared card renderer + AJAX search handler.
 *
 * Ported from cb-pluto2026's blocks/cb-insights-index.php (functionality:
 * year grouping, category/year client-side filters, AJAX search) but
 * restyled with this theme's own card/button conventions instead of
 * pluto's — see src/blocks/cb-post-index.css. The pluto version had no
 * shared render function; the same card markup was hand-duplicated across
 * four files (the block, its AJAX handler, and two page templates). Here
 * it's one function so the initial render and the AJAX-refreshed results
 * can never drift apart.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a single result card.
 *
 * @param WP_Post $post_item Post to render.
 * @return void
 */
function cb_render_post_index_card( $post_item ) {
	$categories = get_the_category( $post_item->ID );
	$cat_slugs  = $categories ? implode( ' ', wp_list_pluck( $categories, 'slug' ) ) : '';
	$cat_name   = $categories ? $categories[0]->name : '';
	$excerpt    = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post_item->post_content ) ), 30 );
	?>
	<div class="cb-post-index__item" data-category="<?= esc_attr( $cat_slugs ); ?>" data-year="<?= esc_attr( get_the_date( 'Y', $post_item ) ); ?>">
		<a href="<?= esc_url( get_permalink( $post_item ) ); ?>" class="cb-post-index__card card-link">
			<?php if ( has_post_thumbnail( $post_item ) ) { ?>
			<div class="cb-post-index__image">
				<?= get_the_post_thumbnail( $post_item, 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $cat_name ) { ?>
				<span class="cb-post-index__pill"><?= esc_html( $cat_name ); ?></span>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="cb-post-index__body">
				<?php if ( $cat_name && ! has_post_thumbnail( $post_item ) ) { ?>
				<div class="cb-post-index__category"><?= esc_html( $cat_name ); ?></div>
				<?php } ?>
				<h3 class="cb-post-index__title"><?= esc_html( get_the_title( $post_item ) ); ?></h3>
				<div class="cb-post-index__date"><?= esc_html( get_the_date( 'jS F, Y', $post_item ) ); ?></div>
				<?php if ( $excerpt ) { ?>
				<p class="cb-post-index__excerpt"><?= esc_html( $excerpt ); ?></p>
				<?php } ?>
				<span class="link-arrow">
					Learn more
					<svg class="link-arrow__icon" width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path d="M2 7h10M8 3l4 4-4 4" />
					</svg>
				</span>
			</div>
		</a>
	</div>
	<?php
}

/**
 * Render every result, grouped by year (most recent year first).
 *
 * @param WP_Post[] $posts Posts to render — already filtered/ordered by the caller.
 * @return void
 */
function cb_render_post_index_results( $posts ) {
	if ( ! $posts ) {
		echo '<p class="cb-post-index__empty">No posts found.</p>';
		return;
	}

	$posts_by_year = array();

	foreach ( $posts as $post_item ) {
		$year                    = get_the_date( 'Y', $post_item );
		$posts_by_year[ $year ][] = $post_item;
	}

	krsort( $posts_by_year );

	foreach ( $posts_by_year as $year => $year_posts ) {
		?>
	<div class="cb-post-index__year-group" data-year="<?= esc_attr( $year ); ?>">
		<h2 class="cb-post-index__year-heading"><?= esc_html( $year ); ?></h2>
		<div class="cb-post-index__cards">
			<?php
			foreach ( $year_posts as $post_item ) {
				cb_render_post_index_card( $post_item );
			}
			?>
		</div>
	</div>
		<?php
	}
}

/**
 * AJAX search — same query shape/results markup as the block's initial
 * render, just filtered by the search term and/or active category.
 *
 * @return void
 */
function cb_ajax_post_index_search() {
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cb_post_index_search' ) ) {
		wp_send_json_error( array( 'message' => 'Security check failed.' ), 403 );
	}

	$search_term = isset( $_POST['search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['search_term'] ) ) : '';
	$category    = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

	$query_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => -1,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	if ( $search_term ) {
		$query_args['s'] = $search_term;
	}

	if ( $category && 'all' !== $category ) {
		$query_args['category_name'] = $category;
	}

	$query = new WP_Query( $query_args );

	ob_start();
	cb_render_post_index_results( $query->posts );
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_cb_post_index_search', 'cb_ajax_post_index_search' );
add_action( 'wp_ajax_nopriv_cb_post_index_search', 'cb_ajax_post_index_search' );
