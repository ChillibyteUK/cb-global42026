<?php
/**
 * Block template for CB Post Index.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => -1,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	)
);

if ( ! $query->have_posts() ) {
	return;
}

$all_years = array();
foreach ( $query->posts as $post_item ) {
	$year               = get_the_date( 'Y', $post_item );
	$all_years[ $year ] = $year;
}
krsort( $all_years );

$all_categories = array();
foreach ( $query->posts as $post_item ) {
	foreach ( get_the_category( $post_item->ID ) as $category ) {
		if ( in_array( $category->slug, array( 'uncategorized', 'uncategorised' ), true ) ) {
			continue;
		}
		$all_categories[ $category->slug ] = $category->name;
	}
}
asort( $all_categories );

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-post-index' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-post-index__controls">
			<div class="cb-post-index__filters">
				<button type="button" class="cb-post-index__filter cb-post-index__filter--active" data-filter="all">All</button>
				<?php
				foreach ( $all_categories as $slug => $name ) {
					?>
				<button type="button" class="cb-post-index__filter" data-filter="<?= esc_attr( $slug ); ?>"><?= esc_html( $name ); ?></button>
					<?php
				}
				?>
			</div>
			<div class="cb-post-index__filters">
				<button type="button" class="cb-post-index__filter cb-post-index__filter--active" data-year="all">All years</button>
				<?php
				foreach ( $all_years as $year ) {
					?>
				<button type="button" class="cb-post-index__filter" data-year="<?= esc_attr( $year ); ?>"><?= esc_html( $year ); ?></button>
					<?php
				}
				?>
			</div>
			<div class="cb-post-index__search">
				<input type="text" class="cb-post-index__search-input" placeholder="Search posts&hellip;" aria-label="Search posts">
				<button type="button" class="btn btn-secondary-dark cb-post-index__reset">Clear</button>
			</div>
		</div>

		<div class="cb-post-index__results" data-ajax-url="<?= esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-nonce="<?= esc_attr( wp_create_nonce( 'cb_post_index_search' ) ); ?>">
			<?php cb_render_post_index_results( $query->posts ); ?>
		</div>
	</div>
</section>
