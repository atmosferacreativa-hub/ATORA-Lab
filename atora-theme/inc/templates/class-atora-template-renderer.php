<?php
/**
 * Renderer de templates y secciones.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Template_Renderer {

    public static function render(string $template_id, string $type, array $context = []): bool {
        $template_id = sanitize_key($template_id);
        $type = sanitize_key($type);

        if (!$template_id || !$type) {
            return false;
        }

        $file = Atora_Template_Loader::resolve_template_file($template_id, $type, $context);
        if (!$file) {
            return false;
        }

        do_action('atora_theme_before_template_render', $template_id, $type, $context);

        $atora_context = $context;
        $atora_template_id = $template_id;
        $atora_template_type = $type;

        include $file;

        do_action('atora_theme_after_template_render', $template_id, $type, $context);

        return true;
    }

    public static function render_template_part(string $part, array $context = []): bool {
        $part = trim(str_replace('..', '', wp_normalize_path($part)), '/');
        if (!$part) {
            return false;
        }

        $file = Atora_Template_Loader::locate_relative('templates/atora/' . $part . '.php');
        if (!$file) {
            $file = Atora_Template_Loader::locate_relative('templates/atora/sections/shared/notice.php');
        }

        if (!$file) {
            return false;
        }

        $atora_context = $context;
        include $file;

        return true;
    }

    public static function render_section(string $section_id, array $context = []): bool {
        $section_id = sanitize_key($section_id);
        if (!$section_id) {
            return false;
        }

        $enabled = apply_filters('atora_theme_section_enabled', true, $section_id, $context);
        if (!$enabled) {
            return false;
        }

        $section = Atora_Template_Registry::get_template($section_id, 'section');
        if (!$section) {
            return false;
        }

        do_action('atora_theme_before_section_render', $section_id, $context);

        $file = Atora_Template_Loader::resolve_template_file($section_id, 'section', $context);
        if (!$file) {
            return false;
        }

        $atora_context = $context;
        include $file;

        do_action('atora_theme_after_section_render', $section_id, $context);

        return true;
    }
}

if (!function_exists('atora_theme_render_course_template')) {
    function atora_theme_render_course_template($template_id, $context = []) {
        return Atora_Template_Renderer::render((string) $template_id, 'course', (array) $context);
    }
}

if (!function_exists('atora_theme_render_lesson_template')) {
    function atora_theme_render_lesson_template($template_id, $context = []) {
        return Atora_Template_Renderer::render((string) $template_id, 'lesson', (array) $context);
    }
}

if (!function_exists('atora_theme_render_program_template')) {
    function atora_theme_render_program_template($template_id, $context = []) {
        return Atora_Template_Renderer::render((string) $template_id, 'program', (array) $context);
    }
}

if (!function_exists('atora_theme_render_site_template')) {
    function atora_theme_render_site_template($template_id, $context = []) {
        $type = isset($context['type']) ? sanitize_key((string) $context['type']) : 'page';
        if (!in_array($type, ['home', 'landing', 'page', 'post'], true)) {
            $type = 'page';
        }

        return Atora_Template_Renderer::render((string) $template_id, $type, (array) $context);
    }
}

if (!function_exists('atora_theme_render_template_part')) {
    function atora_theme_render_template_part($part, $context = []) {
        return Atora_Template_Renderer::render_template_part((string) $part, (array) $context);
    }
}
