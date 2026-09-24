<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($atora_context) && is_array($atora_context) ? $atora_context : [];
$title = $context['title'] ?? get_the_title();
$content = $context['content'] ?? apply_filters('the_content', get_the_content());
$excerpt = $context['excerpt'] ?? '';
$thumbnail = $context['thumbnail'] ?? '';
$courses = isset($context['courses']) && is_array($context['courses']) ? $context['courses'] : [];
?>
<article class="atora-template atora-template-site atora-template-teacher-profile">
    <header class="atora-theme-card">
        <?php if ($thumbnail) : ?><img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>" class="atora-teacher-photo"><?php endif; ?>
        <h1><?php echo esc_html($title); ?></h1>
        <?php if ($excerpt) : ?><p><?php echo esc_html($excerpt); ?></p><?php endif; ?>
    </header>

    <div class="atora-theme-card atora-template-content"><?php echo wp_kses_post($content); ?></div>

    <section class="atora-section">
        <header class="atora-section-header"><h2><?php esc_html_e('Cursos del docente', 'atora-learning'); ?></h2></header>
        <?php if (empty($courses)) : ?>
            <?php atora_theme_render_template_part('sections/shared/empty-state', ['message' => __('No hay cursos asignados actualmente.', 'atora-learning')]); ?>
        <?php else : ?>
            <div class="atora-theme-grid atora-theme-grid--3">
                <?php foreach ($courses as $course) : ?>
                    <article class="atora-theme-card"><a href="<?php echo esc_url(get_permalink($course->ID)); ?>"><?php echo esc_html(get_the_title($course->ID)); ?></a></article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</article>
