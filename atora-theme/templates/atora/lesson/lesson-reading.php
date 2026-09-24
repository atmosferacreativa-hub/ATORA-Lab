<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($atora_context) && is_array($atora_context) ? $atora_context : [];
?>
<article class="atora-template atora-template-lesson atora-template-lesson-reading atora-lesson-shell atora-lesson-reading">
    <?php atora_theme_render_registered_sections('lesson-reading', $context); ?>
</article>
