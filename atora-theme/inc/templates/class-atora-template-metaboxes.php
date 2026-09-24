<?php
/**
 * Metaboxes de plantillas por entidad.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Template_Metaboxes {

    public static function init(): void {
        if (!is_admin()) {
            return;
        }

        add_action('add_meta_boxes', [__CLASS__, 'register_metaboxes']);
        add_action('add_meta_boxes', [__CLASS__, 'hide_native_custom_fields'], 99);
        add_action('save_post', [__CLASS__, 'save_metaboxes'], 20);
        add_action('admin_post_atora_theme_remove_post_template', [__CLASS__, 'handle_remove_post_template']);
        add_action('admin_post_atora_theme_clean_atora_blocks', [__CLASS__, 'handle_clean_atora_blocks']);
        add_action('admin_notices', [__CLASS__, 'render_admin_notice']);
    }

    public static function register_metaboxes(): void {
        $title = __('Configuración visual ATORA', 'atora-learning');

        add_meta_box('atora_theme_visual_course', $title, [__CLASS__, 'render_course_box'], 'lm_course', 'side', 'default');
        add_meta_box('atora_theme_visual_lesson', $title, [__CLASS__, 'render_lesson_box'], 'lm_lesson', 'side', 'default');
        add_meta_box('atora_theme_visual_program', $title, [__CLASS__, 'render_program_box'], 'lm_program', 'side', 'default');
        add_meta_box('atora_theme_visual_page', $title, [__CLASS__, 'render_page_box'], 'page', 'side', 'default');
        add_meta_box('atora_theme_visual_post', $title, [__CLASS__, 'render_post_box'], 'post', 'side', 'default');
        add_meta_box('atora_theme_visual_teacher', $title, [__CLASS__, 'render_teacher_box'], 'atora_teacher', 'side', 'default');
    }

    public static function hide_native_custom_fields(): void {
        $show_technical = (bool) absint(get_option('atora_show_technical_fields', 0));
        if ($show_technical) {
            return;
        }

        $post_types = ['page', 'post', 'lm_course', 'lm_lesson', 'lm_program', 'atora_teacher'];
        foreach ($post_types as $post_type) {
            remove_meta_box('postcustom', $post_type, 'normal');
            remove_meta_box('postcustom', $post_type, 'advanced');
        }
    }

    public static function render_course_box($post): void {
        if (!$post instanceof WP_Post) {
            return;
        }

        wp_nonce_field('atora_theme_template_metaboxes', 'atora_theme_template_metaboxes_nonce');

        $course_template = (string) get_post_meta($post->ID, '_atora_theme_course_template', true);
        $commercial = (string) get_post_meta($post->ID, '_atora_theme_course_template_commercial', true);
        $student = (string) get_post_meta($post->ID, '_atora_theme_course_template_student', true);
        $summary_template = $course_template ?: (string) get_option('atora_theme_global_course_template', 'course-classic');

        self::render_visual_overview($post, $summary_template, 'course', 'atora_theme_course_template');

        echo '<p class="atora-template-admin-help">' . esc_html__('Define qué plantilla verá cada audiencia del curso.', 'atora-learning') . '</p>';
        self::render_visual_select('course_template', __('Plantilla base del curso', 'atora-learning'), Atora_Template_Registry::get_templates('course'), $course_template, 'atora_theme_global_course_template', 'course-classic');
        self::render_visual_select('course_template_commercial', __('Plantilla para visitantes (comercial)', 'atora-learning'), Atora_Template_Registry::get_templates('course'), $commercial, 'atora_theme_global_commercial_course_template', 'course-commercial');
        self::render_visual_select('course_template_student', __('Plantilla para estudiantes inscritos', 'atora-learning'), Atora_Template_Registry::get_templates('course'), $student, 'atora_theme_global_student_course_template', 'course-student');

        self::render_checkbox('course_commercial_mode', __('Activar modo comercial para usuarios no inscritos', 'atora-learning'), (bool) get_post_meta($post->ID, '_atora_theme_course_commercial_mode', true));

        $sections = [
            'hero' => __('Hero principal', 'atora-learning'),
            'teacher-card' => __('Bloque de profesor', 'atora-learning'),
            'benefits' => __('Beneficios', 'atora-learning'),
            'curriculum' => __('Currículo', 'atora-learning'),
            'price-card' => __('Tarjeta de precio', 'atora-learning'),
            'crm-lead-box' => __('Captura CRM', 'atora-learning'),
            'faq' => __('Preguntas frecuentes', 'atora-learning'),
            'testimonials' => __('Testimonios', 'atora-learning'),
            'related-courses' => __('Cursos relacionados', 'atora-learning'),
        ];

        self::render_section_controls('course', $sections, $post->ID);
    }

    public static function render_lesson_box($post): void {
        if (!$post instanceof WP_Post) {
            return;
        }

        wp_nonce_field('atora_theme_template_metaboxes', 'atora_theme_template_metaboxes_nonce');

        $lesson_template = (string) get_post_meta($post->ID, '_atora_theme_lesson_template', true);
        $summary_template = $lesson_template ?: (string) get_option('atora_theme_global_lesson_template', 'lesson-reading');

        self::render_visual_overview($post, $summary_template, 'lesson', 'atora_theme_lesson_template');

        echo '<p class="atora-template-admin-help">' . esc_html__('Selecciona cómo se mostrará esta lección según su objetivo pedagógico.', 'atora-learning') . '</p>';
        self::render_visual_select('lesson_template', __('Plantilla de lección', 'atora-learning'), Atora_Template_Registry::get_templates('lesson'), $lesson_template, 'atora_theme_global_lesson_template', 'lesson-reading');

        self::render_checkbox('lesson_distraction_free', __('Activar modo sin distracciones', 'atora-learning'), (bool) get_post_meta($post->ID, '_atora_theme_lesson_distraction_free', true));

        $types = [
            'reading' => __('Lectura', 'atora-learning'),
            'video' => __('Video', 'atora-learning'),
            'assignment' => __('Tarea', 'atora-learning'),
            'evaluation' => __('Evaluación', 'atora-learning'),
            'live' => __('En vivo', 'atora-learning'),
            'downloadable' => __('Clase descargable', 'atora-learning'),
        ];
        $current_type = (string) get_post_meta($post->ID, '_atora_theme_lesson_visual_type', true);

        echo '<p><label for="atora_theme_lesson_visual_type"><strong>' . esc_html__('Tipo visual de lección', 'atora-learning') . '</strong></label>';
        echo '<select id="atora_theme_lesson_visual_type" name="atora_theme_lesson_visual_type" class="widefat">';
        foreach ($types as $value => $label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($current_type, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select></p>';

        $sections = [
            'sidebar' => __('Barra lateral', 'atora-learning'),
            'progress' => __('Progreso', 'atora-learning'),
            'resources' => __('Recursos', 'atora-learning'),
            'navigation' => __('Navegación', 'atora-learning'),
            'evaluation' => __('Evaluación', 'atora-learning'),
            'video' => __('Bloque de video', 'atora-learning'),
        ];

        self::render_section_controls('lesson', $sections, $post->ID);
    }

    public static function render_program_box($post): void {
        if (!$post instanceof WP_Post) {
            return;
        }

        wp_nonce_field('atora_theme_template_metaboxes', 'atora_theme_template_metaboxes_nonce');

        $program_template = (string) get_post_meta($post->ID, '_atora_theme_program_template', true);
        $program_commercial = (string) get_post_meta($post->ID, '_atora_theme_program_template_commercial', true);
        $summary_template = $program_template ?: (string) get_option('atora_theme_global_program_template', 'program-academy');

        self::render_visual_overview($post, $summary_template, 'program', 'atora_theme_program_template');

        echo '<p class="atora-template-admin-help">' . esc_html__('Configura una plantilla para uso interno y otra para modo comercial del programa.', 'atora-learning') . '</p>';
        self::render_visual_select('program_template', __('Plantilla interna del programa', 'atora-learning'), Atora_Template_Registry::get_templates('program'), $program_template, 'atora_theme_global_program_template', 'program-academy');
        self::render_visual_select('program_template_commercial', __('Plantilla comercial del programa', 'atora-learning'), Atora_Template_Registry::get_templates('program'), $program_commercial, 'atora_theme_global_program_template', 'program-commercial');

        $sections = [
            'diploma-path' => __('Ruta del diplomado', 'atora-learning'),
            'courses' => __('Cursos del programa', 'atora-learning'),
            'teachers' => __('Docentes del programa', 'atora-learning'),
            'certification' => __('Certificación', 'atora-learning'),
            'crm-lead-box' => __('Captura CRM', 'atora-learning'),
            'faq' => __('Preguntas frecuentes', 'atora-learning'),
            'cta' => __('Llamado a la acción', 'atora-learning'),
        ];

        self::render_section_controls('program', $sections, $post->ID);
    }

    public static function render_page_box($post): void {
        if (!$post instanceof WP_Post) {
            return;
        }

        wp_nonce_field('atora_theme_template_metaboxes', 'atora_theme_template_metaboxes_nonce');

        $templates = array_merge(
            Atora_Template_Registry::get_templates('home'),
            Atora_Template_Registry::get_templates('landing'),
            Atora_Template_Registry::get_templates('page')
        );

        $current = (string) get_post_meta($post->ID, '_atora_theme_page_template', true);
        self::render_visual_overview($post, $current, 'page', 'atora_theme_page_template');
        echo '<p class="atora-template-admin-help">' . esc_html__('Plantilla activa para esta página. Si no asignas una, se usará el render normal de WordPress.', 'atora-learning') . '</p>';
        self::render_visual_select('page_template', __('Plantilla visual ATORA', 'atora-learning'), $templates, $current, 'atora_theme_global_page_template', 'page-fullwidth');
    }

    public static function render_post_box($post): void {
        if (!$post instanceof WP_Post) {
            return;
        }

        wp_nonce_field('atora_theme_template_metaboxes', 'atora_theme_template_metaboxes_nonce');

        $current = (string) get_post_meta($post->ID, '_atora_theme_post_template', true);
        $summary_template = $current ?: (string) get_option('atora_theme_global_post_template', 'post-editorial');

        self::render_visual_overview($post, $summary_template, 'post', 'atora_theme_post_template');
        echo '<p class="atora-template-admin-help">' . esc_html__('Define el estilo editorial para esta entrada.', 'atora-learning') . '</p>';
        self::render_visual_select('post_template', __('Plantilla editorial', 'atora-learning'), Atora_Template_Registry::get_templates('post'), $current, 'atora_theme_global_post_template', 'post-editorial');
    }

    public static function render_teacher_box($post): void {
        if (!$post instanceof WP_Post) {
            return;
        }

        wp_nonce_field('atora_theme_template_metaboxes', 'atora_theme_template_metaboxes_nonce');

        self::render_visual_overview($post, 'teacher-profile', 'page', '');
        echo '<p class="atora-template-admin-help">' . esc_html__('El perfil de profesor usa la plantilla visual dedicada del theme.', 'atora-learning') . '</p>';
    }

    public static function save_metaboxes($post_id): void {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!isset($_POST['atora_theme_template_metaboxes_nonce']) || !wp_verify_nonce((string) $_POST['atora_theme_template_metaboxes_nonce'], 'atora_theme_template_metaboxes')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $template_fields = [
            ['meta_key' => '_atora_theme_course_template', 'field_name' => 'atora_theme_course_template', 'type' => 'course'],
            ['meta_key' => '_atora_theme_course_template_commercial', 'field_name' => 'atora_theme_course_template_commercial', 'type' => 'course'],
            ['meta_key' => '_atora_theme_course_template_student', 'field_name' => 'atora_theme_course_template_student', 'type' => 'course'],
            ['meta_key' => '_atora_theme_lesson_template', 'field_name' => 'atora_theme_lesson_template', 'type' => 'lesson'],
            ['meta_key' => '_atora_theme_program_template', 'field_name' => 'atora_theme_program_template', 'type' => 'program'],
            ['meta_key' => '_atora_theme_program_template_commercial', 'field_name' => 'atora_theme_program_template_commercial', 'type' => 'program'],
            ['meta_key' => '_atora_theme_page_template', 'field_name' => 'atora_theme_page_template', 'type' => 'page'],
            ['meta_key' => '_atora_theme_post_template', 'field_name' => 'atora_theme_post_template', 'type' => 'post'],
        ];

        foreach ($template_fields as $item) {
            if (!isset($_POST[$item['field_name']])) {
                continue;
            }

            $value = sanitize_key((string) wp_unslash($_POST[$item['field_name']]));
            if ('' === $value) {
                delete_post_meta($post_id, $item['meta_key']);
                continue;
            }

            if ('page' === $item['type']) {
                $registered = Atora_Template_Registry::get_template($value);
                if ($registered && in_array((string) ($registered['type'] ?? ''), ['home', 'landing', 'page'], true)) {
                    update_post_meta($post_id, $item['meta_key'], $value);
                }
                continue;
            }

            if (Atora_Template_Registry::get_template($value, $item['type'])) {
                update_post_meta($post_id, $item['meta_key'], $value);
            }
        }

        $visual_type = isset($_POST['atora_theme_lesson_visual_type']) ? sanitize_key((string) wp_unslash($_POST['atora_theme_lesson_visual_type'])) : '';
        $allowed_visual_types = ['reading', 'video', 'assignment', 'evaluation', 'live', 'downloadable'];
        if (in_array($visual_type, $allowed_visual_types, true)) {
            update_post_meta($post_id, '_atora_theme_lesson_visual_type', $visual_type);
        }

        $checkbox_fields = [
            'course_commercial_mode',
            'course_section_hero',
            'course_section_teacher-card',
            'course_section_benefits',
            'course_section_curriculum',
            'course_section_price-card',
            'course_section_crm-lead-box',
            'course_section_faq',
            'course_section_testimonials',
            'course_section_related-courses',
            'lesson_distraction_free',
            'lesson_section_sidebar',
            'lesson_section_progress',
            'lesson_section_resources',
            'lesson_section_navigation',
            'lesson_section_evaluation',
            'lesson_section_video',
            'program_section_diploma-path',
            'program_section_courses',
            'program_section_teachers',
            'program_section_certification',
            'program_section_crm-lead-box',
            'program_section_faq',
            'program_section_cta',
        ];

        foreach ($checkbox_fields as $field_name) {
            $meta_key = '_atora_theme_' . $field_name;
            update_post_meta($post_id, $meta_key, isset($_POST['atora_theme_' . $field_name]) ? 1 : 0);
        }
    }

    public static function handle_remove_post_template(): void {
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        if (!$post_id || !current_user_can('edit_post', $post_id)) {
            self::redirect_to_editor($post_id, 'no_permissions', 'error');
        }

        check_admin_referer('atora_theme_remove_post_template_' . $post_id);

        $keys = [
            '_atora_theme_page_template',
            '_atora_theme_post_template',
            '_atora_theme_course_template',
            '_atora_theme_course_template_commercial',
            '_atora_theme_course_template_student',
            '_atora_theme_lesson_template',
            '_atora_theme_program_template',
            '_atora_theme_program_template_commercial',
            '_atora_theme_template_variant',
            '_atora_theme_visual_preset',
        ];

        foreach ($keys as $key) {
            delete_post_meta($post_id, $key);
        }

        self::redirect_to_editor($post_id, 'template_removed', 'success');
    }

    public static function handle_clean_atora_blocks(): void {
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        if (!$post_id || !current_user_can('edit_post', $post_id)) {
            self::redirect_to_editor($post_id, 'no_permissions', 'error');
        }

        check_admin_referer('atora_theme_clean_atora_blocks_' . $post_id);

        $post = get_post($post_id);
        if (!$post instanceof WP_Post) {
            self::redirect_to_editor($post_id, 'target_not_found', 'error');
        }

        $content = (string) $post->post_content;
        if ('' === trim($content)) {
            self::redirect_to_editor($post_id, 'no_blocks_found', 'success');
        }

        $removed = 0;
        $blocks = parse_blocks($content);
        if (!is_array($blocks) || empty($blocks)) {
            $cleaned_content = self::clean_plain_technical_content($content, $removed);
            if ($removed > 0 && $cleaned_content !== $content) {
                wp_update_post([
                    'ID' => $post_id,
                    'post_content' => $cleaned_content,
                ]);
                self::redirect_to_editor($post_id, 'blocks_cleaned', 'success', ['atora_count' => $removed]);
            }
            self::redirect_to_editor($post_id, 'no_blocks_found', 'success');
        }

        $clean_blocks = self::filter_atora_blocks($blocks, $removed);
        if ($removed <= 0) {
            self::redirect_to_editor($post_id, 'no_blocks_found', 'success');
        }

        $new_content = serialize_blocks($clean_blocks);
        if ($new_content !== $content) {
            wp_update_post([
                'ID' => $post_id,
                'post_content' => $new_content,
            ]);
        }

        self::redirect_to_editor($post_id, 'blocks_cleaned', 'success', ['atora_count' => $removed]);
    }

    public static function render_admin_notice(): void {
        if (!is_admin()) {
            return;
        }

        $notice = isset($_GET['atora_notice']) ? sanitize_key((string) wp_unslash($_GET['atora_notice'])) : '';
        if (!$notice) {
            return;
        }

        $message = Atora_Template_Admin::get_notice_message($notice);
        if (!$message) {
            return;
        }

        if ('blocks_cleaned' === $notice) {
            $count = isset($_GET['atora_count']) ? absint($_GET['atora_count']) : 0;
            if ($count > 0) {
                $message = sprintf(__('Se eliminaron %d bloques ATORA del contenido.', 'atora-learning'), $count);
            }
        }

        $type = isset($_GET['atora_notice_type']) ? sanitize_key((string) wp_unslash($_GET['atora_notice_type'])) : 'success';
        $class = ('error' === $type) ? 'notice notice-error is-dismissible' : 'notice notice-success is-dismissible';
        echo '<div class="' . esc_attr($class) . '"><p>' . esc_html($message) . '</p></div>';
    }

    private static function render_visual_overview(WP_Post $post, string $template_id, string $template_type, string $apply_field_id): void {
        $template_id = sanitize_key($template_id);
        $template = $template_id ? Atora_Template_Registry::get_template($template_id, $template_type) : null;
        $template_label = (string) ($template['label'] ?? __('Sin plantilla visual asignada', 'atora-learning'));
        $template_sections = isset($template['sections']) && is_array($template['sections']) ? $template['sections'] : [];

        $preset_label = __('No definido', 'atora-learning');
        $preset_id = sanitize_key((string) get_option('atora_theme_active_preset', ''));
        if ($preset_id && class_exists('Atora_Design_Presets')) {
            $presets = Atora_Design_Presets::get_presets();
            if (isset($presets[$preset_id]['label'])) {
                $preset_label = (string) $presets[$preset_id]['label'];
            }
        }

        $context_label = self::detect_context_label($post);
        $active_sections = self::get_enabled_sections_for_post($post, $template_sections);
        $render_state = $template ? __('Render ATORA activo', 'atora-learning') : __('Render estándar de WordPress', 'atora-learning');
        $preview_link = get_preview_post_link($post);

        echo '<div class="atora-template-current">';
        echo '<p><strong>' . esc_html__('Plantilla activa:', 'atora-learning') . '</strong> ' . esc_html($template_label) . '</p>';
        echo '<p><strong>' . esc_html__('Preset visual activo:', 'atora-learning') . '</strong> ' . esc_html($preset_label) . '</p>';
        echo '<p><strong>' . esc_html__('Contexto detectado:', 'atora-learning') . '</strong> ' . esc_html($context_label) . '</p>';
        echo '<p><strong>' . esc_html__('Estado de render:', 'atora-learning') . '</strong> ' . esc_html($render_state) . '</p>';
        echo '<p><strong>' . esc_html__('Secciones activas:', 'atora-learning') . '</strong> ' . esc_html(!empty($active_sections) ? implode(' · ', $active_sections) : __('No hay secciones activas para esta plantilla.', 'atora-learning')) . '</p>';

        echo '<div class="atora-template-actions">';
        echo '<a class="button" href="' . esc_url(admin_url('admin.php?page=atora-theme-templates')) . '">' . esc_html__('Editar secciones', 'atora-learning') . '</a>';
        if ($apply_field_id) {
            echo '<a class="button" href="#' . esc_attr($apply_field_id) . '">' . esc_html__('Aplicar otra plantilla', 'atora-learning') . '</a>';
        }

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" data-atora-confirm="' . esc_attr__('¿Seguro que deseas quitar la plantilla ATORA de este contenido?', 'atora-learning') . '">';
        echo '<input type="hidden" name="action" value="atora_theme_remove_post_template">';
        echo '<input type="hidden" name="post_id" value="' . esc_attr((string) $post->ID) . '">';
        wp_nonce_field('atora_theme_remove_post_template_' . $post->ID);
        echo '<button type="submit" class="button button-secondary">' . esc_html__('Quitar plantilla ATORA', 'atora-learning') . '</button>';
        echo '</form>';

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" data-atora-confirm="' . esc_attr__('¿Seguro que deseas limpiar bloques ATORA insertados en el contenido?', 'atora-learning') . '">';
        echo '<input type="hidden" name="action" value="atora_theme_clean_atora_blocks">';
        echo '<input type="hidden" name="post_id" value="' . esc_attr((string) $post->ID) . '">';
        wp_nonce_field('atora_theme_clean_atora_blocks_' . $post->ID);
        echo '<button type="submit" class="button">' . esc_html__('Limpiar bloques ATORA', 'atora-learning') . '</button>';
        echo '</form>';

        if ($preview_link) {
            echo '<a class="button button-primary" href="' . esc_url($preview_link) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Vista previa', 'atora-learning') . '</a>';
        }
        echo '</div>';

        echo '<details><summary>' . esc_html__('Detalles técnicos', 'atora-learning') . '</summary>';
        if ($template_id) {
            echo '<p><strong>' . esc_html__('Slug técnico:', 'atora-learning') . '</strong> <code>' . esc_html($template_id) . '</code></p>';
        }
        echo '</details>';
        echo '</div>';
    }

    private static function render_visual_select(string $field, string $label, array $templates, string $selected, string $global_option, string $fallback): void {
        $id = 'atora_theme_' . $field;
        $resolved = self::resolve_selected_template($templates, $selected, $global_option, $fallback);
        $current_template = $resolved['template'];
        $current_label = (string) ($current_template['label'] ?? __('No definida', 'atora-learning'));
        $current_description = (string) ($current_template['description'] ?? '');
        $current_use_case = (string) ($current_template['use_case'] ?? '');
        $current_audience = (string) ($current_template['audience'] ?? '');
        $current_sections = isset($current_template['sections']) && is_array($current_template['sections']) ? $current_template['sections'] : [];
        $current_sections_text = implode(' · ', array_map([__CLASS__, 'humanize_section_id'], $current_sections));
        $current_preview = self::preview_url($current_template);

        echo '<div class="atora-template-selector">';
        echo '<p><label for="' . esc_attr($id) . '"><strong>' . esc_html($label) . '</strong></label>';
        echo '<select id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" class="widefat" data-atora-template-select>';
        echo '<option value=""';
        echo ' data-template-label="' . esc_attr($current_label) . '"';
        echo ' data-template-description="' . esc_attr($current_description) . '"';
        echo ' data-template-use-case="' . esc_attr($current_use_case) . '"';
        echo ' data-template-audience="' . esc_attr($current_audience) . '"';
        echo ' data-template-sections="' . esc_attr($current_sections_text) . '"';
        echo ' data-template-preview="' . esc_attr($current_preview) . '"';
        echo '>' . esc_html__('Usar configuración global', 'atora-learning') . '</option>';

        foreach ($templates as $template_id => $template) {
            $value = sanitize_key((string) $template_id);
            $text = isset($template['label']) ? (string) $template['label'] : $value;
            $sections = isset($template['sections']) && is_array($template['sections']) ? $template['sections'] : [];
            $sections_text = implode(' · ', array_map([__CLASS__, 'humanize_section_id'], $sections));
            $preview_url = self::preview_url($template);

            echo '<option value="' . esc_attr($value) . '" ' . selected($selected, $value, false);
            echo ' data-template-label="' . esc_attr($text) . '"';
            echo ' data-template-description="' . esc_attr((string) ($template['description'] ?? '')) . '"';
            echo ' data-template-use-case="' . esc_attr((string) ($template['use_case'] ?? '')) . '"';
            echo ' data-template-audience="' . esc_attr((string) ($template['audience'] ?? '')) . '"';
            echo ' data-template-sections="' . esc_attr($sections_text) . '"';
            echo ' data-template-preview="' . esc_attr($preview_url) . '"';
            echo '>' . esc_html($text) . '</option>';
        }

        echo '</select></p>';

        echo '<div class="atora-template-current" data-atora-template-current>';
        echo '<p><strong>' . esc_html__('Actualmente estás usando:', 'atora-learning') . '</strong> <span data-template-current-label>' . esc_html($current_label) . '</span></p>';
        echo '<p data-template-current-description>' . esc_html($current_description) . '</p>';
        if ($current_use_case) {
            echo '<p data-template-current-usecase><strong>' . esc_html__('Ideal para:', 'atora-learning') . '</strong> ' . esc_html($current_use_case) . '</p>';
        } else {
            echo '<p data-template-current-usecase></p>';
        }
        if ($current_audience) {
            echo '<p data-template-current-audience><strong>' . esc_html__('Audiencia:', 'atora-learning') . '</strong> ' . esc_html($current_audience) . '</p>';
        } else {
            echo '<p data-template-current-audience></p>';
        }
        if (!empty($current_sections)) {
            echo '<p data-template-current-sections><strong>' . esc_html__('Secciones incluidas:', 'atora-learning') . '</strong> ' . esc_html($current_sections_text) . '</p>';
        } else {
            echo '<p data-template-current-sections></p>';
        }
        if ($current_preview) {
            echo '<p class="atora-template-current-preview"><img data-template-current-image src="' . esc_url($current_preview) . '" alt="' . esc_attr($current_label) . '"></p>';
        } else {
            echo '<p class="atora-template-current-preview"><span data-template-current-image-empty>' . esc_html__('Sin vista previa', 'atora-learning') . '</span></p>';
        }
        echo '</div>';
        echo '</div>';
    }

    private static function render_section_controls(string $prefix, array $labels, int $post_id): void {
        echo '<details class="atora-template-recommended"><summary><strong>' . esc_html__('Secciones activas', 'atora-learning') . '</strong></summary>';
        foreach ($labels as $section => $label) {
            $field = $prefix . '_section_' . $section;
            $meta_key = '_atora_theme_' . $field;
            self::render_checkbox($field, sprintf(__('Mostrar %s', 'atora-learning'), $label), (bool) get_post_meta($post_id, $meta_key, true));
        }
        echo '</details>';
    }

    private static function render_checkbox(string $field, string $label, bool $checked): void {
        $id = 'atora_theme_' . $field;
        echo '<p><label><input type="checkbox" name="' . esc_attr($id) . '" value="1" ' . checked($checked, true, false) . '> ' . esc_html($label) . '</label></p>';
    }

    private static function resolve_selected_template(array $templates, string $selected, string $global_option, string $fallback): array {
        $selected = sanitize_key($selected);
        if ($selected && isset($templates[$selected])) {
            return [
                'id' => $selected,
                'template' => $templates[$selected],
            ];
        }

        $global = sanitize_key((string) get_option($global_option, $fallback));
        if ($global && isset($templates[$global])) {
            return [
                'id' => $global,
                'template' => $templates[$global],
            ];
        }

        return [
            'id' => $fallback,
            'template' => isset($templates[$fallback]) ? $templates[$fallback] : [],
        ];
    }

    private static function preview_url(array $template): string {
        $preview = isset($template['preview']) ? ltrim((string) $template['preview'], '/') : '';
        if (!$preview) {
            return '';
        }

        return trailingslashit(ATORA_THEME_URI) . $preview;
    }

    private static function humanize_section_id(string $section_id): string {
        $section_id = str_replace(['course-', 'lesson-', 'program-', 'site-', 'shared-'], '', sanitize_key($section_id));
        return ucwords(str_replace('-', ' ', $section_id));
    }

    private static function detect_context_label(WP_Post $post): string {
        $post_type = sanitize_key((string) $post->post_type);
        switch ($post_type) {
            case 'page':
                return ((int) get_option('page_on_front', 0) === (int) $post->ID)
                    ? __('Página de inicio', 'atora-learning')
                    : __('Página', 'atora-learning');
            case 'post':
                return __('Entrada', 'atora-learning');
            case 'lm_course':
                return __('Curso', 'atora-learning');
            case 'lm_lesson':
                return __('Lección', 'atora-learning');
            case 'lm_program':
                return __('Programa', 'atora-learning');
            case 'atora_teacher':
                return __('Perfil de profesor', 'atora-learning');
            default:
                return ucfirst($post_type);
        }
    }

    private static function get_enabled_sections_for_post(WP_Post $post, array $sections): array {
        if (empty($sections)) {
            return [];
        }

        $context = [
            'post_id' => $post->ID,
            'course_id' => ('lm_course' === $post->post_type) ? $post->ID : 0,
            'lesson_id' => ('lm_lesson' === $post->post_type) ? $post->ID : 0,
            'program_id' => ('lm_program' === $post->post_type) ? $post->ID : 0,
        ];

        $enabled = [];
        foreach ($sections as $section_id) {
            $is_enabled = apply_filters('atora_theme_section_enabled', true, (string) $section_id, $context);
            if ($is_enabled) {
                $enabled[] = self::humanize_section_id((string) $section_id);
            }
        }

        return $enabled;
    }

    private static function filter_atora_blocks(array $blocks, int &$removed): array {
        $clean = [];
        foreach ($blocks as $block) {
            if (!is_array($block)) {
                continue;
            }

            $block_name = isset($block['blockName']) ? (string) $block['blockName'] : '';
            if (self::is_atora_block_name($block_name) || self::has_technical_noise_block($block)) {
                $removed++;
                continue;
            }

            if (!empty($block['innerBlocks']) && is_array($block['innerBlocks'])) {
                $block['innerBlocks'] = self::filter_atora_blocks($block['innerBlocks'], $removed);
            }

            $clean[] = $block;
        }

        return $clean;
    }

    private static function is_atora_block_name(string $block_name): bool {
        if (!$block_name) {
            return false;
        }

        $prefixes = ['atora/', 'clms/', 'creador-lms/'];
        foreach ($prefixes as $prefix) {
            if (0 === strpos($block_name, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private static function has_technical_noise_block(array $block): bool {
        $samples = [];
        if (isset($block['innerHTML']) && is_string($block['innerHTML'])) {
            $samples[] = $block['innerHTML'];
        }
        if (isset($block['attrs']) && is_array($block['attrs'])) {
            $samples[] = wp_json_encode($block['attrs']);
        }
        if (isset($block['innerContent']) && is_array($block['innerContent'])) {
            $samples[] = implode(' ', array_map('strval', $block['innerContent']));
        }

        foreach ($samples as $sample) {
            if (self::is_technical_noise_text((string) $sample)) {
                return true;
            }
        }

        return false;
    }

    private static function clean_plain_technical_content(string $content, int &$removed): string {
        $patterns = [
            '/<p>\s*\.cc-hero-centered\{[^<]+\}\s*<\/p>/i',
            '/<p>\s*course_commercial[^<]*<\/p>/i',
        ];

        $new_content = $content;
        foreach ($patterns as $pattern) {
            $new_content = preg_replace($pattern, '', $new_content, -1, $count);
            $removed += (int) $count;
        }

        return (string) $new_content;
    }

    private static function is_technical_noise_text(string $text): bool {
        $text = trim(wp_strip_all_tags($text));
        if ('' === $text) {
            return false;
        }

        if (preg_match('/^\.[a-z0-9\-_]+\s*\{[^}]+\}$/i', $text)) {
            return true;
        }

        $needles = ['cc-hero-centered', 'course_commercial', 'atora_hl_type', 'atora_nl_ab_enabled'];
        foreach ($needles as $needle) {
            if (false !== strpos($text, $needle)) {
                return true;
            }
        }

        return false;
    }

    private static function redirect_to_editor(int $post_id, string $notice, string $type = 'success', array $extra_args = []): void {
        $edit_link = $post_id ? get_edit_post_link($post_id, 'raw') : '';
        if (!$edit_link) {
            $edit_link = add_query_arg(['post' => $post_id, 'action' => 'edit'], admin_url('post.php'));
        }

        $args = array_merge([
            'atora_notice' => sanitize_key($notice),
            'atora_notice_type' => sanitize_key($type),
        ], $extra_args);

        wp_safe_redirect(add_query_arg($args, $edit_link));
        exit;
    }
}
