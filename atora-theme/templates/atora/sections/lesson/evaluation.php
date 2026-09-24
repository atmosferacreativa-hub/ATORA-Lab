<?php
if (!defined('ABSPATH')) exit;
?>
<section class="atora-section atora-section-lesson-evaluation">
    <div class="atora-theme-card">
        <h2><?php esc_html_e('Evaluacion', 'atora-learning'); ?></h2>
        <p><?php esc_html_e('La evaluación se renderiza según la lógica académica del plugin.', 'atora-learning'); ?></p>
        <?php if (shortcode_exists('lm_quiz')) : echo do_shortcode('[lm_quiz]'); endif; ?>
    </div>
</section>
