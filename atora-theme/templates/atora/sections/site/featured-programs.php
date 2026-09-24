<?php
if (!defined('ABSPATH')) exit;
$programs = get_posts(['post_type' => 'lm_program', 'posts_per_page' => 6, 'no_found_rows' => true]);
?>
<section class="atora-section atora-section-site-featured-programs">
    <header class="atora-section-header"><h2><?php esc_html_e('Programas destacados', 'atora-learning'); ?></h2></header>
    <?php if (empty($programs)) : ?>
        <?php atora_theme_render_template_part('sections/shared/empty-state', ['message' => __('No hay programas publicados.', 'atora-learning')]); ?>
    <?php else : ?>
        <div class="atora-theme-grid atora-theme-grid--3">
            <?php foreach ($programs as $program) : ?><article class="atora-theme-card"><a href="<?php echo esc_url(get_permalink($program->ID)); ?>"><?php echo esc_html(get_the_title($program->ID)); ?></a></article><?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
