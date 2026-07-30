<?php
/**
 * Block template for CB Home Hero.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$cb_home_hero_particles_js = get_stylesheet_directory() . '/js/particles.js';
if ( file_exists( $cb_home_hero_particles_js ) ) {
	wp_enqueue_script(
		'particles-js',
		get_stylesheet_directory_uri() . '/js/particles.js',
		array(),
		filemtime( $cb_home_hero_particles_js ),
		true
	);
}

?>
<section class="cb-home-hero">
	<canvas id="nokey" width="100%" height="100%">
		Your browser doesn't support the canvas element
	</canvas>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-8 col-lg-6">
				<h1><?= wp_kses_post( get_field( 'hero_title') ); ?></h1>
				<div class="cb-home-hero__content">
					<?= wp_kses_post( get_field( 'hero_content') ); ?>
				</div>
				<?php
				$primary_cta   = get_field( 'primary_cta' );
				$secondary_cta = get_field( 'secondary_cta' );
				if ( $primary_cta || $secondary_cta ) {
					?>
					<div class="cb-home-hero__cta">
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
