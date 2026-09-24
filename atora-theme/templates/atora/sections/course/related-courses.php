<?php
if (!defined('ABSPATH')) exit;
$course_id = get_the_ID();
$related = get_posts([
    'post_type' => 'lm_course',
    'posts_per_page' => 3,
    'post__not_in' => [$course_id],
    'no_found_rows' => true,
]);
?>
<section class="atora-section atora-section-course-related">
    <header class="atora-section-header"><h2><?php esc_html_e('Cursos relacionados', 'atora-learning'); ?></h2></header>
    <?php if (empty($related)) : ?>
        <?php atora_theme_render_template_part('sections/shared/empty-state', ['message' => __('No hay cursos relacionados por ahora.', 'atora-learning')]); ?>
    <?php else : ?>
        <div class="atora-theme-grid atora-theme-grid--3">
            <?php foreach ($related as $course) : ?>
                <article class="atora-theme-card"><a href="<?php echo esc_url(get_permalink($course->ID)); ?>"><?php echo esc_html(get_the_title($course->ID)); ?></a></article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
