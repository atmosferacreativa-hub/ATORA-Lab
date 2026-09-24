<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$progress = (float) ($context['progress'] ?? 0);
?>
<section class="atora-section atora-section-lesson-progress">
    <div class="atora-theme-card atora-lesson-progress">
        <p><strong><?php esc_html_e('Progreso del curso', 'atora-learning'); ?>:</strong> <?php echo esc_html((string) round($progress)); ?>%</p>
        <div class="atora-progress-bar"><span style="width: <?php echo esc_attr((string) max(0, min(100, $progress))); ?>%"></span></div>
    </div>
</section>
