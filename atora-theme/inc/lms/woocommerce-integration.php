<?php
/**
 * WooCommerce Integration
 *
 * Adds WooCommerce theme support and visual styles.
 * Enrollment on purchase is handled exclusively by the ATORA_v5 plugin
 * (CLMS_Commerce_Hooks) to avoid duplicate enrollment and data conflicts.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) exit;

/**
 * Add WooCommerce theme support
 */
function atora_add_woocommerce_support() {
    add_theme_support('woocommerce', [
        'product_grid' => [
            'default_columns' => 3,
            'min_columns' => 1,
            'max_columns' => 6,
        ],
    ]);

    add_theme_support('wc_product_gallery_zoom');
    add_theme_support('wc_product_gallery_lightbox');
    add_theme_support('wc_product_gallery_slider');
}
add_action('after_setup_theme', 'atora_add_woocommerce_support');

/**
 * Enqueue WooCommerce styles in all store-related screens.
 */
function atora_woocommerce_styles() {
    if (!function_exists('is_woocommerce')) {
        return;
    }

    $is_store_context = is_woocommerce()
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_checkout') && is_checkout())
        || (function_exists('is_account_page') && is_account_page());

    if (!$is_store_context) {
        return;
    }

    wp_enqueue_style(
        'atora-woocommerce',
        ATORA_THEME_URI . '/assets/css/woocommerce.css',
        [],
        ATORA_THEME_VERSION
    );

    wp_enqueue_style(
        'atora-woocommerce-layer',
        ATORA_THEME_URI . '/assets/css/atora-woocommerce.css',
        ['atora-woocommerce'],
        ATORA_THEME_VERSION
    );
}
add_action('wp_enqueue_scripts', 'atora_woocommerce_styles');

/**
 * Show courses included in the order on the Thank You / order detail page.
 * Reads meta key _clms_linked_course_id set by CLMS_WooCommerce (plugin).
 */
add_action('woocommerce_order_details_after_order_table', function($order) {
    if (!$order) return;

    $courses_in_order = [];
    foreach ($order->get_items() as $item) {
        if (!is_a($item, 'WC_Order_Item_Product')) continue;

        $product_id = $item->get_product_id();
        $course_id  = get_post_meta($product_id, '_clms_linked_course_id', true);
        if ($course_id) {
            $courses_in_order[] = get_the_title($course_id);
        }
    }

    if ($courses_in_order) {
        echo '<section class="woocommerce-order-details-courses">';
        echo '<h2 class="woocommerce-column__title">' . esc_html__('Courses Included', 'atora-learning') . '</h2>';
        echo '<ul style="list-style: disc; margin-left: 20px;">';
        foreach ($courses_in_order as $course_title) {
            echo '<li>' . esc_html($course_title) . '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }
});
