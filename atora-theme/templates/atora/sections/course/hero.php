<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$title = $context['title'] ?? get_the_title();
$excerpt = $context['excerpt'] ?? get_the_excerpt();
$thumbnail = $context['thumbnail'] ?? '';
?>
<section class="atora-section atora-section-course-hero">
    <div class="atora-theme-card atora-course-hero">
        <div>
            <p class="atora-section-eyebrow"><?php esc_html_e('Curso', 'atora-learning'); ?></p>
            <h1><?php echo esc_html($title); ?></h1>
            <?php if ($excerpt) : ?><p><?php echo esc_html($excerpt); ?></p><?php endif; ?>
        </div>
        <?php if ($thumbnail) : ?>
            <figure class="atora-course-hero__media"><img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>"></figure>
        <?php endif; ?>
    </div>
</section>
