<?php
/**
 * Program archive fallback.
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
		<p class="meridian-eyebrow"><?php esc_html_e( 'Programs', 'atora-learning' ); ?></p>
		<h1><?php esc_html_e( 'Structured learning paths with a stronger premium frame.', 'atora-learning' ); ?></h1>
		<p><?php esc_html_e( 'Programs are now presented with the same modern language used across landings, store and editorial pages.', 'atora-learning' ); ?></p>
	</div>
</section>

<div class="meridian-content-shell">
	<?php if ( have_posts() ) : ?>
		<div class="posts-grid">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article class="post-card" data-reveal>
					<div class="post-thumbnail">
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'atora-meridian-card' ); ?>
							<?php endif; ?>
						</a>
					</div>
					<div class="post-card__body">
						<div class="meridian-entry-meta">
							<span><?php esc_html_e( 'Program', 'atora-learning' ); ?></span>
						</div>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo esc_html( atora_get_plain_excerpt( get_the_ID(), 22 ) ); ?></p>
						<div class="entry-footer">
							<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Program', 'atora-learning' ); ?></a>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<?php atora_render_pagination(); ?>
	<?php else : ?>
		<div class="meridian-empty-state">
			<h2><?php esc_html_e( 'No programs available yet', 'atora-learning' ); ?></h2>
			<p><?php esc_html_e( 'Create the first academic path to activate this section.', 'atora-learning' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
