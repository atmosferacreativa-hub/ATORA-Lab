<?php
if (!defined('ABSPATH')) exit;
$courses_count = wp_count_posts('lm_course')->publish ?? 0;
$programs_count = wp_count_posts('lm_program')->publish ?? 0;
?>
<section class="atora-section atora-section-site-stats">
    <div class="atora-theme-grid atora-theme-grid--3">
        <article class="atora-theme-card"><strong><?php echo esc_html((string) absint($courses_count)); ?></strong><p><?php esc_html_e('Cursos', 'atora-learning'); ?></p></article>
        <article class="atora-theme-card"><strong><?php echo esc_html((string) absint($programs_count)); ?></strong><p><?php esc_html_e('Programas', 'atora-learning'); ?></p></article>
        <article class="atora-theme-card"><strong>100%</strong><p><?php esc_html_e('Enfoque académico', 'atora-learning'); ?></p></article>
    </div>
</section>
