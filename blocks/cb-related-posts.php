<?php
/**
 * Block template for CB Related Posts.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading    = get_field( 'heading' );
$categories = get_field( 'categories' );
$count      = get_field( 'count' ) ? (int) get_field( 'count' ) : 3;

$query_args = array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => $count,
	'no_found_rows'  => true,
);

if ( $categories ) {
	$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => $categories,
		),
	);
}

$related_query = new WP_Query( $query_args );

if ( ! $related_query->have_posts() ) {
	return;
}

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes( array( 'cb-related-posts', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading ) {
			?>
		<h2 class="cb-related-posts__heading"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		?>
		<div class="cb-related-posts__cards">
			<?php
			while ( $related_query->have_posts() ) {
				$related_query->the_post();
				$post_categories = get_the_category();
				?>
			<a href="<?= esc_url( get_permalink() ); ?>" class="cb-related-posts__card card-link">
				<?php
				if ( has_post_thumbnail() ) {
					?>
				<div class="cb-related-posts__image"><?= get_the_post_thumbnail( get_the_ID(), 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php
				}
				?>
				<div class="cb-related-posts__body">
					<?php
					if ( $post_categories ) {
						?>
					<div class="cb-related-posts__category"><?= esc_html( $post_categories[0]->name ); ?></div>
						<?php
					}
					?>
					<h3 class="cb-related-posts__title"><?= esc_html( get_the_title() ); ?></h3>
				</div>
			</a>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
