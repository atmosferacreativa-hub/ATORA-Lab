<?php
/**
 * Course benefits.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $benefit_items ) ) {
	return;
}
?>

<section class="meridian-course-section meridian-course-section--benefits">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Outcomes', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'The promise is clearer when the learning gain is concrete.', 'atora-learning' ); ?></h2>
	<ul class="meridian-course-section__list">
		<?php foreach ( $benefit_items as $item ) : ?>
			<li><?php echo esc_html( $item ); ?></li>
		<?php endforeach; ?>
	</ul>
</section>
