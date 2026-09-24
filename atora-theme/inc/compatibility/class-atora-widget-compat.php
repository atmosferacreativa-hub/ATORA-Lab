<?php
/**
 * Politica de widgets avanzados.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Widget_Compat {

    public static function init(): void {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', [__CLASS__, 'register_menu'], 50);
    }

    public static function register_menu(): void {
        add_submenu_page(
            'atora-theme',
            __('Widgets avanzados', 'atora-learning'),
            __('Widgets avanzados', 'atora-learning'),
            'manage_options',
            'atora-theme-advanced-widgets',
            [__CLASS__, 'render_page']
        );
    }

    public static function render_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        include ATORA_THEME_DIR . '/admin/pages/advanced-widgets.php';
    }

    public static function is_enabled(): bool {
        return (bool) get_option('atora_theme_enable_advanced_widgets', 0);
    }
}
