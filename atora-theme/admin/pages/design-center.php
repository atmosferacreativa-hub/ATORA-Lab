<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap atora-design-center">
    <h1><?php esc_html_e('Centro de Diseño ATORA', 'atora-learning'); ?></h1>
    <p><?php esc_html_e('¿Qué quieres configurar?', 'atora-learning'); ?></p>

    <div class="atora-design-grid">
        <?php
        $items = [
            ['title' => __('Home de academia', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('Landing de curso', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('Curso interno', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('Lección', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('Programa / Diplomado', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('Página institucional', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('Artículo del blog', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('CRM visual', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('WooCommerce visual', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-templates')],
            ['title' => __('Widgets avanzados', 'atora-learning'), 'url' => admin_url('admin.php?page=atora-theme-advanced-widgets')],
        ];

        foreach ($items as $item) :
            ?>
            <a class="atora-design-card" href="<?php echo esc_url($item['url']); ?>">
                <span class="atora-design-card__title"><?php echo esc_html($item['title']); ?></span>
                <span class="atora-design-card__cta"><?php esc_html_e('Configurar', 'atora-learning'); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
