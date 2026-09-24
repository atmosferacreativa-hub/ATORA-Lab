<?php
if (!defined('ABSPATH')) exit;
if (!(bool) get_option('atora_theme_enable_crm_sections', 1)) {
    return;
}
$context = isset($atora_context) ? (array) $atora_context : [];
$course_id = absint($context['course_id'] ?? 0);
?>
<section class="atora-section atora-section-course-crm-box">
    <div class="atora-theme-card atora-crm-box">
        <h3><?php esc_html_e('Captura CRM', 'atora-learning'); ?></h3>
        <?php if (class_exists('Atora_CRM_Bridge') && Atora_CRM_Bridge::is_available()) : ?>
            <form class="atora-theme-crm-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                <input type="hidden" name="action" value="atora_theme_submit_crm_lead">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('atora-theme-nonce')); ?>">
                <input type="hidden" name="course_id" value="<?php echo esc_attr((string) $course_id); ?>">
                <input type="hidden" name="source" value="course-section">
                <p><input type="text" name="first_name" placeholder="<?php esc_attr_e('Nombre', 'atora-learning'); ?>" required></p>
                <p><input type="email" name="email" placeholder="<?php esc_attr_e('Correo', 'atora-learning'); ?>" required></p>
                <p><button type="submit" class="button button-primary"><?php esc_html_e('Quiero informacion', 'atora-learning'); ?></button></p>
            </form>
        <?php else : ?>
            <?php atora_theme_render_template_part('sections/shared/notice', ['message' => __('CRM no activo. Activa ATORA LMS para capturas avanzadas.', 'atora-learning')]); ?>
        <?php endif; ?>
    </div>
</section>
