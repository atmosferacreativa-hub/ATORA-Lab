<?php
/**
 * Importador de configuraciones de templates.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Template_Importer {

    public static function init(): void {
        if (!is_admin()) {
            return;
        }

        add_action('admin_post_atora_theme_import_template_settings', [__CLASS__, 'import']);
    }

    public static function import(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('No autorizado.', 'atora-learning'));
        }

        check_admin_referer('atora_theme_import_template_settings');

        if (empty($_FILES['atora_theme_template_settings_file']['tmp_name'])) {
            wp_safe_redirect(add_query_arg('import', 'empty', wp_get_referer()));
            exit;
        }

        $raw = file_get_contents((string) $_FILES['atora_theme_template_settings_file']['tmp_name']);
        $data = json_decode((string) $raw, true);

        if (!is_array($data)) {
            wp_safe_redirect(add_query_arg('import', 'invalid', wp_get_referer()));
            exit;
        }

        foreach ($data as $option => $value) {
            if (0 === strpos((string) $option, 'atora_theme_')) {
                update_option(sanitize_key((string) $option), is_scalar($value) ? sanitize_text_field((string) $value) : $value);
            }
        }

        wp_safe_redirect(add_query_arg('import', 'ok', wp_get_referer()));
        exit;
    }
}
