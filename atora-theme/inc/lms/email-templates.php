<?php
/**
 * Email Template Helpers
 * 
 * Provides filters and functions for managing email templates.
 * Plugin can override or extend email processing.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) exit;

/**
 * Get email template content
 */
if (!function_exists('atora_get_email_template')) {
    function atora_get_email_template($template_name, $variables = []) {
        $template_path = apply_filters('atora_email_template_path', 
            ATORA_THEME_DIR . '/template-emails/' . $template_name . '.php',
            $template_name
        );

        if (!file_exists($template_path)) {
            return '';
        }

        ob_start();
        if (!empty($variables) && is_array($variables)) {
            extract($variables, EXTR_SKIP);
        }
        include $template_path;
        $content = ob_get_clean();

        return apply_filters('atora_email_template_content', $content, $template_name, $variables);
    }
}

/**
 * Send enrollment email
 * Plugin can hook 'atora_send_enrollment_email' to override
 */
if (!function_exists('atora_send_enrollment_email')) {
    function atora_send_enrollment_email($user_id, $course_id) {
        $user = get_userdata($user_id);
        $course = get_post($course_id);

        if (!$user || !$course) return false;

        // Let plugin handle email if it wants to
        $result = apply_filters('atora_send_enrollment_email', null, $user, $course);
        if ($result !== null) return $result;

        // Default: use theme template
        $variables = [
            'user_name' => $user->first_name ?: $user->user_login,
            'course_title' => $course->post_title,
            'course_url' => get_permalink($course_id),
            'course_instructor' => '',
            'site_name' => get_bloginfo('name'),
            'site_url' => home_url(),
            'logo_url' => function_exists('atora_get_brand_logo_url') ? atora_get_brand_logo_url('full') : '',
        ];

        // Get instructor name if available
        $instructor_id = get_post_meta($course_id, '_course_instructor', true);
        if ($instructor_id) {
            $instructor = get_post($instructor_id);
            if ($instructor) $variables['course_instructor'] = $instructor->post_title;
        }

        $template = atora_get_email_template('course-enrollment', $variables);
        if (!$template) return false;

        $subject = apply_filters('atora_enrollment_email_subject',
            sprintf(__('Welcome to "%s"', 'atora-learning'), $course->post_title),
            $user, $course
        );

        $headers = ['Content-Type: text/html; charset=UTF-8'];
        $headers = apply_filters('atora_enrollment_email_headers', $headers, $user, $course);

        return wp_mail($user->user_email, $subject, $template, $headers);
    }
}

/**
 * Send completion email
 */
if (!function_exists('atora_send_completion_email')) {
    function atora_send_completion_email($user_id, $course_id) {
        $user = get_userdata($user_id);
        $course = get_post($course_id);

        if (!$user || !$course) return false;

        // Let plugin handle email if it wants to
        $result = apply_filters('atora_send_completion_email', null, $user, $course);
        if ($result !== null) return $result;

        // Default: use theme template
        $variables = [
            'user_name' => $user->first_name ?: $user->user_login,
            'course_title' => $course->post_title,
            'course_url' => get_permalink($course_id),
            'certificate_url' => apply_filters('atora_user_certificate_url', '#', $user_id, $course_id),
            'completion_date' => current_time('F d, Y'),
            'site_name' => get_bloginfo('name'),
            'site_url' => home_url(),
            'logo_url' => function_exists('atora_get_brand_logo_url') ? atora_get_brand_logo_url('full') : '',
        ];

        $template = atora_get_email_template('course-completion', $variables);
        if (!$template) return false;

        $subject = apply_filters('atora_completion_email_subject',
            sprintf(__('Congratulations! You completed "%s"', 'atora-learning'), $course->post_title),
            $user, $course
        );

        $headers = ['Content-Type: text/html; charset=UTF-8'];
        $headers = apply_filters('atora_completion_email_headers', $headers, $user, $course);

        return wp_mail($user->user_email, $subject, $template, $headers);
    }
}

/**
 * Send enrollment email after enrollment action
 */
add_action('atora_theme_after_enroll', function($course_id, $user_id) {
    atora_send_enrollment_email($user_id, $course_id);
}, 20, 2);

/**
 * Send completion email when course is completed
 */
add_action('atora_lms_course_completed', function($course_id, $user_id) {
    atora_send_completion_email($user_id, $course_id);
}, 10, 2);
