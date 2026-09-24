<?php
/**
 * WooCommerce wrapper template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="meridian-shop-hero">
	<div class="meridian-shop-hero__grid">
		<div class="meridian-shop-hero__copy" data-reveal>
			<p class="meridian-eyebrow"><?php esc_html_e( 'Store', 'atora-learning' ); ?></p>
			<h1><?php echo esc_html( wp_strip_all_tags( atora_get_context_title() ) ); ?></h1>
			<p><?php echo esc_html( atora_get_context_description() ); ?></p>
		</div>

		<div class="meridian-dashboard-panel" data-reveal>
			<h3><?php esc_html_e( 'Commercial layer ready', 'atora-learning' ); ?></h3>
			<p><?php esc_html_e( 'Products, bundles and LMS-linked offers now sit inside the same premium brand rhythm as the rest of the site.', 'atora-learning' ); ?></p>
			<div class="meridian-hero-actions">
				<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></a>
				<a class="meridian-button meridian-button--ghost meridian-button--small" href="<?php echo esc_url( atora_get_account_url() ); ?>"><?php esc_html_e( 'Account', 'atora-learning' ); ?></a>
			</div>
		</div>
	</div>
</section>

<div class="meridian-shop-shell">
	<div class="meridian-shop-panel" data-reveal>
		<?php woocommerce_content(); ?>
	</div>
</div>

<?php
get_footer();
