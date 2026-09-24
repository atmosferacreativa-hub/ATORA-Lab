<?php
/**
 * Related offers.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) || empty( $related_items ) ) {
	return;
}
?>

<section class="meridian-course-section meridian-course-section--related">
	<p class="meridian-eyebrow"><?php esc_html_e( 'Related offers', 'atora-learning' ); ?></p>
	<h2><?php esc_html_e( 'Cross-sell should feel curated, not bolted on.', 'atora-learning' ); ?></h2>
	<div class="meridian-grid meridian-grid--3">
		<?php foreach ( $related_items as $item ) : ?>
			<?php
			$type_raw   = isset( $item['type'] ) ? strtolower( (string) $item['type'] ) : 'offer';
			$type_label = isset( $related_type_labels[ $type_raw ] ) ? (string) $related_type_labels[ $type_raw ] : ucfirst( $type_raw );
			?>
			<article class="meridian-story-card">
				<p class="meridian-meta-note"><?php echo esc_html( $type_label ); ?></p>
				<h3><?php echo esc_html( (string) ( $item['title'] ?? '' ) ); ?></h3>
				<?php if ( ! empty( $item['subtitle'] ) ) : ?>
					<p><?php echo esc_html( (string) $item['subtitle'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $item['price'] ) ) : ?>
					<p class="meridian-meta-note"><?php echo esc_html( (string) $item['price'] ); ?></p>
				<?php endif; ?>
				<a class="meridian-link" href="<?php echo esc_url( (string) $item['url'] ); ?>"><?php esc_html_e( 'View detail', 'atora-learning' ); ?></a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
