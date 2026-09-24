<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
?>
<section class="atora-section atora-section-lesson-header">
    <header class="atora-theme-card atora-lesson-header">
        <p class="atora-section-eyebrow"><?php esc_html_e('Lección', 'atora-learning'); ?></p>
        <h1><?php echo esc_html((string) ($context['title'] ?? get_the_title())); ?></h1>
        <?php if (!empty($context['course_title'])) : ?><p><?php echo esc_html((string) $context['course_title']); ?></p><?php endif; ?>
    </header>
</section>
