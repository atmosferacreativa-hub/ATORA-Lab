<?php
/**
 * Home showcase shortcodes: real course cards and mentor cards.
 *
 * Usage inside page content:
 *   [atora_home_courses limit="8"]
 *   [atora_home_mentors limit="12" intro="Texto opcional"]
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Public mentor posts linked to a course.
 *
 * @param int $course_id Course ID.
 * @return WP_Post[]
 */
function atora_home_get_course_mentors( $course_id ) {
	$ids = array_filter( array_map( 'absint', (array) get_post_meta( $course_id, '_clms_course_teacher_ids', true ) ) );
	if ( empty( $ids ) || ! post_type_exists( 'atora_teacher' ) ) {
		return array();
	}

	return get_posts(
		array(
			'post_type'      => 'atora_teacher',
			'post_status'    => 'publish',
			'post__in'       => $ids,
			'orderby'        => 'post__in',
			'posts_per_page' => count( $ids ),
		)
	);
}

/**
 * Plain text from HTML, keeping a space where block tags were.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function atora_home_plain_text( $html ) {
	$text = preg_replace( '/<[^>]+>/', ' ', strip_shortcodes( (string) $html ) );
	$text = html_entity_decode( (string) $text, ENT_QUOTES, 'UTF-8' );

	return trim( preg_replace( '/\s+/u', ' ', str_replace( "\xC2\xA0", ' ', $text ) ) );
}

/**
 * Short, readable course description for cards.
 *
 * Prefers the course subtitle (unless it is really the mentor bio), then the
 * excerpt, then the content.
 *
 * @param WP_Post $course  Course post.
 * @param array   $mentors Mentor names linked to the course.
 * @param int     $words   Word limit.
 * @return string
 */
function atora_home_course_description( $course, $mentors = array(), $words = 18 ) {
	$candidates = array(
		(string) get_post_meta( $course->ID, '_clms_course_subtitle', true ),
		(string) $course->post_excerpt,
		(string) $course->post_content,
	);

	foreach ( $candidates as $index => $candidate ) {
		$text = atora_home_plain_text( $candidate );
		$text = preg_replace( '/^descripci[oó]n del curso\s*/iu', '', $text );

		if ( str_word_count( $text ) < 8 ) {
			continue;
		}

		if ( 0 === $index ) {
			foreach ( $mentors as $name ) {
				if ( 0 === stripos( $text, preg_replace( '/^(Dr|Dra|Prof|Lic)\.?\s+/iu', '', $name ) ) ) {
					continue 2;
				}
			}
		}

		return trim( wp_trim_words( $text, $words ) );
	}

	return '';
}

/**
 * [atora_home_courses] — grid of published courses with their featured image.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function atora_home_courses_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'limit' => 8 ), $atts, 'atora_home_courses' );

	if ( ! post_type_exists( 'lm_course' ) ) {
		return '';
	}

	$courses = get_posts(
		array(
			'post_type'      => 'lm_course',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, absint( $atts['limit'] ) ),
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		)
	);

	if ( empty( $courses ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="atora-home-course-grid alignwide">
		<?php foreach ( $courses as $course ) : ?>
			<?php
			$link     = get_permalink( $course );
			$data     = function_exists( 'atora_get_course_card_data' ) ? atora_get_course_card_data( $course->ID ) : array();
			$mentors  = atora_home_get_course_mentors( $course->ID );
			$names    = wp_list_pluck( $mentors, 'post_title' );
			$title    = $course->post_title;
			// Titles like "Curso - Nombre Mentor" repeat the mentor shown below.
			if ( $names && false !== strpos( $title, ' - ' ) ) {
				$title = trim( strstr( $title, ' - ', true ) );
			}
			$lessons  = ! empty( $data['lessons'] ) ? (int) $data['lessons'] : 0;
			$duration = ! empty( $data['duration'] ) ? (string) $data['duration'] : '';
			?>
			<article class="atora-home-course">
				<a class="atora-home-course-media" href="<?php echo esc_url( $link ); ?>" tabindex="-1" aria-hidden="true">
					<?php if ( has_post_thumbnail( $course ) ) : ?>
						<?php echo get_the_post_thumbnail( $course, 'atora-meridian-card', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
					<?php endif; ?>
				</a>
				<div class="atora-home-course-body">
					<h3><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a></h3>
					<?php if ( $names ) : ?>
						<p class="atora-home-course-mentor"><?php echo esc_html( implode( ' · ', $names ) ); ?></p>
					<?php endif; ?>
					<?php $description = atora_home_course_description( $course, $names ); ?>
					<?php if ( $description ) : ?>
						<p class="atora-home-course-desc"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
					<?php if ( $lessons || $duration ) : ?>
						<p class="atora-home-course-meta">
							<?php if ( $lessons ) : ?>
								<span><strong><?php echo esc_html( number_format_i18n( $lessons ) ); ?></strong> <?php echo esc_html( 1 === $lessons ? 'lección' : 'lecciones' ); ?></span>
							<?php endif; ?>
							<?php if ( $duration ) : ?>
								<span><?php echo esc_html( $duration ); ?></span>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'atora_home_courses', 'atora_home_courses_shortcode' );

/**
 * [atora_home_mentors] — mentor cards with photo, specialty and profile link.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function atora_home_mentors_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'limit' => 12, 'intro' => '' ), $atts, 'atora_home_mentors' );

	if ( ! post_type_exists( 'atora_teacher' ) ) {
		return '';
	}

	$mentors = get_posts(
		array(
			'post_type'      => 'atora_teacher',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, absint( $atts['limit'] ) ),
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'meta_query'     => array(
				'relation' => 'OR',
				array( 'key' => '_clms_teacher_public', 'value' => '1' ),
				array( 'key' => '_clms_teacher_public', 'compare' => 'NOT EXISTS' ),
			),
		)
	);

	if ( empty( $mentors ) ) {
		return '';
	}

	$archive = get_post_type_archive_link( 'atora_teacher' );

	ob_start();
	?>
	<div class="atora-home-mentors alignwide">
		<div class="atora-home-mentors-head">
			<div>
				<h3>Mentores</h3>
				<?php if ( '' !== trim( $atts['intro'] ) ) : ?>
					<p><?php echo esc_html( $atts['intro'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $archive ) : ?>
				<a class="atora-home-mentors-all" href="<?php echo esc_url( $archive ); ?>">Ver todos los mentores →</a>
			<?php endif; ?>
		</div>
		<div class="atora-home-mentor-grid">
			<?php foreach ( $mentors as $mentor ) : ?>
				<?php
				$specialty = trim( (string) get_post_meta( $mentor->ID, '_clms_teacher_specialty', true ) );
				$initials  = '';
				foreach ( array_slice( preg_split( '/\s+/u', preg_replace( '/^(Dr|Dra|Prof|Lic)\.?\s+/iu', '', $mentor->post_title ) ), 0, 2 ) as $part ) {
					$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
				}
				?>
				<a class="atora-home-mentor" href="<?php echo esc_url( get_permalink( $mentor ) ); ?>">
					<span class="atora-home-mentor-photo">
						<?php if ( has_post_thumbnail( $mentor ) ) : ?>
							<?php echo get_the_post_thumbnail( $mentor, 'thumbnail', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
						<?php else : ?>
							<?php echo esc_html( $initials ); ?>
						<?php endif; ?>
					</span>
					<span class="atora-home-mentor-name"><?php echo esc_html( $mentor->post_title ); ?></span>
					<?php if ( $specialty ) : ?>
						<span class="atora-home-mentor-role"><?php echo esc_html( $specialty ); ?></span>
					<?php endif; ?>
					<span class="atora-home-mentor-cta">Ver perfil</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'atora_home_mentors', 'atora_home_mentors_shortcode' );
