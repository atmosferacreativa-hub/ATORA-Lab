<?php
if (!defined('ABSPATH')) exit;
$teachers = get_posts(['post_type' => 'atora_teacher', 'posts_per_page' => 8, 'no_found_rows' => true]);
?>
<section class="atora-section atora-section-site-teacher-grid">
    <header class="atora-section-header"><h2><?php esc_html_e('Docentes', 'atora-learning'); ?></h2></header>
    <?php if (empty($teachers)) : ?>
        <?php atora_theme_render_template_part('sections/shared/notice', ['message' => __('No hay docentes publicados.', 'atora-learning')]); ?>
    <?php else : ?>
        <div class="atora-theme-grid atora-theme-grid--4">
            <?php foreach ($teachers as $teacher) : ?><article class="atora-theme-card"><h3><?php echo esc_html(get_the_title($teacher->ID)); ?></h3></article><?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
