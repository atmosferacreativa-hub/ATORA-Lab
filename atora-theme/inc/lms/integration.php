<?php
/**
 * Atora LMS v5 Integration Helpers
 *
 * Delegates to CLMS_Helper (plugin) when available.
 * All fallbacks use the plugin's canonical meta keys so data written by either
 * side is always readable by the other.
 *
 * Meta key reference (CLMS_Helper constants):
 *   USER_ENROLLED_META           = '_clms_enrolled_courses'
 *   COURSE_META_KEY              = '_clms_course_id'       (lesson → course)
 *   COURSE_META_KEY_LEGACY       = 'lm_course_id'
 *   _clms_course_teacher_ids     (course → teacher post IDs, array)
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) exit;

/* -----------------------------------------------------------------------
   1. atora_lms_user_has_access()
   ----------------------------------------------------------------------- */
if (!function_exists('atora_lms_user_has_access')) {
    function atora_lms_user_has_access($user_id, $course_id) {
        $user_id   = absint($user_id);
        $course_id = absint($course_id);

        // Delegate to plugin's authoritative method
        if (class_exists('CLMS_Helper') && method_exists('CLMS_Helper', 'user_is_enrolled_in_course')) {
            return CLMS_Helper::user_is_enrolled_in_course($user_id, $course_id);
        }

        // Allow plugin to respond via filter
        $res = apply_filters('atora_theme_is_enrolled', null, $user_id, $course_id);
        if ($res !== null) {
            return (bool) $res;
        }

        if (!$user_id) return false;

        // Fallback: read plugin's canonical enrolled-courses user meta
        $enrolled = get_user_meta($user_id, '_clms_enrolled_courses', true);
        if (!is_array($enrolled)) {
            $enrolled = !empty($enrolled) ? maybe_unserialize($enrolled) : [];
            if (!is_array($enrolled)) $enrolled = [];
        }

        return in_array($course_id, array_map('intval', $enrolled), true);
    }
}

/* -----------------------------------------------------------------------
   2. atora_lms_get_progress()
   ----------------------------------------------------------------------- */
if (!function_exists('atora_lms_get_progress')) {
    function atora_lms_get_progress($user_id, $course_id) {
        $user_id   = absint($user_id);
        $course_id = absint($course_id);

        // Allow plugin to provide progress via filter
        $progress = apply_filters('atora_theme_user_course_progress', null, $user_id, $course_id);
        if ($progress !== null) return floatval($progress);

        if (!$user_id || !$course_id) return 0.0;

        // Fallback: plugin stores per-course progress in user meta '_clms_progress_{course_id}'
        $stored = get_user_meta($user_id, '_clms_progress_' . $course_id, true);
        if ($stored !== '') return floatval($stored);

        return 0.0;
    }
}

/* -----------------------------------------------------------------------
   3. Lesson → course ID helper
   ----------------------------------------------------------------------- */
if (!function_exists('atora_theme_get_lesson_course_id')) {
    function atora_theme_get_lesson_course_id($lesson_id) {
        $lesson_id = absint($lesson_id);
        if (!$lesson_id) return 0;

        // Delegate to plugin
        if (class_exists('CLMS_Helper') && method_exists('CLMS_Helper', 'get_course_id_from_lesson')) {
            return (int) CLMS_Helper::get_course_id_from_lesson($lesson_id);
        }

        // Canonical key first, then legacy fallbacks
        $course_id = (int) get_post_meta($lesson_id, '_clms_course_id', true);
        if (!$course_id) $course_id = (int) get_post_meta($lesson_id, 'lm_course_id', true);
        if (!$course_id) $course_id = (int) get_post_meta($lesson_id, '_lesson_course', true);

        return $course_id;
    }
}

/* -----------------------------------------------------------------------
   4. Course → lessons helper
   ----------------------------------------------------------------------- */
if (!function_exists('atora_theme_get_course_lessons')) {
    function atora_theme_get_course_lessons($course_id) {
        $course_id = absint($course_id);
        if (!$course_id) return [];

        // Delegate to plugin (handles all legacy keys + caching)
        if (class_exists('CLMS_Helper') && method_exists('CLMS_Helper', 'get_course_lessons')) {
            $ids = CLMS_Helper::get_course_lessons($course_id);
            if (!empty($ids)) {
                return get_posts([
                    'post_type'      => 'lm_lesson',
                    'post__in'       => $ids,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                    'posts_per_page' => -1,
                    'no_found_rows'  => true,
                ]);
            }
            return [];
        }

        // Fallback: try canonical key, then legacy
        $meta_keys = ['_clms_course_id', 'lm_course_id', '_lesson_course'];
        foreach ($meta_keys as $key) {
            $lessons = get_posts([
                'post_type'      => 'lm_lesson',
                'meta_key'       => $key,
                'meta_value'     => $course_id,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'posts_per_page' => -1,
                'no_found_rows'  => true,
            ]);
            if (!empty($lessons)) return $lessons;
        }

        return [];
    }
}

/* -----------------------------------------------------------------------
   5. Program → courses helper
   ----------------------------------------------------------------------- */
if (!function_exists('atora_theme_get_program_courses')) {
    function atora_theme_get_program_courses($program_id) {
        $program_id = absint($program_id);
        if (!$program_id) return [];

        // Delegate to plugin
        if (class_exists('CLMS_Helper') && method_exists('CLMS_Helper', 'get_program_courses')) {
            $ids = CLMS_Helper::get_program_courses($program_id);
            if (!empty($ids)) {
                return get_posts([
                    'post_type'      => 'lm_course',
                    'post__in'       => $ids,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                    'posts_per_page' => -1,
                    'no_found_rows'  => true,
                ]);
            }
            return [];
        }

        // Fallback: plugin stores courses in post meta '_clms_program_courses'
        $ids = get_post_meta($program_id, '_clms_program_courses', true);
        if (!is_array($ids) || empty($ids)) return [];

        return get_posts([
            'post_type'      => 'lm_course',
            'post__in'       => array_map('absint', $ids),
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
        ]);
    }
}

/* -----------------------------------------------------------------------
   6. Course → teacher posts helper
   ----------------------------------------------------------------------- */
if (!function_exists('atora_theme_get_course_teachers')) {
    function atora_theme_get_course_teachers($course_id) {
        $course_id = absint($course_id);
        if (!$course_id) return [];

        $ids = get_post_meta($course_id, '_clms_course_teacher_ids', true);
        if (!is_array($ids)) $ids = [];
        $ids = array_values(array_filter(array_map('absint', $ids)));
        if (empty($ids)) return [];

        return get_posts([
            'post_type'      => 'atora_teacher',
            'post__in'       => $ids,
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            'orderby'        => 'post__in',
        ]);
    }
}

/* -----------------------------------------------------------------------
   7. AJAX: enroll user in course
   ----------------------------------------------------------------------- */
add_action('wp_ajax_atora_theme_enroll', 'atora_theme_ajax_enroll');
add_action('wp_ajax_nopriv_atora_theme_enroll', 'atora_theme_ajax_enroll');

function atora_theme_ajax_enroll() {
    check_ajax_referer('atora-theme-nonce', 'nonce');

    $course_id = isset($_POST['course_id']) ? absint($_POST['course_id']) : 0;
    $user_id   = get_current_user_id();

    if (!$course_id) {
        wp_send_json_error(['message' => __('Invalid course ID', 'atora-learning')], 400);
    }

    if (!$user_id) {
        wp_send_json_error(['message' => 'login_required'], 401);
    }

    // Let plugin handle enrollment (highest priority)
    $plugin_result = apply_filters('atora_theme_enroll', null, $course_id, $user_id, $_POST);
    if ($plugin_result !== null) {
        do_action('atora_theme_after_enroll', $course_id, $user_id);
        wp_send_json_success(['message' => 'enrolled', 'result' => $plugin_result]);
    }

    // Delegate to CLMS_Helper when available
    if (class_exists('CLMS_Helper') && method_exists('CLMS_Helper', 'enroll_user_in_course')) {
        $result = CLMS_Helper::enroll_user_in_course($user_id, $course_id);
        if ($result !== false && !is_wp_error($result)) {
            do_action('atora_theme_after_enroll', $course_id, $user_id);
            wp_send_json_success(['message' => 'enrolled', 'result' => true]);
        }
        wp_send_json_error(['message' => __('Enrollment failed', 'atora-learning')]);
    }

    // Fallback: store in plugin's canonical user meta
    $enrolled = get_user_meta($user_id, '_clms_enrolled_courses', true);
    if (!is_array($enrolled)) {
        $enrolled = !empty($enrolled) ? maybe_unserialize($enrolled) : [];
        if (!is_array($enrolled)) $enrolled = [];
    }

    if (!in_array($course_id, array_map('intval', $enrolled), true)) {
        $enrolled[] = $course_id;
        update_user_meta($user_id, '_clms_enrolled_courses', $enrolled);
    }

    do_action('atora_theme_after_enroll', $course_id, $user_id);
    wp_send_json_success(['message' => 'enrolled', 'result' => true]);
}

/* -----------------------------------------------------------------------
   8. AJAX: get user progress
   ----------------------------------------------------------------------- */
add_action('wp_ajax_atora_theme_get_progress', 'atora_theme_ajax_get_progress');
add_action('wp_ajax_nopriv_atora_theme_get_progress', 'atora_theme_ajax_get_progress');

function atora_theme_ajax_get_progress() {
    check_ajax_referer('atora-theme-nonce', 'nonce');

    $course_id = isset($_REQUEST['course_id']) ? absint($_REQUEST['course_id']) : 0;
    $user_id   = get_current_user_id();

    $progress = atora_lms_get_progress($user_id, $course_id);

    wp_send_json_success(['progress' => floatval($progress)]);
}

/* -----------------------------------------------------------------------
   9. Listen to plugin hooks → update theme-side state
   ----------------------------------------------------------------------- */

// When plugin marks a lesson complete, fire theme action for UI refresh
add_action('clms_lesson_completed', function($user_id, $lesson_id, $course_id) {
    do_action('atora_theme_lesson_completed', $user_id, $lesson_id, $course_id);
}, 10, 3);

// When plugin marks a course complete, fire theme action for UI/certificate
add_action('clms_course_completed', function($user_id, $course_id) {
    do_action('atora_theme_course_completed', $user_id, $course_id);
}, 10, 2);

// When commerce enrolls a user, fire theme after_enroll so CRM/email templates trigger
add_action('clms_commerce_user_enrolled_from_order', function($user_id, $course_id, $order_id, $product_id) {
    do_action('atora_theme_after_enroll', $course_id, $user_id);
}, 10, 4);
