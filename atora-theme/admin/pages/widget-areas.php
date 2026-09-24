<?php
/**
 * Atora Theme — Widget Areas
 */
if (!defined('ABSPATH')) exit;

global $wp_registered_sidebars;

$areas = [
    'Core'  => ['before-header','header-left','header-center','header-right','after-header','before-content','after-content','before-footer','after-footer'],
    'Sidebars' => ['sidebar-primary','sidebar-secondary'],
    'LMS'   => ['student-dashboard','course-sidebar','lesson-sidebar','before-course-content','after-course-content','course-archive-filters'],
    'Footer' => ['footer-1','footer-2','footer-3','footer-4','footer-5','footer-6'],
];

$locations = [
    'before-header' => __('Shown above the site header', 'atora-learning'),
    'header-left' => __('Header builder area (left)', 'atora-learning'),
    'header-center' => __('Header builder area (center)', 'atora-learning'),
    'header-right' => __('Header builder area (right)', 'atora-learning'),
    'after-header' => __('Shown below the site header', 'atora-learning'),
    'before-content' => __('Shown before main page content', 'atora-learning'),
    'sidebar-primary' => __('Main blog sidebar (index/single)', 'atora-learning'),
    'sidebar-secondary' => __('Available for custom templates', 'atora-learning'),
    'after-content' => __('Shown after main page content', 'atora-learning'),
    'before-footer' => __('Shown above the footer', 'atora-learning'),
    'after-footer' => __('Shown below the footer', 'atora-learning'),
    'student-dashboard' => __('Shown on Student Dashboard page', 'atora-learning'),
    'course-sidebar' => __('Shown on single course pages', 'atora-learning'),
    'lesson-sidebar' => __('Shown on single lesson pages', 'atora-learning'),
    'before-course-content' => __('Shown before course content section', 'atora-learning'),
    'after-course-content' => __('Shown after course content section', 'atora-learning'),
    'course-archive-filters' => __('Shown in course archive filters column', 'atora-learning'),
];
?>
<div class="wrap">
<h1><?php esc_html_e('Widget Areas', 'atora-learning'); ?></h1>

<p><?php esc_html_e('The following widget areas are registered by Atora Theme. Add widgets via', 'atora-learning'); ?> <a href="<?php echo esc_url(admin_url('widgets.php')); ?>"><?php esc_html_e('Appearance → Widgets', 'atora-learning'); ?></a>.</p>

<?php foreach ($areas as $group => $ids) : ?>
<div class="atora-card" style="margin-top:20px;">
    <h2 style="margin-top:0;font-size:15px;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;"><?php echo esc_html($group); ?></h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px;">
    <?php foreach ($ids as $id) :
        $registered = isset($wp_registered_sidebars[$id]);
        $count = $registered ? count(wp_get_sidebars_widgets()[$id] ?? []) : 0;
        $sidebar_name = $registered ? $wp_registered_sidebars[$id]['name'] : $id;
        $bg    = $registered ? '#f0fdf4' : '#fef2f2';
        $border= $registered ? '#bbf7d0' : '#fecaca';
        $icon  = $registered ? '✓' : '✗';
        $icolor= $registered ? '#059669' : '#dc2626';
    ?>
    <div style="background:<?php echo $bg; ?>;border:1px solid <?php echo $border; ?>;border-radius:8px;padding:12px 14px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <span style="font-weight:600;font-size:13px;color:#111827;"><?php echo esc_html($sidebar_name); ?></span>
            <span style="color:<?php echo $icolor; ?>;font-size:12px;font-weight:700;"><?php echo $icon; ?></span>
        </div>
        <div style="font-family:monospace;font-size:11px;color:#6b7280;margin-bottom:4px;"><?php echo esc_html($id); ?></div>
        <?php if ($registered) : ?>
        <div style="font-size:12px;color:<?php echo $count ? '#4f46e5' : '#9ca3af'; ?>;">
            <?php echo $count ? sprintf(esc_html(_n('%d widget','%d widgets',$count,'atora-learning')),$count) : esc_html__('No widgets','atora-learning'); ?>
        </div>
        <div style="font-size:11px;color:#6b7280;margin-top:4px;">
            <?php echo esc_html($locations[$id] ?? __('Location defined by template', 'atora-learning')); ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>

<div style="margin-top:20px;">
    <a href="<?php echo esc_url(admin_url('widgets.php')); ?>" class="button button-primary"><?php esc_html_e('Manage Widgets →', 'atora-learning'); ?></a>
</div>
</div>
<style>.atora-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:24px;}</style>
