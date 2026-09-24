<?php
if (!defined('ABSPATH')) {
    exit;
}
$context = isset($atora_context) && is_array($atora_context) ? $atora_context : [];
$title = isset($context['title']) ? (string) $context['title'] : get_the_title();
$content = isset($context['content']) ? (string) $context['content'] : apply_filters('the_content', get_the_content());
?>
<article class="atora-template-fallback atora-theme-card">
    <header class="atora-section-header">
        <h1><?php echo esc_html($title); ?></h1>
    </header>
    <div class="atora-template-content">
        <?php echo wp_kses_post($content); ?>
    </div>
</article>
