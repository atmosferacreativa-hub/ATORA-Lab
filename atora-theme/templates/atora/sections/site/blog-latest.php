<?php
if (!defined('ABSPATH')) exit;
$posts = get_posts(['post_type' => 'post', 'posts_per_page' => 3, 'no_found_rows' => true]);
?>
<section class="atora-section atora-section-site-blog-latest">
    <header class="atora-section-header"><h2><?php esc_html_e('Ultimos articulos', 'atora-learning'); ?></h2></header>
    <?php if (empty($posts)) : ?>
        <?php atora_theme_render_template_part('sections/shared/empty-state', ['message' => __('No hay articulos publicados.', 'atora-learning')]); ?>
    <?php else : ?>
        <div class="atora-theme-grid atora-theme-grid--3">
            <?php foreach ($posts as $post_item) : ?><article class="atora-theme-card"><a href="<?php echo esc_url(get_permalink($post_item->ID)); ?>"><?php echo esc_html(get_the_title($post_item->ID)); ?></a></article><?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
