<?php
/**
 * Course overview about section.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || '' === trim( wp_strip_all_tags( (string) $course_content ) ) ) {
	return;
}
?>

<section class="meridian-overview-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'About the course', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Academic context without the old template noise.', 'atora-learning' ); ?></h2>
	<div class="meridian-richtext">
		<?php echo apply_filters( 'the_content', (string) $course_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
