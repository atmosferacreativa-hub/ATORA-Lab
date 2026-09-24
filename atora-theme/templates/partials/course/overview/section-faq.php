<?php
/**
 * Course overview FAQ.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $faq_items ) ) {
	return;
}
?>

<section class="meridian-overview-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'FAQ', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'The academic surface can answer questions without becoming cluttered.', 'atora-learning' ); ?></h2>
	<div class="meridian-course-section__list">
		<?php foreach ( $faq_items as $faq ) : ?>
			<details>
				<summary><strong><?php echo esc_html( (string) $faq['q'] ); ?></strong></summary>
				<p style="margin-top:0.8rem;"><?php echo esc_html( (string) $faq['a'] ); ?></p>
			</details>
		<?php endforeach; ?>
	</div>
</section>
