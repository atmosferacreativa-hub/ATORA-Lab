<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$course_id = absint($context['course_id'] ?? 0);
$price_html = class_exists('Atora_WooCommerce_Bridge') ? Atora_WooCommerce_Bridge::get_course_price_html($course_id) : '';
$purchase_url = $context['purchase_url'] ?? '';
?>
<section class="atora-section atora-section-course-price-card">
    <div class="atora-theme-card atora-price-card">
        <h3><?php esc_html_e('Inversion', 'atora-learning'); ?></h3>
        <?php if ($price_html) : ?>
            <div class="atora-price-card__price"><?php echo wp_kses_post($price_html); ?></div>
        <?php else : ?>
            <p><?php esc_html_e('Precio disponible al conectar WooCommerce.', 'atora-learning'); ?></p>
        <?php endif; ?>
        <?php if ($purchase_url) : ?><a class="button button-primary" href="<?php echo esc_url($purchase_url); ?>"><?php esc_html_e('Inscribirme', 'atora-learning'); ?></a><?php endif; ?>
    </div>
</section>
