<?php
/**
 * Course archive fallback.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$taxonomy = taxonomy_exists( 'course_category' ) ? 'course_category' : ( taxonomy_exists( 'lm_course_level' ) ? 'lm_course_level' : '' );
$terms    = $taxonomy ? get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true ) ) : array();
$terms    = is_wp_error( $terms ) ? array() : $terms;
?>

<section class="meridian-archive-hero">
	<div class="meridian-archive-hero__copy" data-reveal>
		<p class="meridian-eyebrow"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></p>
		<h1><?php esc_html_e( 'A cleaner course catalog for a more commercial first impression.', 'atora-learning' ); ?></h1>
		<p><?php esc_html_e( 'This fallback archive still keeps the experience premium if WordPress resolves the request inside the theme.', 'atora-learning' ); ?></p>
	</div>
</section>

<div class="meridian-catalog-shell">
	<div class="meridian-content-shell__inner">
		<div>
			<?php if ( have_posts() ) : ?>
				<div class="meridian-catalog-grid meridian-grid--3">
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<?php get_template_part( 'template-parts/content', 'course' ); ?>
					<?php endwhile; ?>
				</div>

				<?php atora_render_pagination(); ?>
			<?php else : ?>
				<div class="meridian-empty-state">
					<h2><?php esc_html_e( 'No courses found', 'atora-learning' ); ?></h2>
					<p><?php esc_html_e( 'Publish a course or connect the plugin archive to populate this section.', 'atora-learning' ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<aside class="meridian-sidebar">
			<div class="meridian-sidebar-card" data-reveal>
				<h3><?php esc_html_e( 'Focus areas', 'atora-learning' ); ?></h3>
				<ul class="meridian-sidebar-list">
					<?php foreach ( $terms as $term ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</aside>
	</div>
</div>

<?php
get_footer();
