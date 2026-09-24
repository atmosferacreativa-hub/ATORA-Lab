<?php
/**
 * Podcast single template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$audio_url = atora_get_podcast_audio_url( get_the_ID() );
	?>
	<section class="meridian-podcast-hero">
		<div class="meridian-podcast-hero__copy" data-reveal>
			<p class="meridian-eyebrow"><?php esc_html_e( 'Podcast episode', 'atora-learning' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<div class="meridian-podcast-meta">
				<span><?php echo esc_html( get_the_date() ); ?></span>
				<span>
					<?php
					printf(
						/* translators: %d: reading time in minutes. */
						esc_html__( '%d min notes', 'atora-learning' ),
						atora_get_reading_time( get_the_ID() )
					);
					?>
				</span>
			</div>
			<?php if ( has_excerpt() ) : ?>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="meridian-featured-media" data-reveal>
				<?php the_post_thumbnail( 'atora-meridian-hero' ); ?>
			</div>
		<?php endif; ?>
	</section>

	<div class="meridian-podcast-shell">
		<?php if ( $audio_url ) : ?>
			<div class="meridian-podcast-player" data-reveal>
				<audio controls preload="metadata" src="<?php echo esc_url( $audio_url ); ?>"></audio>
			</div>
		<?php endif; ?>

		<article class="meridian-content-card meridian-richtext" data-reveal>
			<?php the_content(); ?>
		</article>
	</div>
	<?php
endwhile;

get_footer();
