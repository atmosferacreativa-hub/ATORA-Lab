<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$teachers = isset($context['teachers']) && is_array($context['teachers']) ? $context['teachers'] : [];
?>
<section class="atora-section atora-section-program-teachers">
    <header class="atora-section-header"><h2><?php esc_html_e('Profesores', 'atora-learning'); ?></h2></header>
    <?php if (empty($teachers)) : ?>
        <?php atora_theme_render_template_part('sections/shared/notice', ['message' => __('No hay profesores asignados al programa.', 'atora-learning')]); ?>
    <?php else : ?>
        <div class="atora-theme-grid atora-theme-grid--2">
            <?php foreach ($teachers as $teacher) : ?>
                <article class="atora-theme-card"><h3><?php echo esc_html(get_the_title($teacher->ID)); ?></h3><p><?php echo esc_html(get_the_excerpt($teacher->ID)); ?></p></article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
