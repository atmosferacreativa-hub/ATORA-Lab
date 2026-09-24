<?php
/**
 * Atora LMS CRM Integration
 *
 * Provides helpers to create/update leads in the CRM plugin
 * and log events for enrollment, completion, contact form submissions.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) exit;

/**
 * Create or update a lead in the CRM
 * Plugin can override via 'atora_theme_crm_create_lead' filter
 */
if (!function_exists('atora_crm_create_lead')) {
    function atora_crm_create_lead($email, $first_name = '', $last_name = '', $phone = '', $meta = []) {
        $lead_data = [
            'email' => sanitize_email($email),
            'first_name' => sanitize_text_field($first_name),
            'last_name' => sanitize_text_field($last_name),
            'phone' => sanitize_text_field($phone),
            'meta' => $meta,
        ];

        // Let plugin handle lead creation
        $result = apply_filters('atora_theme_crm_create_lead', null, $lead_data);
        if ($result !== null) return $result;

        // Fallback: store in post meta (for theme to track)
        $leads = get_option('atora_crm_leads', []);
        if (!is_array($leads)) $leads = [];
        
        $lead_id = wp_generate_uuid4();
        $leads[$lead_id] = array_merge($lead_data, [
            'created_at' => current_time('mysql'),
            'id' => $lead_id,
        ]);

        update_option('atora_crm_leads', $leads);
        return $lead_id;
    }
}

/**
 * Log a CRM event (enrollment, completion, contact, etc.)
 */
if (!function_exists('atora_crm_log_event')) {
    function atora_crm_log_event($email, $event_type, $event_data = []) {
        $event = [
            'email' => sanitize_email($email),
            'type' => sanitize_key($event_type),
            'data' => $event_data,
            'timestamp' => current_time('mysql'),
        ];

        // Let plugin handle event logging
        do_action('atora_theme_crm_event', $event);

        // Fallback: store in option
        $events = get_option('atora_crm_events', []);
        if (!is_array($events)) $events = [];
        $events[] = $event;
        update_option('atora_crm_events', $events);
    }
}

/**
 * Hook into enrollment to log CRM event
 */
add_action('atora_theme_after_enroll', function($course_id, $user_id) {
    $user = get_userdata($user_id);
    if (!$user) return;

    $course = get_post($course_id);
    if (!$course) return;

    // Create lead if new
    atora_crm_create_lead(
        $user->user_email,
        $user->first_name,
        $user->last_name,
        get_user_meta($user_id, 'billing_phone', true),
        ['user_id' => $user_id]
    );

    // Log enrollment event
    atora_crm_log_event($user->user_email, 'enrollment', [
        'course_id' => $course_id,
        'course_title' => $course->post_title,
        'user_id' => $user_id,
    ]);
}, 10, 2);

/**
 * Hook for course completion (plugin should fire 'atora_lms_course_completed' action)
 */
add_action('atora_lms_course_completed', function($course_id, $user_id) {
    $user = get_userdata($user_id);
    if (!$user) return;

    $course = get_post($course_id);
    if (!$course) return;

    // Log completion event
    atora_crm_log_event($user->user_email, 'course_completed', [
        'course_id' => $course_id,
        'course_title' => $course->post_title,
        'user_id' => $user_id,
        'completed_at' => current_time('mysql'),
    ]);
}, 10, 2);

/**
 * Contact form integration (theme can use this)
 */
if (!function_exists('atora_crm_contact_form_submit')) {
    function atora_crm_contact_form_submit($data) {
        $lead_id = atora_crm_create_lead(
            $data['email'] ?? '',
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
            $data['phone'] ?? '',
            $data
        );

        atora_crm_log_event($data['email'] ?? '', 'contact_form', [
            'lead_id' => $lead_id,
            'subject' => $data['subject'] ?? '',
            'message' => $data['message'] ?? '',
        ]);

        return $lead_id;
    }
}
