<?php
/**
 * Generic content card.
 *
 * @package Atora_Learning
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card meridian-post-card' ); ?> data-reveal>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'atora-meridian-card' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-card__body">
		<div class="meridian-entry-meta">
			<span><?php echo esc_html( atora_get_primary_label( get_the_ID() ) ); ?></span>
			<span><?php echo esc_html( get_the_date() ); ?></span>
		</div>

		<?php if ( is_singular() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php endif; ?>

		<div class="entry-content">
			<?php if ( is_singular() ) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<p><?php echo esc_html( atora_get_plain_excerpt( get_the_ID(), 24 ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! is_singular() ) : ?>
			<footer class="entry-footer">
				<a class="meridian-button meridian-button--ghost meridian-button--small" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read story', 'atora-learning' ); ?></a>
			</footer>
		<?php endif; ?>
	</div>
</article>
