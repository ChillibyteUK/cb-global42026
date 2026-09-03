<?php
/**
 * Single template for the `download` CPT — landing page for a legacy
 * WP Download Manager item (title + a button to the current file).
 *
 * The file link is resolved fresh on every request via get_field(), not
 * cached anywhere, so replacing the ACF file field's value automatically
 * updates both this page and the legacy ?wpdmdl= redirect in
 * inc/downloads.php with zero further code changes.
 *
 * @package cb-global42026
 */

get_header();

while ( have_posts() ) {
	the_post();

	$download_file = get_field( 'file' );
	$file_url      = '';

	if ( ! empty( $download_file['url'] ) ) {
		$file_url = $download_file['url'];
	}
	?>

<div class="cb-single__intro">
	<div class="container">
		<h1 class="cb-single__title"><?php the_title(); ?></h1>
	</div>
</div>

<div class="container">
	<?php cb_render_breadcrumbs(); ?>
</div>

<div class="container">
	<div class="row">
		<div class="col-12">
			<?php
			if ( $file_url ) {
				?>
			<a class="btn btn-primary" href="<?= esc_url( $file_url ); ?>" download>
				Download
			</a>
				<?php
			} else {
				?>
			<p>No file is currently available for this download.</p>
				<?php
			}
			?>
		</div>
	</div>
</div>

	<?php
}

get_footer();
