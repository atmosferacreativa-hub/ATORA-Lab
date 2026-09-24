<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($atora_context) && is_array($atora_context) ? $atora_context : [];
?>
<article class="atora-template atora-template-course atora-template-course-cohort atora-course-shell atora-course-cohort">
    <?php atora_theme_render_registered_sections('course-cohort', $context); ?>
</article>
