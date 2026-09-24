<?php
if (!defined('ABSPATH')) exit;
if (!(bool) get_option('atora_theme_enable_woocommerce_cta', 1)) {
    return;
}
$context = isset($atora_context) ? (array) $atora_context : [];
$course_id = absint($context['course_id'] ?? 0);
$product_id = class_exists('Atora_WooCommerce_Bridge') ? Atora_WooCommerce_Bridge::get_course_product_id($course_id) : 0;
$purchase_url = class_exists('Atora_WooCommerce_Bridge') ? Atora_WooCommerce_Bridge::get_course_purchase_url($course_id) : '';
$price_html = class_exists('Atora_WooCommerce_Bridge') ? Atora_WooCommerce_Bridge::get_course_price_html($course_id) : '';
?>
<section class="atora-section atora-section-course-woocommerce-cta">
    <div class="atora-theme-card atora-woocommerce-cta">
        <h3><?php esc_html_e('Inscripcion y pago', 'atora-learning'); ?></h3>
        <?php if (!$product_id) : ?>
            <?php atora_theme_render_template_part('sections/shared/notice', ['message' => __('No hay producto WooCommerce conectado para este curso.', 'atora-learning')]); ?>
        <?php elseif (!class_exists('WooCommerce')) : ?>
            <?php atora_theme_render_template_part('sections/shared/notice', ['message' => __('WooCommerce no esta activo.', 'atora-learning')]); ?>
        <?php else : ?>
            <?php if ($price_html) : ?><p class="atora-woocommerce-cta__price"><?php echo wp_kses_post($price_html); ?></p><?php endif; ?>
            <?php if ($purchase_url) : ?><a class="button button-primary" href="<?php echo esc_url($purchase_url); ?>"><?php esc_html_e('Comprar ahora', 'atora-learning'); ?></a><?php endif; ?>
        <?php endif; ?>
    </div>
</section>
