<?php
/**
 * Teacher bio override.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$long_bio = isset( $data['long_bio'] ) ? (string) $data['long_bio'] : '';
if ( '' === trim( $long_bio ) ) {
	return;
}
?>

<section class="meridian-teacher-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Biography', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Authority should read as calm and credible.', 'atora-learning' ); ?></h2>
	<div class="meridian-richtext">
		<?php echo wp_kses_post( wpautop( $long_bio ) ); ?>
	</div>
</section>
