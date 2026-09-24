<?php
/**
 * Teacher hero override.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$name              = isset( $data['name'] ) ? (string) $data['name'] : get_the_title();
$specialty         = isset( $data['specialty'] ) ? (string) $data['specialty'] : '';
$short_bio         = isset( $data['short_bio'] ) ? (string) $data['short_bio'] : '';
$photo_id          = isset( $data['photo_id'] ) ? absint( $data['photo_id'] ) : 0;
$published_courses = isset( $data['published_courses_n'] ) ? absint( $data['published_courses_n'] ) : 0;
$socials           = isset( $data['socials'] ) && is_array( $data['socials'] ) ? $data['socials'] : array();
$initial           = function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 1 ) : substr( $name, 0, 1 );
?>

<section class="meridian-teacher-hero">
	<div class="meridian-teacher-hero__avatar">
		<?php if ( $photo_id ) : ?>
			<?php echo wp_get_attachment_image( $photo_id, 'large', false, array( 'alt' => esc_attr( $name ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<div class="meridian-empty-state"><strong><?php echo esc_html( $initial ); ?></strong></div>
		<?php endif; ?>
	</div>

	<div class="meridian-teacher-hero__copy">
		<p class="meridian-eyebrow"><?php echo esc_html( $specialty ? $specialty : __( 'Instructor', 'atora-learning' ) ); ?></p>
		<h1><?php echo esc_html( $name ); ?></h1>
		<?php if ( $short_bio ) : ?>
			<p><?php echo esc_html( $short_bio ); ?></p>
		<?php endif; ?>

		<div class="meridian-teacher-hero__meta">
			<?php if ( $published_courses ) : ?>
				<span class="meridian-teacher-hero__stat"><?php echo esc_html( sprintf( _n( '%d published course', '%d published courses', $published_courses, 'atora-learning' ), $published_courses ) ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $socials ) ) : ?>
			<div class="meridian-teacher-hero__meta">
				<?php foreach ( $socials as $social ) : ?>
					<?php if ( empty( $social['url'] ) || empty( $social['label'] ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<a class="meridian-teacher-hero__stat" href="<?php echo esc_url( (string) $social['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( (string) $social['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
