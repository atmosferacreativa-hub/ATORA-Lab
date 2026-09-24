<?php
/**
 * Accessibility Enhancements (WCAG 2.1)
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) exit;

/**
 * Improve WooCommerce form field accessibility (label association)
 */
add_filter('woocommerce_form_field_args', function($args, $key, $value) {
    if (!isset($args['label'])) {
        return $args;
    }

    if (!isset($args['id'])) {
        $args['id'] = 'field_' . sanitize_key($key);
    }

    if (!isset($args['label_class'])) {
        $args['label_class'] = [];
    }
    if (!is_array($args['label_class'])) {
        $args['label_class'] = explode(' ', $args['label_class']);
    }

    $args['label_class'][] = 'form-label';

    return $args;
}, 10, 3);

/**
 * Focus visibility and contrast styles
 */
add_action('wp_head', function() {
    ?>
    <style id="atora-a11y-head">
        a:focus,
        button:focus,
        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        body {
            color: var(--color-gray-900);
            background: white;
        }

        a {
            color: var(--color-primary);
            text-decoration: underline;
        }

        a:visited {
            color: #7c3aed;
        }

        button, .btn {
            font-size: inherit;
            line-height: 1.5;
        }

        .btn, button, a.wp-block-button__link,
        input[type="submit"], input[type="button"] {
            min-height: 44px;
            min-width: 44px;
            padding: 10px 16px;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        html {
            font-size: 16px;
        }

        p, li, dd {
            line-height: 1.6;
        }
    </style>
    <?php
});

/**
 * Add ARIA landmarks to navigation menus
 */
add_filter('wp_nav_menu_args', function($args) {
    if (!isset($args['role'])) {
        $args['role'] = 'navigation';
    }
    if (!isset($args['aria_label'])) {
        $args['aria_label'] = __('Main Navigation', 'atora-learning');
    }
    return $args;
});

/**
 * Add aria-label to the search input
 */
add_filter('get_search_form', function($form) {
    $form = str_replace('type="search"', 'type="search" aria-label="' . esc_attr__('Search', 'atora-learning') . '"', $form);
    return $form;
});

/**
 * Add ARIA attributes to course progress bar HTML
 * Expected filter usage: apply_filters('atora_course_progress_html', $html, $progress)
 */
add_filter('atora_course_progress_html', function($html, $progress = 0) {
    $progress = intval($progress);
    $html = str_replace(
        'class="progress-bar"',
        'class="progress-bar" role="progressbar" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" aria-label="' . esc_attr(sprintf(__('Course progress: %d%%', 'atora-learning'), $progress)) . '"',
        $html
    );
    return $html;
}, 10, 2);

/**
 * Add title attribute to video iframes that lack one
 */
add_filter('embed_oembed_html', function($html, $url) {
    if (strpos($html, '<iframe') !== false && strpos($html, 'title=') === false) {
        $html = str_replace('<iframe', '<iframe title="' . esc_attr__('Video player', 'atora-learning') . '"', $html);
    }
    return $html;
}, 10, 2);

/**
 * Additional contrast and interactive element styles (footer to allow overrides)
 */
add_action('wp_footer', function() {
    ?>
    <style id="atora-a11y-footer">
        .btn:hover, button:hover {
            background: var(--color-primary-dark);
        }

        a:not(.btn):not(.wp-block-button__link) {
            text-decoration: underline;
            text-decoration-thickness: 2px;
            text-underline-offset: 4px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 12px;
            border: 1px solid var(--color-gray-300);
        }

        th {
            background: var(--color-gray-100);
            font-weight: bold;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--color-gray-900);
        }

        .required::after {
            content: " *";
            color: var(--color-error);
            font-weight: bold;
        }
    </style>
    <?php
}, 9999);
