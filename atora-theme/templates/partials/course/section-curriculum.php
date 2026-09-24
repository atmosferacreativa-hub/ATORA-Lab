<?php
/**
 * Commercial curriculum preview.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $lesson_ids ) ) {
	return;
}

$preview_max = isset( $preview_max ) ? absint( $preview_max ) : 0;
$shown       = 0;
?>

<section class="meridian-course-section meridian-course-section--curriculum">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Curriculum', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Preview the course before the student commits.', 'atora-learning' ); ?></h2>
	<ol class="meridian-course-section__list">
		<?php foreach ( $lesson_ids as $lesson_id ) : ?>
			<?php
			$lesson = get_post( $lesson_id );
			if ( ! $lesson || 'publish' !== $lesson->post_status ) {
				continue;
			}
			if ( $preview_max > 0 && $shown >= $preview_max ) {
				break;
			}
			++$shown;
			?>
			<li>
				<strong><?php echo esc_html( get_the_title( $lesson_id ) ); ?></strong>
				<?php $subtitle = (string) get_post_meta( $lesson_id, '_clms_lesson_subtitle', true ); ?>
				<?php if ( $subtitle ) : ?>
					<p><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>

	<?php if ( $preview_max > 0 && count( $lesson_ids ) > $preview_max ) : ?>
		<p><?php echo esc_html( sprintf( __( '+ %d more lessons unlock after enrollment.', 'atora-learning' ), count( $lesson_ids ) - $preview_max ) ); ?></p>
	<?php endif; ?>
</section>
