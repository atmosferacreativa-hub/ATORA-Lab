<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$message = $context['message'] ?? __('No hay informacion para mostrar.', 'atora-learning');
?>
<div class="atora-theme-card atora-empty-state">
    <p><?php echo esc_html((string) $message); ?></p>
</div>
