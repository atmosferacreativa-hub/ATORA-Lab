<?php
/**
 * Atora Theme — Advanced Settings
 */
if (!defined('ABSPATH')) exit;

if (isset($_POST['atora_save_advanced'])) {
    check_admin_referer('atora_advanced');
    update_option('atora_custom_css', wp_strip_all_tags($_POST['atora_custom_css'] ?? ''));
    update_option('atora_custom_js',  wp_strip_all_tags($_POST['atora_custom_js']  ?? ''));
    update_option('atora_maintenance_mode', isset($_POST['atora_maintenance_mode']) ? '1' : '0');
    update_option('atora_maintenance_message', sanitize_textarea_field($_POST['atora_maintenance_message'] ?? ''));
    update_option('atora_disable_admin_bar_frontend', isset($_POST['atora_disable_admin_bar_frontend']) ? '1' : '0');
    update_option('atora_show_technical_fields', isset($_POST['atora_show_technical_fields']) ? '1' : '0');
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Advanced settings saved!', 'atora-learning') . '</p></div>';
}

$custom_css    = get_option('atora_custom_css', '');
$custom_js     = get_option('atora_custom_js',  '');
$maintenance   = get_option('atora_maintenance_mode', '0');
$maint_msg     = get_option('atora_maintenance_message', 'We\'ll be back soon. Thank you for your patience.');
$hide_adminbar = get_option('atora_disable_admin_bar_frontend', '0');
$show_technical = get_option('atora_show_technical_fields', '0');
?>
<div class="wrap">
<h1><?php esc_html_e('Advanced Settings', 'atora-learning'); ?></h1>

<?php if ($maintenance === '1') : ?>
<div class="notice notice-warning"><p><strong><?php esc_html_e('⚠️ Maintenance mode is currently ACTIVE.', 'atora-learning'); ?></strong> <?php esc_html_e('Non-admin visitors see the maintenance message.', 'atora-learning'); ?></p></div>
<?php endif; ?>

<form method="post" action="">
<?php wp_nonce_field('atora_advanced'); ?>

<div class="atora-card" style="margin-top:20px;">
    <h2 style="margin-top:0;"><?php esc_html_e('Custom CSS', 'atora-learning'); ?></h2>
    <p class="description"><?php esc_html_e('Added to every frontend page, after all theme styles. Use :root variables to override design tokens.', 'atora-learning'); ?></p>
    <textarea name="atora_custom_css" rows="14" style="width:100%;font-family:monospace;font-size:13px;margin-top:10px;border:1px solid #d1d5db;border-radius:6px;padding:10px;" placeholder="/* Example */&#10;:root {&#10;  --color-primary: #e11d48;&#10;}&#10;.site-header { border-bottom: 2px solid var(--color-primary); }"><?php echo esc_textarea($custom_css); ?></textarea>
</div>

<div class="atora-card" style="margin-top:20px;">
    <h2 style="margin-top:0;"><?php esc_html_e('Custom JavaScript', 'atora-learning'); ?></h2>
    <p class="description"><?php esc_html_e('Injected before </body>. Do NOT include &lt;script&gt; tags.', 'atora-learning'); ?></p>
    <textarea name="atora_custom_js" rows="8" style="width:100%;font-family:monospace;font-size:13px;margin-top:10px;border:1px solid #d1d5db;border-radius:6px;padding:10px;" placeholder="// Example: Google Analytics 4&#10;// window.dataLayer = window.dataLayer || [];"><?php echo esc_textarea($custom_js); ?></textarea>
</div>

<div class="atora-card" style="margin-top:20px;">
    <h2 style="margin-top:0;"><?php esc_html_e('Site Settings', 'atora-learning'); ?></h2>
    <table class="form-table" style="margin:0;">
        <tr>
            <th><?php esc_html_e('Maintenance Mode', 'atora-learning'); ?></th>
            <td>
                <label><input type="checkbox" name="atora_maintenance_mode" value="1" <?php checked($maintenance,'1'); ?>> <?php esc_html_e('Enable maintenance mode for non-admins', 'atora-learning'); ?></label>
                <p class="description"><?php esc_html_e('Admins still see the full site.', 'atora-learning'); ?></p>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e('Maintenance Message', 'atora-learning'); ?></th>
            <td><textarea name="atora_maintenance_message" rows="3" class="large-text"><?php echo esc_textarea($maint_msg); ?></textarea></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Admin Bar', 'atora-learning'); ?></th>
            <td><label><input type="checkbox" name="atora_disable_admin_bar_frontend" value="1" <?php checked($hide_adminbar,'1'); ?>> <?php esc_html_e('Hide admin bar on frontend for non-admins', 'atora-learning'); ?></label></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Campos técnicos', 'atora-learning'); ?></th>
            <td>
                <label><input type="checkbox" name="atora_show_technical_fields" value="1" <?php checked($show_technical, '1'); ?>> <?php esc_html_e('Mostrar metabox nativo de campos personalizados en el editor', 'atora-learning'); ?></label>
                <p class="description"><?php esc_html_e('Por defecto está oculto para evitar ruido técnico en páginas, entradas, cursos, lecciones, programas y docentes.', 'atora-learning'); ?></p>
            </td>
        </tr>
    </table>
</div>

<div style="margin-top:20px;">
    <?php submit_button(__('Save Advanced Settings', 'atora-learning'), 'primary', 'atora_save_advanced', false); ?>
</div>
</form>
</div>
<style>.atora-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:24px;}</style>
