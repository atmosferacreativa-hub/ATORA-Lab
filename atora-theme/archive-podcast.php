<?php
/**
 * Podcast archive template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="meridian-podcast-hero">
	<div class="meridian-podcast-hero__copy" data-reveal>
		<p class="meridian-eyebrow"><?php esc_html_e( 'Podcast', 'atora-learning' ); ?></p>
		<h1><?php echo esc_html( wp_strip_all_tags( atora_get_context_title() ) ); ?></h1>
		<p><?php echo esc_html( atora_get_context_description() ); ?></p>
	</div>
</section>

<div class="meridian-podcast-shell">
	<?php if ( have_posts() ) : ?>
		<div class="meridian-grid meridian-grid--2">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article class="meridian-episode-card" data-reveal>
					<div class="meridian-episode-card__media">
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'atora-meridian-card' ); ?>
							<?php endif; ?>
						</a>
					</div>
					<div class="meridian-episode-card__body">
						<div class="meridian-podcast-meta">
							<span><?php echo esc_html( atora_get_primary_label( get_the_ID() ) ); ?></span>
							<span><?php echo esc_html( get_the_date() ); ?></span>
						</div>
						<h2 class="meridian-episode-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="meridian-episode-card__excerpt"><?php echo esc_html( atora_get_plain_excerpt( get_the_ID(), 24 ) ); ?></p>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<?php atora_render_pagination(); ?>
	<?php else : ?>
		<div class="meridian-empty-state">
			<h2><?php esc_html_e( 'No episodes published yet', 'atora-learning' ); ?></h2>
			<p><?php esc_html_e( 'As soon as the first episode is live, Meridian will present it beautifully here.', 'atora-learning' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
