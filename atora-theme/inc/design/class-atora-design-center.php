<?php
/**
 * Centro de Diseño ATORA.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Design_Center {

    public static function init(): void {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', [__CLASS__, 'register_menu'], 30);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }

    public static function register_menu(): void {
        add_submenu_page(
            'atora-theme',
            __('Centro de Diseño', 'atora-learning'),
            __('Centro de Diseño', 'atora-learning'),
            'manage_options',
            'atora-design-center',
            [__CLASS__, 'render_page']
        );
    }

    public static function enqueue_assets(string $hook): void {
        if (false === strpos($hook, 'atora-design-center')) {
            return;
        }

        wp_enqueue_style('atora-design-center', ATORA_THEME_URI . '/assets/css/atora-design-center.css', [], ATORA_THEME_VERSION);
        wp_enqueue_script('atora-design-center', ATORA_THEME_URI . '/assets/js/atora-design-center.js', ['jquery'], ATORA_THEME_VERSION, true);
    }

    public static function render_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        include ATORA_THEME_DIR . '/admin/pages/design-center.php';
    }
}
