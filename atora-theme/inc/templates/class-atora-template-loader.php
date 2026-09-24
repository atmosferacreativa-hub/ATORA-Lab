<?php
/**
 * Resolver de rutas para templates.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Template_Loader {

    public static function resolve_template_file(string $template_id, $type = null, array $context = []): string {
        $template_id = sanitize_key($template_id);
        $type = $type ? sanitize_key((string) $type) : null;

        $template = Atora_Template_Registry::get_template($template_id, $type);

        if (!$template && $type) {
            $fallback_id = self::get_fallback_template_id_for_type($type);
            $template = Atora_Template_Registry::get_template($fallback_id, $type);
            $template_id = $fallback_id;
        }

        // If the plugin UI is available, prefer a plugin-shell thin wrapper
        // that delegates rendering to the plugin's resolver/engine. This
        // keeps the theme as a presentation layer while the plugin owns
        // the schema/section rendering.
        $plugin_types = array('course', 'lesson', 'program', 'home', 'landing', 'page', 'post');
        if (class_exists('CLMS_UI_Template_Resolver') && in_array($type, $plugin_types, true)) {
            $plugin_shell = self::locate_relative('templates/atora/shared/plugin-shell.php');
            if ($plugin_shell) {
                return $plugin_shell;
            }
        }

        $relative = is_array($template) ? (string) ($template['file'] ?? '') : '';
        $file = self::locate_relative($relative);

        if (!$file) {
            $file = self::locate_relative('templates/atora/shared/fallback.php');
        }

        $file = apply_filters('atora_theme_template_file', $file, $template_id, $type, $context);

        if (!self::is_safe_file($file)) {
            $file = self::locate_relative('templates/atora/shared/fallback.php');
        }

        return $file ?: '';
    }

    public static function locate_relative(string $relative): string {
        $relative = ltrim(wp_normalize_path($relative), '/');

        if (!$relative || false !== strpos($relative, '..') || !preg_match('#^[a-zA-Z0-9_\-/\.]+$#', $relative)) {
            return '';
        }

        $candidates = [
            trailingslashit(wp_normalize_path(get_stylesheet_directory())) . $relative,
            trailingslashit(wp_normalize_path(get_template_directory())) . $relative,
        ];

        foreach ($candidates as $candidate) {
            if (self::is_safe_file($candidate)) {
                return $candidate;
            }
        }

        return '';
    }

    private static function is_safe_file(string $file): bool {
        if (!$file) {
            return false;
        }

        $normalized = wp_normalize_path($file);
        $template_root = trailingslashit(wp_normalize_path(get_template_directory()));
        $stylesheet_root = trailingslashit(wp_normalize_path(get_stylesheet_directory()));

        if (0 !== strpos($normalized, $template_root) && 0 !== strpos($normalized, $stylesheet_root)) {
            return false;
        }

        return file_exists($normalized) && is_file($normalized) && is_readable($normalized);
    }

    private static function get_fallback_template_id_for_type(string $type): string {
        $map = [
            'course' => 'course-classic',
            'lesson' => 'lesson-reading',
            'program' => 'program-academy',
            'home' => 'home-academy',
            'landing' => 'landing-course',
            'page' => 'page-fullwidth',
            'post' => 'post-editorial',
            'section' => 'notice',
        ];

        return $map[$type] ?? 'page-fullwidth';
    }
}
