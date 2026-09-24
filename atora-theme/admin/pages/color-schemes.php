<?php
/**
 * Atora Theme — Color Schemes
 */
if (!defined('ABSPATH')) exit;

if (isset($_POST['atora_save_colors'])) {
    check_admin_referer('atora_colors');
    update_option('atora_color_scheme', sanitize_key($_POST['atora_color_scheme'] ?? 'atora'));
    $custom = sanitize_hex_color($_POST['atora_primary_color'] ?? '');
    if ($custom) set_theme_mod('atora_primary_color', $custom);
    else remove_theme_mod('atora_primary_color');
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Color settings saved!', 'atora-learning') . '</p></div>';
}

$current = get_option('atora_color_scheme', 'atora');
$custom  = get_theme_mod('atora_primary_color', '');
$schemes = class_exists('Atora_Color_Schemes') ? Atora_Color_Schemes::get_schemes() : [];
$default_primary = $schemes['atora']['primary'] ?? '#4A7CB5';
?>
<div class="wrap">
<h1><?php esc_html_e('Color Schemes', 'atora-learning'); ?></h1>

<form method="post" action="">
<?php wp_nonce_field('atora_colors'); ?>

<div class="atora-card" style="margin-top:20px;">
    <h2 style="margin-top:0;"><?php esc_html_e('Preset Schemes', 'atora-learning'); ?></h2>
    <p id="atora-color-pending" class="description" style="display:none;margin:8px 0 0;"><?php esc_html_e('Selection changed. Click "Save Colors" to apply it.', 'atora-learning'); ?></p>
    <div class="atora-scheme-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:12px;margin-top:16px;">
    <?php foreach ($schemes as $id => $s) :
        $active = ($id === $current);
    ?>
    <label class="atora-scheme-option <?php echo $active ? 'is-active' : ''; ?>" data-scheme-option="<?php echo esc_attr($id); ?>" data-primary="<?php echo esc_attr($s['primary']); ?>" style="cursor:pointer;display:block;">
        <input type="radio" name="atora_color_scheme" value="<?php echo esc_attr($id); ?>" <?php checked($id,$current); ?> style="position:absolute;opacity:0;">
        <div class="atora-scheme-card" style="border:2px solid #e5e7eb;border-radius:10px;padding:14px;position:relative;transition:border-color .15s;">
            <span class="atora-scheme-check" aria-hidden="true" style="position:absolute;top:8px;right:8px;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;opacity:0;transform:scale(.85);transition:opacity .15s,transform .15s;">✓</span>
            <div style="display:flex;gap:6px;margin-bottom:10px;">
                <span style="width:26px;height:26px;border-radius:50%;background:<?php echo esc_attr($s['primary']); ?>;display:block;"></span>
                <span style="width:26px;height:26px;border-radius:50%;background:<?php echo esc_attr($s['secondary']); ?>;display:block;"></span>
                <span style="width:26px;height:26px;border-radius:50%;background:<?php echo esc_attr($s['accent']); ?>;display:block;"></span>
            </div>
            <div style="font-size:12px;font-weight:600;color:#111827;"><?php echo esc_html($s['name']); ?></div>
        </div>
    </label>
    <?php endforeach; ?>
    </div>
</div>

<div class="atora-card" style="margin-top:20px;">
    <h2 style="margin-top:0;"><?php esc_html_e('Custom Primary Color', 'atora-learning'); ?></h2>
    <p class="description"><?php esc_html_e('Overrides the preset. Leave empty to use the scheme color.', 'atora-learning'); ?></p>
    <div style="display:flex;align-items:center;gap:12px;margin-top:12px;">
        <input id="atora_primary_color" type="color" name="atora_primary_color" value="<?php echo esc_attr($custom ?: $default_primary); ?>" style="width:44px;height:44px;border-radius:8px;border:1px solid #d1d5db;padding:2px;cursor:pointer;">
        <input id="atora_primary_color_text" type="text" value="<?php echo esc_attr($custom); ?>" placeholder="<?php echo esc_attr($default_primary); ?>" style="width:110px;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;" readonly>
        <button id="atora_primary_color_reset" type="button" class="button"><?php esc_html_e('Reset', 'atora-learning'); ?></button>
    </div>
</div>

<div style="margin-top:20px;">
    <?php submit_button(__('Save Colors', 'atora-learning'), 'primary', 'atora_save_colors', false); ?>
</div>
</form>
</div>
<style>
.atora-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:24px;}
.atora-scheme-option.is-active .atora-scheme-card { border-color: var(--atora-scheme-primary, #4F46E5); }
.atora-scheme-option.is-active .atora-scheme-check { opacity: 1; transform: scale(1); background: var(--atora-scheme-primary, #4F46E5); }
</style>
<script>
(function() {
    const options = Array.from(document.querySelectorAll('[data-scheme-option]'));
    const radios = Array.from(document.querySelectorAll('input[name="atora_color_scheme"]'));
    const pendingNotice = document.getElementById('atora-color-pending');
    const colorInput = document.getElementById('atora_primary_color');
    const colorText = document.getElementById('atora_primary_color_text');
    const resetBtn = document.getElementById('atora_primary_color_reset');

    if (!options.length || !radios.length) return;

    const savedValue = (radios.find((radio) => radio.checked) || {}).value || '';
    let hasPendingChange = false;

    const renderOptions = () => {
        options.forEach((option) => {
            const radio = option.querySelector('input[type="radio"]');
            const primary = option.dataset.primary || '#4F46E5';
            option.style.setProperty('--atora-scheme-primary', primary);
            option.classList.toggle('is-active', !!(radio && radio.checked));
        });

        if (pendingNotice) {
            pendingNotice.style.display = hasPendingChange ? 'block' : 'none';
        }
    };

    radios.forEach((radio) => {
        radio.addEventListener('change', () => {
            hasPendingChange = radio.value !== savedValue;
            renderOptions();
        });
    });

    if (colorInput && colorText) {
        const syncColorText = () => {
            colorText.value = colorInput.value || '';
        };

        colorInput.addEventListener('input', syncColorText);
        colorInput.addEventListener('change', syncColorText);
        syncColorText();

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                colorInput.value = '<?php echo esc_js($default_primary); ?>';
                syncColorText();
            });
        }
    }

    renderOptions();
})();
</script>
