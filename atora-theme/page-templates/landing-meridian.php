<?php
/**
 * Template Name: Meridian Landing
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$hero_media = has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'atora-meridian-hero' ) : '';
	$settings   = atora_get_presentation_settings( get_the_ID() );
	ob_start();
	the_content();
	$landing_content = (string) ob_get_clean();
	$has_editor_content = atora_post_has_editor_content( get_the_ID() );
	$has_visual_shell  = atora_presentation_requests_visual_shell( $settings );
	$content_first_landing = $has_editor_content && ! $has_visual_shell;
	$actions    = atora_get_presentation_actions(
		$settings,
		array(
			array(
				'label' => __( 'See offer', 'atora-learning' ),
				'url'   => atora_get_archive_link( 'lm_course' ),
				'kind'  => 'primary',
			),
			array(
				'label' => __( 'Go to store', 'atora-learning' ),
				'url'   => atora_get_shop_url(),
				'kind'  => 'ghost',
			),
		)
	);

	if ( ! $has_visual_shell ) {
		$settings['hide_hero'] = true;
	}
		?>
		<?php if ( empty( $settings['hide_hero'] ) ) : ?>
		<section class="meridian-page-hero">
			<div class="meridian-page-hero__grid">
				<div class="meridian-page-hero__copy" data-reveal>
					<p class="meridian-eyebrow"><?php echo esc_html( ! empty( $settings['eyebrow'] ) ? (string) $settings['eyebrow'] : __( 'Landing page', 'atora-learning' ) ); ?></p>
					<h1><?php the_title(); ?></h1>
					<p><?php echo esc_html( ! empty( $settings['intro'] ) ? (string) $settings['intro'] : ( has_excerpt() ? get_the_excerpt() : atora_get_plain_excerpt( get_the_ID(), 32 ) ) ); ?></p>
					<div class="meridian-hero-actions">
						<?php foreach ( $actions as $action ) : ?>
							<a class="meridian-button meridian-button--<?php echo esc_attr( $action['kind'] ); ?>" href="<?php echo esc_url( $action['url'] ); ?>"><?php echo esc_html( $action['label'] ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>

			<?php if ( $hero_media ) : ?>
				<div class="meridian-page-hero__panel" data-reveal>
					<div class="meridian-featured-media">
						<?php echo $hero_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			<?php endif; ?>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( $has_editor_content ) : ?>
			<section class="<?php echo esc_attr( $content_first_landing ? 'meridian-visual-canvas' : 'meridian-content-shell' ); ?>">
				<div class="<?php echo esc_attr( $content_first_landing ? 'meridian-visual-canvas__content meridian-richtext' : 'meridian-content-card meridian-richtext' ); ?>" data-reveal>
					<?php echo $landing_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</section>
		<?php endif; ?>
	<?php
endwhile;

get_footer();
