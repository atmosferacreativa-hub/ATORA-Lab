<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
?>
<section class="atora-section atora-section-program-hero">
    <div class="atora-theme-card atora-program-hero">
        <p class="atora-section-eyebrow"><?php esc_html_e('Programa', 'atora-learning'); ?></p>
        <h1><?php echo esc_html((string) ($context['title'] ?? get_the_title())); ?></h1>
        <p><?php esc_html_e('Ruta formativa estructurada para resultados profesionales.', 'atora-learning'); ?></p>
    </div>
</section>
