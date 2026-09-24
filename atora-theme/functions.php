<?php
/**
 * Meridian core for ATORA.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ATORA_THEME_VERSION', '3.0.6' );
define( 'ATORA_THEME_DIR', get_template_directory() );
define( 'ATORA_THEME_URI', get_template_directory_uri() );

require_once ATORA_THEME_DIR . '/inc/meridian-i18n.php';
require_once ATORA_THEME_DIR . '/inc/meridian-editor.php';
require_once ATORA_THEME_DIR . '/inc/compatibility/bootstrap.php';
require_once ATORA_THEME_DIR . '/inc/design/bootstrap.php';
require_once ATORA_THEME_DIR . '/inc/templates/bootstrap.php';
require_once ATORA_THEME_DIR . '/inc/home-showcase.php';
require_once ATORA_THEME_DIR . '/inc/blog-ecosystem.php';

if ( is_admin() ) {
	require_once ATORA_THEME_DIR . '/admin/class-atora-admin.php';
	new Atora_Admin();
}

/**
 * Theme setup.
 */
function atora_theme_setup() {
	load_theme_textdomain( 'atora-learning', ATORA_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 84,
			'width'       => 280,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'woocommerce' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'atora-learning' ),
			'top'     => __( 'Top Rail Navigation', 'atora-learning' ),
			'footer'  => __( 'Footer Navigation', 'atora-learning' ),
			'legal'   => __( 'Legal Navigation', 'atora-learning' ),
		)
	);

	add_image_size( 'atora-meridian-card', 720, 480, true );
	add_image_size( 'atora-meridian-hero', 1600, 900, true );
	add_image_size( 'atora-meridian-square', 800, 800, true );
}
add_action( 'after_setup_theme', 'atora_theme_setup' );

/**
 * Enqueue theme assets.
 */
function atora_theme_enqueue_assets() {
	wp_enqueue_style( 'atora-style', get_stylesheet_uri(), array(), ATORA_THEME_VERSION );

	if ( atora_theme_should_enqueue_home_institucional_styles() ) {
		wp_enqueue_style(
			'atora-pattern-home-institucional',
			ATORA_THEME_URI . '/assets/css/pattern-home.css',
			array( 'atora-style' ),
			ATORA_THEME_VERSION
		);
	}

	wp_enqueue_script( 'atora-main', ATORA_THEME_URI . '/assets/js/main.js', array(), ATORA_THEME_VERSION, true );
	wp_localize_script(
		'atora-main',
		'atoraTheme',
		array(
			'accountUrl' => atora_get_account_url(),
			'cartUrl'    => atora_get_cart_url(),
			'shopUrl'    => atora_get_shop_url(),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'atora_theme_enqueue_assets' );

function atora_theme_enqueue_editor_assets() {
	if ( ! atora_theme_should_enqueue_home_institucional_styles() ) {
		return;
	}

	wp_enqueue_style(
		'atora-pattern-home-institucional-editor',
		ATORA_THEME_URI . '/assets/css/pattern-home.css',
		array(),
		ATORA_THEME_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'atora_theme_enqueue_editor_assets' );

function atora_theme_should_enqueue_home_institucional_styles(): bool {
	if ( is_front_page() ) {
		$post = get_post();
		if ( $post instanceof WP_Post ) {
			return false !== strpos( (string) $post->post_content, 'atora-home-institucional' );
		}
	}

	if ( is_singular() ) {
		$post = get_post();
		if ( $post instanceof WP_Post ) {
			return false !== strpos( (string) $post->post_content, 'atora-home-institucional' );
		}
	}

	return false;
}

/**
 * Filter body classes.
 *
 * @param string[] $classes Existing classes.
 * @return string[]
 */
function atora_theme_body_classes( $classes ) {
	$classes[] = 'atora-meridian';

	if ( is_front_page() ) {
		$classes[] = 'atora-home';
	}

	if ( is_home() || is_singular( 'post' ) ) {
		$classes[] = 'atora-editorial';
	}

	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$classes[] = 'atora-store';
	}

	if ( is_singular( 'podcast' ) || is_post_type_archive( 'podcast' ) ) {
		$classes[] = 'atora-podcast';
	}

	return $classes;
}
add_filter( 'body_class', 'atora_theme_body_classes' );

add_filter(
	'document_title_separator',
	static function () {
		return '|';
	}
);

add_filter(
	'excerpt_length',
	static function ( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		return 28;
	},
	99
);

add_filter(
	'excerpt_more',
	static function () {
		return '...';
	}
);

/**
 * Fallback navigation output.
 *
 * @param array $args wp_nav_menu arguments.
 * @return void
 */
function atora_nav_fallback( $args ) {
	$menu_class = isset( $args['menu_class'] ) ? (string) $args['menu_class'] : 'meridian-menu';
	$pages      = wp_list_pages(
		array(
			'title_li' => '',
			'echo'     => false,
			'depth'    => 1,
		)
	);

	if ( ! $pages ) {
		return;
	}

	echo '<ul class="' . esc_attr( $menu_class ) . '">' . $pages . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get archive link with sane fallbacks.
 *
 * @param string $post_type Post type.
 * @return string
 */
function atora_get_archive_link( $post_type ) {
	$fallbacks = array(
		'lm_course'  => home_url( '/cursos/' ),
		'lm_program' => home_url( '/programas/' ),
		'podcast'    => home_url( '/podcast/' ),
		'product'    => atora_get_shop_url(),
	);

	if ( post_type_exists( $post_type ) ) {
		$link = get_post_type_archive_link( $post_type );
		if ( is_string( $link ) && '' !== trim( $link ) ) {
			return $link;
		}
	}

	return isset( $fallbacks[ $post_type ] ) ? $fallbacks[ $post_type ] : home_url( '/' );
}

/**
 * Get shop URL.
 *
 * @return string
 */
function atora_get_shop_url() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id > 0 ) {
			return (string) get_permalink( $shop_id );
		}
	}

	return home_url( '/tienda/' );
}

/**
 * Get account URL.
 *
 * @return string
 */
function atora_get_account_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$url = wc_get_page_permalink( 'myaccount' );
		if ( is_string( $url ) && '' !== trim( $url ) ) {
			return $url;
		}
	}

	return is_user_logged_in() ? admin_url( 'profile.php' ) : wp_login_url();
}

/**
 * Get cart URL.
 *
 * @return string
 */
function atora_get_cart_url() {
	if ( function_exists( 'wc_get_cart_url' ) ) {
		return wc_get_cart_url();
	}

	return home_url( '/cart/' );
}

/**
 * Get cart count.
 *
 * @return int
 */
function atora_get_cart_count() {
	if ( function_exists( 'WC' ) && WC() && WC()->cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}

	return 0;
}

/**
 * Get the primary site CTA URL.
 *
 * @return string
 */
function atora_get_primary_cta_url() {
	$course_archive = atora_get_archive_link( 'lm_course' );
	if ( $course_archive ) {
		return $course_archive;
	}

	return atora_get_shop_url();
}

/**
 * Get the primary site CTA label.
 *
 * @return string
 */
function atora_get_primary_cta_label() {
	return post_type_exists( 'lm_course' )
		? __( 'Explore Learning', 'atora-learning' )
		: __( 'Open Store', 'atora-learning' );
}

/**
 * Logo markup.
 *
 * @return string
 */
function atora_get_logo_markup() {
	if ( has_custom_logo() ) {
		return (string) get_custom_logo();
	}

	$site_name        = get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description' );

	return sprintf(
		'<a class="meridian-brand__wordmark" href="%1$s" rel="home"><span class="meridian-brand__title">%2$s</span><span class="meridian-brand__tag">%3$s</span></a>',
		esc_url( home_url( '/' ) ),
		esc_html( $site_name ),
		esc_html( $site_description )
	);
}

/**
 * Resolve a term label for a post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function atora_get_primary_label( $post_id = 0 ) {
	$post_id   = $post_id ? absint( $post_id ) : get_the_ID();
	$post_type = get_post_type( $post_id );
	$taxonomy_map = array(
		'post'       => array( 'category' ),
		'lm_course'  => array( 'course_category', 'lm_course_level', 'category' ),
		'lm_program' => array( 'program_category', 'category' ),
		'product'    => array( 'product_cat' ),
		'podcast'    => array( 'category', 'post_tag' ),
	);

	$taxonomies = isset( $taxonomy_map[ $post_type ] ) ? $taxonomy_map[ $post_type ] : array( 'category' );

	foreach ( $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}

		$terms = get_the_terms( $post_id, $taxonomy );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}

		$term = reset( $terms );
		if ( $term instanceof WP_Term ) {
			return (string) $term->name;
		}
	}

	$post_type_object = get_post_type_object( $post_type );

	return $post_type_object && ! empty( $post_type_object->labels->singular_name )
		? (string) $post_type_object->labels->singular_name
		: __( 'Story', 'atora-learning' );
}

/**
 * Plain excerpt helper.
 *
 * @param int|WP_Post|null $post  Post object or ID.
 * @param int              $words Length.
 * @return string
 */
function atora_get_plain_excerpt( $post = null, $words = 28 ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return '';
	}

	$excerpt = trim( (string) $post->post_excerpt );
	if ( '' === $excerpt ) {
		$excerpt = wp_strip_all_tags( (string) $post->post_content );
	}

	return trim( (string) wp_trim_words( $excerpt, absint( $words ) ) );
}

/**
 * Reading time helper.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function atora_get_reading_time( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
	$words   = str_word_count( $content );

	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * Render entry meta.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function atora_render_entry_meta( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$author  = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
	?>
	<div class="meridian-entry-meta">
		<span><?php echo esc_html( get_the_date( '', $post_id ) ); ?></span>
		<?php if ( $author ) : ?>
			<span><?php echo esc_html( $author ); ?></span>
		<?php endif; ?>
		<span>
			<?php
			printf(
				/* translators: %d: reading time in minutes. */
				esc_html__( '%d min read', 'atora-learning' ),
				atora_get_reading_time( $post_id )
			);
			?>
		</span>
	</div>
	<?php
}

/**
 * Query helper for featured sections.
 *
 * @param string $post_type Post type.
 * @param int    $limit     Number of posts.
 * @return WP_Post[]
 */
function atora_get_featured_posts( $post_type, $limit = 3 ) {
	if ( ! post_type_exists( $post_type ) ) {
		return array();
	}

	$query = new WP_Query(
		array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, absint( $limit ) ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	return $query->posts;
}

/**
 * Archive title for non-core contexts.
 *
 * @return string
 */
function atora_get_context_title() {
	if ( is_front_page() ) {
		return get_bloginfo( 'name' );
	}

	if ( is_home() ) {
		$posts_page_id = (int) get_option( 'page_for_posts' );
		return $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Journal', 'atora-learning' );
	}

	if ( is_search() ) {
		return sprintf(
			/* translators: %s: search query. */
			__( 'Search results for "%s"', 'atora-learning' ),
			get_search_query()
		);
	}

	if ( is_post_type_archive() || is_tax() || is_category() || is_tag() || is_author() || is_date() ) {
		return get_the_archive_title();
	}

	if ( is_404() ) {
		return __( 'Page not found', 'atora-learning' );
	}

	return get_the_title();
}

/**
 * Archive description helper.
 *
 * @return string
 */
function atora_get_context_description() {
	if ( is_front_page() ) {
		return get_bloginfo( 'description' );
	}

	if ( is_home() ) {
		return __( 'Editorial stories, course launches, podcasts, product drops and practical thinking from the ATORA ecosystem.', 'atora-learning' );
	}

	if ( is_search() ) {
		return __( 'A focused index across articles, landings, courses, products and podcast episodes.', 'atora-learning' );
	}

	$description = trim( wp_strip_all_tags( (string) get_the_archive_description() ) );
	if ( '' !== $description ) {
		return $description;
	}

	if ( is_post_type_archive( 'lm_course' ) ) {
		return __( 'Commercially crafted course experiences connected to the academic engine of ATORA.', 'atora-learning' );
	}

	if ( is_post_type_archive( 'lm_program' ) ) {
		return __( 'Structured learning paths designed for premium offers, cohorts and long-form progression.', 'atora-learning' );
	}

	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return __( 'A cleaner commerce layer for courses, programs and digital products linked to ATORA.', 'atora-learning' );
	}

	if ( is_404() ) {
		return __( 'The route changed or the page no longer exists, but the rest of the experience is still here.', 'atora-learning' );
	}

	return __( 'A modern editorial-commercial shell built to make ATORA feel sharper, lighter and more valuable.', 'atora-learning' );
}

/**
 * Pagination helper.
 *
 * @return void
 */
function atora_render_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => __( 'Previous', 'atora-learning' ),
			'next_text' => __( 'Next', 'atora-learning' ),
		)
	);
}

/**
 * Get instructor names for a course or program.
 *
 * @param int $post_id Entity ID.
 * @return string[]
 */
function atora_get_instructor_names( $post_id ) {
	$post_id        = absint( $post_id );
	$instructor_ids = get_post_meta( $post_id, '_clms_instructor_ids', true );
	$names          = array();

	if ( ! is_array( $instructor_ids ) || empty( $instructor_ids ) ) {
		$author_id      = (int) get_post_field( 'post_author', $post_id );
		$instructor_ids = $author_id ? array( $author_id ) : array();
	}

	foreach ( $instructor_ids as $instructor_id ) {
		$user = get_userdata( absint( $instructor_id ) );
		if ( ! $user ) {
			continue;
		}

		$names[] = $user->display_name ? $user->display_name : $user->user_login;
	}

	return array_values( array_unique( array_filter( $names ) ) );
}

/**
 * Course lesson count.
 *
 * @param int $course_id Course ID.
 * @return int
 */
function atora_get_course_lesson_count( $course_id ) {
	$course_id = absint( $course_id );

	if ( class_exists( 'CLMS_Helper' ) && method_exists( 'CLMS_Helper', 'get_course_lessons' ) ) {
		$lessons = CLMS_Helper::get_course_lessons( $course_id );
		return is_array( $lessons ) ? count( $lessons ) : 0;
	}

	return 0;
}

/**
 * Course or program offer data.
 *
 * @param int $post_id Entity ID.
 * @return array<string,mixed>
 */
function atora_get_entity_offer_data( $post_id ) {
	$post_id = absint( $post_id );

	if ( class_exists( 'CLMS_Helper' ) && method_exists( 'CLMS_Helper', 'get_entity_offer_data' ) ) {
		$data = CLMS_Helper::get_entity_offer_data( $post_id );
		return is_array( $data ) ? $data : array();
	}

	return array();
}

/**
 * Course card view model.
 *
 * @param int $course_id Course ID.
 * @return array<string,mixed>
 */
function atora_get_course_card_data( $course_id ) {
	$course_id      = absint( $course_id );
	$duration_keys  = array( '_clms_course_duration', '_course_duration', 'course_duration', 'lm_course_duration', '_duration' );
	$duration       = '';
	$students_count = absint( get_post_meta( $course_id, '_clms_enrollment_count', true ) );

	foreach ( $duration_keys as $key ) {
		$value = get_post_meta( $course_id, $key, true );
		if ( is_scalar( $value ) && '' !== trim( (string) $value ) ) {
			$duration = wp_strip_all_tags( (string) $value );
			break;
		}
	}

	$user_id     = get_current_user_id();
	$is_enrolled = false;
	if ( $user_id && class_exists( 'CLMS_Helper' ) && method_exists( 'CLMS_Helper', 'user_can_access_course' ) ) {
		$is_enrolled = (bool) CLMS_Helper::user_can_access_course( $user_id, $course_id );
	}

	$offer      = atora_get_entity_offer_data( $course_id );
	$price_html = '';
	if ( ! empty( $offer['price_label'] ) ) {
		$price_html = (string) $offer['price_label'];
	} elseif ( ! empty( $offer['price'] ) ) {
		$price_html = (string) $offer['price'];
	}

	return array(
		'label'       => atora_get_primary_label( $course_id ),
		'duration'    => $duration,
		'lessons'     => atora_get_course_lesson_count( $course_id ),
		'students'    => $students_count,
		'price'       => $price_html,
		'cta_label'   => $is_enrolled ? __( 'Continue', 'atora-learning' ) : __( 'View Course', 'atora-learning' ),
		'is_enrolled' => $is_enrolled,
		'instructors' => atora_get_instructor_names( $course_id ),
	);
}

/**
 * Dashboard enrolled courses helper.
 *
 * @param int $user_id User ID.
 * @return int[]
 */
function atora_get_user_enrolled_courses( $user_id ) {
	$user_id = absint( $user_id );
	if ( ! $user_id ) {
		return array();
	}

	if ( class_exists( 'CLMS_Helper' ) && method_exists( 'CLMS_Helper', 'get_user_enrolled_courses' ) ) {
		$course_ids = CLMS_Helper::get_user_enrolled_courses( $user_id );
	} else {
		$course_ids = get_user_meta( $user_id, '_clms_enrolled_courses', true );
	}

	return array_values( array_filter( array_map( 'absint', (array) $course_ids ) ) );
}

/**
 * Progress helper.
 *
 * @param int $user_id   User ID.
 * @param int $course_id Course ID.
 * @return int
 */
function atora_get_course_progress( $user_id, $course_id ) {
	$user_id   = absint( $user_id );
	$course_id = absint( $course_id );

	if ( ! $user_id || ! $course_id ) {
		return 0;
	}

	if ( function_exists( 'atora_lms_get_progress' ) ) {
		return max( 0, min( 100, (int) atora_lms_get_progress( $user_id, $course_id ) ) );
	}

	$lesson_ids = class_exists( 'CLMS_Helper' ) && method_exists( 'CLMS_Helper', 'get_course_lessons' )
		? (array) CLMS_Helper::get_course_lessons( $course_id )
		: array();
	$lesson_ids = array_values( array_filter( array_map( 'absint', $lesson_ids ) ) );

	if ( empty( $lesson_ids ) ) {
		return 0;
	}

	$completed = get_user_meta( $user_id, '_clms_completed_lessons', true );
	$completed = is_array( $completed ) ? array_map( 'absint', $completed ) : array();
	$done      = count( array_intersect( $lesson_ids, $completed ) );

	return (int) round( ( $done / count( $lesson_ids ) ) * 100 );
}

/**
 * Resolve podcast audio URL.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function atora_get_podcast_audio_url( $post_id ) {
	$post_id   = absint( $post_id );
	$meta_keys = array(
		'_podcast_audio_url',
		'podcast_audio',
		'episode_audio_url',
		'audio_url',
		'_audio_file',
		'enclosure',
	);

	foreach ( $meta_keys as $meta_key ) {
		$value = get_post_meta( $post_id, $meta_key, true );
		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return esc_url( $value );
		}
	}

	$attachments = get_attached_media( 'audio', $post_id );
	if ( ! empty( $attachments ) ) {
		$attachment = reset( $attachments );
		$url        = wp_get_attachment_url( (int) $attachment->ID );
		if ( $url ) {
			return esc_url( $url );
		}
	}

	return '';
}
