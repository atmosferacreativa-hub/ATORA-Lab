<?php
/**
 * Bridge visual CRM para el theme.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_CRM_Bridge {

    public static function init(): void {
        add_action('wp_ajax_atora_theme_submit_crm_lead', [__CLASS__, 'handle_ajax']);
        add_action('wp_ajax_nopriv_atora_theme_submit_crm_lead', [__CLASS__, 'handle_ajax']);
    }

    public static function is_available(): bool {
        if (function_exists('atora_crm_contact_form_submit')) {
            return true;
        }

        if (has_filter('atora_theme_crm_create_lead') || has_action('atora_theme_crm_event')) {
            return true;
        }

        return false;
    }

    public static function submit_lead(array $data) {
        $payload = [
            'email' => sanitize_email((string) ($data['email'] ?? '')),
            'first_name' => sanitize_text_field((string) ($data['first_name'] ?? '')),
            'last_name' => sanitize_text_field((string) ($data['last_name'] ?? '')),
            'phone' => sanitize_text_field((string) ($data['phone'] ?? '')),
            'course_id' => absint($data['course_id'] ?? 0),
            'program_id' => absint($data['program_id'] ?? 0),
            'source' => sanitize_key((string) ($data['source'] ?? 'theme-section')),
        ];

        if (!$payload['email']) {
            return new WP_Error('atora_crm_missing_email', __('El correo es obligatorio.', 'atora-learning'));
        }

        if (function_exists('atora_crm_contact_form_submit')) {
            return atora_crm_contact_form_submit($payload);
        }

        $result = apply_filters('atora_theme_crm_create_lead', null, $payload);
        if (null !== $result) {
            do_action('atora_theme_crm_event', [
                'email' => $payload['email'],
                'type' => 'theme_section_capture',
                'data' => $payload,
                'timestamp' => current_time('mysql'),
            ]);
            return $result;
        }

        return new WP_Error('atora_crm_unavailable', __('CRM no disponible en este momento.', 'atora-learning'));
    }

    public static function handle_ajax(): void {
        check_ajax_referer('atora-theme-nonce', 'nonce');

        $result = self::submit_lead($_POST);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 400);
        }

        wp_send_json_success(['message' => __('Gracias. Te contactaremos pronto.', 'atora-learning'), 'lead_id' => $result]);
    }
}
