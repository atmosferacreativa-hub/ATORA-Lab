<?php
/**
 * Bootstrap de diseno ATORA Theme.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once ATORA_THEME_DIR . '/inc/design/class-atora-design-center.php';
require_once ATORA_THEME_DIR . '/inc/design/class-atora-design-presets.php';
require_once ATORA_THEME_DIR . '/inc/design/class-atora-design-tokens.php';
require_once ATORA_THEME_DIR . '/inc/design/class-atora-design-wizard.php';

Atora_Design_Center::init();
Atora_Design_Presets::init();
Atora_Design_Wizard::init();

add_action('wp_head', ['Atora_Design_Tokens', 'render_css_variables'], 9);
