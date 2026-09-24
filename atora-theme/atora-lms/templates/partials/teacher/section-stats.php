<?php
/**
 * Teacher stats override.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$published_courses = isset( $data['published_courses_n'] ) ? absint( $data['published_courses_n'] ) : 0;
$students          = 0;

if ( ! empty( $data['published_courses'] ) && is_array( $data['published_courses'] ) ) {
	foreach ( $data['published_courses'] as $course ) {
		$students += (int) get_post_meta( absint( $course->ID ), '_clms_enrollment_count', true );
	}
}

if ( ! $published_courses && ! $students ) {
	return;
}
?>

<section class="meridian-teacher-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Stats', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'A faster credibility snapshot.', 'atora-learning' ); ?></h2>
	<div class="meridian-dashboard-grid">
		<?php if ( $published_courses ) : ?>
			<div class="meridian-stat-card">
				<strong><?php echo esc_html( number_format_i18n( $published_courses ) ); ?></strong>
				<span><?php esc_html_e( 'Published courses', 'atora-learning' ); ?></span>
			</div>
		<?php endif; ?>
		<?php if ( $students ) : ?>
			<div class="meridian-stat-card">
				<strong><?php echo esc_html( number_format_i18n( $students ) ); ?></strong>
				<span><?php esc_html_e( 'Students reached', 'atora-learning' ); ?></span>
			</div>
		<?php endif; ?>
	</div>
</section>
