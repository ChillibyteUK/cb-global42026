<?php
/**
 * Single post template.
 *
 * @package cb-global42026
 */

get_header();

while ( have_posts() ) {
	the_post();

	$reading_minutes   = estimate_reading_time_in_minutes( get_the_content() );
	$categories        = get_the_category();
	$primary_category  = $categories ? $categories[0] : null;
	$prev_post         = get_previous_post();
	$next_post         = get_next_post();
	?>

<div class="cb-single__intro">
	<div class="container">
		<h1 class="cb-single__title"><?php the_title(); ?></h1>
		<div class="cb-single__meta">
			<span><?= esc_html( get_the_date() ); ?></span>
			<span><?= esc_html( $reading_minutes ); ?> min read</span>
		</div>
	</div>
</div>

<div class="container">
	<?php cb_render_breadcrumbs(); ?>
</div>

<div class="container">
	<div class="row">
		<div class="col-12 col-lg-9">
			<?php if ( has_post_thumbnail() ) { ?>
			<div class="cb-single__image">
				<?= get_the_post_thumbnail( get_the_ID(), 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php } ?>

			<article <?php post_class( 'cb-single__content' ); ?>>
				<?php the_content(); ?>
			</article>

			<nav class="cb-single__prev-next" aria-label="<?= esc_attr__( 'Post navigation', 'cb-global42026' ); ?>">
				<?php
				if ( $prev_post ) {
					?>
				<a class="cb-single__prev" href="<?= esc_url( get_permalink( $prev_post ) ); ?>">
					<span class="cb-single__nav-label">&larr; Previous</span>
					<span class="cb-single__nav-title"><?= esc_html( get_the_title( $prev_post ) ); ?></span>
				</a>
					<?php
				} else {
					?>
				<span></span>
					<?php
				}
				if ( $next_post ) {
					?>
				<a class="cb-single__next" href="<?= esc_url( get_permalink( $next_post ) ); ?>">
					<span class="cb-single__nav-label">Next &rarr;</span>
					<span class="cb-single__nav-title"><?= esc_html( get_the_title( $next_post ) ); ?></span>
				</a>
					<?php
				}
				?>
			</nav>
		</div>

		<?php if ( $primary_category ) { ?>
		<aside class="col-12 col-lg-3 cb-single__sidebar">
			<?php
			$related_query = new WP_Query(
				array(
					'post_type'           => 'post',
					'post_status'         => 'publish',
					'posts_per_page'      => 5,
					'post__not_in'        => array( get_the_ID() ),
					'category__in'        => array( $primary_category->term_id ),
					'ignore_sticky_posts' => true,
				)
			);

			if ( $related_query->have_posts() ) {
				?>
			<h2 class="cb-single__sidebar-heading">Related posts</h2>
			<div class="cb-single__related">
				<?php
				foreach ( $related_query->posts as $related_post ) {
					?>
				<a href="<?= esc_url( get_permalink( $related_post ) ); ?>" class="cb-single__related-item card-link">
					<?php if ( has_post_thumbnail( $related_post ) ) { ?>
					<div class="cb-single__related-image">
						<?= get_the_post_thumbnail( $related_post, 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<?php } ?>
					<div class="cb-single__related-body">
						<div class="cb-single__related-title"><?= esc_html( get_the_title( $related_post ) ); ?></div>
						<div class="cb-single__related-date"><?= esc_html( get_the_date( '', $related_post ) ); ?></div>
					</div>
				</a>
					<?php
				}
				?>
			</div>
				<?php
			}
			wp_reset_postdata();
			?>
		</aside>
		<?php } ?>
	</div>
</div>

	<?php
}

get_footer();
