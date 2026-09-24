<?php
if (!defined('ABSPATH')) exit;
$courses = get_posts(['post_type' => 'lm_course', 'posts_per_page' => 6, 'no_found_rows' => true]);
?>
<section class="atora-section atora-section-site-featured-courses">
    <header class="atora-section-header"><h2><?php esc_html_e('Cursos destacados', 'atora-learning'); ?></h2></header>
    <?php if (empty($courses)) : ?>
        <?php atora_theme_render_template_part('sections/shared/empty-state', ['message' => __('No hay cursos publicados.', 'atora-learning')]); ?>
    <?php else : ?>
        <div class="atora-theme-grid atora-theme-grid--3">
            <?php foreach ($courses as $course) : ?><article class="atora-theme-card"><a href="<?php echo esc_url(get_permalink($course->ID)); ?>"><?php echo esc_html(get_the_title($course->ID)); ?></a></article><?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
