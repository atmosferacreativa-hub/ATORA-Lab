<?php
/**
 * Curriculum accordion.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $lesson_ids ) ) {
	return;
}
?>

<section class="meridian-course-section meridian-course-section--curriculum">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Curriculum', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'A more editorial preview of the learning path.', 'atora-learning' ); ?></h2>
	<details open>
		<summary><?php esc_html_e( 'Open course outline', 'atora-learning' ); ?></summary>
		<ol class="meridian-course-section__list" style="margin-top:1rem;">
			<?php foreach ( $lesson_ids as $lesson_id ) : ?>
				<?php if ( 'publish' !== get_post_status( $lesson_id ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<li><strong><?php echo esc_html( get_the_title( $lesson_id ) ); ?></strong></li>
			<?php endforeach; ?>
		</ol>
	</details>
</section>
