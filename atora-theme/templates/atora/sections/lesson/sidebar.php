<?php
if (!defined('ABSPATH')) exit;
global $post;
$post_id = get_the_ID() ?: ($post->ID ?? 0);
?>
<aside class="atora-section atora-section-lesson-sidebar">
<?php
$rendered = '';
if (class_exists('CLMS_UI_Template_Resolver') && class_exists('CLMS_UI_Template_Context') && class_exists('CLMS_UI_Template_Engine')) {
    try {
        $resolver = new CLMS_UI_Template_Resolver();
        $schema = $resolver->resolve((int) $post_id, 'lesson');
        $ctx = CLMS_UI_Template_Context::make((int) $post_id, 'lesson');
        $engine = new CLMS_UI_Template_Engine();
        $rendered = $engine->render($ctx, $schema);
    } catch (Throwable $e) {
        $rendered = '';
    }
}

if (!empty(trim((string) $rendered))) {
    echo $rendered;
} elseif (is_active_sidebar('lesson-sidebar')) {
    dynamic_sidebar('lesson-sidebar');
} else {
    atora_theme_render_template_part('sections/shared/notice', ['message' => __('Sidebar de lección vacío.', 'atora-learning')]);
}
?>
</aside>
