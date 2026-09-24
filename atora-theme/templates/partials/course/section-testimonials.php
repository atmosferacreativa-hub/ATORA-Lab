<?php
/**
 * Commercial testimonials.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $testimonial_items ) ) {
	return;
}
?>

<section class="meridian-course-section meridian-course-section--testimonials">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Social proof', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Trust rises when the student voice feels real and curated.', 'atora-learning' ); ?></h2>
	<div class="meridian-grid meridian-grid--2">
		<?php foreach ( $testimonial_items as $testimonial ) : ?>
			<article class="meridian-story-card">
				<?php if ( ! empty( $testimonial['rating'] ) ) : ?>
					<p class="meridian-meta-note"><?php echo esc_html( sprintf( __( '%d/5 rating', 'atora-learning' ), (int) $testimonial['rating'] ) ); ?></p>
				<?php endif; ?>
				<h3><?php echo esc_html( (string) ( $testimonial['name'] ?? __( 'Student', 'atora-learning' ) ) ); ?></h3>
				<p><?php echo esc_html( (string) ( $testimonial['text'] ?? '' ) ); ?></p>
				<?php if ( ! empty( $testimonial['role'] ) ) : ?>
					<p class="meridian-meta-note"><?php echo esc_html( (string) $testimonial['role'] ); ?></p>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
</section>
