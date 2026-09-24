<?php
/**
 * Bootstrap del sistema de templates ATORA Theme.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once ATORA_THEME_DIR . '/inc/templates/class-atora-template-registry.php';
require_once ATORA_THEME_DIR . '/inc/templates/class-atora-template-loader.php';
require_once ATORA_THEME_DIR . '/inc/templates/class-atora-template-renderer.php';
require_once ATORA_THEME_DIR . '/inc/templates/class-atora-template-context.php';
require_once ATORA_THEME_DIR . '/inc/templates/class-atora-template-admin.php';
require_once ATORA_THEME_DIR . '/inc/templates/class-atora-template-metaboxes.php';
require_once ATORA_THEME_DIR . '/inc/templates/class-atora-template-importer.php';
require_once ATORA_THEME_DIR . '/inc/templates/template-hooks.php';

Atora_Template_Admin::init();
Atora_Template_Metaboxes::init();
Atora_Template_Importer::init();
