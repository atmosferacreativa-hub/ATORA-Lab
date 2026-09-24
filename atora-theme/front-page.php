<?php
/**
 * Front page template.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( 'page' !== get_option( 'show_on_front' ) ) {
	get_header();
	get_template_part( 'template-parts/journal', 'index' );
	get_footer();
	return;
}

get_header();

$front_page_id = 0;
$hero_title    = get_bloginfo( 'name' );
$hero_copy     = get_bloginfo( 'description' );
$hero_media    = '';
$content_html  = '';
$settings      = atora_get_presentation_defaults();

if ( have_posts() ) {
	the_post();
	$front_page_id = get_the_ID();
	$settings      = atora_get_presentation_settings( $front_page_id );
	$hero_title    = get_the_title() ? get_the_title() : $hero_title;
	$hero_copy     = ! empty( $settings['intro'] )
		? (string) $settings['intro']
		: ( has_excerpt() ? get_the_excerpt() : $hero_copy );
	$hero_media    = has_post_thumbnail() ? get_the_post_thumbnail( $front_page_id, 'atora-meridian-hero' ) : '';

	ob_start();
	the_content();
	$content_html = (string) ob_get_clean();
}

$has_editor_content = $front_page_id ? atora_post_has_editor_content( $front_page_id ) : false;
$has_visual_shell  = atora_presentation_requests_visual_shell( $settings );
$content_first_home = $has_editor_content && ! $has_visual_shell;

if ( ! $has_visual_shell ) {
	$settings['hide_hero']             = true;
	$settings['show_curated_sections'] = false;
}

$hero_actions = atora_get_presentation_actions(
	$settings,
	array(
		array(
			'label' => __( 'Explore Courses', 'atora-learning' ),
			'url'   => atora_get_archive_link( 'lm_course' ),
			'kind'  => 'primary',
		),
		array(
			'label' => __( 'Open Store', 'atora-learning' ),
			'url'   => atora_get_shop_url(),
			'kind'  => 'ghost',
		),
	)
);

$courses_count  = post_type_exists( 'lm_course' ) ? (int) wp_count_posts( 'lm_course' )->publish : 0;
$programs_count = post_type_exists( 'lm_program' ) ? (int) wp_count_posts( 'lm_program' )->publish : 0;
$products_count = post_type_exists( 'product' ) ? (int) wp_count_posts( 'product' )->publish : 0;

$featured_courses  = atora_get_featured_posts( 'lm_course', 3 );
$featured_programs = atora_get_featured_posts( 'lm_program', 3 );
$featured_products = atora_get_featured_posts( 'product', 4 );
$latest_posts      = atora_get_featured_posts( 'post', 3 );
$podcast_posts     = atora_get_featured_posts( 'podcast', 2 );

$blog_page_id = (int) get_option( 'page_for_posts' );
$blog_url     = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog/' );
?>

<?php if ( empty( $settings['hide_hero'] ) ) : ?>
<section class="meridian-home-hero">
	<div class="meridian-home-hero__grid">
		<div class="meridian-home-hero__copy" data-reveal>
			<p class="meridian-eyebrow"><?php echo esc_html( ! empty( $settings['eyebrow'] ) ? (string) $settings['eyebrow'] : __( 'ATORA experience system', 'atora-learning' ) ); ?></p>
			<h1><?php echo esc_html( $hero_title ); ?></h1>
			<p><?php echo esc_html( $hero_copy ); ?></p>

			<div class="meridian-home-hero__actions">
				<?php foreach ( $hero_actions as $action ) : ?>
					<a class="meridian-button meridian-button--<?php echo esc_attr( $action['kind'] ); ?>" href="<?php echo esc_url( $action['url'] ); ?>"><?php echo esc_html( $action['label'] ); ?></a>
				<?php endforeach; ?>
			</div>

			<div class="meridian-metrics">
				<div class="meridian-metric">
					<strong><?php echo esc_html( number_format_i18n( $courses_count ) ); ?></strong>
					<span><?php esc_html_e( 'Courses', 'atora-learning' ); ?></span>
				</div>
				<div class="meridian-metric">
					<strong><?php echo esc_html( number_format_i18n( $programs_count ) ); ?></strong>
					<span><?php esc_html_e( 'Programs', 'atora-learning' ); ?></span>
				</div>
				<div class="meridian-metric">
					<strong><?php echo esc_html( number_format_i18n( $products_count ) ); ?></strong>
					<span><?php esc_html_e( 'Products', 'atora-learning' ); ?></span>
				</div>
			</div>
		</div>

		<div class="meridian-home-hero__panel" data-reveal>
			<?php if ( $hero_media ) : ?>
				<div class="meridian-featured-media" style="margin-bottom:1rem;">
					<?php echo $hero_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>

			<h3><?php esc_html_e( 'Three modes, one brand rhythm', 'atora-learning' ); ?></h3>
			<ul class="meridian-home-hero__panel-list">
				<li>
					<strong><?php esc_html_e( 'Commercial', 'atora-learning' ); ?></strong>
					<p><?php esc_html_e( 'Sharper landing pages, cleaner CTAs and a more premium first impression for high-intent buyers.', 'atora-learning' ); ?></p>
				</li>
				<li>
					<strong><?php esc_html_e( 'Academic', 'atora-learning' ); ?></strong>
					<p><?php esc_html_e( 'Course and program views that feel lighter, clearer and more aligned with the real value of the learning path.', 'atora-learning' ); ?></p>
				</li>
				<li>
					<strong><?php esc_html_e( 'Store and Editorial', 'atora-learning' ); ?></strong>
					<p><?php esc_html_e( 'WooCommerce, blog, podcast and pages now share one visual system instead of feeling stitched together.', 'atora-learning' ); ?></p>
				</li>
			</ul>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $settings['show_curated_sections'] ) ) : ?>
<section class="meridian-section">
	<div class="meridian-section__head">
		<div>
			<p class="meridian-eyebrow"><?php esc_html_e( 'Brand architecture', 'atora-learning' ); ?></p>
			<h2><?php esc_html_e( 'A theme built for selling, teaching and publishing without visual friction.', 'atora-learning' ); ?></h2>
		</div>
	</div>

	<div class="meridian-vision-grid">
		<article class="meridian-vision-card" data-reveal>
			<h3><?php esc_html_e( 'Commercial landings', 'atora-learning' ); ?></h3>
			<p><?php esc_html_e( 'Hero-first pages with decisive hierarchy, stronger price blocks and CTA behavior tuned for mobile conversion.', 'atora-learning' ); ?></p>
			<a class="meridian-link" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'See course catalog', 'atora-learning' ); ?></a>
		</article>

		<article class="meridian-vision-card" data-reveal>
			<h3><?php esc_html_e( 'Academic flows', 'atora-learning' ); ?></h3>
			<p><?php esc_html_e( 'Lessons, progress, instructors and dashboards inherit a calmer interface that still feels premium and business-ready.', 'atora-learning' ); ?></p>
			<a class="meridian-link" href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'View programs', 'atora-learning' ); ?></a>
		</article>

		<article class="meridian-vision-card" data-reveal>
			<h3><?php esc_html_e( 'Store plus editorial', 'atora-learning' ); ?></h3>
			<p><?php esc_html_e( 'Products, articles, podcast episodes and branded pages now sit inside the same modern visual cadence.', 'atora-learning' ); ?></p>
			<a class="meridian-link" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Read the journal', 'atora-learning' ); ?></a>
		</article>
	</div>
</section>
<?php endif; ?>

<?php if ( $has_editor_content ) : ?>
	<section class="<?php echo esc_attr( $content_first_home ? 'meridian-visual-canvas' : 'meridian-content-shell' ); ?>">
		<div class="<?php echo esc_attr( $content_first_home ? 'meridian-visual-canvas__content meridian-richtext' : 'meridian-content-card meridian-richtext' ); ?>" data-reveal>
			<?php echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( ! empty( $settings['show_curated_sections'] ) && ! empty( $featured_courses ) ) : ?>
	<section class="meridian-section">
		<div class="meridian-section__head">
			<div>
				<p class="meridian-eyebrow"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></p>
				<h2><?php esc_html_e( 'Commercial-ready learning products with sharper presentation.', 'atora-learning' ); ?></h2>
			</div>
			<a class="meridian-link" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Full catalog', 'atora-learning' ); ?></a>
		</div>

		<div class="meridian-grid meridian-grid--3">
			<?php foreach ( $featured_courses as $post ) : ?>
				<?php setup_postdata( $post ); ?>
				<?php get_template_part( 'template-parts/content', 'course' ); ?>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( ! empty( $settings['show_curated_sections'] ) && ! empty( $featured_programs ) ) : ?>
	<section class="meridian-section">
		<div class="meridian-section__head">
			<div>
				<p class="meridian-eyebrow"><?php esc_html_e( 'Programs', 'atora-learning' ); ?></p>
				<h2><?php esc_html_e( 'Long-form offers designed to connect academic structure with commercial clarity.', 'atora-learning' ); ?></h2>
			</div>
			<a class="meridian-link" href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'All programs', 'atora-learning' ); ?></a>
		</div>

		<div class="meridian-grid meridian-grid--3">
			<?php foreach ( $featured_programs as $program_post ) : ?>
				<article class="post-card" data-reveal>
					<div class="post-thumbnail">
						<a href="<?php echo esc_url( get_permalink( $program_post ) ); ?>">
							<?php echo get_the_post_thumbnail( $program_post, 'atora-meridian-card' ); ?>
						</a>
					</div>
					<div class="post-card__body">
						<div class="meridian-entry-meta">
							<span><?php esc_html_e( 'Program', 'atora-learning' ); ?></span>
						</div>
						<h3 class="entry-title"><a href="<?php echo esc_url( get_permalink( $program_post ) ); ?>"><?php echo esc_html( get_the_title( $program_post ) ); ?></a></h3>
						<p><?php echo esc_html( atora_get_plain_excerpt( $program_post, 24 ) ); ?></p>
						<div class="entry-footer">
							<a class="meridian-button meridian-button--ghost meridian-button--small" href="<?php echo esc_url( get_permalink( $program_post ) ); ?>"><?php esc_html_e( 'View Program', 'atora-learning' ); ?></a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( ! empty( $settings['show_curated_sections'] ) && ! empty( $featured_products ) ) : ?>
	<section class="meridian-section">
		<div class="meridian-section__head">
			<div>
				<p class="meridian-eyebrow"><?php esc_html_e( 'Store', 'atora-learning' ); ?></p>
				<h2><?php esc_html_e( 'WooCommerce now speaks the same premium language as the rest of the brand.', 'atora-learning' ); ?></h2>
			</div>
			<a class="meridian-link" href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Open store', 'atora-learning' ); ?></a>
		</div>

		<div class="meridian-grid meridian-grid--3">
			<?php foreach ( $featured_products as $product_post ) : ?>
				<?php $product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_post->ID ) : null; ?>
				<article class="post-card" data-reveal>
					<div class="post-thumbnail">
						<a href="<?php echo esc_url( get_permalink( $product_post ) ); ?>">
							<?php echo get_the_post_thumbnail( $product_post, 'atora-meridian-card' ); ?>
						</a>
					</div>
					<div class="post-card__body">
						<div class="meridian-entry-meta">
							<span><?php esc_html_e( 'Product', 'atora-learning' ); ?></span>
						</div>
						<h3 class="entry-title"><a href="<?php echo esc_url( get_permalink( $product_post ) ); ?>"><?php echo esc_html( get_the_title( $product_post ) ); ?></a></h3>
						<p><?php echo esc_html( atora_get_plain_excerpt( $product_post, 18 ) ); ?></p>
						<div class="entry-footer">
							<div>
								<?php if ( $product ) : ?>
									<span class="atora-course-price-current"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
								<?php endif; ?>
							</div>
							<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php echo esc_url( get_permalink( $product_post ) ); ?>"><?php esc_html_e( 'View Product', 'atora-learning' ); ?></a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( ! empty( $settings['show_curated_sections'] ) && ! empty( $latest_posts ) ) : ?>
	<section class="meridian-section">
		<div class="meridian-section__head">
			<div>
				<p class="meridian-eyebrow"><?php esc_html_e( 'Journal', 'atora-learning' ); ?></p>
				<h2><?php esc_html_e( 'Posts and branded storytelling now look intentional instead of generic.', 'atora-learning' ); ?></h2>
			</div>
			<a class="meridian-link" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Visit the blog', 'atora-learning' ); ?></a>
		</div>

		<div class="posts-grid">
			<?php foreach ( $latest_posts as $post ) : ?>
				<?php setup_postdata( $post ); ?>
				<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( ! empty( $settings['show_curated_sections'] ) && ! empty( $podcast_posts ) ) : ?>
	<section class="meridian-section">
		<div class="meridian-section__head">
			<div>
				<p class="meridian-eyebrow"><?php esc_html_e( 'Podcast', 'atora-learning' ); ?></p>
				<h2><?php esc_html_e( 'Audio publishing gets the same elegant container as courses, store and editorial.', 'atora-learning' ); ?></h2>
			</div>
			<a class="meridian-link" href="<?php echo esc_url( atora_get_archive_link( 'podcast' ) ); ?>"><?php esc_html_e( 'All episodes', 'atora-learning' ); ?></a>
		</div>

		<div class="meridian-grid meridian-grid--2">
			<?php foreach ( $podcast_posts as $podcast_post ) : ?>
				<article class="meridian-episode-card" data-reveal>
					<div class="meridian-episode-card__media">
						<a href="<?php echo esc_url( get_permalink( $podcast_post ) ); ?>">
							<?php echo get_the_post_thumbnail( $podcast_post, 'atora-meridian-card' ); ?>
						</a>
					</div>
					<div class="meridian-episode-card__body">
						<div class="meridian-podcast-meta">
							<span><?php esc_html_e( 'Podcast episode', 'atora-learning' ); ?></span>
							<span><?php echo esc_html( get_the_date( '', $podcast_post ) ); ?></span>
						</div>
						<h3 class="meridian-episode-card__title"><a href="<?php echo esc_url( get_permalink( $podcast_post ) ); ?>"><?php echo esc_html( get_the_title( $podcast_post ) ); ?></a></h3>
						<p class="meridian-episode-card__excerpt"><?php echo esc_html( atora_get_plain_excerpt( $podcast_post, 22 ) ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
