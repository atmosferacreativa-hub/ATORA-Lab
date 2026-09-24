<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#site-main"><?php esc_html_e( 'Skip to content', 'atora-learning' ); ?></a>

<header class="meridian-header" data-site-header>
	<div class="meridian-header__rail">
		<div class="meridian-shell meridian-header__rail-inner">
			<p class="meridian-header__signal"><?php esc_html_e( 'Commercial polish, academic clarity and WooCommerce-ready growth.', 'atora-learning' ); ?></p>

			<div class="meridian-header__rail-nav">
				<?php if ( has_nav_menu( 'top' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'top',
							'container'      => false,
							'menu_class'     => 'meridian-mini-menu',
							'fallback_cb'    => 'atora_nav_fallback',
						)
					);
					?>
				<?php else : ?>
					<nav aria-label="<?php esc_attr_e( 'Utility navigation', 'atora-learning' ); ?>">
						<ul class="meridian-mini-menu">
							<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></a></li>
							<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'Programs', 'atora-learning' ); ?></a></li>
							<li><a href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Store', 'atora-learning' ); ?></a></li>
							<li><a href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Journal', 'atora-learning' ); ?></a></li>
						</ul>
					</nav>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="meridian-shell meridian-header__main">
		<div class="meridian-header__row meridian-header__row--top">
			<div class="meridian-header__brand">
				<?php echo atora_get_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<button class="meridian-header__toggle" type="button" data-mobile-toggle aria-controls="site-navigation" aria-expanded="false">
				<span></span>
				<span></span>
				<span></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'atora-learning' ); ?></span>
			</button>

			<div class="meridian-header__actions">
				<button class="meridian-action meridian-action--search" type="button" data-search-toggle aria-expanded="false" aria-controls="site-search-panel">
					<?php esc_html_e( 'Search', 'atora-learning' ); ?>
				</button>
				<a class="meridian-action" href="<?php echo esc_url( atora_get_account_url() ); ?>"><?php esc_html_e( 'Account', 'atora-learning' ); ?></a>
				<a class="meridian-action meridian-action--cart" href="<?php echo esc_url( atora_get_cart_url() ); ?>">
					<?php esc_html_e( 'Cart', 'atora-learning' ); ?>
					<span class="meridian-cart-count"><?php echo esc_html( number_format_i18n( atora_get_cart_count() ) ); ?></span>
				</a>
				<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php echo esc_url( atora_get_primary_cta_url() ); ?>">
					<?php echo esc_html( atora_get_primary_cta_label() ); ?>
				</a>
			</div>
		</div>

		<div class="meridian-header__row meridian-header__row--nav">
			<nav id="site-navigation" class="meridian-header__nav" data-navigation aria-label="<?php esc_attr_e( 'Primary navigation', 'atora-learning' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'meridian-menu',
						'fallback_cb'    => 'atora_nav_fallback',
					)
				);
				?>

				<div class="meridian-header__nav-actions">
					<a class="meridian-action" href="<?php echo esc_url( atora_get_account_url() ); ?>"><?php esc_html_e( 'Account', 'atora-learning' ); ?></a>
					<a class="meridian-action meridian-action--cart" href="<?php echo esc_url( atora_get_cart_url() ); ?>">
						<?php esc_html_e( 'Cart', 'atora-learning' ); ?>
						<span class="meridian-cart-count"><?php echo esc_html( number_format_i18n( atora_get_cart_count() ) ); ?></span>
					</a>
					<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php echo esc_url( atora_get_primary_cta_url() ); ?>">
						<?php echo esc_html( atora_get_primary_cta_label() ); ?>
					</a>
				</div>
			</nav>
		</div>
	</div>

	<div id="site-search-panel" class="meridian-search-panel" data-search-panel hidden>
		<div class="meridian-shell meridian-search-panel__inner">
			<div class="meridian-search-panel__copy">
				<p class="meridian-eyebrow"><?php esc_html_e( 'Search the ecosystem', 'atora-learning' ); ?></p>
				<h2><?php esc_html_e( 'Find courses, products, posts and podcast episodes without friction.', 'atora-learning' ); ?></h2>
			</div>

			<div class="meridian-search-panel__form">
				<?php get_search_form(); ?>
				<button class="meridian-button meridian-button--ghost meridian-button--small" type="button" data-search-close>
					<?php esc_html_e( 'Close', 'atora-learning' ); ?>
				</button>
			</div>
		</div>
	</div>
</header>

<main id="site-main" class="site-main">
