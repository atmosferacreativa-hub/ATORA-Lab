<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$content = $context['content'] ?? apply_filters('the_content', get_the_content());
?>
<section class="atora-section atora-section-lesson-content">
    <div class="atora-theme-card atora-lesson-content"><?php echo wp_kses_post($content); ?></div>
</section>
