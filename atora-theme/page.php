<?php
/**
 * Generic page template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$page_media = has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'atora-meridian-hero' ) : '';
	$settings   = atora_get_presentation_settings( get_the_ID() );
	ob_start();
	the_content();
	$page_content = (string) ob_get_clean();
	$has_editor_content = atora_post_has_editor_content( get_the_ID() );
	$has_visual_shell  = atora_presentation_requests_visual_shell( $settings );
	$content_first_page = $has_editor_content && ! $has_visual_shell;
	$actions    = atora_get_presentation_actions(
		$settings,
		array(
			array(
				'label' => __( 'Explore Courses', 'atora-learning' ),
				'url'   => atora_get_archive_link( 'lm_course' ),
				'kind'  => 'primary',
			),
			array(
				'label' => __( 'Open Store', 'atora-learning' ),
				'url'   => atora_get_shop_url(),
				'kind'  => 'ghost',
			),
		)
	);

	if ( ! $has_visual_shell ) {
		$settings['hide_hero']    = true;
		$settings['hide_sidebar'] = true;
	}
		?>
		<?php if ( empty( $settings['hide_hero'] ) ) : ?>
		<section class="meridian-page-hero">
			<div class="meridian-page-hero__grid">
				<div class="meridian-page-hero__copy" data-reveal>
					<p class="meridian-eyebrow"><?php echo esc_html( ! empty( $settings['eyebrow'] ) ? (string) $settings['eyebrow'] : atora_get_primary_label( get_the_ID() ) ); ?></p>
					<h1><?php the_title(); ?></h1>
					<?php if ( ! empty( $settings['intro'] ) ) : ?>
						<p><?php echo esc_html( (string) $settings['intro'] ); ?></p>
					<?php elseif ( has_excerpt() ) : ?>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php else : ?>
						<p><?php echo esc_html( atora_get_context_description() ); ?></p>
					<?php endif; ?>

					<div class="meridian-hero-actions">
						<?php foreach ( $actions as $action ) : ?>
							<a class="meridian-button meridian-button--<?php echo esc_attr( $action['kind'] ); ?>" href="<?php echo esc_url( $action['url'] ); ?>"><?php echo esc_html( $action['label'] ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>

			<?php if ( $page_media ) : ?>
				<div class="meridian-page-hero__panel" data-reveal>
					<div class="meridian-featured-media">
						<?php echo $page_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			<?php endif; ?>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( $has_editor_content && $content_first_page ) : ?>
			<section class="meridian-visual-canvas">
				<div class="meridian-visual-canvas__content meridian-richtext" data-reveal>
					<?php echo $page_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					wp_link_pages(
						array(
							'before' => '<div class="page-links">',
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</section>
		<?php elseif ( $has_editor_content ) : ?>
			<div class="meridian-content-shell">
				<div class="meridian-content-shell__inner<?php echo ! empty( $settings['hide_sidebar'] ) ? ' is-single-column' : ''; ?>">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'meridian-content-card meridian-richtext' ); ?> data-reveal>
					<?php echo $page_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					wp_link_pages(
						array(
							'before' => '<div class="page-links">',
							'after'  => '</div>',
						)
					);
					?>
				</article>

					<?php if ( empty( $settings['hide_sidebar'] ) ) : ?>
						<aside class="meridian-sidebar">
							<div class="meridian-sidebar-card" data-reveal>
								<h3><?php esc_html_e( 'Useful paths', 'atora-learning' ); ?></h3>
								<ul class="meridian-sidebar-list">
									<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></a></li>
									<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'Programs', 'atora-learning' ); ?></a></li>
									<li><a href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Store', 'atora-learning' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>"><?php esc_html_e( 'Podcast', 'atora-learning' ); ?></a></li>
								</ul>
							</div>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

	<?php
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
