<?php
/**
 * Course overview instructor block.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $overview_instructor_html ) ) {
	return;
}
?>

<section class="meridian-overview-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Instructor', 'atora-learning' ); ?></p>
	<h2><?php echo esc_html( $overview_instructor_title ); ?></h2>
	<div class="meridian-richtext">
		<?php echo $overview_instructor_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
