<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($atora_context) && is_array($atora_context) ? $atora_context : [];
?>
<article class="atora-template atora-template-course atora-template-course-classic atora-course-shell atora-course-classic">
    <?php atora_theme_render_registered_sections('course-classic', $context); ?>
</article>
