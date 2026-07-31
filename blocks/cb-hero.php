<?php
/**
 * Block template for CB Hero.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$is_front_page    = is_front_page();
$background_image = get_field( 'background_image' );

$classes = array( 'cb-hero' );

if ( $is_front_page ) {
	$classes[] = 'cb-hero--home';

	$particles_js = get_stylesheet_directory() . '/js/particles.js';
	if ( file_exists( $particles_js ) ) {
		wp_enqueue_script(
			'particles-js',
			get_stylesheet_directory_uri() . '/js/particles.js',
			array(),
			filemtime( $particles_js ),
			true
		);
	}
} elseif ( ! empty( $background_image['url'] ) ) {
	$classes[] = 'cb-hero--has-bg-image';
}
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>"
	<?php
	if ( ! $is_front_page && ! empty( $background_image['url'] ) ) {
		?>
	style="--cb-hero-bg-image: url('<?= esc_url( $background_image['url'] ); ?>');"
		<?php
	}
	?>
>
	<?php
	if ( $is_front_page ) {
		?>
	<canvas id="nokey" width="100%" height="100%">
		Your browser doesn't support the canvas element
	</canvas>
		<?php
	}
	?>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-8 col-lg-6">
				<h1><?= wp_kses_post( get_field( 'hero_title' ) ); ?></h1>
				<div class="cb-hero__content">
					<?= wp_kses_post( get_field( 'hero_content' ) ); ?>
				</div>
				<?php
				$primary_cta   = get_field( 'primary_cta' );
				$secondary_cta = get_field( 'secondary_cta' );
				if ( $primary_cta || $secondary_cta ) {
					?>
				<div class="cb-hero__cta">
					<?php
					if ( $primary_cta ) {
						?>
					<a href="<?= esc_url( $primary_cta['url'] ); ?>" class="btn btn-primary"><?= esc_html( $primary_cta['title'] ); ?></a>
						<?php
					}
					if ( $secondary_cta ) {
						?>
					<a href="<?= esc_url( $secondary_cta['url'] ); ?>" class="btn btn-secondary"><?= esc_html( $secondary_cta['title'] ); ?></a>
						<?php
					}
					?>
				</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
