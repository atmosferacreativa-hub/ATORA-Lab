<?php
/**
 * Single post template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$author_id    = (int) get_post_field( 'post_author', get_the_ID() );
	$settings     = atora_get_presentation_settings( get_the_ID() );
	$recent_posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'post__not_in'   => array( get_the_ID() ),
			'no_found_rows'  => true,
		)
		);
		?>
		<?php if ( empty( $settings['hide_hero'] ) ) : ?>
		<section class="meridian-single-hero">
			<div class="meridian-single-hero__copy" data-reveal>
				<p class="meridian-eyebrow"><?php echo esc_html( ! empty( $settings['eyebrow'] ) ? (string) $settings['eyebrow'] : atora_get_primary_label( get_the_ID() ) ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php atora_render_entry_meta( get_the_ID() ); ?>
				<?php if ( ! empty( $settings['intro'] ) ) : ?>
					<p><?php echo esc_html( (string) $settings['intro'] ); ?></p>
				<?php elseif ( has_excerpt() ) : ?>
					<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="meridian-featured-media" data-reveal>
				<?php the_post_thumbnail( 'atora-meridian-hero' ); ?>
			</div>
			<?php endif; ?>
		</section>
		<?php endif; ?>

		<div class="meridian-article-shell meridian-editorial-layout">
			<div class="meridian-single-layout<?php echo ! empty( $settings['hide_sidebar'] ) ? ' is-single-column' : ''; ?>">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'meridian-article' ); ?> data-reveal>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>

				<div class="meridian-author-card">
					<div><?php echo get_avatar( $author_id, 72 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div>
						<h3><?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></h3>
						<p><?php echo esc_html( get_the_author_meta( 'description', $author_id ) ); ?></p>
					</div>
				</div>
			</article>

				<?php if ( empty( $settings['hide_sidebar'] ) ) : ?>
					<aside class="meridian-sidebar">
						<div class="meridian-sidebar-card" data-reveal>
							<h3><?php esc_html_e( 'Recent stories', 'atora-learning' ); ?></h3>
							<ul class="meridian-sidebar-list">
								<?php foreach ( $recent_posts as $recent_post ) : ?>
									<li><a href="<?php echo esc_url( get_permalink( $recent_post ) ); ?>"><?php echo esc_html( get_the_title( $recent_post ) ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>

						<div class="meridian-sidebar-card" data-reveal>
							<h3><?php esc_html_e( 'Keep exploring', 'atora-learning' ); ?></h3>
							<ul class="meridian-sidebar-list">
								<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></a></li>
								<li><a href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Store', 'atora-learning' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>"><?php esc_html_e( 'Podcast', 'atora-learning' ); ?></a></li>
							</ul>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</div>

	<?php
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
