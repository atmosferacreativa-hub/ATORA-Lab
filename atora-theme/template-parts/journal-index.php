<?php
/**
 * Shared journal index layout.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$posts_page_id = (int) get_option( 'page_for_posts' );
$posts_page    = $posts_page_id ? get_post( $posts_page_id ) : null;
$settings      = $posts_page_id ? atora_get_presentation_settings( $posts_page_id ) : atora_get_presentation_defaults();
$blog_title    = $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Journal', 'atora-learning' );
$blog_copy     = ! empty( $settings['intro'] )
	? (string) $settings['intro']
	: ( $posts_page_id && has_excerpt( $posts_page_id )
		? get_the_excerpt( $posts_page_id )
		: __( 'Beautiful editorial publishing for announcements, essays, launches, podcast notes and brand stories.', 'atora-learning' ) );
$blog_media       = $posts_page_id && has_post_thumbnail( $posts_page_id ) ? get_the_post_thumbnail( $posts_page_id, 'atora-meridian-hero' ) : '';
$blog_content     = $posts_page instanceof WP_Post ? (string) $posts_page->post_content : '';
$has_blog_content   = $posts_page_id ? atora_post_has_editor_content( $posts_page_id ) : false;
$has_visual_shell   = atora_presentation_requests_visual_shell( $settings );
$content_first_blog = $has_blog_content && ! $has_visual_shell;

if ( ! $has_visual_shell ) {
	$settings['hide_hero'] = true;
}
?>

<?php if ( empty( $settings['hide_hero'] ) ) : ?>
<section class="meridian-archive-hero">
	<div class="meridian-page-hero__grid">
		<div class="meridian-archive-hero__copy" data-reveal>
			<p class="meridian-eyebrow"><?php echo esc_html( ! empty( $settings['eyebrow'] ) ? (string) $settings['eyebrow'] : __( 'Editorial', 'atora-learning' ) ); ?></p>
			<h1><?php echo esc_html( $blog_title ); ?></h1>
			<p><?php echo esc_html( $blog_copy ); ?></p>
		</div>

		<?php if ( $blog_media ) : ?>
			<div class="meridian-page-hero__panel" data-reveal>
				<div class="meridian-featured-media">
					<?php echo $blog_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php if ( $posts_page instanceof WP_Post && $has_blog_content ) : ?>
<section class="<?php echo esc_attr( $content_first_blog ? 'meridian-visual-canvas' : 'meridian-content-shell' ); ?>">
	<div class="<?php echo esc_attr( $content_first_blog ? 'meridian-visual-canvas__content meridian-richtext' : 'meridian-content-card meridian-richtext' ); ?>" data-reveal>
		<?php
		$previous_post = $GLOBALS['post'] ?? null;
		$GLOBALS['post'] = $posts_page;
		setup_postdata( $posts_page );

		echo apply_filters( 'the_content', $blog_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		wp_reset_postdata();
		$GLOBALS['post'] = $previous_post;
		?>
	</div>
</section>
<?php endif; ?>

<div class="meridian-content-shell">
	<?php if ( have_posts() ) : ?>
		<div class="posts-grid">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
			<?php endwhile; ?>
		</div>

		<?php atora_render_pagination(); ?>
	<?php else : ?>
		<div class="meridian-empty-state" data-reveal>
			<h2><?php esc_html_e( 'No stories yet', 'atora-learning' ); ?></h2>
			<p><?php esc_html_e( 'Publish the first article and this new editorial shell will take it from there.', 'atora-learning' ); ?></p>
		</div>
	<?php endif; ?>
</div>
