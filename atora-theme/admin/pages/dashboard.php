<?php
/**
 * Atora Theme — Admin Dashboard
 */
if (!defined('ABSPATH')) exit;

$scheme_id = get_option('atora_color_scheme', 'atora');
$scheme    = class_exists('Atora_Color_Schemes') ? Atora_Color_Schemes::get_scheme($scheme_id) : ['primary' => '#4F46E5'];
$primary   = esc_attr($scheme['primary']);
$brand_logo_html = function_exists('atora_get_brand_logo_html')
    ? atora_get_brand_logo_html([
        'link'        => false,
        'image_class' => 'atora-admin-brand-logo',
        'loading'     => 'eager',
        'width'       => 220,
    ])
    : '';
$lms_active  = class_exists('Atora_LMS') || defined('ATORA_LMS_VERSION');
$woo_active  = class_exists('WooCommerce');
$clms_active = class_exists('CLMS_Helper');
?>
<div class="wrap atora-admin-wrap">
<h1 style="display:none"></h1>

<div style="background:linear-gradient(135deg,<?php echo $primary; ?>,#7C3AED);color:#fff;padding:32px 36px;border-radius:12px;margin:20px 0 24px;display:flex;justify-content:space-between;align-items:center;">
    <div>
        <?php if ($brand_logo_html) : ?>
            <div style="margin:0 0 14px;line-height:1;">
                <?php echo $brand_logo_html; ?>
            </div>
        <?php endif; ?>
        <h1 style="color:#fff;font-size:26px;margin:0 0 6px;">ATORA Theme <span style="font-weight:400;opacity:.8;">v<?php echo esc_html(ATORA_THEME_VERSION); ?></span></h1>
        <p style="margin:0;opacity:.85;font-size:14px;"><?php esc_html_e('LMS Theme Dashboard', 'atora-learning'); ?></p>
    </div>
    <div style="text-align:right;font-size:13px;opacity:.8;line-height:1.8;">
        <div>WordPress <?php echo esc_html(get_bloginfo('version')); ?></div>
        <div>PHP <?php echo esc_html(PHP_VERSION); ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
<?php
$course_count  = wp_count_posts('lm_course')->publish ?? 0;
$lesson_count  = wp_count_posts('lm_lesson')->publish  ?? 0;
$program_count = post_type_exists('lm_program') ? (wp_count_posts('lm_program')->publish ?? 0) : 0;
$user_count    = count_users()['total_users'];
foreach ([
    [$course_count,  __('Published Courses','atora-learning'), '#4F46E5'],
    [$lesson_count,  __('Published Lessons','atora-learning'), '#10B981'],
    [$program_count, __('Programs','atora-learning'),          '#F59E0B'],
    [$user_count,    __('Total Users','atora-learning'),       '#EC4899'],
] as [$val, $label, $color]) : ?>
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px 24px;">
    <div style="font-size:30px;font-weight:800;color:<?php echo esc_attr($color); ?>;line-height:1;"><?php echo esc_html($val); ?></div>
    <div style="font-size:12px;color:#6b7280;margin-top:6px;"><?php echo esc_html($label); ?></div>
</div>
<?php endforeach; ?>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<div class="postbox" style="margin:0;">
    <div class="postbox-header"><h2 class="hndle"><?php esc_html_e('Integration Status', 'atora-learning'); ?></h2></div>
    <div class="inside" style="margin-bottom:0;">
        <?php foreach ([
            [$lms_active,  __('Atora LMS plugin active', 'atora-learning')],
            [$clms_active, __('CLMS_Helper available', 'atora-learning')],
            [$woo_active,  __('WooCommerce active', 'atora-learning')],
            [taxonomy_exists('lm_course_level'),        __('Taxonomy lm_course_level OK', 'atora-learning')],
            [post_type_exists('lm_cohort'),             __('Post type lm_cohort OK', 'atora-learning')],
            [is_active_sidebar('student-dashboard'),    __('Widget: student-dashboard', 'atora-learning')],
            [file_exists(ATORA_THEME_DIR.'/archive-lm_program.php'), __('Template archive-lm_program.php', 'atora-learning')],
        ] as [$ok, $label]) :
            $color = $ok ? '#059669' : '#DC2626';
            $bg    = $ok ? '#d1fae5' : '#fee2e2';
            $icon  = $ok ? '✓' : '✗';
        ?>
        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid #f3f4f6;">
            <span style="width:22px;height:22px;background:<?php echo $bg; ?>;color:<?php echo $color; ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;"><?php echo $icon; ?></span>
            <span style="font-size:13px;color:#374151;"><?php echo esc_html($label); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="postbox" style="margin:0;">
    <div class="postbox-header"><h2 class="hndle"><?php esc_html_e('Quick Links', 'atora-learning'); ?></h2></div>
    <div class="inside" style="margin-bottom:0;">
        <?php foreach ([
            [admin_url('admin.php?page=atora-colors'),      __('Color Schemes','atora-learning'),         'dashicons-art'],
            [admin_url('admin.php?page=atora-header'),      __('Header Builder','atora-learning'),        'dashicons-admin-appearance'],
            [admin_url('admin.php?page=atora-footer'),      __('Footer Builder','atora-learning'),        'dashicons-layout'],
            [admin_url('admin.php?page=atora-typography'),  __('Typography','atora-learning'),            'dashicons-editor-textcolor'],
            [admin_url('admin.php?page=atora-performance'), __('Performance','atora-learning'),           'dashicons-performance'],
            [admin_url('admin.php?page=atora-advanced'),    __('Advanced / Custom CSS','atora-learning'), 'dashicons-editor-code'],
            [admin_url('widgets.php'),                      __('Manage Widgets','atora-learning'),        'dashicons-screenoptions'],
            [admin_url('edit.php?post_type=lm_course'),     __('Manage Courses','atora-learning'),        'dashicons-welcome-learn-more'],
        ] as [$url, $label, $icon]) : ?>
        <a href="<?php echo esc_url($url); ?>" style="display:flex;align-items:center;gap:10px;padding:9px 4px;border-bottom:1px solid #f3f4f6;color:#1d2327;text-decoration:none;font-size:13px;">
            <span class="dashicons <?php echo esc_attr($icon); ?>" style="color:<?php echo $primary; ?>;font-size:16px;width:16px;height:16px;flex-shrink:0;"></span>
            <?php echo esc_html($label); ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

</div>
</div>
