<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$message = $context['message'] ?? __('Informacion no disponible.', 'atora-learning');
?>
<div class="atora-theme-card atora-notice"><p><?php echo esc_html((string) $message); ?></p></div>
