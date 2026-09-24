<?php
/**
 * Tokens visuales del theme.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Design_Tokens {

    public static function get_tokens(): array {
        $stored = get_option('atora_theme_preset_tokens', []);
        $stored = is_array($stored) ? $stored : [];

        $defaults = [
            'accent' => '#0f766e',
            'surface' => '#ffffff',
            'text' => '#0f172a',
            'muted' => '#475569',
        ];

        $tokens = wp_parse_args($stored, $defaults);
        $safe_tokens = [];

        foreach ($tokens as $key => $value) {
            $safe_key = self::sanitize_token_key((string) $key);
            $safe_value = self::sanitize_token_value((string) $value);
            if ($safe_key && '' !== $safe_value) {
                $safe_tokens[$safe_key] = $safe_value;
            }
        }

        return wp_parse_args($safe_tokens, $defaults);
    }

    public static function render_css_variables(): void {
        $tokens = self::get_tokens();

        if (empty($tokens)) {
            return;
        }

        echo '<style id="atora-design-tokens">:root{';
        foreach ($tokens as $key => $value) {
            $safe_key = self::sanitize_token_key((string) $key);
            $safe_value = self::sanitize_token_value((string) $value);
            if (!$safe_key || '' === $safe_value) {
                continue;
            }

            echo '--atora-token-' . esc_attr($safe_key) . ':' . esc_attr($safe_value) . ';';
            echo '--atora-' . esc_attr($safe_key) . ':' . esc_attr($safe_value) . ';';
        }
        echo '}</style>';
    }

    private static function sanitize_token_key(string $key): string {
        $key = preg_replace('/[^a-zA-Z0-9\-_]/', '', $key);
        return strtolower((string) $key);
    }

    private static function sanitize_token_value(string $value): string {
        $value = trim(sanitize_text_field($value));
        if ('' === $value || strlen($value) > 80) {
            return '';
        }

        // Allow safe CSS token values (colors, sizes and basic keywords).
        if (preg_match('/^#[0-9a-fA-F]{3,8}$/', $value)) {
            return $value;
        }
        if (preg_match('/^(rgb|rgba|hsl|hsla)\([0-9\.\,\%\s]+\)$/', $value)) {
            return $value;
        }
        if (preg_match('/^[0-9\.\-]+(px|rem|em|%)$/', $value)) {
            return $value;
        }
        if (preg_match('/^[a-zA-Z0-9\-\s]+$/', $value)) {
            return $value;
        }

        return '';
    }
}
