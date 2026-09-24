<?php
/**
 * Atora Admin Panel
 * Main administration class
 */

class Atora_Admin {

	private function admin_icon(): string {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">'
			. '<path fill="currentColor" d="M3.2 18.9h7.5l-1.1-3.2H4.3l-1.1 3.2z"/>'
			. '<path fill="currentColor" d="M5.2 13.2h3.5L7 8.4l-1.8 4.8z"/>'
			. '<path fill="currentColor" d="M6.2 6.3h1.6L7 4.1 6.2 6.3z"/>'
			. '<path fill="currentColor" d="M12.2 3.2c-.7.2-1.2.8-1.2 1.6v14.4c0 .7.5 1.4 1.2 1.6l1.1.3c.9.2 1.7-.4 1.9-1.2l.2-.9c.1-.4.4-.8.8-1l3.9-2c.6-.3.9-.9.9-1.5V9.5c0-.6-.3-1.2-.9-1.5l-3.9-2c-.4-.2-.7-.6-.8-1l-.2-.9c-.2-.9-1-1.4-1.9-1.2l-1.1.3z"/>'
			. '</svg>';

		return 'data:image/svg+xml;base64,' . base64_encode( $svg );
	}
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('ATORA Theme', 'atora-learning'),
            __('ATORA THEME', 'atora-learning'),
            'manage_options',
            'atora-theme',
            [$this, 'dashboard_page'],
            $this->admin_icon(),
            59
        );
        
        add_submenu_page('atora-theme', __('Escritorio', 'atora-learning'), __('Escritorio', 'atora-learning'), 'manage_options', 'atora-theme', [$this, 'dashboard_page']);

        // Mantener builders visibles en entornos de desarrollo (ATORA_DEV_MODE),
        // incluso si el plugin ya maneja composición de templates.
        $dev_mode = defined('ATORA_DEV_MODE') && ATORA_DEV_MODE;
        if ($dev_mode || !class_exists('CLMS_UI_Template_Resolver')) {
            add_submenu_page('atora-theme', __('Constructor de cabecera', 'atora-learning'), __('Constructor de cabecera', 'atora-learning'), 'manage_options', 'atora-header', [$this, 'header_page']);
            add_submenu_page('atora-theme', __('Constructor de pie', 'atora-learning'), __('Constructor de pie', 'atora-learning'), 'manage_options', 'atora-footer', [$this, 'footer_page']);
        }
        add_submenu_page('atora-theme', __('Áreas de widgets', 'atora-learning'), __('Áreas de widgets', 'atora-learning'), 'manage_options', 'atora-widgets', [$this, 'widgets_page']);
        add_submenu_page('atora-theme', __('Esquemas de color', 'atora-learning'), __('Esquemas de color', 'atora-learning'), 'manage_options', 'atora-colors', [$this, 'colors_page']);
        add_submenu_page('atora-theme', __('Tipografía', 'atora-learning'), __('Tipografía', 'atora-learning'), 'manage_options', 'atora-typography', [$this, 'typography_page']);
        add_submenu_page('atora-theme', __('Rendimiento', 'atora-learning'), __('Rendimiento', 'atora-learning'), 'manage_options', 'atora-performance', [$this, 'performance_page']);
        add_submenu_page('atora-theme', __('Configuración de título', 'atora-learning'), __('Configuración de título', 'atora-learning'), 'manage_options', 'atora-title-settings', [$this, 'title_settings_page']);
        add_submenu_page('atora-theme', __('Avanzado', 'atora-learning'), __('Avanzado', 'atora-learning'), 'manage_options', 'atora-advanced', [$this, 'advanced_page']);
        add_submenu_page('atora-theme', __('Importar/Exportar', 'atora-learning'), __('Importar/Exportar', 'atora-learning'), 'manage_options', 'atora-import-export', [$this, 'import_export_page']);
    }
    
    public function register_settings() {
        register_setting('atora_options', 'atora_color_scheme');
        register_setting('atora_options', 'atora_header_layout');
        register_setting('atora_options', 'atora_footer_columns');
        register_setting('atora_options', 'atora_sticky_header');
        register_setting('atora_options', 'atora_performance_settings');
    }
    
    public function dashboard_page() {
        include ATORA_THEME_DIR . '/admin/pages/dashboard.php';
    }
    
    public function header_page() {
        include ATORA_THEME_DIR . '/admin/pages/header-builder.php';
    }
    
    public function footer_page() {
        include ATORA_THEME_DIR . '/admin/pages/footer-builder.php';
    }
    
    public function widgets_page() {
        include ATORA_THEME_DIR . '/admin/pages/widget-areas.php';
    }
    
    public function colors_page() {
        include ATORA_THEME_DIR . '/admin/pages/color-schemes.php';
    }
    
    public function typography_page() {
        include ATORA_THEME_DIR . '/admin/pages/typography.php';
    }
    
    public function performance_page() {
        include ATORA_THEME_DIR . '/admin/pages/performance.php';
    }
    
    public function title_settings_page() {
        include ATORA_THEME_DIR . '/admin/pages/title-settings.php';
    }
    
    public function advanced_page() {
        include ATORA_THEME_DIR . '/admin/pages/advanced.php';
    }
    
    public function import_export_page() {
        include ATORA_THEME_DIR . '/admin/pages/import-export.php';
    }
}
