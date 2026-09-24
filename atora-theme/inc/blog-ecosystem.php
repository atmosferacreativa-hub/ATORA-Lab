<?php
/**
 * Blog layout aligned with the institutional home: hero, post grid and an
 * ecosystem sidebar (ATORA LMS plugin, ATORA theme and AI copilots).
 *
 * Used by home.php (posts page), category.php and tag.php.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the current request renders the ecosystem blog layout.
 *
 * @return bool
 */
function atora_is_blog_ecosystem_view() {
	return is_home() || is_category() || is_tag();
}

/**
 * Posts per page for the blog layout: one featured card plus an even grid.
 *
 * @param WP_Query $query Query.
 * @return void
 */
function atora_blog_ecosystem_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() || $query->is_category() || $query->is_tag() ) {
		$query->set( 'posts_per_page', 11 );
	}
}
add_action( 'pre_get_posts', 'atora_blog_ecosystem_query' );

/**
 * Enqueue the blog layout stylesheet.
 *
 * @return void
 */
function atora_blog_ecosystem_enqueue() {
	if ( ! atora_is_blog_ecosystem_view() ) {
		return;
	}

	wp_enqueue_style(
		'atora-blog-ecosystem',
		ATORA_THEME_URI . '/assets/css/blog-ecosystem.css',
		array( 'atora-style' ),
		ATORA_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'atora_blog_ecosystem_enqueue', 20 );

/**
 * Categories shown as chips (most used first, generic ones excluded).
 *
 * @param int $limit Max chips.
 * @return WP_Term[]
 */
function atora_blog_ecosystem_categories( $limit = 9 ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	$default = (int) get_option( 'default_category' );
	$terms   = array_filter(
		$terms,
		static function ( $term ) use ( $default ) {
			return (int) $term->term_id !== $default && 'blog' !== $term->slug;
		}
	);

	return array_slice( array_values( $terms ), 0, $limit );
}

/**
 * Hero stats for the blog.
 *
 * @return array<int,array{value:int,label:string}>
 */
function atora_blog_ecosystem_stats() {
	$count = static function ( $type ) {
		return post_type_exists( $type ) ? (int) wp_count_posts( $type )->publish : 0;
	};

	$stats = array(
		array( 'value' => $count( 'post' ), 'label' => 'artículos publicados' ),
		array( 'value' => count( atora_blog_ecosystem_categories( 99 ) ), 'label' => 'temas editoriales' ),
		array( 'value' => $count( 'lm_course' ), 'label' => 'cursos en la academia' ),
		array( 'value' => $count( 'atora_teacher' ), 'label' => 'mentores' ),
	);

	return array_values( array_filter( $stats, static function ( $stat ) {
		return $stat['value'] > 0;
	} ) );
}

/**
 * Ecosystem content for the sidebar (source: atora.studio).
 *
 * @return array<string,mixed>
 */
function atora_blog_ecosystem_data() {
	$site = 'https://atora.studio/';

	$data = array(
		'links'   => array(
			'ecosystem' => $site . '#/ecosistema',
			'features'  => $site . '#/funciones',
			'ai'        => $site . '#/ia',
			'demo'      => $site . '#/contacto',
		),
		'flow'    => array( 'Atrae', 'Matricula', 'Enseña', 'Evalúa', 'Acompaña', 'Certifica' ),
		'plugin'  => array(
			'Aprender'  => array(
				'Rutas de aprendizaje con IA',
				'Contenido interactivo con H5P',
				'Chat con el contenido del curso',
			),
			'Enseñar'   => array(
				'Rúbricas y coevaluación entre pares',
				'SpeedGrade para calificar rápido',
				'Cuestionarios generados por IA',
				'Contenido programado (drip)',
				'Videollamadas con Meet y Zoom',
			),
			'Gestionar' => array(
				'Grupos y cohortes',
				'Alertas académicas tempranas',
				'Panel «Hoy» con lo urgente del día',
				'CRM y automatización integrados',
				'WhatsApp y Telegram nativos',
				'Certificados por reglas de elegibilidad',
			),
		),
		'theme'   => array(
			array( 'Plantillas educativas', 'Cursos, programas, lecciones y perfiles de mentor listos para publicar.' ),
			array( 'Landings que venden', 'Página de curso con SEO y captación conectada al CRM.' ),
			array( 'Vista académica', 'Panel del estudiante, progreso y lecciones sin distracciones.' ),
			array( 'Tu marca, sin código', 'Constructor de cabecera y pie, tipografías, esquemas de color y modo oscuro.' ),
			array( 'Pensado para el móvil', 'Rápido y legible donde ocurre la mayor parte del aprendizaje.' ),
		),
		'copilots' => array(
			array( 'Docente', 'Planifica cursos, mejora lecciones y genera rúbricas y cuestionarios.' ),
			array( 'Evaluador', 'Califica contra la rúbrica del docente y redacta la retroalimentación.' ),
			array( 'Estudiantil', 'Responde y resume con el contenido real de la lección.' ),
			array( 'Comercial', 'Orienta a quien aún no se inscribe y entrega la conversación al CRM.' ),
		),
		'extras'  => array(
			array( 'Soberanía de datos', 'Autohospedado en tu propio WordPress y servidor.' ),
			array( 'Español operativo', 'Diseñado en Venezuela para América Latina, no traducido después.' ),
			array( 'App Android / iOS', 'Descargas y entregas sin conexión que se sincronizan después.' ),
			array( 'Video protegido', 'Streaming HLS con tokens y marca de agua por estudiante.' ),
		),
	);

	/**
	 * Filter the ecosystem sidebar content.
	 *
	 * @param array $data Sidebar data.
	 */
	return apply_filters( 'atora_blog_ecosystem_data', $data );
}

/**
 * Most specific category of a post (skips the default and generic "Blog").
 *
 * @param int $post_id Post ID.
 * @return WP_Term|null
 */
function atora_blog_ecosystem_primary_category( $post_id ) {
	$terms   = get_the_category( $post_id );
	$default = (int) get_option( 'default_category' );

	foreach ( $terms as $term ) {
		if ( (int) $term->term_id !== $default && 'blog' !== $term->slug ) {
			return $term;
		}
	}

	return $terms ? $terms[0] : null;
}

/**
 * Render one post card.
 *
 * @param WP_Post $post     Post.
 * @param bool    $featured Large featured variant.
 * @return void
 */
function atora_blog_ecosystem_card( $post, $featured = false ) {
	$link     = get_permalink( $post );
	$category = atora_blog_ecosystem_primary_category( $post->ID );
	$minutes = function_exists( 'atora_get_reading_time' ) ? atora_get_reading_time( $post->ID ) : 0;
	$excerpt = atora_get_plain_excerpt( $post, $featured ? 38 : 20 );
	$excerpt = preg_replace( '/^Respuesta rápida\s*/u', '', html_entity_decode( $excerpt, ENT_QUOTES, 'UTF-8' ) );
	?>
	<article class="atora-blog-card<?php echo $featured ? ' is-featured' : ''; ?>">
		<a class="atora-blog-card__media" href="<?php echo esc_url( $link ); ?>" tabindex="-1" aria-hidden="true">
			<?php if ( has_post_thumbnail( $post ) ) : ?>
				<?php echo get_the_post_thumbnail( $post, $featured ? 'atora-meridian-hero' : 'atora-meridian-card', array( 'alt' => '', 'loading' => $featured ? 'eager' : 'lazy' ) ); ?>
			<?php else : ?>
				<span class="atora-blog-card__placeholder"><?php echo esc_html( $category ? $category->name : 'ATORA Lab' ); ?></span>
			<?php endif; ?>
		</a>
		<div class="atora-blog-card__body">
			<div class="atora-blog-card__meta">
				<?php if ( $category ) : ?>
					<a class="atora-blog-chip" href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
				<?php endif; ?>
				<?php if ( $featured ) : ?>
					<span class="atora-blog-chip is-amber">Destacado</span>
				<?php endif; ?>
			</div>
			<h2><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h2>
			<?php if ( $excerpt ) : ?>
				<p class="atora-blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
			<?php endif; ?>
			<p class="atora-blog-card__footer">
				<span><?php echo esc_html( get_the_date( '', $post ) ); ?></span>
				<?php if ( $minutes ) : ?>
					<span><?php echo esc_html( $minutes . ' min de lectura' ); ?></span>
				<?php endif; ?>
				<a href="<?php echo esc_url( $link ); ?>">Leer artículo →</a>
			</p>
		</div>
	</article>
	<?php
}
