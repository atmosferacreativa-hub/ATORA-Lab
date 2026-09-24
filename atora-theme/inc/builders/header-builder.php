<?php
/**
 * Header Builder — Frontend Renderer
 * Reads atora_header_config and outputs the <header> tag.
 */
if (!defined('ABSPATH')) exit;

function atora_get_header_config() {
    $saved = get_option('atora_header_config', null);
    if ($saved && is_array($saved) && !empty($saved['zones'])) {
        return $saved;
    }
    // Default config
    return [
        'announcement' => ['enabled' => false, 'text' => '', 'bg' => '#4F46E5', 'color' => '#fff', 'dismissible' => false],
        'sticky'       => true,
        'transparent'  => false,
        'bg'           => '#ffffff',
        'border'       => true,
        'height'       => 72,
        'zones'        => [
            'left'   => [['type' => 'logo',     'id' => 'default-logo']],
            'center' => [['type' => 'nav',       'id' => 'default-nav',  'settings' => ['location' => 'primary']]],
            'right'  => [['type' => 'user_menu', 'id' => 'default-user', 'settings' => []]],
        ],
    ];
}

function atora_render_header_block($block) {
    $type     = $block['type']     ?? '';
    $settings = $block['settings'] ?? [];

    switch ($type) {

        case 'logo':
            echo '<div class="hb-block hb-logo">';
            $logo_html = function_exists('atora_get_brand_logo_html')
                ? atora_get_brand_logo_html([
                    'class'         => 'custom-logo-link atora-brand-logo-link atora-header-logo-link',
                    'image_class'   => 'custom-logo atora-brand-logo atora-header-logo',
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                ])
                : '';

            if ($logo_html) {
                echo $logo_html;
            } else {
                $w = absint($settings['text_size'] ?? 0);
                echo '<a href="' . esc_url(home_url('/')) . '" class="site-name-link" style="' . ($w ? "font-size:{$w}px;" : '') . '">' . esc_html(get_bloginfo('name')) . '</a>';
            }
            echo '</div>';
            break;

        case 'nav':
            $loc = sanitize_key($settings['location'] ?? 'primary');
            echo '<nav class="hb-block hb-nav main-navigation" aria-label="' . esc_attr__('Navigation', 'atora-learning') . '">';
            wp_nav_menu(['theme_location' => $loc, 'menu_class' => 'primary-menu', 'container' => false, 'fallback_cb' => false]);
            echo '</nav>';
            break;

        case 'search':
            echo '<div class="hb-block hb-search">';
            echo '<button type="button" class="hb-search-toggle" data-search-toggle aria-controls="atora-search-overlay" aria-expanded="false" aria-label="' . esc_attr__('Search', 'atora-learning') . '">';
            echo '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';
            echo '</button></div>';
            break;

        case 'cart':
            if (!function_exists('wc_get_cart_url')) break;
            $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
            echo '<div class="hb-block hb-cart">';
            echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="hb-cart-link" aria-label="' . esc_attr__('Cart', 'atora-learning') . '">';
            echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>';
            if ($count > 0) echo '<span class="cart-count">' . esc_html($count) . '</span>';
            echo '</a></div>';
            break;

        case 'cta':
            $text  = esc_html($settings['text']  ?? __('Get Started', 'atora-learning'));
            $url   = esc_url($settings['url']     ?? '#');
            $style = sanitize_key($settings['style'] ?? 'primary');
            echo '<div class="hb-block hb-cta">';
            echo '<a href="' . $url . '" class="btn btn-' . $style . ' btn-sm">' . $text . '</a>';
            echo '</div>';
            break;

        case 'user_menu':
            echo '<div class="hb-block hb-user">';
            if (is_user_logged_in()) {
                $acct_url = function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('dashboard') : get_permalink(get_option('woocommerce_myaccount_page_id'));
                echo '<a href="' . esc_url($acct_url) . '" class="btn btn-outline btn-sm">' . esc_html__('My Account', 'atora-learning') . '</a>';
                echo '<a href="' . esc_url(wp_logout_url(home_url())) . '" class="btn btn-ghost btn-sm">' . esc_html__('Logout', 'atora-learning') . '</a>';
            } else {
                echo '<a href="' . esc_url(wp_login_url()) . '" class="btn btn-outline btn-sm">' . esc_html__('Login', 'atora-learning') . '</a>';
                echo '<a href="' . esc_url(wp_registration_url()) . '" class="btn btn-primary btn-sm">' . esc_html__('Sign Up', 'atora-learning') . '</a>';
            }
            echo '</div>';
            break;

        case 'phone':
            $number = esc_html($settings['number'] ?? '');
            if (!$number) break;
            echo '<div class="hb-block hb-phone">';
            echo '<a href="tel:' . esc_attr(preg_replace('/\D/', '', $number)) . '" style="display:flex;align-items:center;gap:6px;font-size:14px;color:inherit;text-decoration:none;">';
            echo '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8 19.79 19.79 0 01.0 2.19 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>';
            echo $number . '</a></div>';
            break;

        case 'text':
            $html = wp_kses_post($settings['html'] ?? '');
            if ($html) echo '<div class="hb-block hb-text">' . $html . '</div>';
            break;

        case 'spacer':
            $size = absint($settings['size'] ?? 24);
            echo '<div class="hb-block hb-spacer" style="width:' . $size . 'px;flex-shrink:0;"></div>';
            break;

        case 'mobile_toggle':
            echo '<div class="hb-block hb-mobile-toggle">';
            echo '<button class="mobile-menu-toggle" aria-label="' . esc_attr__('Toggle Menu', 'atora-learning') . '" aria-expanded="false">';
            echo '<span class="hamburger"><span></span><span></span><span></span></span>';
            echo '</button></div>';
            break;
    }
}

function atora_render_header() {
    $cfg = atora_get_header_config();

    $sticky      = !empty($cfg['sticky']);
    $transparent = !empty($cfg['transparent']);
    $bg          = sanitize_hex_color($cfg['bg'] ?? '#ffffff') ?: '#ffffff';
    $height      = absint($cfg['height'] ?? 72);
    $border      = !empty($cfg['border']);

    $header_style = implode(';', array_filter([
        "background:{$bg}",
        "height:{$height}px",
        $border ? 'border-bottom:1px solid rgba(0,0,0,.07)' : '',
    ]));

    $classes = 'site-header';
    if ($sticky)      $classes .= ' is-sticky';
    if ($transparent) $classes .= ' is-transparent';

    // Announcement bar
    $ann = $cfg['announcement'] ?? [];
    if (!empty($ann['enabled']) && !empty($ann['text'])) {
        $ann_bg    = sanitize_hex_color($ann['bg']    ?? '#4F46E5') ?: '#4F46E5';
        $ann_color = sanitize_hex_color($ann['color'] ?? '#ffffff') ?: '#ffffff';
        echo '<div class="atora-announcement-bar" style="background:' . $ann_bg . ';color:' . $ann_color . ';text-align:center;padding:8px 16px;font-size:14px;">';
        echo wp_kses_post($ann['text']);
        if (!empty($ann['dismissible'])) {
            echo '<button onclick="this.parentElement.style.display=\'none\'" style="background:none;border:none;color:inherit;cursor:pointer;margin-left:12px;font-size:18px;line-height:1;" aria-label="Dismiss">×</button>';
        }
        echo '</div>';
    }

    echo '<header class="' . esc_attr($classes) . '" role="banner" style="' . esc_attr($header_style) . '">';
    echo '<div class="container">';
    echo '<div class="header-inner" style="display:flex;align-items:center;height:' . esc_attr($height) . 'px;gap:16px;">';

    // Zone: left
    $left = $cfg['zones']['left'] ?? [];
    echo '<div class="hb-zone hb-zone-left" style="display:flex;align-items:center;gap:12px;flex-shrink:0;">';
    foreach ($left as $block) atora_render_header_block($block);
    echo '</div>';

    // Zone: center (flex 1)
    $center = $cfg['zones']['center'] ?? [];
    echo '<div class="hb-zone hb-zone-center" style="display:flex;align-items:center;gap:12px;flex:1;justify-content:center;">';
    foreach ($center as $block) atora_render_header_block($block);
    echo '</div>';

    // Zone: right
    $right = $cfg['zones']['right'] ?? [];
    echo '<div class="hb-zone hb-zone-right" style="display:flex;align-items:center;gap:12px;flex-shrink:0;">';
    foreach ($right as $block) atora_render_header_block($block);
    // Always add mobile toggle at end of right zone
    echo '<div class="hb-block hb-mobile-toggle">';
    echo '<button class="mobile-menu-toggle" aria-label="' . esc_attr__('Toggle Menu', 'atora-learning') . '" aria-expanded="false">';
    echo '<span class="hamburger"><span></span><span></span><span></span></span>';
    echo '</button></div>';
    echo '</div>';

    echo '</div></div></header>';
    echo '<div class="nav-overlay" aria-hidden="true"></div>';

    $search_form = get_search_form(false);
    echo '<div id="atora-search-overlay" class="atora-search-overlay" hidden>';
    echo '<div class="atora-search-overlay-backdrop" data-search-close></div>';
    echo '<div class="atora-search-overlay-panel" role="dialog" aria-modal="true" aria-label="' . esc_attr__('Search the site', 'atora-learning') . '">';
    echo '<button type="button" class="atora-search-close" data-search-close aria-label="' . esc_attr__('Close search', 'atora-learning') . '">×</button>';
    echo '<h2 class="atora-search-title">' . esc_html__('Search', 'atora-learning') . '</h2>';
    echo '<div class="atora-search-form-wrap">' . $search_form . '</div>';
    echo '</div>';
    echo '</div>';
}
