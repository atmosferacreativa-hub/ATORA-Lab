<?php
/**
 * Ingress / egress profiles.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || ( ! $ingress && ! $egress ) ) {
	return;
}
?>

<section class="meridian-course-section meridian-course-section--profiles">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Fit', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Clarify who this is for and what changes after completion.', 'atora-learning' ); ?></h2>
	<div class="meridian-grid meridian-grid--2">
		<?php if ( $ingress ) : ?>
			<div class="meridian-story-card">
				<h3><?php esc_html_e( 'Best for', 'atora-learning' ); ?></h3>
				<p><?php echo esc_html( $ingress ); ?></p>
			</div>
		<?php endif; ?>
		<?php if ( $egress ) : ?>
			<div class="meridian-story-card">
				<h3><?php esc_html_e( 'After finishing', 'atora-learning' ); ?></h3>
				<p><?php echo esc_html( $egress ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
