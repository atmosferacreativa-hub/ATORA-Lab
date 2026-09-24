	</main>

	<footer class="meridian-footer">
		<div class="meridian-shell">
			<div class="meridian-footer__grid">
				<div class="meridian-footer__brand">
					<?php echo atora_get_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<p><?php esc_html_e( 'A refined publishing and commerce shell designed to make ATORA feel faster, clearer and more premium across every touchpoint.', 'atora-learning' ); ?></p>
				</div>

				<div>
					<h3><?php esc_html_e( 'Learning', 'atora-learning' ); ?></h3>
					<ul class="meridian-footer__links">
						<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'Programs', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>"><?php esc_html_e( 'Student Dashboard', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( atora_get_account_url() ); ?>"><?php esc_html_e( 'Account', 'atora-learning' ); ?></a></li>
					</ul>
				</div>

				<div>
					<h3><?php esc_html_e( 'Commerce', 'atora-learning' ); ?></h3>
					<ul class="meridian-footer__links">
						<li><a href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Shop', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( atora_get_cart_url() ); ?>"><?php esc_html_e( 'Cart', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>"><?php esc_html_e( 'Podcast', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Journal', 'atora-learning' ); ?></a></li>
					</ul>
				</div>

				<div>
					<h3><?php esc_html_e( 'Navigation', 'atora-learning' ); ?></h3>
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'menu_class'     => 'meridian-footer__links',
								'fallback_cb'    => 'atora_nav_fallback',
							)
						);
						?>
					<?php else : ?>
						<ul class="meridian-footer__links">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'atora-learning' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'atora-learning' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'atora-learning' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/support/' ) ); ?>"><?php esc_html_e( 'Support', 'atora-learning' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
			</div>

			<div class="meridian-footer__bottom">
				<p>
					<?php
					printf(
						/* translators: %d: current year. */
						esc_html__( '© %d ATORA. Meridian theme system.', 'atora-learning' ),
						(int) gmdate( 'Y' )
					);
					?>
				</p>
			</div>

			<div class="meridian-footer__legal-row" aria-label="<?php esc_attr_e( 'Legal navigation', 'atora-learning' ); ?>">
				<?php if ( has_nav_menu( 'legal' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'legal',
							'container'      => false,
							'menu_class'     => 'meridian-mini-menu',
							'fallback_cb'    => 'atora_nav_fallback',
						)
					);
					?>
				<?php else : ?>
					<ul class="meridian-mini-menu">
						<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'atora-learning' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
