<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$lessons = isset($context['lessons']) && is_array($context['lessons']) ? $context['lessons'] : [];
?>
<section class="atora-section atora-section-course-curriculum">
    <header class="atora-section-header"><h2><?php esc_html_e('Curriculum', 'atora-learning'); ?></h2></header>
    <?php if (empty($lessons)) : ?>
        <?php atora_theme_render_template_part('sections/shared/empty-state', ['message' => __('No hay lecciones registradas aun.', 'atora-learning')]); ?>
    <?php else : ?>
        <ul class="atora-curriculum-list">
            <?php foreach ($lessons as $lesson) : ?>
                <li class="atora-theme-card"><a href="<?php echo esc_url(get_permalink($lesson->ID)); ?>"><?php echo esc_html(get_the_title($lesson->ID)); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
