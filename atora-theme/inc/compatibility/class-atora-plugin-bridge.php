<?php
/**
 * Plugin Bridge seguro para ATORA Theme.
 *
 * Centraliza detección de plugin LMS + wrappers defensivos para evitar
 * fatals cuando el plugin no está activo.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('Atora_Theme_Plugin_Bridge')) {
    return;
}

final class Atora_Theme_Plugin_Bridge {

    /**
     * Detecta si ATORA LMS parece activo.
     */
    public static function is_lms_active(): bool {
        if (defined('ATORA_LMS_VERSION') || defined('ATORA_VERSION')) {
            return true;
        }

        if (class_exists('CLMS_Loader') || class_exists('CLMS_Helper')) {
            return true;
        }

        return (bool) apply_filters('atora_theme_bridge_lms_active', false);
    }

    /**
     * Wrapper defensivo para comprobar funciones del plugin.
     */
    public static function plugin_function_exists($function): bool {
        if (!is_string($function) || '' === trim($function)) {
            return false;
        }

        return function_exists($function);
    }

    /**
     * Obtiene progreso de usuario en curso (0-100).
     */
    public static function get_course_progress($course_id, $user_id = 0): float {
        $course_id = absint($course_id);
        $user_id   = self::normalize_user_id($user_id);

        if (!$course_id || !$user_id) {
            return 0.0;
        }

        $progress = apply_filters('atora_theme_user_course_progress', null, $user_id, $course_id);
        if (null !== $progress) {
            return self::clamp_progress($progress);
        }

        $stored = get_user_meta($user_id, '_clms_progress_' . $course_id, true);
        if ('' !== $stored && null !== $stored) {
            return self::clamp_progress($stored);
        }

        return 0.0;
    }

    /**
     * Comprueba acceso del usuario al curso.
     */
    public static function user_has_course_access($course_id, $user_id = 0): bool {
        $course_id = absint($course_id);
        $user_id   = self::normalize_user_id($user_id);

        if (!$course_id || !$user_id) {
            return false;
        }

        if (class_exists('CLMS_Helper') && method_exists('CLMS_Helper', 'user_is_enrolled_in_course')) {
            return (bool) CLMS_Helper::user_is_enrolled_in_course($user_id, $course_id);
        }

        $enrolled = apply_filters('atora_theme_is_enrolled', null, $user_id, $course_id);
        if (null !== $enrolled) {
            return (bool) $enrolled;
        }

        $courses = get_user_meta($user_id, '_clms_enrolled_courses', true);
        $courses = self::normalize_id_list($courses);

        return in_array($course_id, $courses, true);
    }

    /**
     * Obtiene IDs de lecciones asociadas al curso.
     *
     * @return int[]
     */
    public static function get_course_lessons($course_id): array {
        $course_id = absint($course_id);
        if (!$course_id) {
            return [];
        }

        if (class_exists('CLMS_Helper') && method_exists('CLMS_Helper', 'get_course_lessons')) {
            $ids = CLMS_Helper::get_course_lessons($course_id);
            return self::normalize_id_list($ids);
        }

        $lesson_ids = get_posts([
            'post_type'      => 'lm_lesson',
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'no_found_rows'  => true,
            'meta_query'     => [
                'relation' => 'OR',
                [
                    'key'   => '_clms_course_id',
                    'value' => $course_id,
                ],
                [
                    'key'   => 'lm_course_id',
                    'value' => $course_id,
                ],
                [
                    'key'   => '_lesson_course',
                    'value' => $course_id,
                ],
                [
                    'key'   => '_clms_lesson_course_id',
                    'value' => $course_id,
                ],
            ],
        ]);

        return self::normalize_id_list($lesson_ids);
    }

    /**
     * Obtiene IDs de docentes asociados al curso.
     *
     * @return int[]
     */
    public static function get_course_teachers($course_id): array {
        $course_id = absint($course_id);
        if (!$course_id) {
            return [];
        }

        $teacher_ids = get_post_meta($course_id, '_clms_course_teacher_ids', true);
        return self::normalize_id_list($teacher_ids);
    }

    /**
     * Normaliza user_id para wrappers (usa usuario actual como fallback).
     */
    private static function normalize_user_id($user_id): int {
        $user_id = absint($user_id);
        if ($user_id > 0) {
            return $user_id;
        }

        return get_current_user_id() ? absint(get_current_user_id()) : 0;
    }

    /**
     * Convierte cualquier lista de IDs a int[] limpio.
     *
     * @param mixed $value
     * @return int[]
     */
    private static function normalize_id_list($value): array {
        if (!is_array($value)) {
            $value = !empty($value) ? maybe_unserialize($value) : [];
        }

        if (!is_array($value)) {
            return [];
        }

        $value = array_map('absint', $value);
        $value = array_values(array_filter($value));

        return array_values(array_unique($value));
    }

    /**
     * Asegura progreso en rango 0..100.
     */
    private static function clamp_progress($progress): float {
        $progress = (float) $progress;

        if ($progress < 0) {
            return 0.0;
        }

        if ($progress > 100) {
            return 100.0;
        }

        return $progress;
    }
}

if (!function_exists('atora_theme_is_lms_active')) {
    function atora_theme_is_lms_active() {
        return Atora_Theme_Plugin_Bridge::is_lms_active();
    }
}

if (!function_exists('atora_theme_plugin_function_exists')) {
    function atora_theme_plugin_function_exists($function) {
        return Atora_Theme_Plugin_Bridge::plugin_function_exists($function);
    }
}

if (!function_exists('atora_theme_get_plugin_course_progress')) {
    function atora_theme_get_plugin_course_progress($course_id, $user_id = 0) {
        return Atora_Theme_Plugin_Bridge::get_course_progress($course_id, $user_id);
    }
}

if (!function_exists('atora_theme_plugin_user_has_access')) {
    function atora_theme_plugin_user_has_access($course_id, $user_id = 0) {
        return Atora_Theme_Plugin_Bridge::user_has_course_access($course_id, $user_id);
    }
}

if (!function_exists('atora_theme_plugin_get_course_lessons')) {
    function atora_theme_plugin_get_course_lessons($course_id) {
        return Atora_Theme_Plugin_Bridge::get_course_lessons($course_id);
    }
}

if (!function_exists('atora_theme_plugin_get_course_teachers')) {
    function atora_theme_plugin_get_course_teachers($course_id) {
        return Atora_Theme_Plugin_Bridge::get_course_teachers($course_id);
    }
}
