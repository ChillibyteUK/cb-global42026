<?php
/**
 * Single template for the `download` CPT.
 *
 * Normally never seen — cb_global42026_download_redirect() in
 * inc/downloads.php redirects this permalink straight to the post's current
 * file before this template ever loads. This only renders in the one case
 * that hook lets through: a `download` post published with no file yet
 * uploaded to its `file` ACF field.
 *
 * @package cb-global42026
 */

get_header();

while ( have_posts() ) {
	the_post();
	?>

<div class="cb-single__intro">
	<div class="container">
		<h1 class="cb-single__title"><?php the_title(); ?></h1>
	</div>
</div>

<div class="container">
	<?php cb_render_breadcrumbs(); ?>
</div>

<div class="container pb-5">
	<div class="row">
		<div class="col-12">
			<p>No file is currently available for this download.</p>
		</div>
	</div>
</div>

	<?php
}

get_footer();
