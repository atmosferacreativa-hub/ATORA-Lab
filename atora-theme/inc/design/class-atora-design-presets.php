<?php
/**
 * Presets visuales ATORA.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Design_Presets {

    public static function init(): void {
        add_filter('body_class', [__CLASS__, 'filter_body_class'], 30);

        if (is_admin()) {
            add_action('admin_menu', [__CLASS__, 'register_menu'], 31);
            add_action('admin_post_atora_theme_apply_preset', [__CLASS__, 'apply_preset']);
        }
    }

    public static function register_menu(): void {
        add_submenu_page(
            'atora-theme',
            __('Presets', 'atora-learning'),
            __('Presets', 'atora-learning'),
            'manage_options',
            'atora-theme-presets',
            [__CLASS__, 'render_page']
        );
    }

    public static function get_presets(): array {
        return [
            'academia-limpia' => [
                'label' => __('Academia limpia', 'atora-learning'),
                'course' => 'course-classic',
                'lesson' => 'lesson-reading',
                'program' => 'program-academy',
                'home' => 'home-academy',
                'sections' => ['hero', 'curriculum', 'teacher-card', 'stats'],
                'css_class' => 'atora-preset-academia-limpia',
                'tokens' => ['accent' => '#0f766e', 'surface' => '#ffffff'],
            ],
            'academia-comercial' => [
                'label' => __('Academia comercial', 'atora-learning'),
                'course' => 'course-commercial',
                'lesson' => 'lesson-video',
                'program' => 'program-commercial',
                'home' => 'home-commercial',
                'sections' => ['hero', 'benefits', 'price-card', 'crm-lead-box', 'cta'],
                'css_class' => 'atora-preset-academia-comercial',
                'tokens' => ['accent' => '#c2410c', 'surface' => '#fff7ed'],
            ],
            'institucional-premium' => [
                'label' => __('Institucional premium', 'atora-learning'),
                'course' => 'course-premium',
                'lesson' => 'lesson-focus',
                'program' => 'program-institutional',
                'home' => 'home-institutional',
                'sections' => ['hero', 'teachers', 'certification', 'stats', 'cta'],
                'css_class' => 'atora-preset-institucional-premium',
                'tokens' => ['accent' => '#1d4ed8', 'surface' => '#eff6ff'],
            ],
            'creador-cursos' => [
                'label' => __('Creador de cursos', 'atora-learning'),
                'course' => 'course-minimal',
                'lesson' => 'lesson-reading',
                'program' => 'program-academy',
                'home' => 'home-academy',
                'sections' => ['hero', 'curriculum', 'resources', 'cta'],
                'css_class' => 'atora-preset-creador-cursos',
                'tokens' => ['accent' => '#7c3aed', 'surface' => '#f5f3ff'],
            ],
            'diplomado-profesional' => [
                'label' => __('Diplomado profesional', 'atora-learning'),
                'course' => 'course-premium',
                'lesson' => 'lesson-focus',
                'program' => 'program-diploma',
                'home' => 'home-institutional',
                'sections' => ['hero', 'diploma-path', 'courses', 'teachers', 'certification'],
                'css_class' => 'atora-preset-diplomado-profesional',
                'tokens' => ['accent' => '#334155', 'surface' => '#f8fafc'],
            ],
            'cohorte-intensiva' => [
                'label' => __('Cohorte intensiva', 'atora-learning'),
                'course' => 'course-cohort',
                'lesson' => 'lesson-live',
                'program' => 'program-commercial',
                'home' => 'home-commercial',
                'sections' => ['hero', 'benefits', 'video', 'progress', 'crm-lead-box'],
                'css_class' => 'atora-preset-cohorte-intensiva',
                'tokens' => ['accent' => '#be123c', 'surface' => '#fff1f2'],
            ],
            'minimalista' => [
                'label' => __('Minimalista', 'atora-learning'),
                'course' => 'course-minimal',
                'lesson' => 'lesson-focus',
                'program' => 'program-academy',
                'home' => 'home-academy',
                'sections' => ['hero', 'content', 'navigation'],
                'css_class' => 'atora-preset-minimalista',
                'tokens' => ['accent' => '#0f172a', 'surface' => '#ffffff'],
            ],
            'modo-oscuro-elegante' => [
                'label' => __('Modo oscuro elegante', 'atora-learning'),
                'course' => 'course-premium',
                'lesson' => 'lesson-video',
                'program' => 'program-institutional',
                'home' => 'home-commercial',
                'sections' => ['hero', 'benefits', 'teacher-card', 'cta'],
                'css_class' => 'atora-preset-oscuro-elegante',
                'tokens' => ['accent' => '#14b8a6', 'surface' => '#0f172a'],
            ],
        ];
    }

    public static function render_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        include ATORA_THEME_DIR . '/admin/pages/presets.php';
    }

    public static function apply_preset(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('No autorizado.', 'atora-learning'));
        }

        check_admin_referer('atora_theme_apply_preset');

        $preset_id = isset($_POST['preset_id']) ? sanitize_key((string) wp_unslash($_POST['preset_id'])) : '';
        $presets = self::get_presets();

        if (!isset($presets[$preset_id])) {
            wp_safe_redirect(add_query_arg('preset', 'invalid', admin_url('admin.php?page=atora-theme-presets')));
            exit;
        }

        $preset = $presets[$preset_id];

        update_option('atora_theme_global_course_template', sanitize_key((string) $preset['course']));
        update_option('atora_theme_global_commercial_course_template', sanitize_key((string) $preset['course']));
        update_option('atora_theme_global_student_course_template', sanitize_key((string) $preset['course']));
        update_option('atora_theme_global_lesson_template', sanitize_key((string) $preset['lesson']));
        update_option('atora_theme_global_program_template', sanitize_key((string) $preset['program']));
        update_option('atora_theme_global_home_template', sanitize_key((string) $preset['home']));
        update_option('atora_theme_active_preset', $preset_id);
        update_option('atora_theme_preset_sections', (array) ($preset['sections'] ?? []));
        update_option('atora_theme_preset_css_class', sanitize_html_class((string) ($preset['css_class'] ?? '')));
        update_option('atora_theme_preset_tokens', (array) ($preset['tokens'] ?? []));

        wp_safe_redirect(add_query_arg('preset', 'applied', admin_url('admin.php?page=atora-theme-presets')));
        exit;
    }

    public static function filter_body_class(array $classes): array {
        $preset_class = sanitize_html_class((string) get_option('atora_theme_preset_css_class', ''));
        if ($preset_class && !in_array($preset_class, $classes, true)) {
            $classes[] = $preset_class;
        }

        return $classes;
    }
}
