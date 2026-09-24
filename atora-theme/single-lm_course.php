<?php
/**
 * Course single fallback forwarder.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'ATORA_LMS_DIR' ) ) {
	// template_include in ATORA_v5 should normally resolve the correct course template
	// before WordPress falls back to this theme file. Keep this file as a minimal safety net.
	$plugin_template = trailingslashit( ATORA_LMS_DIR ) . 'templates/single-course.php';

	if ( file_exists( $plugin_template ) ) {
		include $plugin_template;
		return;
	}
}

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="meridian-page-hero">
		<div class="meridian-page-hero__copy">
			<p class="meridian-eyebrow"><?php esc_html_e( 'Course', 'atora-learning' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p><?php echo esc_html( atora_get_plain_excerpt( get_the_ID(), 26 ) ); ?></p>
		</div>
	</section>
	<div class="meridian-content-shell">
		<div class="meridian-content-card"><?php the_content(); ?></div>
	</div>
	<?php
endwhile;
get_footer();
