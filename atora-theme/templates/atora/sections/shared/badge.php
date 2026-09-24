<?php
if (!defined('ABSPATH')) exit;
$context = isset($atora_context) ? (array) $atora_context : [];
$label = $context['label'] ?? __('Nuevo', 'atora-learning');
?>
<span class="atora-badge"><?php echo esc_html((string) $label); ?></span>
