<?php
/**
 * Bootstrap de compatibilidad ATORA Theme.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once ATORA_THEME_DIR . '/inc/compatibility/class-atora-plugin-bridge.php';
require_once ATORA_THEME_DIR . '/inc/compatibility/class-atora-woocommerce-bridge.php';
require_once ATORA_THEME_DIR . '/inc/compatibility/class-atora-crm-bridge.php';
require_once ATORA_THEME_DIR . '/inc/compatibility/class-atora-widget-compat.php';

Atora_CRM_Bridge::init();
Atora_Widget_Compat::init();
