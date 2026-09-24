<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($atora_context) && is_array($atora_context) ? $atora_context : [];
?>
<article class="atora-template atora-template-program atora-template-program-commercial atora-program-shell atora-program-commercial">
    <?php atora_theme_render_registered_sections('program-commercial', $context); ?>
</article>
