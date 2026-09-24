<?php
/**
 * Atora Theme — Import / Export Settings
 */
if (!defined('ABSPATH')) exit;

$option_keys = [
    'atora_color_scheme','atora_header_layout','atora_sticky_header','atora_header_transparent',
    'atora_header_show_search','atora_header_show_cart','atora_header_cta_text','atora_header_cta_url',
    'atora_header_announcement','atora_footer_columns','atora_footer_copyright','atora_footer_bg_color',
    'atora_footer_text_color','atora_footer_show_social','atora_footer_show_menus',
    'atora_font_body','atora_font_heading','atora_font_size_base','atora_line_height','atora_google_fonts_url',
    'atora_performance_settings','atora_custom_css','atora_custom_js',
    'atora_maintenance_mode','atora_maintenance_message','atora_disable_admin_bar_frontend',
    'atora_hide_title_post','atora_hide_title_page',
];
$theme_mods = ['atora_primary_color'];

// Export
if (isset($_POST['atora_export']) && check_admin_referer('atora_import_export')) {
    $data = ['options' => [], 'theme_mods' => []];
    foreach ($option_keys as $k)  $data['options'][$k]    = get_option($k);
    foreach ($theme_mods  as $k)  $data['theme_mods'][$k] = get_theme_mod($k);
    $json = wp_json_encode($data, JSON_PRETTY_PRINT);
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="atora-theme-settings-' . gmdate('Y-m-d') . '.json"');
    header('Content-Length: ' . strlen($json));
    echo $json;
    exit;
}

// Import
if (isset($_POST['atora_import']) && check_admin_referer('atora_import_export')) {
    if (!empty($_FILES['atora_import_file']['tmp_name'])) {
        $raw  = file_get_contents(sanitize_text_field($_FILES['atora_import_file']['tmp_name']));
        $data = json_decode($raw, true);
        if (is_array($data)) {
            foreach ((array)($data['options']    ?? []) as $k => $v) if (in_array($k, $option_keys, true)) update_option(sanitize_key($k), $v);
            foreach ((array)($data['theme_mods'] ?? []) as $k => $v) if (in_array($k, $theme_mods,  true)) set_theme_mod(sanitize_key($k), $v);
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings imported successfully!', 'atora-learning') . '</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('Invalid JSON file.', 'atora-learning') . '</p></div>';
        }
    }
}

// Reset
if (isset($_POST['atora_reset']) && check_admin_referer('atora_import_export')) {
    if ($_POST['atora_reset_confirm'] === 'RESET') {
        foreach ($option_keys as $k) delete_option($k);
        foreach ($theme_mods  as $k) remove_theme_mod($k);
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('All theme settings have been reset to defaults.', 'atora-learning') . '</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('Type RESET to confirm.', 'atora-learning') . '</p></div>';
    }
}
?>
<div class="wrap">
<h1><?php esc_html_e('Import / Export', 'atora-learning'); ?></h1>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">

<div class="atora-card">
    <h2 style="margin-top:0;"><?php esc_html_e('Export Settings', 'atora-learning'); ?></h2>
    <p><?php esc_html_e('Download all current theme settings as a JSON file. Use it to migrate settings to another site or as a backup.', 'atora-learning'); ?></p>
    <form method="post" action="">
        <?php wp_nonce_field('atora_import_export'); ?>
        <?php submit_button(__('Download Settings JSON', 'atora-learning'), 'primary', 'atora_export', false); ?>
    </form>
</div>

<div class="atora-card">
    <h2 style="margin-top:0;"><?php esc_html_e('Import Settings', 'atora-learning'); ?></h2>
    <p><?php esc_html_e('Upload a previously exported JSON file to restore settings.', 'atora-learning'); ?></p>
    <form method="post" enctype="multipart/form-data" action="">
        <?php wp_nonce_field('atora_import_export'); ?>
        <input type="file" name="atora_import_file" accept=".json" style="margin-bottom:12px;display:block;">
        <?php submit_button(__('Import Settings', 'atora-learning'), 'secondary', 'atora_import', false); ?>
    </form>
</div>

</div>

<div class="atora-card" style="margin-top:20px;border-color:#fecaca;background:#fff5f5;">
    <h2 style="margin-top:0;color:#991b1b;"><?php esc_html_e('Reset to Defaults', 'atora-learning'); ?></h2>
    <p><?php esc_html_e('This will delete all Atora Theme settings and restore defaults. This action cannot be undone.', 'atora-learning'); ?></p>
    <form method="post" action="" onsubmit="return document.getElementById('reset-confirm').value === 'RESET' || (alert('Type RESET to confirm'), false);">
        <?php wp_nonce_field('atora_import_export'); ?>
        <input type="text" id="reset-confirm" name="atora_reset_confirm" placeholder="<?php esc_attr_e('Type RESET to confirm', 'atora-learning'); ?>" style="padding:8px;border:1px solid #fca5a5;border-radius:6px;width:240px;margin-right:10px;">
        <?php submit_button(__('Reset All Settings', 'atora-learning'), 'delete', 'atora_reset', false); ?>
    </form>
</div>

</div>
<style>.atora-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:24px;}</style>
