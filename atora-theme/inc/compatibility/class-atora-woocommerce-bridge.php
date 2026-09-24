<?php
/**
 * Bridge visual WooCommerce para theme.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_WooCommerce_Bridge {

    public static function is_available(): bool {
        return class_exists('WooCommerce') && function_exists('wc_get_product');
    }

    public static function get_course_product_id($course_id): int {
        $course_id = absint($course_id);
        if (!$course_id) {
            return 0;
        }

        $keys = ['_clms_linked_product_id', '_clms_product_id', 'product_id'];
        foreach ($keys as $key) {
            $product_id = (int) get_post_meta($course_id, $key, true);
            if ($product_id) {
                return $product_id;
            }
        }

        return 0;
    }

    public static function get_course_purchase_url($course_id): string {
        $product_id = self::get_course_product_id($course_id);

        if (!$product_id) {
            return '';
        }

        if (self::is_available()) {
            $product = wc_get_product($product_id);
            if ($product) {
                return (string) get_permalink($product_id);
            }
        }

        return (string) get_permalink($product_id);
    }

    public static function get_course_price_html($course_id): string {
        $product_id = self::get_course_product_id($course_id);
        if (!$product_id) {
            return '';
        }

        if (!self::is_available()) {
            return '';
        }

        $product = wc_get_product($product_id);
        if (!$product) {
            return '';
        }

        return (string) $product->get_price_html();
    }
}
