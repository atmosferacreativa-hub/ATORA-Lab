<?php
/**
 * Atora Theme — Visual Footer Builder
 */
if (!defined('ABSPATH')) exit;

// Deprecation / compatibility notice when plugin UI is present.
if (class_exists('CLMS_UI_Template_Resolver')) {
    echo '<div class="notice notice-warning is-dismissible" style="max-width:1100px;margin:12px 0 0;">';
    echo '<p>' . esc_html__('Nota: este constructor es una compatibilidad legacy. El Composer del plugin ATORA gestiona ahora la composición de templates. Usa este builder sólo si entiendes el riesgo de duplicación.', 'atora-learning') . '</p>';
    echo '</div>';
}

// Load config
$defaults = [
    'bg'         => '#111827',
    'text_color' => '#9CA3AF',
    'link_color' => '#D1D5DB',
    'columns'    => [
        ['widget' => 'footer-1', 'span' => 3],
        ['widget' => 'footer-2', 'span' => 3],
        ['widget' => 'footer-3', 'span' => 3],
        ['widget' => 'footer-4', 'span' => 3],
    ],
    'bottom' => [
        'copyright' => '© ' . gmdate('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
        'menu'      => 'footer',
        'show_logo' => true,
        'border'    => true,
    ],
];

$config = get_option('atora_footer_config', []);
if (!is_array($config)) {
    $config = [];
}
$config['columns'] = (!empty($config['columns']) && is_array($config['columns'])) ? $config['columns'] : $defaults['columns'];
$config['bottom'] = wp_parse_args((array) ($config['bottom'] ?? []), $defaults['bottom']);
$config = wp_parse_args($config, $defaults);

// Save
if (isset($_POST['atora_save_footer_builder'])) {
    check_admin_referer('atora_footer_builder');
    $raw     = stripslashes($_POST['atora_footer_config_json'] ?? '{}');
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        update_option('atora_footer_config', $decoded);
        $config = $decoded;
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Footer saved! ✓', 'atora-learning') . '</p></div>';
    }
}

// All registered sidebars
global $wp_registered_sidebars;
$footer_areas = [];
for ($i = 1; $i <= 6; $i++) {
    $id = 'footer-' . $i;
    if (isset($wp_registered_sidebars[$id])) $footer_areas[$id] = $wp_registered_sidebars[$id]['name'];
}
$footer_areas[''] = '— ' . __('None', 'atora-learning') . ' —';

$nav_menus = [];
foreach (get_registered_nav_menus() as $slug => $name) $nav_menus[$slug] = $name;

$config_json = wp_json_encode($config);
?>
<div class="wrap" style="max-width:1100px;">
<h1 style="font-size:22px;margin-bottom:4px;"><?php esc_html_e('Footer Builder', 'atora-learning'); ?></h1>
<p style="color:#6b7280;margin-bottom:20px;"><?php esc_html_e('Configure footer columns, colors, and the bottom bar.', 'atora-learning'); ?></p>

<form method="post" id="fb-form">
    <?php wp_nonce_field('atora_footer_builder'); ?>
    <input type="hidden" name="atora_footer_config_json" id="fb-config-input" value="">

<div style="display:grid;grid-template-columns:1fr 280px;gap:16px;">

<!-- ── LEFT: Footer Canvas ── -->
<div>

    <!-- Colors & global -->
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px 16px;margin-bottom:14px;display:flex;gap:20px;flex-wrap:wrap;align-items:center;">
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
            BG <input type="color" id="fb-bg" value="<?php echo esc_attr($config['bg'] ?? '#111827'); ?>" style="width:38px;height:30px;border-radius:4px;border:1px solid #d1d5db;padding:1px;cursor:pointer;">
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
            <?php esc_html_e('Text', 'atora-learning'); ?> <input type="color" id="fb-text" value="<?php echo esc_attr($config['text_color'] ?? '#9CA3AF'); ?>" style="width:38px;height:30px;border-radius:4px;border:1px solid #d1d5db;padding:1px;cursor:pointer;">
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
            <?php esc_html_e('Links', 'atora-learning'); ?> <input type="color" id="fb-links" value="<?php echo esc_attr($config['link_color'] ?? '#D1D5DB'); ?>" style="width:38px;height:30px;border-radius:4px;border:1px solid #d1d5db;padding:1px;cursor:pointer;">
        </label>
    </div>

    <!-- Column layout -->
    <div style="background:#1f2937;border-radius:12px;padding:20px;margin-bottom:14px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <span style="color:#9ca3af;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;"><?php esc_html_e('Footer Columns', 'atora-learning'); ?></span>
            <button type="button" id="fb-add-col" class="button button-secondary" style="font-size:12px;padding:4px 12px;"><?php esc_html_e('+ Add Column', 'atora-learning'); ?></button>
        </div>
        <div id="fb-columns" style="display:flex;gap:12px;align-items:stretch;min-height:160px;"></div>
    </div>

    <!-- Bottom bar -->
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px;">
        <h3 style="font-size:13px;font-weight:700;margin:0 0 12px;"><?php esc_html_e('Bottom Bar', 'atora-learning'); ?></h3>
        <table class="form-table" style="margin:0;">
            <tr>
                <th style="padding:8px 12px 8px 0;font-size:13px;"><?php esc_html_e('Copyright Text', 'atora-learning'); ?></th>
                <td><input type="text" id="fb-copyright" value="<?php echo esc_attr($config['bottom']['copyright'] ?? ''); ?>" class="regular-text" style="font-size:13px;"></td>
            </tr>
            <tr>
                <th style="padding:8px 12px 8px 0;font-size:13px;"><?php esc_html_e('Menu', 'atora-learning'); ?></th>
                <td>
                    <select id="fb-menu" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                        <option value=""><?php esc_html_e('— None —', 'atora-learning'); ?></option>
                        <?php foreach ($nav_menus as $slug => $name) : ?>
                        <option value="<?php echo esc_attr($slug); ?>" <?php selected($slug, $config['bottom']['menu'] ?? 'footer'); ?>><?php echo esc_html($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th style="padding:8px 12px 8px 0;font-size:13px;"><?php esc_html_e('Show Logo', 'atora-learning'); ?></th>
                <td><label><input type="checkbox" id="fb-show-logo" <?php checked(!empty($config['bottom']['show_logo'])); ?>> <?php esc_html_e('Show site logo in bottom bar', 'atora-learning'); ?></label></td>
            </tr>
            <tr>
                <th style="padding:8px 12px 8px 0;font-size:13px;"><?php esc_html_e('Top Border', 'atora-learning'); ?></th>
                <td><label><input type="checkbox" id="fb-border" <?php checked($config['bottom']['border'] ?? true); ?>> <?php esc_html_e('Show divider line above bottom bar', 'atora-learning'); ?></label></td>
            </tr>
        </table>
    </div>

</div>

<!-- ── RIGHT: Actions & guide ── -->
<div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px;">
        <button type="submit" name="atora_save_footer_builder" class="button button-primary" style="width:100%;padding:10px;font-size:14px;font-weight:600;border-radius:8px;">
            <?php esc_html_e('💾 Save Footer', 'atora-learning'); ?>
        </button>
        <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" class="button" style="width:100%;text-align:center;margin-top:8px;display:block;box-sizing:border-box;"><?php esc_html_e('👁 Preview Site', 'atora-learning'); ?></a>
        <a href="<?php echo esc_url(admin_url('widgets.php')); ?>" class="button" style="width:100%;text-align:center;margin-top:8px;display:block;box-sizing:border-box;"><?php esc_html_e('⚙️ Manage Widgets', 'atora-learning'); ?></a>
    </div>

    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px;margin-top:12px;">
        <h3 style="font-size:13px;font-weight:700;color:#065f46;margin:0 0 8px;">💡 <?php esc_html_e('How widgets work', 'atora-learning'); ?></h3>
        <ol style="font-size:12px;color:#047857;line-height:1.7;margin:0;padding-left:16px;">
            <li><?php esc_html_e('Each column shows a "Footer Column" widget area.', 'atora-learning'); ?></li>
            <li><?php esc_html_e('Go to Appearance → Widgets.', 'atora-learning'); ?></li>
            <li><?php esc_html_e('Find "Footer Column 1, 2, 3…" and expand it.', 'atora-learning'); ?></li>
            <li><?php esc_html_e('Drag widgets (Text, Menu, Image…) into the area.', 'atora-learning'); ?></li>
            <li><?php esc_html_e('Save here to control how many columns appear.', 'atora-learning'); ?></li>
        </ol>
    </div>

    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px;margin-top:12px;">
        <h3 style="font-size:13px;font-weight:700;color:#1e40af;margin:0 0 8px;">📐 <?php esc_html_e('Column Widths', 'atora-learning'); ?></h3>
        <p style="font-size:12px;color:#1d4ed8;margin:0;">
            <?php esc_html_e('Span = number of grid units (1–12). Total of all spans = equal distribution. Example: 4 columns of span 3 = equal 4-column grid.', 'atora-learning'); ?>
        </p>
    </div>
</div>

</div><!-- /grid -->
</form>
</div>

<style>
.fb-col-card {
    flex: 1; min-width: 120px; background: rgba(255,255,255,.06);
    border: 1.5px dashed rgba(255,255,255,.2); border-radius: 10px;
    padding: 12px; color: #d1d5db; position: relative;
    transition: border-color .15s;
}
.fb-col-card:hover { border-color: rgba(99,102,241,.7); }
.fb-col-card select, .fb-col-card input { background: rgba(0,0,0,.3); color: #e5e7eb; border: 1px solid rgba(255,255,255,.15); border-radius:6px; padding:4px 6px; font-size:12px; width:100%; margin-top:6px; }
.fb-col-card select option { background: #1f2937; }
.fb-col-remove { position: absolute; top: 6px; right: 6px; background: #ef4444; color: #fff; border: none; border-radius: 50%; width: 18px; height: 18px; cursor: pointer; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.fb-col-remove:hover { background: #dc2626; }
</style>

<script>
(function(){
'use strict';

const CONFIG = <?php echo $config_json; ?>;
if (!CONFIG.columns) CONFIG.columns = [{widget:'footer-1',span:3},{widget:'footer-2',span:3},{widget:'footer-3',span:3},{widget:'footer-4',span:3}];
if (!CONFIG.bottom) CONFIG.bottom = {copyright:'',menu:'footer',show_logo:true,border:true};

const WIDGET_AREAS = <?php echo wp_json_encode($footer_areas); ?>;
const colsEl = document.getElementById('fb-columns');
const configInput = document.getElementById('fb-config-input');

function renderColumns() {
    colsEl.innerHTML = '';
    CONFIG.columns.forEach((col, idx) => {
        const card = document.createElement('div');
        card.className = 'fb-col-card';
        card.innerHTML = `
            <button type="button" class="fb-col-remove" data-idx="${idx}" title="Remove">×</button>
            <div style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Column ${idx+1}</div>
            <select data-idx="${idx}" data-key="widget">
                ${Object.entries(WIDGET_AREAS).map(([k,v]) => `<option value="${k}" ${col.widget===k?'selected':''}>${v}</option>`).join('')}
            </select>
            <div style="margin-top:8px;font-size:11px;color:#9ca3af;">Span (width)</div>
            <input type="number" data-idx="${idx}" data-key="span" value="${col.span||3}" min="1" max="12">
        `;
        card.querySelector('.fb-col-remove').addEventListener('click', function() {
            CONFIG.columns.splice(idx, 1);
            renderColumns();
            save();
        });
        card.querySelectorAll('[data-key]').forEach(el => {
            el.addEventListener('change', function() {
                CONFIG.columns[idx][el.dataset.key] = el.tagName === 'INPUT' ? (parseInt(el.value)||3) : el.value;
                save();
            });
        });
        colsEl.appendChild(card);
    });
}

document.getElementById('fb-add-col').addEventListener('click', () => {
    const nextIdx = CONFIG.columns.length + 1;
    CONFIG.columns.push({widget: 'footer-' + nextIdx, span: 3});
    renderColumns();
    save();
});

// Global color / settings
['fb-bg','fb-text','fb-links'].forEach(id => {
    document.getElementById(id).addEventListener('input', save);
});
['fb-copyright','fb-menu'].forEach(id => {
    document.getElementById(id).addEventListener('input', save);
});
['fb-show-logo','fb-border'].forEach(id => {
    document.getElementById(id).addEventListener('change', save);
});

function save() {
    CONFIG.bg         = document.getElementById('fb-bg').value;
    CONFIG.text_color = document.getElementById('fb-text').value;
    CONFIG.link_color = document.getElementById('fb-links').value;
    CONFIG.bottom.copyright = document.getElementById('fb-copyright').value;
    CONFIG.bottom.menu      = document.getElementById('fb-menu').value;
    CONFIG.bottom.show_logo = document.getElementById('fb-show-logo').checked;
    CONFIG.bottom.border    = document.getElementById('fb-border').checked;
    configInput.value = JSON.stringify(CONFIG);
}

document.getElementById('fb-form').addEventListener('submit', save);

renderColumns();
save();
})();
</script>
