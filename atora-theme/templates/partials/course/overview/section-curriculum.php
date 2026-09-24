<?php
/**
 * Course overview curriculum.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="meridian-overview-section">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Curriculum', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'A sharper academic list with clearer states.', 'atora-learning' ); ?></h2>

	<?php if ( empty( $lesson_ids ) ) : ?>
		<p><?php esc_html_e( 'This course does not have published lessons yet.', 'atora-learning' ); ?></p>
	<?php else : ?>
		<ol class="meridian-overview-curriculum">
			<?php foreach ( $lesson_ids as $lesson_id ) : ?>
				<?php
				$lesson = get_post( $lesson_id );
				if ( ! $lesson || 'publish' !== $lesson->post_status ) {
					continue;
				}
				$done       = in_array( $lesson_id, $completed, true );
				$can_access = $is_admin || (
					$user_id
					&& $is_enrolled
					&& class_exists( 'CLMS_Helper' )
					&& method_exists( 'CLMS_Helper', 'user_can_access_lesson' )
					&& CLMS_Helper::user_can_access_lesson( $user_id, $lesson_id )
				);
				$item_class = $done ? 'is-complete' : ( $can_access ? '' : 'is-locked' );
				?>
				<li class="<?php echo esc_attr( $item_class ); ?>">
					<strong><?php echo esc_html( get_the_title( $lesson_id ) ); ?></strong>
					<?php $subtitle = (string) get_post_meta( $lesson_id, '_clms_lesson_subtitle', true ); ?>
					<?php if ( $subtitle ) : ?>
						<p><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
					<?php if ( $can_access ) : ?>
						<a class="meridian-link" href="<?php echo esc_url( get_permalink( $lesson_id ) ); ?>">
							<?php echo esc_html( $done ? __( 'Review lesson', 'atora-learning' ) : __( 'Open lesson', 'atora-learning' ) ); ?>
						</a>
					<?php else : ?>
						<p class="meridian-meta-note"><?php esc_html_e( 'Locked until enrollment or unlock.', 'atora-learning' ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php endif; ?>
</section>
