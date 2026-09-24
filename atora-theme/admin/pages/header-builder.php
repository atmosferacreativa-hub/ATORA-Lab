<?php
/**
 * Atora Theme — Visual Header Builder
 */
if (!defined('ABSPATH')) exit;

// Deprecation / compatibility notice when plugin UI is present.
if (class_exists('CLMS_UI_Template_Resolver')) {
    echo '<div class="notice notice-warning is-dismissible" style="max-width:1100px;margin:12px 0 0;">';
    echo '<p>' . esc_html__('Nota: este constructor es una compatibilidad legacy. El Composer del plugin ATORA gestiona ahora la composición de templates. Usa este builder sólo si entiendes el riesgo de duplicación.', 'atora-learning') . '</p>';
    echo '</div>';
}

// Load or init config
$config = get_option('atora_header_config', []);
if (empty($config) || !isset($config['zones'])) {
    $config = [
        'announcement' => ['enabled' => false, 'text' => '', 'bg' => '#4F46E5', 'color' => '#fff', 'dismissible' => false],
        'sticky'       => true,
        'transparent'  => false,
        'bg'           => '#ffffff',
        'border'       => true,
        'height'       => 72,
        'zones'        => [
            'left'   => [['type' => 'logo',     'id' => uniqid('b'), 'settings' => []]],
            'center' => [['type' => 'nav',       'id' => uniqid('b'), 'settings' => ['location' => 'primary']]],
            'right'  => [['type' => 'user_menu', 'id' => uniqid('b'), 'settings' => []]],
        ],
    ];
}

// Save
if (isset($_POST['atora_save_header_builder'])) {
    check_admin_referer('atora_header_builder');
    $raw     = stripslashes($_POST['atora_header_config_json'] ?? '{}');
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        update_option('atora_header_config', $decoded);
        $config = $decoded;
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Header saved! ✓', 'atora-learning') . '</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('Error saving header config.', 'atora-learning') . '</p></div>';
    }
}

$config_json = wp_json_encode($config);

$block_types = [
    'logo'         => ['label' => 'Logo',          'icon' => '🖼️',  'color' => '#6366f1'],
    'nav'          => ['label' => 'Navigation',     'icon' => '☰',   'color' => '#0ea5e9'],
    'search'       => ['label' => 'Search',         'icon' => '🔍',  'color' => '#8b5cf6'],
    'cart'         => ['label' => 'Cart',           'icon' => '🛒',  'color' => '#f59e0b'],
    'cta'          => ['label' => 'CTA Button',     'icon' => '🔘',  'color' => '#10b981'],
    'user_menu'    => ['label' => 'User Menu',      'icon' => '👤',  'color' => '#ec4899'],
    'phone'        => ['label' => 'Phone',          'icon' => '📞',  'color' => '#14b8a6'],
    'text'         => ['label' => 'Text / HTML',    'icon' => '✏️',  'color' => '#f97316'],
    'spacer'       => ['label' => 'Spacer',         'icon' => '↔️',  'color' => '#94a3b8'],
];
?>
<div class="wrap" style="max-width:1400px;">
<h1 style="font-size:22px;margin-bottom:4px;"><?php esc_html_e('Header Builder', 'atora-learning'); ?></h1>
<p style="color:#6b7280;margin-bottom:20px;"><?php esc_html_e('Drag blocks into the header zones. Click a block to edit its settings. Save when done.', 'atora-learning'); ?></p>

<form method="post" id="hb-form">
    <?php wp_nonce_field('atora_header_builder'); ?>
    <input type="hidden" name="atora_header_config_json" id="hb-config-input" value="">

    <div style="display:grid;grid-template-columns:220px 1fr 280px;gap:16px;align-items:start;">

        <!-- ── LEFT: Block Palette ── -->
        <div>
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px;">
                <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin:0 0 12px;"><?php esc_html_e('Available Blocks', 'atora-learning'); ?></h3>
                <div id="hb-palette">
                    <?php foreach ($block_types as $type => $meta) : ?>
                    <div class="hb-palette-item" draggable="true" data-type="<?php echo esc_attr($type); ?>"
                         style="display:flex;align-items:center;gap:10px;padding:10px 12px;margin-bottom:6px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;cursor:grab;user-select:none;transition:all .15s;"
                         onmouseenter="this.style.borderColor='<?php echo esc_attr($meta['color']); ?>';this.style.background='#f0f9ff'"
                         onmouseleave="this.style.borderColor='#e5e7eb';this.style.background='#f9fafb'">
                        <span style="font-size:18px;line-height:1;"><?php echo $meta['icon']; ?></span>
                        <span style="font-size:13px;font-weight:600;color:#374151;"><?php echo esc_html($meta['label']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p style="font-size:11px;color:#9ca3af;margin:10px 0 0;text-align:center;"><?php esc_html_e('Drag to a zone →', 'atora-learning'); ?></p>
            </div>
        </div>

        <!-- ── CENTER: Header Preview ── -->
        <div>
            <!-- Global settings bar -->
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;margin-bottom:12px;display:flex;gap:20px;align-items:center;flex-wrap:wrap;">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" id="hb-sticky" style="accent-color:#4f46e5;">
                    <span><?php esc_html_e('Sticky', 'atora-learning'); ?></span>
                </label>
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" id="hb-transparent" style="accent-color:#4f46e5;">
                    <span><?php esc_html_e('Transparent on top', 'atora-learning'); ?></span>
                </label>
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" id="hb-border" checked style="accent-color:#4f46e5;">
                    <span><?php esc_html_e('Bottom border', 'atora-learning'); ?></span>
                </label>
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                    <span><?php esc_html_e('BG:', 'atora-learning'); ?></span>
                    <input type="color" id="hb-bg" value="#ffffff" style="width:36px;height:28px;border:1px solid #d1d5db;border-radius:4px;padding:1px;cursor:pointer;">
                </label>
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                    <span><?php esc_html_e('Height:', 'atora-learning'); ?></span>
                    <input type="number" id="hb-height" value="72" min="48" max="120" style="width:60px;padding:4px 6px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                    <span>px</span>
                </label>
            </div>

            <!-- Announcement Bar -->
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px 16px;margin-bottom:12px;">
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;cursor:pointer;margin-bottom:10px;">
                    <input type="checkbox" id="hb-ann-enabled" style="accent-color:#4f46e5;">
                    <?php esc_html_e('Announcement Bar', 'atora-learning'); ?>
                </label>
                <div id="hb-ann-fields" style="display:none;display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                    <input type="text" id="hb-ann-text" placeholder="<?php esc_attr_e('Announcement text...', 'atora-learning'); ?>" style="flex:1;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;min-width:200px;">
                    <label style="display:flex;align-items:center;gap:4px;font-size:12px;">BG <input type="color" id="hb-ann-bg" value="#4F46E5" style="width:30px;height:26px;border-radius:4px;border:1px solid #d1d5db;padding:1px;cursor:pointer;"></label>
                    <label style="display:flex;align-items:center;gap:4px;font-size:12px;">Text <input type="color" id="hb-ann-color" value="#ffffff" style="width:30px;height:26px;border-radius:4px;border:1px solid #d1d5db;padding:1px;cursor:pointer;"></label>
                    <label style="display:flex;align-items:center;gap:4px;font-size:12px;cursor:pointer;"><input type="checkbox" id="hb-ann-dismiss" style="accent-color:#4f46e5;"> Dismissible</label>
                </div>
            </div>

            <!-- Zone canvas -->
            <div id="hb-canvas" style="background:#fff;border:2px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                <!-- Zone labels -->
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;text-align:center;padding:6px 0;background:#f9fafb;border-bottom:1px solid #f3f4f6;">
                    <span>Left</span><span>Center</span><span>Right</span>
                </div>
                <!-- Drop zones -->
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;min-height:88px;gap:0;">
                    <div id="zone-left"   class="hb-drop-zone" data-zone="left"   style="border-right:1px dashed #e5e7eb;padding:10px;display:flex;flex-wrap:wrap;align-items:center;gap:6px;min-height:80px;"></div>
                    <div id="zone-center" class="hb-drop-zone" data-zone="center" style="border-right:1px dashed #e5e7eb;padding:10px;display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:6px;min-height:80px;"></div>
                    <div id="zone-right"  class="hb-drop-zone" data-zone="right"  style="padding:10px;display:flex;flex-wrap:wrap;align-items:center;justify-content:flex-end;gap:6px;min-height:80px;"></div>
                </div>
            </div>
            <p style="font-size:11px;color:#9ca3af;text-align:center;margin-top:6px;"><?php esc_html_e('Drop blocks above · Click a block to edit · Drag to reorder', 'atora-learning'); ?></p>
        </div>

        <!-- ── RIGHT: Settings Panel ── -->
        <div>
            <div id="hb-settings-panel" style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;">
                <h3 style="font-size:13px;font-weight:700;color:#374151;margin:0 0 12px;"><?php esc_html_e('Block Settings', 'atora-learning'); ?></h3>
                <p id="hb-no-selection" style="font-size:13px;color:#9ca3af;"><?php esc_html_e('Click a block in the header to edit its settings.', 'atora-learning'); ?></p>
                <div id="hb-settings-content" style="display:none;"></div>
            </div>

            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-top:12px;">
                <button type="submit" name="atora_save_header_builder" class="button button-primary" style="width:100%;padding:10px;font-size:14px;font-weight:600;border-radius:8px;">
                    <?php esc_html_e('💾 Save Header', 'atora-learning'); ?>
                </button>
                <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" class="button" style="width:100%;text-align:center;margin-top:8px;display:block;box-sizing:border-box;"><?php esc_html_e('👁 Preview Site', 'atora-learning'); ?></a>
            </div>
        </div>

    </div><!-- /grid -->
</form>
</div><!-- /wrap -->

<style>
.hb-drop-zone { transition: background .15s; }
.hb-drop-zone.drag-over { background: #eff6ff !important; outline: 2px dashed #4f46e5; }
.hb-block-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 10px; background: #f0f9ff; border: 1.5px solid #bfdbfe;
    border-radius: 8px; font-size: 12px; font-weight: 600; color: #1e40af;
    cursor: pointer; user-select: none; position: relative;
    transition: all .15s;
}
.hb-block-chip:hover { border-color: #4f46e5; background: #ede9fe; color: #4f46e5; }
.hb-block-chip.selected { border-color: #4f46e5; background: #ede9fe; color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.15); }
.hb-block-chip .chip-remove {
    width: 16px; height: 16px; background: #ef4444; color: #fff;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; cursor: pointer; flex-shrink: 0;
    margin-left: 2px;
}
.hb-block-chip .chip-remove:hover { background: #dc2626; }
.hb-drop-zone .hb-placeholder { font-size: 11px; color: #d1d5db; text-align: center; width: 100%; pointer-events: none; }
#hb-settings-content table { width: 100%; }
#hb-settings-content th { font-size: 12px; font-weight: 600; color: #374151; padding: 6px 0; text-align: left; white-space: nowrap; padding-right: 8px; }
#hb-settings-content td { padding: 4px 0; }
#hb-settings-content input[type=text], #hb-settings-content input[type=url], #hb-settings-content select, #hb-settings-content textarea {
    width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;
}
#hb-settings-content input[type=color] { width: 38px; height: 30px; border-radius: 4px; border: 1px solid #d1d5db; padding: 1px; cursor: pointer; }
</style>

<script>
(function(){
'use strict';

// ── State ──────────────────────────────────────────────────────────────────
const CONFIG = <?php echo $config_json; ?>;
// Normalize
if (!CONFIG.zones) CONFIG.zones = {left:[],center:[],right:[]};
['left','center','right'].forEach(z => { if (!CONFIG.zones[z]) CONFIG.zones[z] = []; });
if (!CONFIG.announcement) CONFIG.announcement = {enabled:false,text:'',bg:'#4F46E5',color:'#fff',dismissible:false};

let selectedBlockId = null;

const BLOCK_META = <?php echo wp_json_encode($block_types); ?>;
const SETTINGS_DEFS = {
    logo:      [],
    nav:       [{key:'location', label:'Menu Location', type:'select', opts: <?php
        $locs = get_registered_nav_menus();
        $opts = [];
        foreach ($locs as $slug => $name) $opts[$slug] = $name;
        echo wp_json_encode($opts);
    ?>}],
    search:    [],
    cart:      [],
    cta:       [
        {key:'text',  label:'Button Text',  type:'text',  default:'Get Started'},
        {key:'url',   label:'URL',          type:'url',   default:'#'},
        {key:'style', label:'Style',        type:'select', opts:{primary:'Primary',secondary:'Secondary',outline:'Outline',white:'White'}},
    ],
    user_menu: [],
    phone:     [{key:'number', label:'Phone Number', type:'text', default:''}],
    text:      [{key:'html',   label:'HTML Content',  type:'textarea', default:''}],
    spacer:    [{key:'size',   label:'Width (px)',    type:'number',   default:24}],
};

// ── DOM refs ───────────────────────────────────────────────────────────────
const zones = {
    left:   document.getElementById('zone-left'),
    center: document.getElementById('zone-center'),
    right:  document.getElementById('zone-right'),
};
const settingsContent = document.getElementById('hb-settings-content');
const noSelection     = document.getElementById('hb-no-selection');
const configInput     = document.getElementById('hb-config-input');
const palette         = document.getElementById('hb-palette');

// ── Render all zones ───────────────────────────────────────────────────────
function renderZones() {
    Object.keys(zones).forEach(zone => {
        const el = zones[zone];
        el.innerHTML = '';
        const blocks = CONFIG.zones[zone] || [];
        if (blocks.length === 0) {
            el.innerHTML = '<div class="hb-placeholder">Drop blocks here</div>';
        }
        blocks.forEach(block => el.appendChild(makeChip(block, zone)));
    });
    syncGlobalUI();
    saveToInput();
}

function makeChip(block, zone) {
    const meta = BLOCK_META[block.type] || {label: block.type, icon: '▢'};
    const chip = document.createElement('div');
    chip.className = 'hb-block-chip' + (block.id === selectedBlockId ? ' selected' : '');
    chip.dataset.id   = block.id;
    chip.dataset.zone = zone;
    chip.draggable    = true;
    chip.innerHTML = `<span>${meta.icon}</span><span>${meta.label}</span><span class="chip-remove" data-id="${block.id}" data-zone="${zone}" title="Remove">×</span>`;

    chip.addEventListener('click', e => {
        if (e.target.classList.contains('chip-remove')) return;
        selectBlock(block.id, zone);
    });
    chip.querySelector('.chip-remove').addEventListener('click', e => {
        e.stopPropagation();
        removeBlock(block.id, zone);
    });

    // Drag within zones
    chip.addEventListener('dragstart', e => {
        e.dataTransfer.setData('application/x-block-move', JSON.stringify({id: block.id, fromZone: zone}));
        e.dataTransfer.effectAllowed = 'move';
        setTimeout(() => chip.style.opacity = '.4', 0);
    });
    chip.addEventListener('dragend', () => chip.style.opacity = '1');
    return chip;
}

// ── Drop zones ─────────────────────────────────────────────────────────────
Object.keys(zones).forEach(zone => {
    const el = zones[zone];
    el.addEventListener('dragover', e => { e.preventDefault(); e.dataTransfer.dropEffect = 'copy'; el.classList.add('drag-over'); });
    el.addEventListener('dragleave', () => el.classList.remove('drag-over'));
    el.addEventListener('drop', e => {
        e.preventDefault();
        el.classList.remove('drag-over');

        // Palette → zone
        const newType = e.dataTransfer.getData('text/plain');
        if (newType) {
            addBlock(zone, newType);
            return;
        }

        // Move existing block
        const moveData = e.dataTransfer.getData('application/x-block-move');
        if (moveData) {
            const {id, fromZone} = JSON.parse(moveData);
            if (fromZone === zone) return;
            const block = findBlock(id);
            if (!block) return;
            CONFIG.zones[fromZone] = CONFIG.zones[fromZone].filter(b => b.id !== id);
            CONFIG.zones[zone].push(block);
            renderZones();
        }
    });
});

// Palette drag
palette.querySelectorAll('.hb-palette-item').forEach(item => {
    item.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', item.dataset.type);
        e.dataTransfer.effectAllowed = 'copy';
    });
});

// ── CRUD ───────────────────────────────────────────────────────────────────
function addBlock(zone, type) {
    const defaults = {};
    (SETTINGS_DEFS[type] || []).forEach(s => { if (s.default !== undefined) defaults[s.key] = s.default; });
    const block = {type, id: 'b' + Date.now() + Math.random().toString(36).slice(2,6), settings: defaults};
    CONFIG.zones[zone].push(block);
    renderZones();
    selectBlock(block.id, zone);
}

function removeBlock(id, zone) {
    CONFIG.zones[zone] = CONFIG.zones[zone].filter(b => b.id !== id);
    if (selectedBlockId === id) clearSettings();
    renderZones();
}

function findBlock(id) {
    for (const z of ['left','center','right']) {
        const b = CONFIG.zones[z].find(b => b.id === id);
        if (b) return b;
    }
    return null;
}

// ── Settings panel ─────────────────────────────────────────────────────────
function selectBlock(id, zone) {
    selectedBlockId = id;
    document.querySelectorAll('.hb-block-chip').forEach(c => c.classList.toggle('selected', c.dataset.id === id));
    const block = findBlock(id);
    if (!block) return;
    renderSettings(block);
}

function clearSettings() {
    selectedBlockId = null;
    settingsContent.style.display = 'none';
    noSelection.style.display = '';
}

function renderSettings(block) {
    const defs = SETTINGS_DEFS[block.type] || [];
    noSelection.style.display = 'none';
    settingsContent.style.display = '';

    if (defs.length === 0) {
        settingsContent.innerHTML = '<p style="font-size:13px;color:#6b7280;">This block has no configurable settings.</p>';
        return;
    }

    let html = '<table>';
    defs.forEach(def => {
        html += `<tr><th>${def.label}</th><td>`;
        const val = block.settings[def.key] !== undefined ? block.settings[def.key] : (def.default || '');
        if (def.type === 'select') {
            html += `<select data-key="${def.key}">`;
            Object.entries(def.opts).forEach(([k,v]) => html += `<option value="${k}" ${val===k?'selected':''}>${v}</option>`);
            html += '</select>';
        } else if (def.type === 'textarea') {
            html += `<textarea data-key="${def.key}" rows="4">${escHtml(String(val))}</textarea>`;
        } else if (def.type === 'number') {
            html += `<input type="number" data-key="${def.key}" value="${parseInt(val)||0}" min="0" max="400">`;
        } else {
            html += `<input type="${def.type||'text'}" data-key="${def.key}" value="${escHtml(String(val))}">`;
        }
        html += '</td></tr>';
    });
    html += '</table>';
    settingsContent.innerHTML = html;

    settingsContent.querySelectorAll('[data-key]').forEach(input => {
        input.addEventListener('input', () => {
            block.settings[input.dataset.key] = input.value;
            saveToInput();
        });
    });
}

function escHtml(s) { return s.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

// ── Global settings sync ───────────────────────────────────────────────────
function syncGlobalUI() {
    document.getElementById('hb-sticky').checked      = !!CONFIG.sticky;
    document.getElementById('hb-transparent').checked = !!CONFIG.transparent;
    document.getElementById('hb-border').checked      = CONFIG.border !== false;
    document.getElementById('hb-bg').value            = CONFIG.bg || '#ffffff';
    document.getElementById('hb-height').value        = CONFIG.height || 72;
    // Announcement
    const ann = CONFIG.announcement || {};
    document.getElementById('hb-ann-enabled').checked = !!ann.enabled;
    document.getElementById('hb-ann-text').value      = ann.text || '';
    document.getElementById('hb-ann-bg').value        = ann.bg   || '#4F46E5';
    document.getElementById('hb-ann-color').value     = ann.color || '#ffffff';
    document.getElementById('hb-ann-dismiss').checked = !!ann.dismissible;
    document.getElementById('hb-ann-fields').style.display = ann.enabled ? 'flex' : 'none';
}

['hb-sticky','hb-transparent','hb-border','hb-bg','hb-height'].forEach(id => {
    document.getElementById(id).addEventListener('change', function() {
        CONFIG.sticky      = document.getElementById('hb-sticky').checked;
        CONFIG.transparent = document.getElementById('hb-transparent').checked;
        CONFIG.border      = document.getElementById('hb-border').checked;
        CONFIG.bg          = document.getElementById('hb-bg').value;
        CONFIG.height      = parseInt(document.getElementById('hb-height').value) || 72;
        saveToInput();
    });
});

['hb-ann-enabled','hb-ann-text','hb-ann-bg','hb-ann-color','hb-ann-dismiss'].forEach(id => {
    document.getElementById(id).addEventListener('change', function() {
        if (!CONFIG.announcement) CONFIG.announcement = {};
        CONFIG.announcement.enabled    = document.getElementById('hb-ann-enabled').checked;
        CONFIG.announcement.text       = document.getElementById('hb-ann-text').value;
        CONFIG.announcement.bg         = document.getElementById('hb-ann-bg').value;
        CONFIG.announcement.color      = document.getElementById('hb-ann-color').value;
        CONFIG.announcement.dismissible= document.getElementById('hb-ann-dismiss').checked;
        document.getElementById('hb-ann-fields').style.display = CONFIG.announcement.enabled ? 'flex' : 'none';
        saveToInput();
    });
});

// ── Serialize ──────────────────────────────────────────────────────────────
function saveToInput() {
    configInput.value = JSON.stringify(CONFIG);
}

// ── Form submit: serialize first ───────────────────────────────────────────
document.getElementById('hb-form').addEventListener('submit', saveToInput);

// ── Init ───────────────────────────────────────────────────────────────────
renderZones();

})();
</script>
