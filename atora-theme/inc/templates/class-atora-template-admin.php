<?php
/**
 * Admin de templates globales.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Template_Admin {

    public static function init(): void {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', [__CLASS__, 'register_menu'], 40);
        add_action('admin_post_atora_theme_save_template_options', [__CLASS__, 'save']);
        add_action('admin_post_atora_theme_create_page_with_template', [__CLASS__, 'create_page_with_template']);
        add_action('admin_post_atora_theme_apply_template_to_existing', [__CLASS__, 'apply_template_to_existing']);
        add_action('admin_post_atora_theme_assign_template_as_front_page', [__CLASS__, 'assign_template_as_front_page']);
    }

    public static function register_menu(): void {
        add_submenu_page(
            'atora-theme',
            __('Plantillas', 'atora-learning'),
            __('Plantillas', 'atora-learning'),
            'manage_options',
            'atora-theme-templates',
            [__CLASS__, 'render_page']
        );
    }

    public static function render_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        include ATORA_THEME_DIR . '/admin/pages/templates.php';
    }

    public static function save(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('No autorizado.', 'atora-learning'));
        }

        check_admin_referer('atora_theme_save_template_options');

        $map = [
            'atora_theme_global_course_template' => 'course',
            'atora_theme_global_commercial_course_template' => 'course',
            'atora_theme_global_student_course_template' => 'course',
            'atora_theme_global_lesson_template' => 'lesson',
            'atora_theme_global_program_template' => 'program',
            'atora_theme_global_home_template' => 'home',
            'atora_theme_global_landing_template' => 'landing',
            'atora_theme_global_page_template' => 'page',
            'atora_theme_global_post_template' => 'post',
        ];

        foreach ($map as $option => $type) {
            $value = isset($_POST[$option]) ? sanitize_key((string) wp_unslash($_POST[$option])) : '';
            if ($value && Atora_Template_Registry::get_template($value, $type)) {
                update_option($option, $value);
            }
        }

        $bools = [
            'atora_theme_enable_commercial_course_mode',
            'atora_theme_enable_distraction_free_lessons',
            'atora_theme_enable_crm_sections',
            'atora_theme_enable_woocommerce_cta',
            'atora_theme_enable_teacher_conversion_block',
            'atora_theme_enable_related_courses',
            'atora_theme_enable_program_landings',
            'atora_theme_enable_visual_presets',
            'atora_theme_enable_basic_wizard',
            'atora_theme_enable_advanced_widgets',
        ];

        foreach ($bools as $option) {
            update_option($option, isset($_POST[$option]) ? 1 : 0);
        }

        wp_safe_redirect(add_query_arg('updated', '1', admin_url('admin.php?page=atora-theme-templates')));
        exit;
    }

    public static function create_page_with_template(): void {
        if (!current_user_can('edit_pages')) {
            self::redirect_with_notice('no_permissions', 'error');
        }

        check_admin_referer('atora_theme_create_page_with_template');

        $template_id = isset($_POST['template_id']) ? sanitize_key((string) wp_unslash($_POST['template_id'])) : '';
        $template = Atora_Template_Registry::get_template($template_id);
        if (!$template) {
            self::redirect_with_notice('template_not_found', 'error');
        }

        $type = sanitize_key((string) ($template['type'] ?? ''));
        if (!in_array($type, ['home', 'landing', 'page'], true)) {
            self::redirect_with_notice('template_not_supported_for_page', 'error');
        }

        $page_id = self::create_page_from_template($template_id, $template);
        if (!$page_id) {
            self::redirect_with_notice('create_page_failed', 'error');
        }

        $edit_link = get_edit_post_link($page_id, 'raw');
        if (!$edit_link) {
            self::redirect_with_notice('page_created', 'success');
        }

        wp_safe_redirect(add_query_arg([
            'atora_notice' => 'page_created',
            'atora_notice_type' => 'success',
        ], $edit_link));
        exit;
    }

    public static function apply_template_to_existing(): void {
        if (!current_user_can('edit_posts')) {
            self::redirect_with_notice('no_permissions', 'error');
        }

        check_admin_referer('atora_theme_apply_template_to_existing');

        $template_id = isset($_POST['template_id']) ? sanitize_key((string) wp_unslash($_POST['template_id'])) : '';
        $target_post_id = isset($_POST['target_post_id']) ? absint($_POST['target_post_id']) : 0;

        if (!$target_post_id) {
            self::redirect_with_notice('target_required', 'error');
        }

        $template = Atora_Template_Registry::get_template($template_id);
        if (!$template) {
            self::redirect_with_notice('template_not_found', 'error');
        }

        $post = get_post($target_post_id);
        if (!$post instanceof WP_Post) {
            self::redirect_with_notice('target_not_found', 'error');
        }

        if (!current_user_can('edit_post', $target_post_id)) {
            self::redirect_with_notice('no_permissions', 'error');
        }

        $template_type = sanitize_key((string) ($template['type'] ?? ''));
        $meta_key = self::get_meta_key_for_template_type($template_type, (string) $post->post_type);
        if (!$meta_key) {
            self::redirect_with_notice('template_target_mismatch', 'error');
        }

        update_post_meta($target_post_id, $meta_key, $template_id);

        self::redirect_with_notice('template_applied', 'success');
    }

    public static function assign_template_as_front_page(): void {
        if (!current_user_can('manage_options') || !current_user_can('edit_pages')) {
            self::redirect_with_notice('no_permissions', 'error');
        }

        check_admin_referer('atora_theme_assign_template_as_front_page');

        $template_id = isset($_POST['template_id']) ? sanitize_key((string) wp_unslash($_POST['template_id'])) : '';
        $existing_page_id = isset($_POST['existing_page_id']) ? absint($_POST['existing_page_id']) : 0;

        $template = Atora_Template_Registry::get_template($template_id, 'home');
        if (!$template) {
            self::redirect_with_notice('template_not_found', 'error');
        }

        $page_id = 0;
        if ($existing_page_id > 0) {
            $post = get_post($existing_page_id);
            if (!$post instanceof WP_Post || 'page' !== $post->post_type) {
                self::redirect_with_notice('target_not_found', 'error');
            }
            if (!current_user_can('edit_post', $existing_page_id)) {
                self::redirect_with_notice('no_permissions', 'error');
            }
            $page_id = $existing_page_id;
            update_post_meta($page_id, '_atora_theme_page_template', $template_id);
        } else {
            $existing_pages = get_posts([
                'post_type' => 'page',
                'post_status' => ['publish', 'draft', 'private', 'pending'],
                'posts_per_page' => 1,
                'no_found_rows' => true,
                'meta_key' => '_atora_theme_page_template',
                'meta_value' => $template_id,
                'fields' => 'ids',
            ]);
            $page_id = !empty($existing_pages) ? absint($existing_pages[0]) : 0;

            if (!$page_id) {
                $page_id = self::create_page_from_template($template_id, $template);
            }
        }

        if (!$page_id) {
            self::redirect_with_notice('create_page_failed', 'error');
        }

        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);

        self::redirect_with_notice('front_page_assigned', 'success');
    }

    public static function get_notice_message(string $notice): string {
        $messages = [
            'template_applied' => __('Plantilla aplicada correctamente.', 'atora-learning'),
            'page_created' => __('Página creada con la plantilla seleccionada.', 'atora-learning'),
            'front_page_assigned' => __('La página seleccionada ahora es la página de inicio del sitio.', 'atora-learning'),
            'template_removed' => __('La plantilla ATORA fue quitada de este contenido.', 'atora-learning'),
            'blocks_cleaned' => __('Se limpiaron los bloques ATORA del contenido.', 'atora-learning'),
            'no_blocks_found' => __('No se encontraron bloques ATORA para limpiar.', 'atora-learning'),
            'template_not_found' => __('La plantilla seleccionada no existe.', 'atora-learning'),
            'template_not_supported_for_page' => __('Esta plantilla no se puede usar para crear páginas.', 'atora-learning'),
            'template_target_mismatch' => __('La plantilla seleccionada no aplica a ese tipo de contenido.', 'atora-learning'),
            'target_required' => __('Debes seleccionar un contenido existente.', 'atora-learning'),
            'target_not_found' => __('El contenido seleccionado no existe.', 'atora-learning'),
            'create_page_failed' => __('No se pudo crear la página con la plantilla seleccionada.', 'atora-learning'),
            'no_permissions' => __('No tienes permisos suficientes para esta acción.', 'atora-learning'),
        ];

        return $messages[$notice] ?? '';
    }

    private static function create_page_from_template(string $template_id, array $template): int {
        $title = self::build_page_title($template_id, $template);
        $page_id = wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'draft',
            'post_title' => $title,
            'post_content' => '',
        ], true);

        if (is_wp_error($page_id)) {
            return 0;
        }

        $page_id = absint($page_id);
        if (!$page_id) {
            return 0;
        }

        update_post_meta($page_id, '_atora_theme_page_template', sanitize_key($template_id));

        $recommended_preset = isset($template['recommended_preset']) ? sanitize_key((string) $template['recommended_preset']) : '';
        if ($recommended_preset) {
            update_post_meta($page_id, '_atora_theme_visual_preset', $recommended_preset);
        }

        return $page_id;
    }

    private static function build_page_title(string $template_id, array $template): string {
        $label = isset($template['label']) ? sanitize_text_field((string) $template['label']) : '';
        $type = sanitize_key((string) ($template['type'] ?? ''));

        if (!$label) {
            $label = str_replace('-', ' ', $template_id);
            $label = ucwords($label);
        }

        if ('home' === $type) {
            if (0 === stripos($label, 'home')) {
                $label = trim(substr($label, 4));
            }
            $label = $label ? $label : __('Principal', 'atora-learning');
            return sprintf(__('Inicio %s', 'atora-learning'), $label);
        }

        if ('landing' === $type) {
            if (0 === stripos($label, 'landing')) {
                return $label;
            }
            return sprintf(__('Landing %s', 'atora-learning'), $label);
        }

        return sprintf(__('Página %s', 'atora-learning'), $label);
    }

    private static function get_meta_key_for_template_type(string $template_type, string $post_type): string {
        $template_type = sanitize_key($template_type);
        $post_type = sanitize_key($post_type);

        if (in_array($template_type, ['home', 'landing', 'page'], true) && 'page' === $post_type) {
            return '_atora_theme_page_template';
        }
        if ('post' === $template_type && 'post' === $post_type) {
            return '_atora_theme_post_template';
        }
        if ('course' === $template_type && 'lm_course' === $post_type) {
            return '_atora_theme_course_template';
        }
        if ('lesson' === $template_type && 'lm_lesson' === $post_type) {
            return '_atora_theme_lesson_template';
        }
        if ('program' === $template_type && 'lm_program' === $post_type) {
            return '_atora_theme_program_template';
        }

        return '';
    }

    private static function redirect_with_notice(string $notice, string $type = 'success'): void {
        $url = add_query_arg([
            'page' => 'atora-theme-templates',
            'atora_notice' => sanitize_key($notice),
            'atora_notice_type' => sanitize_key($type),
        ], admin_url('admin.php'));
        wp_safe_redirect($url);
        exit;
    }
}
