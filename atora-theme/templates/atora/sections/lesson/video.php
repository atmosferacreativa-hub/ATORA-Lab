<?php
if (!defined('ABSPATH')) exit;
$video_url = get_post_meta(get_the_ID(), '_clms_video_url', true);
?>
<section class="atora-section atora-section-lesson-video">
    <header class="atora-section-header"><h2><?php esc_html_e('Video destacado', 'atora-learning'); ?></h2></header>
    <div class="atora-theme-card">
        <?php if ($video_url) : ?>
            <div class="atora-video-shell"><?php echo wp_oembed_get(esc_url($video_url)); ?></div>
        <?php else : ?>
            <?php atora_theme_render_template_part('sections/shared/notice', ['message' => __('No hay video configurado para esta leccion.', 'atora-learning')]); ?>
        <?php endif; ?>
    </div>
</section>
