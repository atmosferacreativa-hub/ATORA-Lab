<?php
/**
 * Teacher single fallback forwarder.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'ATORA_LMS_DIR' ) ) {
	$template = trailingslashit( ATORA_LMS_DIR ) . 'templates/instructor-profile.php';
	if ( file_exists( $template ) ) {
		include $template;
		return;
	}
}

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="meridian-page-hero">
		<div class="meridian-page-hero__grid">
			<div class="meridian-page-hero__copy">
				<p class="meridian-eyebrow"><?php esc_html_e( 'Instructor', 'atora-learning' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<p><?php echo esc_html( atora_get_plain_excerpt( get_the_ID(), 22 ) ); ?></p>
			</div>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="meridian-page-hero__panel">
					<div class="meridian-featured-media"><?php the_post_thumbnail( 'atora-meridian-card' ); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<div class="meridian-content-shell">
		<div class="meridian-content-card"><?php the_content(); ?></div>
	</div>
	<?php
endwhile;
get_footer();
