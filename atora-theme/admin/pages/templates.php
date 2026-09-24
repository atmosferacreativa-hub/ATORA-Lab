<?php
if (!defined('ABSPATH')) {
    exit;
}

$groups = [
    'course' => atora_theme_get_registered_templates('course'),
    'lesson' => atora_theme_get_registered_templates('lesson'),
    'program' => atora_theme_get_registered_templates('program'),
    'home' => atora_theme_get_registered_templates('home'),
    'landing' => atora_theme_get_registered_templates('landing'),
    'page' => atora_theme_get_registered_templates('page'),
    'post' => atora_theme_get_registered_templates('post'),
];

$values = [
    'atora_theme_global_course_template' => get_option('atora_theme_global_course_template', 'course-classic'),
    'atora_theme_global_commercial_course_template' => get_option('atora_theme_global_commercial_course_template', 'course-commercial'),
    'atora_theme_global_student_course_template' => get_option('atora_theme_global_student_course_template', 'course-student'),
    'atora_theme_global_lesson_template' => get_option('atora_theme_global_lesson_template', 'lesson-reading'),
    'atora_theme_global_program_template' => get_option('atora_theme_global_program_template', 'program-academy'),
    'atora_theme_global_home_template' => get_option('atora_theme_global_home_template', 'home-academy'),
    'atora_theme_global_landing_template' => get_option('atora_theme_global_landing_template', 'landing-course'),
    'atora_theme_global_page_template' => get_option('atora_theme_global_page_template', 'page-fullwidth'),
    'atora_theme_global_post_template' => get_option('atora_theme_global_post_template', 'post-editorial'),
];

$features = [
    'atora_theme_enable_commercial_course_mode' => __('Activar modo comercial para visitantes', 'atora-learning'),
    'atora_theme_enable_distraction_free_lessons' => __('Activar modo sin distracciones en lecciones', 'atora-learning'),
    'atora_theme_enable_crm_sections' => __('Activar secciones CRM', 'atora-learning'),
    'atora_theme_enable_woocommerce_cta' => __('Activar CTA de WooCommerce', 'atora-learning'),
    'atora_theme_enable_teacher_conversion_block' => __('Activar bloque de conversión de profesor', 'atora-learning'),
    'atora_theme_enable_related_courses' => __('Activar cursos relacionados', 'atora-learning'),
    'atora_theme_enable_program_landings' => __('Activar landings de programa', 'atora-learning'),
    'atora_theme_enable_visual_presets' => __('Activar presets visuales', 'atora-learning'),
    'atora_theme_enable_basic_wizard' => __('Activar asistente básico', 'atora-learning'),
    'atora_theme_enable_advanced_widgets' => __('Activar widgets avanzados', 'atora-learning'),
];

$tabs = [
    'course' => [
        'label' => __('Cursos', 'atora-learning'),
        'option' => 'atora_theme_global_course_template',
        'description' => __('Plantillas para curso base, comercial e interno.', 'atora-learning'),
        'selector_post_type' => 'lm_course',
    ],
    'lesson' => [
        'label' => __('Lecciones', 'atora-learning'),
        'option' => 'atora_theme_global_lesson_template',
        'description' => __('Experiencias de estudio según tipo de clase.', 'atora-learning'),
        'selector_post_type' => 'lm_lesson',
    ],
    'program' => [
        'label' => __('Programas', 'atora-learning'),
        'option' => 'atora_theme_global_program_template',
        'description' => __('Plantillas para rutas formativas y diplomados.', 'atora-learning'),
        'selector_post_type' => 'lm_program',
    ],
    'home' => [
        'label' => __('Home', 'atora-learning'),
        'option' => 'atora_theme_global_home_template',
        'description' => __('Portadas principales para academia.', 'atora-learning'),
        'selector_post_type' => 'page',
    ],
    'landing' => [
        'label' => __('Landings', 'atora-learning'),
        'option' => 'atora_theme_global_landing_template',
        'description' => __('Páginas de conversión para cursos y programas.', 'atora-learning'),
        'selector_post_type' => 'page',
    ],
    'page' => [
        'label' => __('Páginas', 'atora-learning'),
        'option' => 'atora_theme_global_page_template',
        'description' => __('Plantillas para páginas institucionales y comerciales.', 'atora-learning'),
        'selector_post_type' => 'page',
    ],
    'post' => [
        'label' => __('Entradas', 'atora-learning'),
        'option' => 'atora_theme_global_post_template',
        'description' => __('Plantillas editoriales para el blog.', 'atora-learning'),
        'selector_post_type' => 'post',
    ],
];

if (!function_exists('atora_theme_template_preview_url')) {
    function atora_theme_template_preview_url(array $template): string {
        $preview = isset($template['preview']) ? ltrim((string) $template['preview'], '/') : '';
        if (!$preview) {
            return '';
        }

        return esc_url(trailingslashit(ATORA_THEME_URI) . $preview);
    }
}

if (!function_exists('atora_theme_template_selector_posts')) {
    function atora_theme_template_selector_posts(string $post_type): array {
        static $cache = [];
        $post_type = sanitize_key($post_type);

        if (isset($cache[$post_type])) {
            return $cache[$post_type];
        }

        $cache[$post_type] = get_posts([
            'post_type' => $post_type,
            'post_status' => ['publish', 'draft', 'pending', 'private'],
            'posts_per_page' => 80,
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
        ]);

        return $cache[$post_type];
    }
}

if (!function_exists('atora_theme_template_usage_items')) {
    function atora_theme_template_usage_items(string $template_id, string $tab_id): array {
        $template_id = sanitize_key($template_id);
        $tab_id = sanitize_key($tab_id);
        if (!$template_id || !$tab_id) {
            return [];
        }

        $base_args = [
            'post_status' => ['publish', 'draft', 'pending', 'private'],
            'posts_per_page' => 20,
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
        ];

        if (in_array($tab_id, ['home', 'landing', 'page'], true)) {
            return get_posts($base_args + [
                'post_type' => 'page',
                'meta_key' => '_atora_theme_page_template',
                'meta_value' => $template_id,
            ]);
        }

        if ('post' === $tab_id) {
            return get_posts($base_args + [
                'post_type' => 'post',
                'meta_key' => '_atora_theme_post_template',
                'meta_value' => $template_id,
            ]);
        }

        if ('course' === $tab_id) {
            return get_posts($base_args + [
                'post_type' => 'lm_course',
                'meta_query' => [
                    'relation' => 'OR',
                    [
                        'key' => '_atora_theme_course_template',
                        'value' => $template_id,
                    ],
                    [
                        'key' => '_atora_theme_course_template_commercial',
                        'value' => $template_id,
                    ],
                    [
                        'key' => '_atora_theme_course_template_student',
                        'value' => $template_id,
                    ],
                ],
            ]);
        }

        if ('lesson' === $tab_id) {
            return get_posts($base_args + [
                'post_type' => 'lm_lesson',
                'meta_key' => '_atora_theme_lesson_template',
                'meta_value' => $template_id,
            ]);
        }

        if ('program' === $tab_id) {
            return get_posts($base_args + [
                'post_type' => 'lm_program',
                'meta_query' => [
                    'relation' => 'OR',
                    [
                        'key' => '_atora_theme_program_template',
                        'value' => $template_id,
                    ],
                    [
                        'key' => '_atora_theme_program_template_commercial',
                        'value' => $template_id,
                    ],
                ],
            ]);
        }

        return [];
    }
}

$notice_code = isset($_GET['atora_notice']) ? sanitize_key((string) wp_unslash($_GET['atora_notice'])) : '';
$notice_message = $notice_code ? Atora_Template_Admin::get_notice_message($notice_code) : '';
$notice_type = isset($_GET['atora_notice_type']) ? sanitize_key((string) wp_unslash($_GET['atora_notice_type'])) : 'success';
$notice_class = ('error' === $notice_type) ? 'notice notice-error' : 'notice notice-success';
?>
<div class="wrap">
    <h1><?php esc_html_e('Plantillas globales', 'atora-learning'); ?></h1>
    <p class="atora-template-admin-help">
        <?php esc_html_e('Crea páginas con plantilla, aplícalas a contenido existente o asígnalas como inicio. El plugin ATORA LMS mantiene lógica y datos; el theme controla la presentación.', 'atora-learning'); ?>
    </p>

    <?php if (isset($_GET['updated'])) : ?>
        <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Configuración guardada.', 'atora-learning'); ?></p></div>
    <?php endif; ?>

    <?php if ($notice_message) : ?>
        <div class="<?php echo esc_attr($notice_class); ?> is-dismissible"><p><?php echo esc_html($notice_message); ?></p></div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="atora-template-browser-form">
        <input type="hidden" name="action" value="atora_theme_save_template_options">
        <?php wp_nonce_field('atora_theme_save_template_options'); ?>

        <?php foreach ($values as $option => $value) : ?>
            <input
                type="hidden"
                name="<?php echo esc_attr($option); ?>"
                id="<?php echo esc_attr($option); ?>"
                value="<?php echo esc_attr((string) $value); ?>">
        <?php endforeach; ?>

        <div class="atora-template-browser" data-atora-template-browser>
            <div class="atora-template-tabs" role="tablist" aria-label="<?php echo esc_attr__('Tipos de plantillas', 'atora-learning'); ?>">
                <?php $is_first_tab = true; ?>
                <?php foreach ($tabs as $tab_id => $tab) : ?>
                    <button
                        type="button"
                        class="atora-template-tab <?php echo $is_first_tab ? 'is-active' : ''; ?>"
                        data-atora-template-tab="<?php echo esc_attr($tab_id); ?>"
                        role="tab"
                        aria-selected="<?php echo $is_first_tab ? 'true' : 'false'; ?>">
                        <?php echo esc_html((string) $tab['label']); ?>
                    </button>
                    <?php $is_first_tab = false; ?>
                <?php endforeach; ?>
            </div>

            <?php $is_first_panel = true; ?>
            <?php foreach ($tabs as $tab_id => $tab) : ?>
                <?php
                $tab_templates = $groups[$tab_id] ?? [];
                $selector_post_type = (string) ($tab['selector_post_type'] ?? 'page');
                $selector_posts = atora_theme_template_selector_posts($selector_post_type);
                ?>
                <section class="atora-template-panel <?php echo $is_first_panel ? 'is-active' : ''; ?>" data-atora-template-panel="<?php echo esc_attr($tab_id); ?>">
                    <div class="atora-template-current">
                        <strong><?php esc_html_e('Plantilla global activa:', 'atora-learning'); ?></strong>
                        <?php
                        $active_template_id = (string) ($values[$tab['option']] ?? '');
                        $active_template = $active_template_id ? atora_theme_get_template($active_template_id, $tab_id) : null;
                        echo esc_html((string) ($active_template['label'] ?? __('No definida', 'atora-learning')));
                        ?>
                    </div>
                    <p class="atora-template-admin-help"><?php echo esc_html((string) $tab['description']); ?></p>

                    <?php if (empty($tab_templates)) : ?>
                        <div class="atora-template-empty-state"><?php esc_html_e('No hay plantillas disponibles en este grupo.', 'atora-learning'); ?></div>
                    <?php else : ?>
                        <div class="atora-template-grid">
                            <?php foreach ($tab_templates as $template_id => $template) : ?>
                                <?php
                                $preview_url = atora_theme_template_preview_url($template);
                                $is_active = $active_template_id === $template_id;
                                $sections = isset($template['sections']) && is_array($template['sections']) ? $template['sections'] : [];
                                $recommended_for = isset($template['recommended_for']) && is_array($template['recommended_for']) ? $template['recommended_for'] : [];
                                $supports = isset($template['supports']) && is_array($template['supports']) ? $template['supports'] : [];
                                $template_type = sanitize_key((string) ($template['type'] ?? $tab_id));
                                $usage_items = atora_theme_template_usage_items((string) $template_id, $tab_id);
                                ?>
                                <article class="atora-template-card <?php echo $is_active ? 'is-active' : ''; ?>">
                                    <div class="atora-template-preview">
                                        <?php if ($preview_url) : ?>
                                            <img src="<?php echo $preview_url; ?>" alt="<?php echo esc_attr((string) ($template['label'] ?? $template_id)); ?>">
                                        <?php else : ?>
                                            <span><?php esc_html_e('Vista previa no disponible', 'atora-learning'); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="atora-template-title"><?php echo esc_html((string) ($template['label'] ?? $template_id)); ?></h3>
                                    <p class="atora-template-description"><?php echo esc_html((string) ($template['description'] ?? '')); ?></p>
                                    <p class="atora-template-use-case"><?php echo esc_html((string) ($template['use_case'] ?? '')); ?></p>

                                    <?php if (!empty($template['audience'])) : ?>
                                        <p class="atora-template-recommended">
                                            <strong><?php esc_html_e('Audiencia:', 'atora-learning'); ?></strong>
                                            <?php echo esc_html((string) $template['audience']); ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="atora-template-badges">
                                        <?php if (!empty($template['complexity'])) : ?>
                                            <span class="atora-template-badge"><?php echo esc_html((string) $template['complexity']); ?></span>
                                        <?php endif; ?>
                                        <?php if ($is_active) : ?>
                                            <span class="atora-template-badge atora-template-badge--active"><?php esc_html_e('Activa', 'atora-learning'); ?></span>
                                        <?php else : ?>
                                            <span class="atora-template-badge"><?php esc_html_e('No activa', 'atora-learning'); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($recommended_for)) : ?>
                                        <p class="atora-template-recommended">
                                            <strong><?php esc_html_e('Ideal para:', 'atora-learning'); ?></strong>
                                            <?php echo esc_html(implode(' · ', array_map('sanitize_text_field', $recommended_for))); ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($sections)) : ?>
                                        <p class="atora-template-sections">
                                            <strong><?php esc_html_e('Incluye:', 'atora-learning'); ?></strong>
                                            <?php
                                            echo esc_html(implode(' · ', array_map(static function ($section): string {
                                                $section = str_replace(['course-', 'lesson-', 'program-', 'site-', 'shared-'], '', (string) $section);
                                                $section = str_replace('-', ' ', $section);
                                                return ucwords($section);
                                            }, $sections)));
                                            ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($supports)) : ?>
                                        <div class="atora-template-badges">
                                            <?php foreach ($supports as $support) : ?>
                                                <span class="atora-template-badge"><?php echo esc_html(ucfirst(str_replace('-', ' ', (string) $support))); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($template['admin_hint'])) : ?>
                                        <p class="atora-template-admin-help"><?php echo esc_html((string) $template['admin_hint']); ?></p>
                                    <?php endif; ?>

                                    <div class="atora-template-actions">
                                        <?php if ('course' === $tab_id) : ?>
                                            <button type="button" class="button button-primary" data-atora-activate-template data-target-option="atora_theme_global_course_template" data-template-id="<?php echo esc_attr($template_id); ?>">
                                                <?php esc_html_e('Activar curso base', 'atora-learning'); ?>
                                            </button>
                                            <button type="button" class="button" data-atora-activate-template data-target-option="atora_theme_global_commercial_course_template" data-template-id="<?php echo esc_attr($template_id); ?>">
                                                <?php esc_html_e('Activar modo comercial', 'atora-learning'); ?>
                                            </button>
                                            <button type="button" class="button" data-atora-activate-template data-target-option="atora_theme_global_student_course_template" data-template-id="<?php echo esc_attr($template_id); ?>">
                                                <?php esc_html_e('Activar modo inscrito', 'atora-learning'); ?>
                                            </button>
                                        <?php elseif ('post' === $tab_id) : ?>
                                            <button type="button" class="button button-primary" data-atora-activate-template data-target-option="<?php echo esc_attr((string) $tab['option']); ?>" data-template-id="<?php echo esc_attr($template_id); ?>">
                                                <?php esc_html_e('Aplicar como plantilla global de entradas', 'atora-learning'); ?>
                                            </button>
                                        <?php else : ?>
                                            <button type="button" class="button button-primary" data-atora-activate-template data-target-option="<?php echo esc_attr((string) $tab['option']); ?>" data-template-id="<?php echo esc_attr($template_id); ?>">
                                                <?php esc_html_e('Activar como global', 'atora-learning'); ?>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (in_array($tab_id, ['home', 'landing', 'page'], true)) : ?>
                                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                                <input type="hidden" name="action" value="atora_theme_create_page_with_template">
                                                <input type="hidden" name="template_id" value="<?php echo esc_attr((string) $template_id); ?>">
                                                <?php wp_nonce_field('atora_theme_create_page_with_template'); ?>
                                                <button type="submit" class="button"><?php esc_html_e('Crear página con esta plantilla', 'atora-learning'); ?></button>
                                            </form>

                                            <details class="atora-template-inline-details">
                                                <summary class="button"><?php esc_html_e('Aplicar a página existente', 'atora-learning'); ?></summary>
                                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="atora-template-inline-form">
                                                    <input type="hidden" name="action" value="atora_theme_apply_template_to_existing">
                                                    <input type="hidden" name="template_id" value="<?php echo esc_attr((string) $template_id); ?>">
                                                    <?php wp_nonce_field('atora_theme_apply_template_to_existing'); ?>
                                                    <select name="target_post_id" required>
                                                        <option value=""><?php esc_html_e('Selecciona una página', 'atora-learning'); ?></option>
                                                        <?php foreach ($selector_posts as $selector_post) : ?>
                                                            <option value="<?php echo esc_attr((string) $selector_post->ID); ?>"><?php echo esc_html(get_the_title($selector_post->ID) ?: ('#' . $selector_post->ID)); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="button button-secondary"><?php esc_html_e('Aplicar', 'atora-learning'); ?></button>
                                                </form>
                                            </details>
                                        <?php endif; ?>

                                        <?php if ('home' === $tab_id) : ?>
                                            <details class="atora-template-inline-details">
                                                <summary class="button"><?php esc_html_e('Asignar como inicio', 'atora-learning'); ?></summary>
                                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="atora-template-inline-form" data-atora-confirm="<?php echo esc_attr__('¿Asignar esta plantilla como página de inicio del sitio?', 'atora-learning'); ?>">
                                                    <input type="hidden" name="action" value="atora_theme_assign_template_as_front_page">
                                                    <input type="hidden" name="template_id" value="<?php echo esc_attr((string) $template_id); ?>">
                                                    <?php wp_nonce_field('atora_theme_assign_template_as_front_page'); ?>
                                                    <select name="existing_page_id">
                                                        <option value=""><?php esc_html_e('Crear nueva página automáticamente', 'atora-learning'); ?></option>
                                                        <?php foreach ($selector_posts as $selector_post) : ?>
                                                            <option value="<?php echo esc_attr((string) $selector_post->ID); ?>"><?php echo esc_html(get_the_title($selector_post->ID) ?: ('#' . $selector_post->ID)); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="button button-secondary"><?php esc_html_e('Asignar', 'atora-learning'); ?></button>
                                                </form>
                                            </details>
                                        <?php endif; ?>

                                        <?php if (in_array($tab_id, ['course', 'lesson', 'program'], true)) : ?>
                                            <details class="atora-template-inline-details">
                                                <summary class="button"><?php esc_html_e('Aplicar a contenido existente', 'atora-learning'); ?></summary>
                                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="atora-template-inline-form">
                                                    <input type="hidden" name="action" value="atora_theme_apply_template_to_existing">
                                                    <input type="hidden" name="template_id" value="<?php echo esc_attr((string) $template_id); ?>">
                                                    <?php wp_nonce_field('atora_theme_apply_template_to_existing'); ?>
                                                    <select name="target_post_id" required>
                                                        <option value=""><?php esc_html_e('Selecciona un contenido', 'atora-learning'); ?></option>
                                                        <?php foreach ($selector_posts as $selector_post) : ?>
                                                            <option value="<?php echo esc_attr((string) $selector_post->ID); ?>"><?php echo esc_html(get_the_title($selector_post->ID) ?: ('#' . $selector_post->ID)); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="button button-secondary"><?php esc_html_e('Aplicar', 'atora-learning'); ?></button>
                                                </form>
                                            </details>
                                            <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=atora-theme-templates#tab-' . $tab_id)); ?>"><?php esc_html_e('Editar secciones', 'atora-learning'); ?></a>
                                        <?php endif; ?>

                                        <?php if ($preview_url) : ?>
                                            <a class="button" href="<?php echo esc_url($preview_url); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Vista previa', 'atora-learning'); ?></a>
                                        <?php endif; ?>
                                    </div>

                                    <details class="atora-template-usage">
                                        <summary><?php esc_html_e('Ver páginas que usan esta plantilla', 'atora-learning'); ?></summary>
                                        <?php if (empty($usage_items)) : ?>
                                            <p><?php esc_html_e('Esta plantilla aún no está asignada a ningún contenido.', 'atora-learning'); ?></p>
                                        <?php else : ?>
                                            <table class="widefat striped">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Título', 'atora-learning'); ?></th>
                                                        <th><?php esc_html_e('Tipo', 'atora-learning'); ?></th>
                                                        <th><?php esc_html_e('Estado', 'atora-learning'); ?></th>
                                                        <th><?php esc_html_e('Editar', 'atora-learning'); ?></th>
                                                        <th><?php esc_html_e('Ver', 'atora-learning'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($usage_items as $usage_item) : ?>
                                                        <tr>
                                                            <td><?php echo esc_html(get_the_title($usage_item->ID) ?: ('#' . $usage_item->ID)); ?></td>
                                                            <td><?php echo esc_html(ucwords(str_replace('_', ' ', (string) $usage_item->post_type))); ?></td>
                                                            <td><?php echo esc_html(get_post_status_object((string) $usage_item->post_status)->label ?? (string) $usage_item->post_status); ?></td>
                                                            <td>
                                                                <?php $edit_link = get_edit_post_link($usage_item->ID); ?>
                                                                <?php if ($edit_link) : ?>
                                                                    <a href="<?php echo esc_url($edit_link); ?>"><?php esc_html_e('Editar', 'atora-learning'); ?></a>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php $view_link = get_permalink($usage_item->ID); ?>
                                                                <?php if ($view_link) : ?>
                                                                    <a href="<?php echo esc_url($view_link); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Ver', 'atora-learning'); ?></a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </details>

                                    <details>
                                        <summary><?php esc_html_e('Detalles técnicos', 'atora-learning'); ?></summary>
                                        <p><code><?php echo esc_html(sanitize_key((string) $template_id)); ?></code></p>
                                        <p><code><?php echo esc_html($template_type); ?></code></p>
                                    </details>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
                <?php $is_first_panel = false; ?>
            <?php endforeach; ?>
        </div>

        <h2><?php esc_html_e('Compatibilidad avanzada y comportamiento global', 'atora-learning'); ?></h2>
        <p class="atora-template-admin-help">
            <?php esc_html_e('Estas opciones permiten mantener compatibilidad con flujos legacy o activar bloques avanzados cuando el proyecto lo necesite.', 'atora-learning'); ?>
        </p>
        <fieldset class="atora-template-recommended">
            <?php foreach ($features as $option => $label) : ?>
                <p>
                    <label>
                        <input type="checkbox" name="<?php echo esc_attr($option); ?>" value="1" <?php checked((bool) get_option($option, 0), true); ?>>
                        <?php echo esc_html((string) $label); ?>
                    </label>
                </p>
            <?php endforeach; ?>
        </fieldset>

        <?php submit_button(__('Guardar configuración de plantillas', 'atora-learning')); ?>
    </form>
</div>
