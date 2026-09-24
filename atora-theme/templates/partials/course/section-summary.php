<?php
/**
 * Course summary.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $course_include_items ) ) {
	return;
}
?>

<section class="meridian-course-section meridian-course-section--summary">
	<p class="meridian-eyebrow"><?php esc_html_e( 'What is included', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'A commercial page should explain value fast.', 'atora-learning' ); ?></h2>
	<div class="meridian-grid meridian-grid--2">
		<?php foreach ( $course_include_items as $item ) : ?>
			<div class="meridian-story-card">
				<h3><?php echo esc_html( $item ); ?></h3>
			</div>
		<?php endforeach; ?>
	</div>
</section>
