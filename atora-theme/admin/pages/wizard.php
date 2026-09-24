<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap atora-design-wizard" data-atora-wizard>
    <h1><?php esc_html_e('Asistente ATORA', 'atora-learning'); ?></h1>
    <p><?php esc_html_e('Configura tu experiencia en seis pasos simples.', 'atora-learning'); ?></p>

    <?php if (isset($_GET['updated'])) : ?>
        <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Asistente guardado.', 'atora-learning'); ?></p></div>
    <?php endif; ?>

    <ol class="atora-wizard-steps">
        <li><?php esc_html_e('Paso 1: ¿Qué estás creando?', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Paso 2: Elige tipo de experiencia.', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Paso 3: Elige plantillas.', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Paso 4: Activa secciones.', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Paso 5: Conecta curso/programa/producto.', 'atora-learning'); ?></li>
        <li><?php esc_html_e('Paso 6: Revisión final.', 'atora-learning'); ?></li>
    </ol>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="atora_theme_save_wizard">
        <?php wp_nonce_field('atora_theme_save_wizard'); ?>

        <table class="form-table" role="presentation">
            <tr>
                <th><label for="atora_wizard_course"><?php esc_html_e('Plantilla de curso', 'atora-learning'); ?></label></th>
                <td>
                    <select name="atora_wizard_course" id="atora_wizard_course">
                        <?php foreach (atora_theme_get_registered_templates('course') as $id => $template) : ?>
                            <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html((string) $template['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="atora_wizard_lesson"><?php esc_html_e('Plantilla de lección', 'atora-learning'); ?></label></th>
                <td>
                    <select name="atora_wizard_lesson" id="atora_wizard_lesson">
                        <?php foreach (atora_theme_get_registered_templates('lesson') as $id => $template) : ?>
                            <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html((string) $template['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="atora_wizard_program"><?php esc_html_e('Plantilla de programa', 'atora-learning'); ?></label></th>
                <td>
                    <select name="atora_wizard_program" id="atora_wizard_program">
                        <?php foreach (atora_theme_get_registered_templates('program') as $id => $template) : ?>
                            <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html((string) $template['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="atora_wizard_home"><?php esc_html_e('Plantilla de home', 'atora-learning'); ?></label></th>
                <td>
                    <select name="atora_wizard_home" id="atora_wizard_home">
                        <?php foreach (atora_theme_get_registered_templates('home') as $id => $template) : ?>
                            <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html((string) $template['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Guardar asistente', 'atora-learning')); ?>
    </form>
</div>
