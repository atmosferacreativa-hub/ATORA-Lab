<?php
/**
 * 404 template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="meridian-404-shell">
	<div class="meridian-empty-state" data-reveal>
		<p class="meridian-eyebrow"><?php esc_html_e( '404', 'atora-learning' ); ?></p>
		<h1><?php esc_html_e( 'This route is no longer part of the new map.', 'atora-learning' ); ?></h1>
		<p><?php esc_html_e( 'The page may have moved, the URL may be outdated, or the section has been redesigned.', 'atora-learning' ); ?></p>
		<div class="meridian-hero-actions">
			<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return home', 'atora-learning' ); ?></a>
			<a class="meridian-button meridian-button--ghost" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Browse courses', 'atora-learning' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
