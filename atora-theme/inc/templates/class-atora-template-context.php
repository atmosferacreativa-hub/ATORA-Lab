<?php
/**
 * Context builder para templates ATORA.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Template_Context {

    public static function get_template_context($post_id, $type = null): array {
        $post_id = absint($post_id);
        if (!$post_id) {
            return [];
        }

        $post = get_post($post_id);
        if (!$post instanceof WP_Post) {
            return [];
        }

        $detected_type = $type ? sanitize_key((string) $type) : sanitize_key((string) $post->post_type);

        switch ($detected_type) {
            case 'lm_course':
            case 'course':
                $context = self::get_course_context($post_id);
                break;
            case 'lm_lesson':
            case 'lesson':
                $context = self::get_lesson_context($post_id);
                break;
            case 'lm_program':
            case 'program':
                $context = self::get_program_context($post_id);
                break;
            case 'post':
                $context = self::get_post_context($post_id);
                break;
            case 'page':
            default:
                $context = self::get_page_context($post_id);
                break;
        }

        return apply_filters('atora_theme_template_context', (array) $context, $post_id, $detected_type);
    }

    public static function get_course_context($course_id): array {
        $course_id = absint($course_id);
        $user_id = get_current_user_id();

        $teachers = function_exists('atora_theme_get_course_teachers')
            ? (array) atora_theme_get_course_teachers($course_id)
            : [];

        $lesson_ids = function_exists('atora_theme_plugin_get_course_lessons')
            ? (array) atora_theme_plugin_get_course_lessons($course_id)
            : [];

        $lessons = !empty($lesson_ids)
            ? get_posts([
                'post_type' => 'lm_lesson',
                'post__in' => array_map('absint', $lesson_ids),
                'posts_per_page' => -1,
                'orderby' => 'post__in',
                'no_found_rows' => true,
            ])
            : [];

        $programs = get_posts([
            'post_type' => 'lm_program',
            'posts_per_page' => 6,
            'no_found_rows' => true,
            'meta_query' => [
                [
                    'key' => '_clms_program_courses',
                    'value' => '"' . $course_id . '"',
                    'compare' => 'LIKE',
                ],
            ],
        ]);

        $has_access = self::user_has_course_access($course_id, $user_id);
        $progress = self::get_course_progress($course_id, $user_id);
        $product_id = self::get_course_product_id($course_id);

        $context = [
            'post_id' => $course_id,
            'course_id' => $course_id,
            'user_id' => $user_id,
            'title' => get_the_title($course_id),
            'content' => apply_filters('the_content', (string) get_post_field('post_content', $course_id)),
            'excerpt' => get_the_excerpt($course_id),
            'thumbnail' => get_the_post_thumbnail_url($course_id, 'large'),
            'has_access' => $has_access,
            'is_enrolled' => $has_access,
            'progress' => $progress,
            'teachers' => $teachers,
            'lessons' => $lessons,
            'programs' => $programs,
            'product_id' => $product_id,
            'purchase_url' => self::get_course_purchase_url($course_id),
            'crm_enabled' => class_exists('Atora_CRM_Bridge') ? Atora_CRM_Bridge::is_available() : function_exists('atora_crm_contact_form_submit'),
            'woocommerce_enabled' => class_exists('Atora_WooCommerce_Bridge') ? Atora_WooCommerce_Bridge::is_available() : class_exists('WooCommerce'),
        ];

        return apply_filters('atora_theme_course_context', $context, $course_id);
    }

    public static function get_lesson_context($lesson_id): array {
        $lesson_id = absint($lesson_id);
        $course_id = self::get_lesson_course_id($lesson_id);
        $user_id = get_current_user_id();

        $course_title = $course_id ? get_the_title($course_id) : '';
        $has_access = $course_id ? self::user_has_course_access($course_id, $user_id) : is_user_logged_in();

        $previous = null;
        $next = null;

        if ($course_id) {
            $lesson_ids = function_exists('atora_theme_plugin_get_course_lessons')
                ? (array) atora_theme_plugin_get_course_lessons($course_id)
                : [];

            if (!empty($lesson_ids)) {
                $lesson_ids = array_values(array_map('absint', $lesson_ids));
                $current_idx = array_search($lesson_id, $lesson_ids, true);
                if (false !== $current_idx) {
                    $prev_id = $lesson_ids[$current_idx - 1] ?? 0;
                    $next_id = $lesson_ids[$current_idx + 1] ?? 0;
                    $previous = $prev_id ? get_post($prev_id) : null;
                    $next = $next_id ? get_post($next_id) : null;
                }
            }
        }

        $context = [
            'post_id' => $lesson_id,
            'lesson_id' => $lesson_id,
            'course_id' => $course_id,
            'user_id' => $user_id,
            'title' => get_the_title($lesson_id),
            'content' => apply_filters('the_content', (string) get_post_field('post_content', $lesson_id)),
            'has_access' => $has_access,
            'course_title' => $course_title,
            'progress' => $course_id ? self::get_course_progress($course_id, $user_id) : 0,
            'previous' => $previous,
            'next' => $next,
            'resources' => self::normalize_lines(get_post_meta($lesson_id, '_clms_lesson_resources', true)),
            'lesson_type' => sanitize_key((string) get_post_meta($lesson_id, 'lm_activity_type', true)),
            'thumbnail' => get_the_post_thumbnail_url($lesson_id, 'large'),
            'back_url' => $course_id ? get_permalink($course_id) : home_url('/'),
        ];

        return apply_filters('atora_theme_lesson_context', $context, $lesson_id);
    }

    public static function get_program_context($program_id): array {
        $program_id = absint($program_id);
        $user_id = get_current_user_id();

        $courses = function_exists('atora_theme_get_program_courses')
            ? (array) atora_theme_get_program_courses($program_id)
            : [];

        $teachers = [];
        foreach ($courses as $course) {
            $course_teachers = function_exists('atora_theme_get_course_teachers')
                ? (array) atora_theme_get_course_teachers($course->ID)
                : [];
            foreach ($course_teachers as $teacher) {
                if ($teacher instanceof WP_Post) {
                    $teachers[$teacher->ID] = $teacher;
                }
            }
        }

        $has_access = is_user_logged_in();
        $progress = 0;

        if (!empty($courses) && $user_id) {
            $progress_values = [];
            foreach ($courses as $course) {
                $progress_values[] = self::get_course_progress($course->ID, $user_id);
            }
            $progress = !empty($progress_values) ? (float) round(array_sum($progress_values) / count($progress_values), 2) : 0;
        }

        $context = [
            'post_id' => $program_id,
            'program_id' => $program_id,
            'user_id' => $user_id,
            'title' => get_the_title($program_id),
            'content' => apply_filters('the_content', (string) get_post_field('post_content', $program_id)),
            'courses' => $courses,
            'teachers' => array_values($teachers),
            'has_access' => $has_access,
            'progress' => $progress,
            'crm_enabled' => class_exists('Atora_CRM_Bridge') ? Atora_CRM_Bridge::is_available() : function_exists('atora_crm_contact_form_submit'),
            'woocommerce_enabled' => class_exists('Atora_WooCommerce_Bridge') ? Atora_WooCommerce_Bridge::is_available() : class_exists('WooCommerce'),
            'thumbnail' => get_the_post_thumbnail_url($program_id, 'large'),
        ];

        return apply_filters('atora_theme_program_context', $context, $program_id);
    }

    public static function get_page_context($page_id): array {
        $page_id = absint($page_id);

        return [
            'post_id' => $page_id,
            'page_id' => $page_id,
            'title' => get_the_title($page_id),
            'content' => apply_filters('the_content', (string) get_post_field('post_content', $page_id)),
            'thumbnail' => get_the_post_thumbnail_url($page_id, 'large'),
            'type' => 'page',
        ];
    }

    public static function get_post_context($post_id): array {
        $post_id = absint($post_id);

        return [
            'post_id' => $post_id,
            'title' => get_the_title($post_id),
            'content' => apply_filters('the_content', (string) get_post_field('post_content', $post_id)),
            'excerpt' => get_the_excerpt($post_id),
            'thumbnail' => get_the_post_thumbnail_url($post_id, 'large'),
            'author_name' => get_the_author_meta('display_name', (int) get_post_field('post_author', $post_id)),
            'date' => get_the_date('', $post_id),
            'type' => 'post',
        ];
    }

    public static function user_has_course_access($course_id, $user_id = null): bool {
        $course_id = absint($course_id);
        $user_id = absint($user_id ?: get_current_user_id());

        if (!$course_id || !$user_id) {
            return false;
        }

        if (function_exists('atora_theme_plugin_user_has_access')) {
            return (bool) atora_theme_plugin_user_has_access($course_id, $user_id);
        }

        return false;
    }

    public static function get_course_progress($course_id, $user_id = null): float {
        $course_id = absint($course_id);
        $user_id = absint($user_id ?: get_current_user_id());

        if (!$course_id || !$user_id) {
            return 0.0;
        }

        if (function_exists('atora_theme_get_plugin_course_progress')) {
            return (float) atora_theme_get_plugin_course_progress($course_id, $user_id);
        }

        return 0.0;
    }

    public static function get_lesson_course_id($lesson_id): int {
        $lesson_id = absint($lesson_id);

        if (!$lesson_id) {
            return 0;
        }

        if (function_exists('atora_theme_get_lesson_course_id')) {
            return (int) atora_theme_get_lesson_course_id($lesson_id);
        }

        $keys = ['_clms_course_id', 'lm_course_id', '_lesson_course', '_clms_lesson_course_id'];
        foreach ($keys as $key) {
            $course_id = (int) get_post_meta($lesson_id, $key, true);
            if ($course_id) {
                return $course_id;
            }
        }

        return 0;
    }

    public static function get_course_product_id($course_id): int {
        $course_id = absint($course_id);
        if (!$course_id) {
            return 0;
        }

        if (class_exists('Atora_WooCommerce_Bridge')) {
            return (int) Atora_WooCommerce_Bridge::get_course_product_id($course_id);
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
        $course_id = absint($course_id);

        if (class_exists('Atora_WooCommerce_Bridge')) {
            return (string) Atora_WooCommerce_Bridge::get_course_purchase_url($course_id);
        }

        $product_id = self::get_course_product_id($course_id);

        return $product_id ? (string) get_permalink($product_id) : '';
    }

    private static function normalize_lines($raw): array {
        if (is_array($raw)) {
            $lines = $raw;
        } else {
            $raw = (string) $raw;
            $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        }

        $lines = array_map('sanitize_text_field', $lines);
        $lines = array_values(array_filter($lines));

        return $lines;
    }
}

if (!function_exists('atora_theme_get_template_context')) {
    function atora_theme_get_template_context($post_id, $type = null) {
        return Atora_Template_Context::get_template_context($post_id, $type);
    }
}

if (!function_exists('atora_theme_get_course_context')) {
    function atora_theme_get_course_context($course_id) {
        return Atora_Template_Context::get_course_context($course_id);
    }
}

if (!function_exists('atora_theme_get_lesson_context')) {
    function atora_theme_get_lesson_context($lesson_id) {
        return Atora_Template_Context::get_lesson_context($lesson_id);
    }
}

if (!function_exists('atora_theme_get_program_context')) {
    function atora_theme_get_program_context($program_id) {
        return Atora_Template_Context::get_program_context($program_id);
    }
}

if (!function_exists('atora_theme_user_has_course_access')) {
    function atora_theme_user_has_course_access($course_id, $user_id = null) {
        return Atora_Template_Context::user_has_course_access($course_id, $user_id);
    }
}

if (!function_exists('atora_theme_get_course_progress')) {
    function atora_theme_get_course_progress($course_id, $user_id = null) {
        return Atora_Template_Context::get_course_progress($course_id, $user_id);
    }
}

if (!function_exists('atora_theme_get_course_product_id')) {
    function atora_theme_get_course_product_id($course_id) {
        return Atora_Template_Context::get_course_product_id($course_id);
    }
}

if (!function_exists('atora_theme_get_course_purchase_url')) {
    function atora_theme_get_course_purchase_url($course_id) {
        return Atora_Template_Context::get_course_purchase_url($course_id);
    }
}
