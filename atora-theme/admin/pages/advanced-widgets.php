<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1><?php esc_html_e('Widgets avanzados', 'atora-learning'); ?></h1>

    <div class="notice notice-info">
        <p><?php esc_html_e('Los widgets clásicos quedan como modo avanzado de compatibilidad. El flujo principal es Plantillas + Secciones + Presets + Asistente.', 'atora-learning'); ?></p>
    </div>

    <h2><?php esc_html_e('Cuando usar widgets avanzados', 'atora-learning'); ?></h2>
    <ul>
        <li><?php esc_html_e('Cuando necesitas insertar contenido en sidebars legacy.', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Cuando mantienes layouts heredados de versiones anteriores.', 'atora-learning'); ?></li>
    </ul>

    <h2><?php esc_html_e('Recomendacion', 'atora-learning'); ?></h2>
    <p><?php esc_html_e('Para captacion de leads, usa la seccion "Captura CRM" en templates de curso/programa/landing en lugar del widget clasico.', 'atora-learning'); ?></p>

    <p>
        <a class="button button-primary" href="<?php echo esc_url(admin_url('widgets.php')); ?>"><?php esc_html_e('Ir a Widgets (avanzado)', 'atora-learning'); ?></a>
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=atora-theme-templates')); ?>"><?php esc_html_e('Volver a Plantillas', 'atora-learning'); ?></a>
    </p>
</div>
