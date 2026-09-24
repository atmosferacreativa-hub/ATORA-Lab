<?php
/**
 * Teacher extra block override.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$extra_title   = isset( $data['extra_title'] ) ? (string) $data['extra_title'] : '';
$extra_content = isset( $data['extra_content'] ) ? (string) $data['extra_content'] : '';

if ( '' === trim( $extra_title ) && '' === trim( $extra_content ) ) {
	return;
}
?>

<section class="meridian-teacher-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Additional context', 'atora-learning' ); ?></p>
	<?php if ( $extra_title ) : ?>
		<h2><?php echo esc_html( $extra_title ); ?></h2>
	<?php endif; ?>
	<?php if ( $extra_content ) : ?>
		<div class="meridian-richtext">
			<?php echo wp_kses_post( wpautop( $extra_content ) ); ?>
		</div>
	<?php endif; ?>
</section>
