<?php
/**
 * Teacher courses override.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $courses ) ) {
	return;
}
?>

<section class="meridian-teacher-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Teaching catalog', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Published courses tied to this profile.', 'atora-learning' ); ?></h2>
	<div class="meridian-teacher-courses">
		<?php foreach ( $courses as $course ) : ?>
			<article class="meridian-teacher-course">
				<h3><?php echo esc_html( get_the_title( $course->ID ) ); ?></h3>
				<p><?php echo esc_html( atora_get_plain_excerpt( $course->ID, 16 ) ); ?></p>
				<a class="meridian-link" href="<?php echo esc_url( get_permalink( $course->ID ) ); ?>"><?php esc_html_e( 'View course', 'atora-learning' ); ?></a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
