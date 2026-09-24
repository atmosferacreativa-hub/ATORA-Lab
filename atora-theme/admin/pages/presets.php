<?php
if (!defined('ABSPATH')) {
    exit;
}

$presets = Atora_Design_Presets::get_presets();
$active = (string) get_option('atora_theme_active_preset', '');
?>
<div class="wrap">
    <h1><?php esc_html_e('Presets visuales', 'atora-learning'); ?></h1>
    <p><?php esc_html_e('Aplica un preset para configurar plantillas y tokens visuales recomendados.', 'atora-learning'); ?></p>

    <?php if (isset($_GET['preset']) && 'applied' === $_GET['preset']) : ?>
        <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Preset aplicado correctamente.', 'atora-learning'); ?></p></div>
    <?php endif; ?>

    <div class="atora-design-grid">
        <?php foreach ($presets as $preset_id => $preset) : ?>
            <div class="atora-design-card <?php echo $active === $preset_id ? 'is-active' : ''; ?>">
                <h2><?php echo esc_html((string) $preset['label']); ?></h2>
                <p><strong><?php esc_html_e('Curso:', 'atora-learning'); ?></strong> <?php echo esc_html((string) $preset['course']); ?></p>
                <p><strong><?php esc_html_e('Lección:', 'atora-learning'); ?></strong> <?php echo esc_html((string) $preset['lesson']); ?></p>
                <p><strong><?php esc_html_e('Programa:', 'atora-learning'); ?></strong> <?php echo esc_html((string) $preset['program']); ?></p>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="atora_theme_apply_preset">
                    <input type="hidden" name="preset_id" value="<?php echo esc_attr($preset_id); ?>">
                    <?php wp_nonce_field('atora_theme_apply_preset'); ?>
                    <?php submit_button($active === $preset_id ? __('Preset activo', 'atora-learning') : __('Aplicar preset', 'atora-learning'), 'secondary', 'submit', false); ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
