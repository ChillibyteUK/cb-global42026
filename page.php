<?php
/**
 * Page template.
 *
 * Most projects build page layouts from ACF blocks rather than the_content()
 * directly — override this per project as needed.
 *
 * @package cb-global42026
 */

get_header();
?>

<main id="main">
	<?php
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</main>

<?php
get_footer();
