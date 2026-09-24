<?php
/**
 * Course overview testimonials.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $testimonial_items ) ) {
	return;
}
?>

<section class="meridian-overview-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Student voice', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Testimonials can still support the academic view when they are carefully framed.', 'atora-learning' ); ?></h2>
	<div class="meridian-grid meridian-grid--2">
		<?php foreach ( $testimonial_items as $testimonial ) : ?>
			<div class="meridian-story-card">
				<h3><?php echo esc_html( (string) ( $testimonial['name'] ?? __( 'Student', 'atora-learning' ) ) ); ?></h3>
				<p><?php echo esc_html( (string) ( $testimonial['text'] ?? '' ) ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</section>
