<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$teachers = isset($context['teachers']) && is_array($context['teachers']) ? $context['teachers'] : [];
?>
<section class="atora-section atora-section-course-teacher-card">
    <header class="atora-section-header"><h2><?php esc_html_e('Profesor destacado', 'atora-learning'); ?></h2></header>
    <?php if (empty($teachers)) : ?>
        <?php atora_theme_render_template_part('sections/shared/notice', ['message' => __('Este curso aun no tiene profesor asignado.', 'atora-learning')]); ?>
    <?php else : $teacher = reset($teachers); ?>
        <article class="atora-theme-card atora-teacher-card">
            <div class="atora-teacher-card__avatar"><?php echo get_the_post_thumbnail($teacher->ID, [96, 96]); ?></div>
            <div>
                <h3><?php echo esc_html(get_the_title($teacher->ID)); ?></h3>
                <p><?php echo esc_html(get_the_excerpt($teacher->ID)); ?></p>
                <a href="<?php echo esc_url(get_permalink($teacher->ID)); ?>"><?php esc_html_e('Ver perfil docente', 'atora-learning'); ?></a>
            </div>
        </article>
    <?php endif; ?>
</section>
