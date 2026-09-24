<?php
/**
 * Atora Theme — Typography Settings
 */
if (!defined('ABSPATH')) exit;

if (isset($_POST['atora_save_typography'])) {
    check_admin_referer('atora_typography');
    update_option('atora_google_fonts_url', esc_url_raw($_POST['atora_google_fonts_url'] ?? ''));
    update_option('atora_font_body',        sanitize_text_field($_POST['atora_font_body']    ?? ''));
    update_option('atora_font_heading',     sanitize_text_field($_POST['atora_font_heading'] ?? ''));
    update_option('atora_font_size_base',   absint($_POST['atora_font_size_base']   ?? 16));
    update_option('atora_line_height',      sanitize_text_field($_POST['atora_line_height']  ?? '1.6'));
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Typography settings saved!', 'atora-learning') . '</p></div>';
}

$gf_url    = get_option('atora_google_fonts_url', '');
$font_body = get_option('atora_font_body', '');
$font_head = get_option('atora_font_heading', '');
$font_size = get_option('atora_font_size_base', 16);
$lh        = get_option('atora_line_height', '1.6');

$google_presets = [
    '' => __('— System Default —', 'atora-learning'),
    'Inter'       => 'Inter',
    'Nunito'      => 'Nunito',
    'Poppins'     => 'Poppins',
    'Raleway'     => 'Raleway',
    'Lato'        => 'Lato',
    'Open Sans'   => 'Open Sans',
    'Roboto'      => 'Roboto',
    'Montserrat'  => 'Montserrat',
    'Playfair Display' => 'Playfair Display',
    'Merriweather'=> 'Merriweather',
];
?>
<div class="wrap">
<h1><?php esc_html_e('Typography', 'atora-learning'); ?></h1>

<form method="post" action="">
<?php wp_nonce_field('atora_typography'); ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">

<div class="atora-card">
    <h2 style="margin-top:0;"><?php esc_html_e('Font Families', 'atora-learning'); ?></h2>
    <table class="form-table" style="margin:0;">
        <tr>
            <th><?php esc_html_e('Body Font', 'atora-learning'); ?></th>
            <td>
                <select id="atora_font_body" name="atora_font_body" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;width:100%;">
                    <?php foreach ($google_presets as $val => $label) : ?>
                    <option value="<?php echo esc_attr($val); ?>" <?php selected($val, $font_body); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e('Heading Font', 'atora-learning'); ?></th>
            <td>
                <select id="atora_font_heading" name="atora_font_heading" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;width:100%;">
                    <?php foreach ($google_presets as $val => $label) : ?>
                    <option value="<?php echo esc_attr($val); ?>" <?php selected($val, $font_head); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <p class="description" style="margin-top:10px;"><?php esc_html_e('Fonts are loaded from Google Fonts automatically when selected.', 'atora-learning'); ?></p>
</div>

<div class="atora-card">
    <h2 style="margin-top:0;"><?php esc_html_e('Size & Spacing', 'atora-learning'); ?></h2>
    <table class="form-table" style="margin:0;">
        <tr>
            <th><?php esc_html_e('Base Font Size', 'atora-learning'); ?></th>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <input id="atora_font_size_base" type="number" name="atora_font_size_base" value="<?php echo esc_attr($font_size); ?>" min="12" max="24" style="width:70px;padding:6px;border:1px solid #d1d5db;border-radius:6px;">
                    <span style="color:#6b7280;">px</span>
                </div>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e('Line Height', 'atora-learning'); ?></th>
            <td>
                <select id="atora_line_height" name="atora_line_height" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;">
                    <?php foreach (['1.4','1.5','1.6','1.7','1.8'] as $v) : ?>
                    <option value="<?php echo $v; ?>" <?php selected($v, $lh); ?>><?php echo $v; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
</div>

<div class="atora-card" style="grid-column:1/-1;">
    <h2 style="margin-top:0;"><?php esc_html_e('Live Preview', 'atora-learning'); ?></h2>
    <p class="description"><?php esc_html_e('Preview updates instantly so users do not have to guess typography choices.', 'atora-learning'); ?></p>

    <div id="atora-typography-preview" style="margin-top:12px;border:1px solid #e5e7eb;border-radius:8px;padding:20px;background:#fafafa;">
        <h3 id="atora-preview-heading" style="margin:0 0 10px 0;font-size:32px;line-height:1.2;"><?php esc_html_e('The quick brown fox jumps over the lazy dog', 'atora-learning'); ?></h3>
        <p id="atora-preview-body" style="margin:0;font-size:16px;color:#374151;">ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz 0123456789</p>
    </div>
</div>

<div class="atora-card" style="grid-column:1/-1;">
    <h2 style="margin-top:0;"><?php esc_html_e('Custom Google Fonts URL', 'atora-learning'); ?></h2>
    <p class="description"><?php esc_html_e('Paste a full Google Fonts @import URL to load a font not in the preset list. Overrides the font family selectors above.', 'atora-learning'); ?></p>
    <input type="url" name="atora_google_fonts_url" value="<?php echo esc_attr($gf_url); ?>" class="large-text" style="margin-top:10px;" placeholder="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <p class="description" style="margin-top:8px;"><a href="https://fonts.google.com/" target="_blank"><?php esc_html_e('Browse Google Fonts ↗', 'atora-learning'); ?></a></p>
</div>

</div>

<div style="margin-top:20px;">
    <?php submit_button(__('Save Typography', 'atora-learning'), 'primary', 'atora_save_typography', false); ?>
</div>
</form>
</div>
<style>.atora-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:24px;}</style>
<script>
(function() {
    const bodyFontEl = document.getElementById('atora_font_body');
    const headingFontEl = document.getElementById('atora_font_heading');
    const sizeEl = document.getElementById('atora_font_size_base');
    const lineHeightEl = document.getElementById('atora_line_height');
    const bodySample = document.getElementById('atora-preview-body');
    const headingSample = document.getElementById('atora-preview-heading');

    if (!bodyFontEl || !headingFontEl || !sizeEl || !lineHeightEl || !bodySample || !headingSample) {
        return;
    }

    const loadGoogleFont = (fontName) => {
        if (!fontName) return;
        const id = 'atora-font-preview-' + fontName.replace(/\s+/g, '-').toLowerCase();
        if (document.getElementById(id)) return;

        const link = document.createElement('link');
        link.id = id;
        link.rel = 'stylesheet';
        link.href = 'https://fonts.googleapis.com/css2?family=' + encodeURIComponent(fontName) + ':wght@400;600;700&display=swap';
        document.head.appendChild(link);
    };

    const getFontFamily = (value) => value ? '"' + value + '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif' : 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif';

    const renderPreview = () => {
        const bodyFont = bodyFontEl.value;
        const headingFont = headingFontEl.value;
        const fontSize = parseInt(sizeEl.value || '16', 10);
        const lineHeight = lineHeightEl.value || '1.6';

        loadGoogleFont(bodyFont);
        loadGoogleFont(headingFont);

        bodySample.style.fontFamily = getFontFamily(bodyFont);
        bodySample.style.fontSize = fontSize + 'px';
        bodySample.style.lineHeight = lineHeight;

        headingSample.style.fontFamily = getFontFamily(headingFont || bodyFont);
        headingSample.style.lineHeight = lineHeight;
    };

    [bodyFontEl, headingFontEl, sizeEl, lineHeightEl].forEach((el) => {
        el.addEventListener('change', renderPreview);
        el.addEventListener('input', renderPreview);
    });

    renderPreview();
})();
</script>
