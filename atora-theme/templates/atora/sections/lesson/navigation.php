<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$prev = $context['previous'] ?? null;
$next = $context['next'] ?? null;
?>
<section class="atora-section atora-section-lesson-navigation">
    <div class="atora-theme-card atora-lesson-navigation">
        <?php if ($prev instanceof WP_Post) : ?><a class="button" href="<?php echo esc_url(get_permalink($prev->ID)); ?>"><?php esc_html_e('Lección anterior', 'atora-learning'); ?></a><?php endif; ?>
        <?php if ($next instanceof WP_Post) : ?><a class="button button-primary" href="<?php echo esc_url(get_permalink($next->ID)); ?>"><?php esc_html_e('Siguiente leccion', 'atora-learning'); ?></a><?php endif; ?>
    </div>
</section>
