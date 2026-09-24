<?php
/**
 * Atora Theme — Performance Settings
 */
if (!defined('ABSPATH')) exit;

if (isset($_POST['atora_save_performance'])) {
    check_admin_referer('atora_performance');
    $settings = [
        'disable_emoji'     => isset($_POST['disable_emoji'])     ? 1 : 0,
        'remove_query_strings' => isset($_POST['remove_query_strings']) ? 1 : 0,
        'defer_js'          => isset($_POST['defer_js'])          ? 1 : 0,
        'lazy_load_images'  => isset($_POST['lazy_load_images'])  ? 1 : 0,
        'preconnect_fonts'  => isset($_POST['preconnect_fonts'])  ? 1 : 0,
        'disable_xmlrpc'    => isset($_POST['disable_xmlrpc'])    ? 1 : 0,
        'disable_embeds'    => isset($_POST['disable_embeds'])    ? 1 : 0,
        'limit_revisions'   => absint($_POST['limit_revisions']   ?? 5),
    ];
    update_option('atora_performance_settings', $settings);
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Performance settings saved!', 'atora-learning') . '</p></div>';
}

$s = wp_parse_args(get_option('atora_performance_settings', []), [
    'disable_emoji'        => 1,
    'remove_query_strings' => 0,
    'defer_js'             => 0,
    'lazy_load_images'     => 1,
    'preconnect_fonts'     => 1,
    'disable_xmlrpc'       => 1,
    'disable_embeds'       => 0,
    'limit_revisions'      => 5,
]);

function atora_perf_row($name, $label, $desc, $val) {
    echo '<tr>';
    echo '<th scope="row">' . esc_html($label) . '</th>';
    echo '<td><label><input type="checkbox" name="' . esc_attr($name) . '" value="1"' . checked($val, 1, false) . '> ' . esc_html($desc) . '</label></td>';
    echo '</tr>';
}
?>
<div class="wrap">
<h1><?php esc_html_e('Performance', 'atora-learning'); ?></h1>

<form method="post" action="">
<?php wp_nonce_field('atora_performance'); ?>

<div class="atora-card" style="margin-top:20px;">
    <h2 style="margin-top:0;"><?php esc_html_e('Optimization Options', 'atora-learning'); ?></h2>
    <table class="form-table">
        <?php
        atora_perf_row('disable_emoji',     __('Disable Emoji Scripts',    'atora-learning'), __('Remove emoji detection JS and CSS (saves ~20KB)',           'atora-learning'), $s['disable_emoji']);
        atora_perf_row('remove_query_strings', __('Remove Query Strings', 'atora-learning'), __('Strip version query strings from CSS/JS for better caching', 'atora-learning'), $s['remove_query_strings']);
        atora_perf_row('defer_js',          __('Defer Non-Critical JS',    'atora-learning'), __('Add defer attribute to third-party scripts',                 'atora-learning'), $s['defer_js']);
        atora_perf_row('lazy_load_images',  __('Lazy Load Images',         'atora-learning'), __('Add loading="lazy" to images automatically',                 'atora-learning'), $s['lazy_load_images']);
        atora_perf_row('preconnect_fonts',  __('Preconnect Google Fonts',  'atora-learning'), __('Add preconnect hints for faster font loading',               'atora-learning'), $s['preconnect_fonts']);
        atora_perf_row('disable_xmlrpc',    __('Disable XML-RPC',          'atora-learning'), __('Block XML-RPC requests (security + performance)',            'atora-learning'), $s['disable_xmlrpc']);
        atora_perf_row('disable_embeds',    __('Disable oEmbed JS',        'atora-learning'), __('Remove WordPress embed script if not needed',                'atora-learning'), $s['disable_embeds']);
        ?>
        <tr>
            <th><?php esc_html_e('Limit Post Revisions', 'atora-learning'); ?></th>
            <td>
                <select name="limit_revisions" style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;">
                    <?php foreach ([0,3,5,10,20] as $n) : ?>
                    <option value="<?php echo $n; ?>" <?php selected($n, $s['limit_revisions']); ?>>
                        <?php echo $n === 0 ? esc_html__('Unlimited', 'atora-learning') : esc_html($n . ' ' . __('revisions max','atora-learning')); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
</div>

<div class="atora-card" style="margin-top:20px;background:#f0fdf4;border-color:#bbf7d0;">
    <h3 style="margin-top:0;color:#166534;">💡 <?php esc_html_e('Additional Recommendations', 'atora-learning'); ?></h3>
    <ul style="margin-bottom:0;color:#15803d;font-size:13px;line-height:1.8;">
        <li><?php esc_html_e('Use LiteSpeed Cache or WP Super Cache for full-page caching', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Serve images in WebP format via ShortPixel or Imagify', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Enable Gzip/Brotli compression at the server level', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Use a CDN (Cloudflare, BunnyCDN) for static assets', 'atora-learning'); ?></li>
    </ul>
</div>

<div style="margin-top:20px;">
    <?php submit_button(__('Save Performance Settings', 'atora-learning'), 'primary', 'atora_save_performance', false); ?>
</div>
</form>
</div>
<style>.atora-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:24px;}</style>
