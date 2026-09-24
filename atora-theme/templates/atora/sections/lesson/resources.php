<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$resources = isset($context['resources']) && is_array($context['resources']) ? $context['resources'] : [];
?>
<section class="atora-section atora-section-lesson-resources">
    <header class="atora-section-header"><h2><?php esc_html_e('Recursos', 'atora-learning'); ?></h2></header>
    <?php if (empty($resources)) : ?>
        <?php atora_theme_render_template_part('sections/shared/empty-state', ['message' => __('No hay recursos descargables.', 'atora-learning')]); ?>
    <?php else : ?>
        <ul class="atora-theme-card">
            <?php foreach ($resources as $resource) : ?><li><?php echo esc_html((string) $resource); ?></li><?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
