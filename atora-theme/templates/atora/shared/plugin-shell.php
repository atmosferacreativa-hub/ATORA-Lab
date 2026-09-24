<?php
if (!defined('ABSPATH')) {
    exit;
}

$context = isset($atora_context) && is_array($atora_context) ? $atora_context : [];
$template_id = isset($atora_template_id) ? sanitize_key((string) $atora_template_id) : '';
$template_type = isset($atora_template_type) ? sanitize_key((string) $atora_template_type) : (isset($context['type']) ? sanitize_key((string) $context['type']) : 'page');

// Resolve an entity id from context or global post
$post_id = 0;
if (!empty($context['post_id'])) {
    $post_id = absint($context['post_id']);
} elseif (!empty($context['course_id'])) {
    $post_id = absint($context['course_id']);
} elseif (!empty($context['lesson_id'])) {
    $post_id = absint($context['lesson_id']);
} elseif (!empty($context['program_id'])) {
    $post_id = absint($context['program_id']);
} else {
    $post_id = function_exists('get_the_ID') ? absint(get_the_ID()) : 0;
}

// Map theme template -> plugin schema context
$schema_context = '';
if ('course' === $template_type) {
    $is_commercial = false;
    if (strpos($template_id, 'commercial') !== false) {
        $is_commercial = true;
    }
    if (!$is_commercial && isset($context['mode']) && 'commercial' === $context['mode']) {
        $is_commercial = true;
    }
    $schema_context = $is_commercial ? 'course_commercial' : 'course_overview';
} elseif ('lesson' === $template_type) {
    $schema_context = 'lesson';
} elseif ('program' === $template_type) {
    $is_commercial = false;
    if (strpos($template_id, 'commercial') !== false) {
        $is_commercial = true;
    }
    if (!$is_commercial && isset($context['mode']) && 'commercial' === $context['mode']) {
        $is_commercial = true;
    }
    $schema_context = $is_commercial ? 'program_commercial' : 'program_overview';
} elseif ('home' === $template_type) {
    $schema_context = 'home_academy';
} else {
    $schema_context = 'page';
}

// If plugin UI is available, use resolver/engine
if (class_exists('CLMS_UI_Template_Resolver') && class_exists('CLMS_UI_Template_Context') && class_exists('CLMS_UI_Template_Engine')) {
    try {
        $resolver = new CLMS_UI_Template_Resolver();
        $schema = $resolver->resolve((int) $post_id, (string) $schema_context);
        $ctx = CLMS_UI_Template_Context::make((int) $post_id, (string) $schema_context);
        $engine = new CLMS_UI_Template_Engine();
        $sections_output = $engine->render_to_array($ctx, $schema);
        $sections_order = array_keys($sections_output);
    } catch (Throwable $e) {
        $sections_output = array();
        $sections_order = array();
    }

    echo '<div class="atora-plugin-shell atora-theme-card">';
    foreach ($sections_order as $section_id) {
        if (!empty($sections_output[$section_id])) {
            echo $sections_output[$section_id];
        }
    }
    echo '</div>';
    return;
}

// Fallback: include the theme template file if available
$template = Atora_Template_Registry::get_template($template_id, $template_type);
$relative = is_array($template) ? (string) ($template['file'] ?? '') : '';
$file = Atora_Template_Loader::locate_relative($relative);
if (!$file) {
    $file = Atora_Template_Loader::locate_relative('templates/atora/shared/fallback.php');
}

if ($file) {
    include $file;
}
