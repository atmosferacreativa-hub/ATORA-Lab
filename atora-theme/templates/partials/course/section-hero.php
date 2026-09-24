<?php
/**
 * Meridian course hero.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_modifier = isset( $meridian_course_hero_modifier ) ? (string) $meridian_course_hero_modifier : '';
$hero_cta_url  = $cta_url ? $cta_url : ( is_user_logged_in() ? '' : wp_login_url( $course_permalink ) );
$hero_cta_text = $cta_url ? $cta_label : ( is_user_logged_in() ? '' : __( 'Log in to access', 'atora-learning' ) );
$hero_lead     = $tagline ? $tagline : $subtitle;
?>

<section class="meridian-course-hero<?php echo esc_attr( $hero_modifier ); ?>">
	<div class="meridian-course-hero__copy">
		<p class="meridian-eyebrow"><?php esc_html_e( 'Course', 'atora-learning' ); ?></p>
		<h1><?php echo esc_html( $title ); ?></h1>

		<?php if ( $hero_lead ) : ?>
			<p><?php echo esc_html( $hero_lead ); ?></p>
		<?php endif; ?>

		<div class="meridian-course-hero__meta">
			<?php if ( $duration ) : ?>
				<span class="meridian-course-hero__stat"><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $lesson_ids ) ) : ?>
				<span class="meridian-course-hero__stat">
					<?php
					echo esc_html(
						sprintf(
							_n( '%d lesson', '%d lessons', count( $lesson_ids ), 'atora-learning' ),
							count( $lesson_ids )
						)
					);
					?>
				</span>
			<?php endif; ?>
			<?php if ( $certificate ) : ?>
				<span class="meridian-course-hero__stat"><?php esc_html_e( 'Certificate included', 'atora-learning' ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $program_ids ) ) : ?>
			<div class="meridian-course-hero__meta">
				<?php foreach ( $program_ids as $program_id ) : ?>
					<a class="meridian-course-hero__stat" href="<?php echo esc_url( get_permalink( $program_id ) ); ?>">
						<?php echo esc_html( get_the_title( $program_id ) ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="meridian-course-hero__aside">
		<?php if ( $hero_has_video_media || ! empty( $hero_featured_image_html ) ) : ?>
			<div class="meridian-course-hero__media">
				<?php if ( $embed_src ) : ?>
					<div class="atora-lesson-video-ratio">
						<iframe src="<?php echo esc_url( $embed_src ); ?>" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
					</div>
				<?php elseif ( $hero_fallback_iframe_src ) : ?>
					<div class="atora-lesson-video-ratio">
						<iframe src="<?php echo esc_url( $hero_fallback_iframe_src ); ?>" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
					</div>
				<?php elseif ( $hero_has_direct_video ) : ?>
					<div class="atora-lesson-video-ratio">
						<video controls preload="metadata"<?php echo ! empty( $hero_featured_image_url ) ? ' poster="' . esc_url( $hero_featured_image_url ) . '"' : ''; ?>>
							<source src="<?php echo esc_url( $hero_direct_video_url ); ?>">
						</video>
					</div>
				<?php elseif ( ! empty( $hero_featured_image_html ) ) : ?>
					<?php echo $hero_featured_image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="meridian-course-hero__offer">
			<?php if ( $price ) : ?>
				<span class="meridian-course-hero__price"><?php echo esc_html( $price ); ?></span>
			<?php endif; ?>

			<?php if ( $price_label ) : ?>
				<p><?php echo esc_html( $price_label ); ?></p>
			<?php endif; ?>

			<ul class="meridian-offer-list">
				<?php if ( ! empty( $lesson_ids ) ) : ?>
					<li><?php esc_html_e( 'Commercial landing connected to the academic course engine.', 'atora-learning' ); ?></li>
				<?php endif; ?>
				<li><?php esc_html_e( 'Designed to look credible, premium and mobile-ready.', 'atora-learning' ); ?></li>
				<li><?php esc_html_e( 'Powered by ATORA data instead of legacy theme widgets.', 'atora-learning' ); ?></li>
			</ul>

			<?php if ( $hero_cta_url && $hero_cta_text ) : ?>
				<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( $hero_cta_url ); ?>"><?php echo esc_html( $hero_cta_text ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
