<?php
/**
 * Footer Builder — Frontend Renderer
 */
if (!defined('ABSPATH')) exit;

function atora_get_footer_config() {
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
            'copyright'  => '© ' . gmdate('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
            'menu'       => 'footer',
            'show_logo'  => true,
            'border'     => true,
        ],
    ];

    $saved = get_option('atora_footer_config', null);
    if (!$saved || !is_array($saved)) {
        return $defaults;
    }

    $saved['columns'] = (!empty($saved['columns']) && is_array($saved['columns'])) ? $saved['columns'] : $defaults['columns'];
    $saved['bottom'] = wp_parse_args((array) ($saved['bottom'] ?? []), $defaults['bottom']);

    return wp_parse_args($saved, $defaults);
}

function atora_render_footer() {
    $cfg        = atora_get_footer_config();
    $bg         = sanitize_hex_color($cfg['bg']         ?? '#111827') ?: '#111827';
    $text_color = sanitize_hex_color($cfg['text_color'] ?? '#9CA3AF') ?: '#9CA3AF';
    $link_color = sanitize_hex_color($cfg['link_color'] ?? '#D1D5DB') ?: '#D1D5DB';
    $columns    = $cfg['columns'] ?? [];
    $bottom     = $cfg['bottom'] ?? [];

    echo '<footer class="site-footer" role="contentinfo" style="background:' . esc_attr($bg) . ';color:' . esc_attr($text_color) . ';">';
    echo '<style>.site-footer a{color:' . esc_attr($link_color) . ';} .site-footer a:hover{color:#fff;}</style>';
    echo '<div class="container">';

    // Widget columns
    if (!empty($columns)) {
        $has_widgets = false;
        foreach ($columns as $col) {
            $area = $col['widget'] ?? '';
            if ($area && is_active_sidebar($area)) { $has_widgets = true; break; }
        }

        if ($has_widgets) {
            $total = count($columns);
            $col_width = $total > 0 ? (100 / $total) : 25;
            echo '<div class="footer-widgets" style="display:grid;grid-template-columns:repeat(' . $total . ',1fr);gap:40px;padding:60px 0 40px;">';
            foreach ($columns as $col) {
                $area = $col['widget'] ?? '';
                echo '<div class="footer-col">';
                if ($area && is_active_sidebar($area)) dynamic_sidebar($area);
                echo '</div>';
            }
            echo '</div>';
        }
    }

    // Bottom bar
    $border_style = !empty($bottom['border']) ? 'border-top:1px solid rgba(255,255,255,.08);' : '';
    echo '<div class="footer-bottom" style="' . $border_style . 'padding:24px 0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">';

    $left_content = [];
    if (!empty($bottom['show_logo'])) {
        $logo_html = function_exists('atora_get_brand_logo_html')
            ? atora_get_brand_logo_html([
                'class'       => 'custom-logo-link atora-brand-logo-link atora-footer-logo-link',
                'image_class' => 'custom-logo atora-brand-logo atora-footer-logo',
                'loading'     => 'lazy',
                'width'       => 180,
            ])
            : '';
        if ($logo_html) {
            $left_content[] = '<div class="footer-logo">' . $logo_html . '</div>';
        }
    }
    $copyright = wp_kses_post($bottom['copyright'] ?? '');
    if ($copyright) $left_content[] = '<p style="margin:0;font-size:13px;">' . $copyright . '</p>';
    echo '<div>' . implode('', $left_content) . '</div>';

    $menu_loc = sanitize_key($bottom['menu'] ?? 'footer');
    if (has_nav_menu($menu_loc)) {
        wp_nav_menu(['theme_location' => $menu_loc, 'menu_class' => 'footer-menu', 'container' => false, 'depth' => 1]);
    }

    echo '</div></div></footer>';
}
