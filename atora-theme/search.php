<?php
/**
 * Search results template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="meridian-archive-hero">
	<div class="meridian-archive-hero__copy" data-reveal>
		<p class="meridian-eyebrow"><?php esc_html_e( 'Search', 'atora-learning' ); ?></p>
		<h1><?php echo esc_html( atora_get_context_title() ); ?></h1>
		<p><?php echo esc_html( atora_get_context_description() ); ?></p>
	</div>
</section>

<div class="meridian-search-shell">
	<div class="meridian-content-card" style="margin-bottom:1rem;" data-reveal>
		<?php get_search_form(); ?>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="posts-grid">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php if ( 'lm_course' === get_post_type() ) : ?>
					<?php get_template_part( 'template-parts/content', 'course' ); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>

		<?php atora_render_pagination(); ?>
	<?php else : ?>
		<div class="meridian-empty-state" data-reveal>
			<h2><?php esc_html_e( 'No results found', 'atora-learning' ); ?></h2>
			<p><?php esc_html_e( 'Try a broader search term or explore the catalog directly.', 'atora-learning' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
