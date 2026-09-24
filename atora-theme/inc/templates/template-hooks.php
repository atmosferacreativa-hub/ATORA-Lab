<?php
/**
 * Hooks y utilidades de rendering de secciones.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('atora_theme_render_registered_sections')) {
    function atora_theme_render_registered_sections($template_id, $context = []) {
        $template = Atora_Template_Registry::get_template((string) $template_id);
        if (!$template) {
            return;
        }

        $sections = isset($template['sections']) && is_array($template['sections']) ? $template['sections'] : [];
        $sections = apply_filters('atora_theme_section_order', $sections, $template_id, $context);

        foreach ($sections as $section_id) {
            Atora_Template_Renderer::render_section((string) $section_id, (array) $context);
        }
    }
}

if (!function_exists('atora_theme_filter_section_enabled_from_meta')) {
    function atora_theme_filter_section_enabled_from_meta($enabled, $section_id, $context = []) {
        $section_id = sanitize_key((string) $section_id);
        $post_id = isset($context['post_id']) ? absint($context['post_id']) : 0;

        if (!$post_id) {
            $post_id = isset($context['course_id']) ? absint($context['course_id']) : 0;
        }
        if (!$post_id) {
            $post_id = isset($context['lesson_id']) ? absint($context['lesson_id']) : 0;
        }
        if (!$post_id) {
            $post_id = isset($context['program_id']) ? absint($context['program_id']) : 0;
        }

        if (!$post_id || !$section_id) {
            return (bool) $enabled;
        }

        $map = [
            'course-hero' => [
                'course' => '_atora_theme_course_section_hero',
            ],
            'course-benefits' => [
                'course' => '_atora_theme_course_section_benefits',
            ],
            'course-curriculum' => [
                'course' => '_atora_theme_course_section_curriculum',
            ],
            'course-teacher-card' => [
                'course' => '_atora_theme_course_section_teacher-card',
            ],
            'course-price-card' => [
                'course' => '_atora_theme_course_section_price-card',
            ],
            'course-crm-lead-box' => [
                'course' => '_atora_theme_course_section_crm-lead-box',
            ],
            'course-faq' => [
                'course' => '_atora_theme_course_section_faq',
            ],
            'course-testimonials' => [
                'course' => '_atora_theme_course_section_testimonials',
            ],
            'course-related-courses' => [
                'course' => '_atora_theme_course_section_related-courses',
            ],
            'lesson-sidebar' => [
                'lesson' => '_atora_theme_lesson_section_sidebar',
            ],
            'lesson-progress' => [
                'lesson' => '_atora_theme_lesson_section_progress',
            ],
            'lesson-resources' => [
                'lesson' => '_atora_theme_lesson_section_resources',
            ],
            'lesson-navigation' => [
                'lesson' => '_atora_theme_lesson_section_navigation',
            ],
            'lesson-evaluation' => [
                'lesson' => '_atora_theme_lesson_section_evaluation',
            ],
            'lesson-video' => [
                'lesson' => '_atora_theme_lesson_section_video',
            ],
            'program-diploma-path' => [
                'program' => '_atora_theme_program_section_diploma-path',
            ],
            'program-courses' => [
                'program' => '_atora_theme_program_section_courses',
            ],
            'program-teachers' => [
                'program' => '_atora_theme_program_section_teachers',
            ],
            'program-certification' => [
                'program' => '_atora_theme_program_section_certification',
            ],
            'program-crm-lead-box' => [
                'program' => '_atora_theme_program_section_crm-lead-box',
            ],
            'program-faq' => [
                'program' => '_atora_theme_program_section_faq',
            ],
        ];

        if (!isset($map[$section_id])) {
            return (bool) $enabled;
        }

        $post_type = get_post_type($post_id);
        $type_map = [
            'lm_course' => 'course',
            'lm_lesson' => 'lesson',
            'lm_program' => 'program',
        ];
        $type = isset($type_map[$post_type]) ? $type_map[$post_type] : '';

        if (!$type || empty($map[$section_id][$type])) {
            return (bool) $enabled;
        }

        $meta_key = (string) $map[$section_id][$type];
        $value = get_post_meta($post_id, $meta_key, true);

        if ('' === $value) {
            return (bool) $enabled;
        }

        return (bool) absint($value);
    }
}
add_filter('atora_theme_section_enabled', 'atora_theme_filter_section_enabled_from_meta', 10, 3);

if (!function_exists('atora_theme_get_active_course_template')) {
    function atora_theme_get_active_course_template($course_id, $mode = null) {
        $course_id = absint($course_id);
        $mode = $mode ? sanitize_key((string) $mode) : '';

        $has_access = atora_theme_user_has_course_access($course_id);

        if (!$mode) {
            $mode = (!$has_access && (bool) get_option('atora_theme_enable_commercial_course_mode', true))
                ? 'commercial'
                : 'student';
        }

        if ('commercial' === $mode) {
            $template_id = (string) get_post_meta($course_id, '_atora_theme_course_template_commercial', true);
            if (!$template_id) {
                $template_id = (string) get_option('atora_theme_global_commercial_course_template', 'course-commercial');
            }
        } else {
            $template_id = (string) get_post_meta($course_id, '_atora_theme_course_template_student', true);
            if (!$template_id) {
                $template_id = (string) get_post_meta($course_id, '_atora_theme_course_template', true);
            }
            if (!$template_id) {
                $template_id = (string) get_option('atora_theme_global_student_course_template', 'course-student');
            }
        }

        if (!Atora_Template_Registry::get_template($template_id, 'course')) {
            $template_id = 'course-classic';
        }

        return apply_filters('atora_theme_course_template', $template_id, $course_id);
    }
}

if (!function_exists('atora_theme_get_active_lesson_template')) {
    function atora_theme_get_active_lesson_template($lesson_id) {
        $lesson_id = absint($lesson_id);
        $template_id = (string) get_post_meta($lesson_id, '_atora_theme_lesson_template', true);

        if (!$template_id) {
            $template_id = (string) get_option('atora_theme_global_lesson_template', 'lesson-reading');
        }

        if (!Atora_Template_Registry::get_template($template_id, 'lesson')) {
            $template_id = 'lesson-reading';
        }

        $course_id = Atora_Template_Context::get_lesson_course_id($lesson_id);

        return apply_filters('atora_theme_lesson_template', $template_id, $lesson_id, $course_id);
    }
}

if (!function_exists('atora_theme_get_active_program_template')) {
    function atora_theme_get_active_program_template($program_id, $mode = null) {
        $program_id = absint($program_id);
        $mode = $mode ? sanitize_key((string) $mode) : '';

        if (!$mode) {
            $mode = is_user_logged_in() ? 'overview' : 'commercial';
        }

        if ('commercial' === $mode) {
            $template_id = (string) get_post_meta($program_id, '_atora_theme_program_template_commercial', true);
            if (!$template_id) {
                $template_id = (string) get_option('atora_theme_global_program_template', 'program-commercial');
            }
        } else {
            $template_id = (string) get_post_meta($program_id, '_atora_theme_program_template', true);
            if (!$template_id) {
                $template_id = (string) get_option('atora_theme_global_program_template', 'program-academy');
            }
        }

        if (!Atora_Template_Registry::get_template($template_id, 'program')) {
            $template_id = 'program-academy';
        }

        return apply_filters('atora_theme_program_template', $template_id, $program_id);
    }
}

if (!function_exists('atora_theme_get_active_page_template')) {
    function atora_theme_get_active_page_template($page_id) {
        $page_id = absint($page_id);
        $template_id = (string) get_post_meta($page_id, '_atora_theme_page_template', true);

        if (!$template_id) {
            $is_front = (int) get_option('page_on_front', 0) === $page_id || is_front_page();
            $slug = sanitize_title((string) get_post_field('post_name', $page_id));
            $wp_template = (string) get_page_template_slug($page_id);

            if ($is_front) {
                $template_id = (string) get_option('atora_theme_global_home_template', 'home-academy');
            } elseif (false !== strpos($slug, 'landing') || false !== strpos($wp_template, 'landing')) {
                $template_id = (string) get_option('atora_theme_global_landing_template', 'landing-course');
            } else {
                $template_id = (string) get_option('atora_theme_global_page_template', 'page-fullwidth');
            }
        }

        $registered = Atora_Template_Registry::get_template($template_id);
        if (!$registered || !in_array($registered['type'], ['home', 'landing', 'page'], true)) {
            $template_id = 'page-fullwidth';
        }

        return apply_filters('atora_theme_page_template', $template_id, $page_id);
    }
}

if (!function_exists('atora_theme_get_active_post_template')) {
    function atora_theme_get_active_post_template($post_id) {
        $post_id = absint($post_id);
        $template_id = (string) get_post_meta($post_id, '_atora_theme_post_template', true);

        if (!$template_id) {
            $template_id = (string) get_option('atora_theme_global_post_template', 'post-editorial');
        }

        if (!Atora_Template_Registry::get_template($template_id, 'post')) {
            $template_id = 'post-editorial';
        }

        return apply_filters('atora_theme_post_template', $template_id, $post_id);
    }
}
