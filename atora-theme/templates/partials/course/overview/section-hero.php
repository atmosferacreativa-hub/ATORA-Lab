<?php
/**
 * Meridian course overview hero.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$overview_label = '';
if ( $user_id && $is_enrolled ) {
	$overview_label = __( 'Continue learning', 'atora-learning' );
} elseif ( ! $user_id ) {
	$overview_label = __( 'Log in to access', 'atora-learning' );
} else {
	$overview_label = __( 'Enrollment required', 'atora-learning' );
}
?>

<section class="meridian-overview-hero">
	<div class="meridian-overview-hero__copy">
		<p class="meridian-eyebrow"><?php esc_html_e( 'Academic view', 'atora-learning' ); ?></p>
		<h1><?php echo esc_html( get_the_title( $course_id ) ); ?></h1>

		<?php if ( $course_excerpt ) : ?>
			<p><?php echo esc_html( $course_excerpt ); ?></p>
		<?php endif; ?>

		<div class="meridian-overview-hero__meta">
			<span class="meridian-overview-hero__stat">
				<?php
				echo esc_html(
					sprintf(
						_n( '%d lesson', '%d lessons', (int) $total, 'atora-learning' ),
						(int) $total
					)
				);
				?>
			</span>
			<?php if ( ! empty( $instructor_names ) ) : ?>
				<span class="meridian-overview-hero__stat"><?php echo esc_html( implode( ', ', $instructor_names ) ); ?></span>
			<?php endif; ?>
			<span class="meridian-overview-hero__stat"><?php echo esc_html( $overview_label ); ?></span>
		</div>

		<?php if ( $user_id && $is_enrolled ) : ?>
			<div class="meridian-overview-progress">
				<strong><?php echo esc_html( sprintf( __( '%d%% completed', 'atora-learning' ), (int) $progress ) ); ?></strong>
				<div class="meridian-progress"><span style="width:<?php echo esc_attr( (int) $progress ); ?>%;"></span></div>
				<p><?php echo esc_html( sprintf( __( '%1$d of %2$d lessons completed.', 'atora-learning' ), (int) $done, (int) $total ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<div class="meridian-overview-hero__aside">
		<?php if ( $course_thumbnail_id ) : ?>
			<div class="meridian-overview-hero__media" style="margin-bottom:1rem;">
				<?php echo wp_get_attachment_image( $course_thumbnail_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<?php if ( ! $user_id ) : ?>
			<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( wp_login_url( get_permalink( $course_id ) ) ); ?>"><?php esc_html_e( 'Log in to access', 'atora-learning' ); ?></a>
		<?php elseif ( ! $is_enrolled ) : ?>
			<p><?php esc_html_e( 'This course is connected to the academic engine but still protected by enrollment.', 'atora-learning' ); ?></p>
		<?php elseif ( $cta_lesson_id ) : ?>
			<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( get_permalink( $cta_lesson_id ) ); ?>">
				<?php echo esc_html( __( $cta_label_key, 'atora-lms' ) ); // phpcs:ignore WordPress.WP.I18n ?>
			</a>
		<?php endif; ?>
	</div>
</section>
