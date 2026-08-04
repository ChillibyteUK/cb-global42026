<?php
/**
 * Block template for CB Hero.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$is_front_page    = is_front_page();
$background_image = get_field( 'background_image' );
$has_bg_image      = ! empty( $background_image['url'] );

$classes = array( 'cb-hero' );

if ( $is_front_page ) {
	$classes[] = 'cb-hero--home';
}

if ( $has_bg_image ) {
	$classes[] = 'cb-hero--has-bg-image';
}

// Reference canvas the mask logo is positioned against. preserveAspectRatio
// "slice" on the <svg> below scales this to cover the hero at whatever its
// real aspect ratio turns out to be, the same way background-size:cover
// does, so the logo never distorts even though its own viewBox is roughly
// square and the hero is much wider than it is tall.
$mask_vb_width  = 1440;
$mask_vb_height = 720;

// Native viewBox of the Global 4 logo device (Asset 4.svg — compound paths
// released so each blob is its own <path>).
$logo_width  = 319.5548;
$logo_height = 314.5635;

// Scale the logo so it's taller than the hero (bleeds off top/bottom), then
// centre that oversized shape in the right half of the reference canvas.
$logo_scale     = ( $mask_vb_height * 1.3 ) / $logo_height;
$logo_render_w  = $logo_width * $logo_scale;
$logo_render_h  = $logo_height * $logo_scale;
$logo_x         = ( $mask_vb_width * 0.75 ) - ( $logo_render_w / 2 );
$logo_y         = ( $mask_vb_height - $logo_render_h ) / 2;
$logo_transform = "translate({$logo_x}, {$logo_y}) scale({$logo_scale})";

$mask_id = $has_bg_image ? wp_unique_id( 'cb-hero-mask-' ) : '';

// A single photo (.cb-hero__bg) sits behind everything. This SVG only ever
// draws the dark scrim rect, punched through with logo-shaped holes via an
// SVG <mask> (white = scrim visible, black = scrim hidden) — there is no
// second copy of the image anywhere, so there's nothing for it to fall out
// of alignment with. <mask>, unlike <clipPath>, permits <g> as a child, so
// the whole logo is one group with one transform (see
// src/js/scroll-animate.js for how the scroll parallax updates it).
$logo_paths = array(
	'M274.9644,116.7172c36.8092,49.4473,6.419,122.2219-48.5336,116.2221-55.7496-6.0869-71.8145-86.3868-37.5757-127.2291,27.2203-26.043,66.5004-15.3342,86.1092,11.007',
	'M319.5353,156.3782c-.1813-10.7298-1.6357-16.2798-2.4866-18.6331-1.6094-4.4511-5.2069-9.8626-11.2194,3.9046-9.7912,26.6856-9.0174,65.7279-3.0054,74.1553,3.8933,5.4574,8.6926-3.1861,13.1674-23.714l.6714-3.4263c1.0514-6.7137,2.0657-15.725,2.6796-22.4928.2637-5.5804.2177-8.3213.1931-9.7939',
	'M240.0974,274.8113c-1.4531-17.9469-22.7629-33.4975-54.6567-27.3262-34.8269,9.6463-42.6962,39.3392-24.4735,55.9793,27.1255,24.7696,81.8511,4.9534,79.1302-28.6531',
	'M164.4522,195.9648c-5.3168-12.0851-22.6351-26.3696-42.922-18.649-25.6008,13.0451-19.6453,47.4345,2.7148,58.7748,35.7646,18.1386,54.575-7.512,40.2072-40.1257',
	'M293.7899,240.7523c1.9658-10.3201-3.7864-19.2247-22.7291-6.7101-19.0212,14.2373-26.7689,33.6992-20.7419,40.9418,9.2732,11.1435,39.3373-12.5307,43.471-34.2317',
	'M176.3021,19.838c-13.7981-1.0509-26.1363,2.7593-25.6305,6.4794,3.0767,6.8219,44.1888,13.4351,50.5632,8.1334,5.4026-4.4934-7.2563-13.2664-24.9328-14.6128',
	'M168.377.0036c-2.049-.0244-4.6377.0771-6.4417.2526-6.513.6343-13.7667,2.9542-10.4462,5.6605,6.9244,3.7879,45.0737,6.4535,48.0232,3.3555C202.3956,6.2438,184.2678.1945,168.377.0036',
	'M157.9329,119.2603c-7.836-10.3033-26.075-20.509-39.939-12.873-.5003.3719-1.1937.8345-1.6676,1.2395-13.4947,11.5326-6.3066,50.3569,20.0494,54.0992,21.323,3.0276,37.5527-21.4337,21.5572-42.4656',
	'M87.8148,201.6378c-3.002-12.788-15.1671-30.7764-31.7447-28.7735-.6493.1701-1.528.3536-2.1645.5669-20.1461,6.751-21.6091,44.4573,2.5231,57.2642,16.8154,8.9239,37.0508-6.2701,31.3861-29.0576',
	'M137.6176,269.6353c-6.7813-14.5732-29.4732-33.362-58.6901-29.5055-2.132.485-4.987,1.1573-7.0108,1.9547-28.0245,11.0423-17.3394,47.8123,17.5893,60.5292,32.8452,11.9583,60.2734-6.8425,48.1117-32.9784',
	'M192.5959,48.1219c-13.982-4.8654-33.7518-4.8048-38.2858,2.5648-3.5966,11.8069,37.0228,36.5888,54.6572,28.4549,12.3557-5.6991,5.7273-23.3299-16.3713-31.0198',
	'M260.4822,36.4955c-4.0281-3.3799-9.8513-7.3141-14.1237-10.3793-23.3092-14.9783-35.3637-11.5042-33.5516.5077,6.3438,20.7871,59.2275,73.8986,82.528,78.9999,11.2279,2.4582,15.7919-5.7726.6778-29.702-3.5939-5.2404-8.766-11.9602-12.6709-16.9731-8.4208-9.698-9.7873-10.9051-22.8595-22.4533',
	'M24.6568,244.1769c7.7659,11.4535,18.8759,19.1058,21.0246,16.0272,6.4722-9.2733-10.2006-41.764-25.1067-44.6369-6.9-1.3298-5.8666,13.9368,4.0821,28.6096',
	'M34.0826,119.6601c11.7326,22.3549,53.8092,4.677,62.5304-26.2713,6.7652-24.007-13.9925-38.3777-37.6452-26.0621-1.8662.9717-4.2184,2.5164-5.9703,3.6827-23.0699,17.7413-23.8453,39.2564-18.9149,48.6506',
	'M88.3402,49.8026c13.4784,12.9197,57.4773,3.1197,57.4133-12.7879-.0413-10.2408-20.4814-16.9206-43.3954-9.8264-19.772,7.3112-19.0914,17.751-14.0179,22.6142',
	'M130.1531,93.4616c16.9331,3.2156,31.8074-5.9995,22.7219-18.5415-8.1293-11.2221-30.881-14.0392-39.037-1.5266-4.5185,9.7643,4.747,17.8714,16.3151,20.0682',
	'M43.8299,55.9245c3.2572,8.6601,30.5763-3.5538,36.5392-16.3361,3.5548-7.6202-3.5397-10.7642-14.5983-6.4695-2.9113,1.1307-4.8422,2.2364-6.8865,3.4071-10.0445,6.0607-16.7979,14.7628-15.0544,19.3985',
	'M66.832,164.0797c10.7845,12.7155,31.8581,3.8151,33.16-14.005,1.0094-13.8166-12.9844-26.8268-29.0899-15.7782-9.4081,7.3295-11.31,21.2469-4.0701,29.7832',
	'M5.1844,187.0419c10.1971,15.1604,28.1074,8.0885,32.4814-12.8254,4.6681-22.3206-9.721-41.2023-24.833-32.5863-2.3282,1.3274-4.0387,3.1355-5.4131,4.5883-8.9289,10.655-9.9526,29.3498-2.2354,40.8234',
	'M96.4338,17.6551c17.7283,1.9174,45.9217-7.7073,42.7352-12.7749-.4579-.7282-2.1298-1.8806-8.1832-1.4175l-3.9305.4514c-8.708,1.5576-9.0479,1.6183-14.3383,3.254-7.4558,2.6523-7.5982,2.7029-11.6248,4.6147-2.6654,1.374-6.1959,3.2833-8.8114,4.8102.1447.1991.4834.6653,4.1529,1.0622',
	'M8.0509,122.0021c12.5994-12.6183,22.7569-40.6242,16.8107-41.3146-.8544-.0992-2.8.4822-6.2308,5.491l-2.1102,3.3466c-4.2404,7.7636-4.406,8.0666-6.4449,13.2151-2.6027,7.4732-2.6525,7.616-3.6845,11.9522-.5985,2.9383-1.3217,6.8864-1.7698,9.8816.2458.0119.8214.0399,3.4294-2.5719',
);
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>"
	<?php
	if ( $has_bg_image ) {
		?>
	style="--cb-hero-bg-image: url('<?= esc_url( $background_image['url'] ); ?>');"
		<?php
	}
	?>
>
	<?php if ( $has_bg_image ) { ?>
	<div class="cb-hero__bg"></div>
	<svg class="cb-hero__scrim" viewBox="0 0 <?= esc_attr( $mask_vb_width ); ?> <?= esc_attr( $mask_vb_height ); ?>" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
		<defs>
			<mask id="<?= esc_attr( $mask_id ); ?>" maskUnits="userSpaceOnUse" x="0" y="0" width="<?= esc_attr( $mask_vb_width ); ?>" height="<?= esc_attr( $mask_vb_height ); ?>">
				<rect x="0" y="0" width="<?= esc_attr( $mask_vb_width ); ?>" height="<?= esc_attr( $mask_vb_height ); ?>" fill="#fff" />
				<g class="cb-hero__clip-parallax" fill="#000" transform="<?= esc_attr( $logo_transform ); ?>" data-target-x="<?= esc_attr( $mask_vb_width * 0.75 ); ?>" data-target-y="<?= esc_attr( $mask_vb_height / 2 ); ?>" data-half-w="<?= esc_attr( $logo_width / 2 ); ?>" data-half-h="<?= esc_attr( $logo_height / 2 ); ?>" data-base-scale="<?= esc_attr( $logo_scale ); ?>">
					<?php
					foreach ( $logo_paths as $d ) {
						?>
					<path d="<?= esc_attr( $d ); ?>" />
						<?php
					}
					?>
				</g>
			</mask>
		</defs>
		<rect class="cb-hero__scrim-rect" x="0" y="0" width="<?= esc_attr( $mask_vb_width ); ?>" height="<?= esc_attr( $mask_vb_height ); ?>" mask="url(#<?= esc_attr( $mask_id ); ?>)" />
	</svg>
	<?php } ?>
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
