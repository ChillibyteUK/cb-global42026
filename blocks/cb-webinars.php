<?php
/**
 * Block template for CB Webinars.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$query = new WP_Query(
	array(
		'post_type'      => 'webinar',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

if ( ! $query->have_posts() ) {
	return;
}

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes( array( 'cb-webinars', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-webinars__cards">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();

				$webinar_id    = get_the_ID();
				$presenters    = get_field( 'presenters', $webinar_id );
				$overview      = get_field( 'overview', $webinar_id );
				$youtube_link  = get_field( 'youtube_link', $webinar_id );
				$file_download = get_field( 'file_download', $webinar_id );
				$youtube_url   = $youtube_link ? $youtube_link['url'] : '';
				$youtube_id    = cb_get_youtube_id( $youtube_url );
				$dialog_id     = 'cb-webinar-video-' . $webinar_id;
				$webinar_title = get_the_title( $webinar_id );
				?>
			<div class="cb-webinars__card row">
				<div class="col-12 col-lg-5">
					<?php if ( $youtube_id ) { ?>
					<button type="button" class="cb-webinars__video" data-dialog-target="<?= esc_attr( $dialog_id ); ?>" data-youtube-id="<?= esc_attr( $youtube_id ); ?>" aria-label="<?= esc_attr( 'Play: ' . $webinar_title ); ?>">
						<img class="cb-webinars__thumbnail" src="<?= esc_url( "https://img.youtube.com/vi/{$youtube_id}/hqdefault.jpg" ); ?>" alt="" loading="lazy" />
						<span class="cb-webinars__play-icon" aria-hidden="true">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z" /></svg>
						</span>
					</button>
					<?php } ?>
				</div>
				<div class="col-12 col-lg-7 cb-webinars__content">
					<h2 class="cb-webinars__title"><?= esc_html( $webinar_title ); ?></h2>
					<?php if ( $presenters ) { ?>
					<div class="cb-webinars__presenters">Presenters: <?= wp_kses_post( $presenters ); ?></div>
					<?php } ?>
					<?php if ( $overview ) { ?>
					<div class="cb-webinars__overview"><?= wp_kses_post( $overview ); ?></div>
					<?php } ?>
					<?php if ( ! empty( $file_download['url'] ) ) { ?>
					<a class="btn btn-secondary-dark cb-webinars__download" href="<?= esc_url( $file_download['url'] ); ?>" target="_blank" rel="noopener">
						Download <?= esc_html( $file_download['title'] ? $file_download['title'] : 'file' ); ?>
					</a>
					<?php } ?>
				</div>
			</div>
				<?php
				if ( $youtube_id ) {
					?>
			<dialog id="<?= esc_attr( $dialog_id ); ?>" class="cb-webinars__dialog">
				<button type="button" data-dialog-close class="cb-webinars__dialog-close" aria-label="Close">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4l12 12M16 4L4 16" /></svg>
				</button>
				<div class="cb-webinars__player"></div>
			</dialog>
					<?php
				}
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
