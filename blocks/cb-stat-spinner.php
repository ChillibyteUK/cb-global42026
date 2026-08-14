<?php
/**
 * Block template for CB Stat Spinner.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$stats = get_field( 'stats' );

if ( ! $stats ) {
	return;
}

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-stat-spinner' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-stat-spinner__stats" style="--cb-stat-spinner-columns: <?= esc_attr( count( $stats ) ); ?>;">
			<?php
			foreach ( $stats as $stat_row ) {
				$stat        = $stat_row['stat'];
				$suffix      = $stat_row['suffix'];
				$description = $stat_row['description'];

				if ( '' === $stat && ! $description ) {
					continue;
				}

				// Built up as a string rather than inline markup so the suffix
				// sits flush against the stat — dropping in and out of PHP
				// between two inline spans would emit whitespace between them,
				// which renders as a visible gap.
				//
				// is_numeric() covers ints and floats (and negatives), so
				// "250", "99.9" and "-3" animate while "24/7" or "Two" don't.
				// counter.js takes it from the data attribute and matches the
				// decimal places of whatever's given.
				$value = is_numeric( $stat )
					? '<span data-counter="' . esc_attr( $stat ) . '">0</span>'
					: esc_html( $stat );

				if ( $suffix ) {
					$value .= '<span class="cb-stat-spinner__suffix">' . esc_html( $suffix ) . '</span>';
				}
				?>
			<div class="cb-stat-spinner__stat">
				<?php
				if ( '' !== $stat || $suffix ) {
					?>
				<div class="cb-stat-spinner__value"><?= $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php
				}
				if ( $description ) {
					?>
				<div class="cb-stat-spinner__description"><?= esc_html( $description ); ?></div>
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
