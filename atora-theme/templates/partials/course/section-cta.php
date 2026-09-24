<?php
/**
 * Commercial closing CTA.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bottom_cta_url   = $cta_url ? $cta_url : ( is_user_logged_in() ? $course_permalink : wp_login_url( $course_permalink ) );
$bottom_cta_label = $cta_url ? $cta_label : ( is_user_logged_in() ? __( 'View course', 'atora-learning' ) : __( 'Log in', 'atora-learning' ) );
$bottom_copy      = $tagline ? $tagline : $subtitle;
?>

<section class="meridian-course-section meridian-course-section--cta">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Ready to convert', 'atora-learning' ); ?></p>
	<h2><?php echo esc_html( $title ?? get_the_title() ); ?></h2>
	<?php if ( $bottom_copy ) : ?>
		<p><?php echo esc_html( $bottom_copy ); ?></p>
	<?php endif; ?>
	<div class="meridian-hero-actions">
		<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( $bottom_cta_url ); ?>"><?php echo esc_html( $bottom_cta_label ); ?></a>
		<a class="meridian-button meridian-button--ghost" href="<?php echo esc_url( $course_permalink ); ?>"><?php esc_html_e( 'Academic view', 'atora-learning' ); ?></a>
	</div>
</section>
