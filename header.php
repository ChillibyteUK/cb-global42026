<?php
/**
 * Header template.
 *
 * @package cb-global42026
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="preload"
		href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/roboto-v51-latin-regular.woff2' ); ?>"
		as="font" type="font/woff2" crossorigin="anonymous">
	<link rel="preload"
		href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/roboto-v51-latin-700.woff2' ); ?>"
		as="font" type="font/woff2" crossorigin="anonymous">
	<link rel="preload"
		href="<?= esc_url( get_stylesheet_directory_uri() . '/fonts/roboto-v51-latin-900.woff2' ); ?>"
		as="font" type="font/woff2" crossorigin="anonymous">

	<?php
	if ( ! is_user_logged_in() ) {
		if ( get_field( 'ga_property', 'options' ) ) {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async
				src="<?= esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . get_field( 'ga_property', 'options' ) ); ?>">
			</script>
			<script>
				window.dataLayer = window.dataLayer || [];

				function gtag() {
					dataLayer.push(arguments);
				}
				gtag('js', new Date());
				gtag('config',
					'<?= esc_js( get_field( 'ga_property', 'options' ) ); ?>'
				);
			</script>
			<?php
		}
		if ( get_field( 'gtm_property', 'options' ) ) {
			?>
			<!-- Google Tag Manager -->
			<script>
				(function(w, d, s, l, i) {
					w[l] = w[l] || [];
					w[l].push({
						'gtm.start': new Date().getTime(),
						event: 'gtm.js'
					});
					var f = d.getElementsByTagName(s)[0],
						j = d.createElement(s),
						dl = l != 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src =
						'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore(j, f);
				})(window, document, 'script', 'dataLayer',
					'<?= esc_js( get_field( 'gtm_property', 'options' ) ); ?>'
				);
			</script>
			<!-- End Google Tag Manager -->
			<?php
		}
	}
	if ( get_field( 'google_site_verification', 'options' ) ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( get_field( 'google_site_verification', 'options' ) ) . '" />';
	}
	if ( get_field( 'bing_site_verification', 'options' ) ) {
		echo '<meta name="msvalidate.01" content="' . esc_attr( get_field( 'bing_site_verification', 'options' ) ) . '" />';
	}
	< ? php wp_head();
	?>
</head>

<body <?php body_class(); ?>>
<a class="visually-hidden" href="#main">Skip to content</a>
<?php wp_body_open(); ?>

<header id="masthead">
	<nav class="navbar container" aria-label="Primary navigation">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<?php cb_render_logo_svg( 'nav' ); ?>
		</a>

		<?php
		// Landing pages (PPC campaigns — see inc/landing-pages.php) get the
		// logo only: no nav, no toggle, nothing else to click away with. The
		// .navbar-brand above already links home, which is the one route out
		// they should have.
		if ( ! is_singular( 'landing_page' ) ) {
			?>
		<button class="navbar-toggler" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="Toggle navigation">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M2 5h16M2 10h16M2 15h16" />
			</svg>
		</button>

		<div class="navbar-collapse" id="primary-menu">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'navbar-nav',
					'container'      => false,
					'fallback_cb'    => false,
					'walker'         => new CB_Global_4_Nav_Walker(),
				)
			);
			?>
		</div>
			<?php
		}
		?>
	</nav>
</header>