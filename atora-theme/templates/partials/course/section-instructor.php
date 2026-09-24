<?php
/**
 * Commercial instructor block.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $instructor_html ) ) {
	return;
}
?>

<section class="meridian-course-section meridian-course-section--instructor">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Instructor', 'atora-learning' ); ?></p>
	<h2><?php echo esc_html( $teacher_section_title ); ?></h2>
	<div class="meridian-richtext">
		<?php echo $instructor_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
