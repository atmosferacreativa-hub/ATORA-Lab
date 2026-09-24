<?php
/**
 * Teacher achievements override.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$achievements = isset( $data['achievements'] ) && is_array( $data['achievements'] ) ? $data['achievements'] : array();
$video_url    = isset( $data['video_url'] ) ? (string) $data['video_url'] : '';
$video_embed  = '';

if ( $video_url && class_exists( 'CLMS_UI_Teacher_Sections' ) && method_exists( 'CLMS_UI_Teacher_Sections', 'get_video_embed' ) ) {
	$video_embed = (string) CLMS_UI_Teacher_Sections::get_video_embed( $video_url );
}

if ( empty( $achievements ) && '' === trim( $video_embed ) ) {
	return;
}
?>

<section class="meridian-teacher-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Experience', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'What makes this instructor worth trusting.', 'atora-learning' ); ?></h2>
	<div class="meridian-grid meridian-grid--2">
		<?php if ( ! empty( $achievements ) ) : ?>
			<ul class="meridian-teacher-list">
				<?php foreach ( $achievements as $achievement ) : ?>
					<li><?php echo esc_html( (string) $achievement ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $video_embed ) : ?>
			<div class="meridian-featured-media">
				<?php echo $video_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</section>
