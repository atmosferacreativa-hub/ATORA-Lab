<?php
/**
 * Asistente visual para usuarios básicos.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Design_Wizard {

    public static function init(): void {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', [__CLASS__, 'register_menu'], 32);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('admin_post_atora_theme_save_wizard', [__CLASS__, 'save']);
    }

    public static function register_menu(): void {
        add_submenu_page(
            'atora-theme',
            __('Asistente', 'atora-learning'),
            __('Asistente', 'atora-learning'),
            'manage_options',
            'atora-theme-wizard',
            [__CLASS__, 'render_page']
        );
    }

    public static function enqueue_assets(string $hook): void {
        if (false === strpos($hook, 'atora-theme-wizard')) {
            return;
        }

        wp_enqueue_script('atora-wizard', ATORA_THEME_URI . '/assets/js/atora-wizard.js', ['jquery'], ATORA_THEME_VERSION, true);
    }

    public static function render_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        include ATORA_THEME_DIR . '/admin/pages/wizard.php';
    }

    public static function save(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('No autorizado.', 'atora-learning'));
        }

        check_admin_referer('atora_theme_save_wizard');

        $map = [
            'course' => 'atora_theme_global_course_template',
            'lesson' => 'atora_theme_global_lesson_template',
            'program' => 'atora_theme_global_program_template',
            'home' => 'atora_theme_global_home_template',
        ];

        foreach ($map as $field => $option) {
            $key = 'atora_wizard_' . $field;
            if (!isset($_POST[$key])) {
                continue;
            }
            $value = sanitize_key((string) wp_unslash($_POST[$key]));
            if (Atora_Template_Registry::get_template($value)) {
                update_option($option, $value);
            }
        }

        update_option('atora_theme_enable_basic_wizard', 1);

        wp_safe_redirect(add_query_arg('updated', '1', admin_url('admin.php?page=atora-theme-wizard')));
        exit;
    }
}
