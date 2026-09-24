<?php
/**
 * Blog sidebar: the ATORA ecosystem (plugin, theme, AI copilots).
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eco = isset( $args['eco'] ) ? $args['eco'] : atora_blog_ecosystem_data();
?>

<aside class="atora-blog-sidebar" aria-label="Ecosistema ATORA">

	<section class="atora-eco-card is-dark">
		<p class="atora-blog-eyebrow is-on-dark">Ecosistema ATORA</p>
		<h3>Capta, enseña y certifica sin salir de tu sitio.</h3>
		<p>Un plugin LMS, un tema diseñado para educación y copilotos de IA que comparten los mismos datos, en el WordPress de tu institución.</p>
		<ol class="atora-eco-flow">
			<?php foreach ( $eco['flow'] as $step_index => $step ) : ?>
				<li><span><?php echo esc_html( $step_index + 1 ); ?></span><?php echo esc_html( $step ); ?></li>
			<?php endforeach; ?>
		</ol>
		<a class="atora-eco-link is-on-dark" href="<?php echo esc_url( $eco['links']['ecosystem'] ); ?>" target="_blank" rel="noopener">Ver el ecosistema completo →</a>
	</section>

	<section class="atora-eco-card">
		<div class="atora-eco-card__head">
			<svg class="atora-eco-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3.8L20 7.8L12 11.8L4 7.8L12 3.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M20 11.2L12 15.2L4 11.2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 14.6L12 18.6L4 14.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
			<div>
				<p class="atora-eco-card__kicker">El plugin</p>
				<h3>ATORA LMS + CRM</h3>
			</div>
		</div>
		<p>Un solo flujo para captar, matricular, enseñar, evaluar y acompañar, sin plugins sueltos que mantener.</p>
		<?php foreach ( $eco['plugin'] as $group => $features ) : ?>
			<p class="atora-eco-group"><?php echo esc_html( $group ); ?></p>
			<ul class="atora-eco-checks">
				<?php foreach ( $features as $feature ) : ?>
					<li><?php echo esc_html( $feature ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
		<a class="atora-eco-link" href="<?php echo esc_url( $eco['links']['features'] ); ?>" target="_blank" rel="noopener">Todas las funciones →</a>
	</section>

	<section class="atora-eco-card">
		<div class="atora-eco-card__head">
			<svg class="atora-eco-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4.5 6.5C4.5 5.395 5.395 4.5 6.5 4.5H17.5C18.605 4.5 19.5 5.395 19.5 6.5V17.5C19.5 18.605 18.605 19.5 17.5 19.5H6.5C5.395 19.5 4.5 18.605 4.5 17.5V6.5Z" stroke="currentColor" stroke-width="1.7"/><path d="M4.5 9H19.5" stroke="currentColor" stroke-width="1.7"/><path d="M9 9V19.5" stroke="currentColor" stroke-width="1.7"/></svg>
			<div>
				<p class="atora-eco-card__kicker">El tema</p>
				<h3>Tema ATORA, diseñado para educación</h3>
			</div>
		</div>
		<ul class="atora-eco-features">
			<?php foreach ( $eco['theme'] as $item ) : ?>
				<li><strong><?php echo esc_html( $item[0] ); ?></strong><span><?php echo esc_html( $item[1] ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class="atora-eco-card is-amber">
		<div class="atora-eco-card__head">
			<svg class="atora-eco-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3L13.9 8.1L19 10L13.9 11.9L12 17L10.1 11.9L5 10L10.1 8.1L12 3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M18.5 15.5L19.3 17.7L21.5 18.5L19.3 19.3L18.5 21.5L17.7 19.3L15.5 18.5L17.7 17.7L18.5 15.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
			<div>
				<p class="atora-eco-card__kicker">Inteligencia artificial</p>
				<h3>Cuatro copilotos con una tarea concreta</h3>
			</div>
		</div>
		<ul class="atora-eco-copilots">
			<?php foreach ( $eco['copilots'] as $copilot ) : ?>
				<li><strong><?php echo esc_html( $copilot[0] ); ?></strong><span><?php echo esc_html( $copilot[1] ); ?></span></li>
			<?php endforeach; ?>
		</ul>
		<p class="atora-eco-note">Cada copiloto opera en modo manual, asistido, híbrido o automático. El docente siempre revisa y decide.</p>
		<a class="atora-eco-link" href="<?php echo esc_url( $eco['links']['ai'] ); ?>" target="_blank" rel="noopener">Conocer los copilotos →</a>
	</section>

	<section class="atora-eco-card">
		<p class="atora-eco-card__kicker">Además</p>
		<ul class="atora-eco-features is-compact">
			<?php foreach ( $eco['extras'] as $item ) : ?>
				<li><strong><?php echo esc_html( $item[0] ); ?></strong><span><?php echo esc_html( $item[1] ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class="atora-eco-card is-cta">
		<h3>¿Tu institución necesita algo así?</h3>
		<p>Conversemos sobre tus estudiantes, tus objetivos y tu presupuesto. ATORA crece desde ahí.</p>
		<a class="atora-blog-button is-blue" href="<?php echo esc_url( $eco['links']['demo'] ); ?>" target="_blank" rel="noopener">Solicitar una demo</a>
	</section>
</aside>
