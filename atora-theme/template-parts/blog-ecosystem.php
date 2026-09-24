<?php
/**
 * Blog layout: hero, category chips, post grid and ecosystem sidebar.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$posts_page_id = (int) get_option( 'page_for_posts' );
$blog_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/blog/' );
$paged         = max( 1, (int) get_query_var( 'paged' ) );
$current_term  = ( is_category() || is_tag() ) ? get_queried_object() : null;

if ( $current_term instanceof WP_Term ) {
	$eyebrow = is_category() ? 'Tema editorial' : 'Etiqueta';
	$title   = $current_term->name;
	$intro   = $current_term->description
		? wp_strip_all_tags( $current_term->description )
		: sprintf( 'Artículos de ATORA Lab sobre %s: ideas, guías y recursos para aplicar en tu trabajo.', mb_strtolower( $current_term->name ) );
} else {
	$eyebrow = 'Blog · ATORA Lab';
	$title   = 'Ideas para mirar, crear y aprender mejor.';
	$intro   = 'Ensayos, guías, entrevistas y recursos académicos sobre comunicación, fotografía y educación digital, escritos por los mentores de la academia.';
}

$latest = get_posts(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
	)
);

$categories = atora_blog_ecosystem_categories();
$stats      = atora_blog_ecosystem_stats();
$courses    = function_exists( 'atora_get_archive_link' ) ? atora_get_archive_link( 'lm_course' ) : home_url( '/cursos/' );
$eco        = atora_blog_ecosystem_data();
?>

<div class="atora-blog">
	<section class="atora-blog-hero">
		<div class="atora-blog-shell atora-blog-hero__grid">
			<div class="atora-blog-hero__copy">
				<p class="atora-blog-eyebrow is-on-dark"><?php echo esc_html( $eyebrow ); ?></p>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p class="atora-blog-hero__intro"><?php echo esc_html( $intro ); ?></p>

				<div class="atora-blog-actions">
					<a class="atora-blog-button is-amber" href="<?php echo esc_url( $courses ); ?>">Explorar cursos</a>
					<a class="atora-blog-button is-outline" href="<?php echo esc_url( $eco['links']['ecosystem'] ); ?>" target="_blank" rel="noopener">Conocer el ecosistema ATORA</a>
				</div>

				<?php if ( $stats && ! $current_term ) : ?>
					<ul class="atora-blog-stats">
						<?php foreach ( $stats as $stat ) : ?>
							<li><strong><?php echo esc_html( number_format_i18n( $stat['value'] ) ); ?></strong><span><?php echo esc_html( $stat['label'] ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( $latest ) : ?>
				<aside class="atora-blog-panel" aria-label="Lo más reciente">
					<div class="atora-blog-panel__head">
						<strong>Lo más reciente</strong>
						<span class="atora-blog-badge">Actualizado <?php echo esc_html( get_the_date( 'j M', $latest[0] ) ); ?></span>
					</div>
					<ol class="atora-blog-panel__list">
						<?php foreach ( $latest as $item ) : ?>
							<?php $item_cat = atora_blog_ecosystem_primary_category( $item->ID ); ?>
							<li>
								<a href="<?php echo esc_url( get_permalink( $item ) ); ?>">
									<span class="atora-blog-panel__thumb">
										<?php if ( has_post_thumbnail( $item ) ) : ?>
											<?php echo get_the_post_thumbnail( $item, 'thumbnail', array( 'alt' => '' ) ); ?>
										<?php endif; ?>
									</span>
									<span class="atora-blog-panel__text">
										<span class="atora-blog-panel__title"><?php echo esc_html( get_the_title( $item ) ); ?></span>
										<span class="atora-blog-panel__meta"><?php echo esc_html( get_the_date( '', $item ) ); ?><?php echo $item_cat ? ' · ' . esc_html( $item_cat->name ) : ''; ?></span>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ol>
				</aside>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $categories ) : ?>
		<nav class="atora-blog-shell atora-blog-topics" aria-label="Temas del blog">
			<a class="atora-blog-topic<?php echo $current_term ? '' : ' is-active'; ?>" href="<?php echo esc_url( $blog_url ); ?>">Todos</a>
			<?php foreach ( $categories as $category ) : ?>
				<?php $active = $current_term instanceof WP_Term && (int) $current_term->term_id === (int) $category->term_id; ?>
				<a class="atora-blog-topic<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?> <span><?php echo esc_html( number_format_i18n( $category->count ) ); ?></span></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<div class="atora-blog-shell atora-blog-layout">
		<div class="atora-blog-main">
			<p class="atora-blog-kicker"><?php echo esc_html( $paged > 1 ? sprintf( 'Artículos · página %d', $paged ) : 'Artículos' ); ?></p>

			<?php if ( have_posts() ) : ?>
				<div class="atora-blog-grid">
					<?php
					$index = 0;
					while ( have_posts() ) :
						the_post();
						atora_blog_ecosystem_card( get_post(), 0 === $index );
						++$index;
					endwhile;
					?>
				</div>

				<div class="atora-blog-pagination">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 1,
							'prev_text' => '← Anteriores',
							'next_text' => 'Siguientes →',
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="atora-blog-empty">
					<h2>Todavía no hay artículos aquí</h2>
					<p>Vuelve pronto o explora los demás temas del blog.</p>
				</div>
			<?php endif; ?>
		</div>

		<?php get_template_part( 'template-parts/blog', 'sidebar', array( 'eco' => $eco ) ); ?>
	</div>

	<section class="atora-blog-final">
		<div class="atora-blog-shell">
			<p class="atora-blog-eyebrow is-on-dark">Siguiente paso</p>
			<h2>Lo que lees aquí, se aprende en la academia.</h2>
			<p>Cursos con mentores reales, rúbricas claras y acompañamiento en cada entrega. Todo funciona sobre el ecosistema ATORA.</p>
			<div class="atora-blog-actions">
				<a class="atora-blog-button is-amber" href="<?php echo esc_url( $courses ); ?>">Ver cursos</a>
				<a class="atora-blog-button is-outline" href="<?php echo esc_url( $eco['links']['demo'] ); ?>" target="_blank" rel="noopener">Solicitar una demo de ATORA</a>
			</div>
		</div>
	</section>
</div>
