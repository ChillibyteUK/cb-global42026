<?php
/**
 * Block template for CB Text 2 Counters.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading       = get_field( 'heading' );
$content       = get_field( 'content' );
$number_1      = get_field( 'number_1' );
$description_1 = get_field( 'description_1' );
$number_2      = get_field( 'number_2' );
$description_2 = get_field( 'description_2' );

if ( ! $heading && ! $content && '' === $number_1 && '' === $number_2 ) {
	return;
}

$counters = array(
	array(
		'number'      => $number_1,
		'description' => $description_1,
	),
	array(
		'number'      => $number_2,
		'description' => $description_2,
	),
);

$direction = get_field( 'direction' ) ? get_field( 'direction' ) : 'text_stats';

/** @var array $block ACF block data. */
$classes = cb_block_classes(
	array(
		'cb-text-2-counters',
		'stats_text' === $direction ? 'cb-text-2-counters--stats-first' : '',
	),
	$block
);

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-6 cb-text-2-counters__text">
				<?php
				if ( $heading ) {
					?>
				<h2 class="cb-text-2-counters__heading"><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $content ) {
					?>
				<div class="cb-text-2-counters__content"><?= wp_kses_post( $content ); ?></div>
					<?php
				}
				?>
			</div>
			<?php
			foreach ( $counters as $counter ) {
				if ( '' === $counter['number'] ) {
					continue;
				}
				?>
			<div class="col-12 col-md-6 col-lg-3 cb-text-2-counters__card">
				<div class="cb-text-2-counters__number"><span data-counter="<?= esc_attr( $counter['number'] ); ?>">0</span>%</div>
				<?php
				if ( $counter['description'] ) {
					?>
				<div class="cb-text-2-counters__description"><?= esc_html( $counter['description'] ); ?></div>
					<?php
				}
				?>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
