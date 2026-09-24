<?php
/**
 * Gutenberg Editor Styles
 * 
 * Adds editor styles to match frontend design
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) exit;

/**
 * Add editor styles support
 */
function atora_add_editor_styles() {
    add_theme_support('editor-styles');
    
    // Editor CSS
    add_editor_style('assets/css/editor-styles.css');
    
    // Font sizes
    add_theme_support('editor-font-sizes', [
        [
            'name' => __('Small', 'atora-learning'),
            'slug' => 'small',
            'size' => 14,
        ],
        [
            'name' => __('Normal', 'atora-learning'),
            'slug' => 'normal',
            'size' => 16,
        ],
        [
            'name' => __('Large', 'atora-learning'),
            'slug' => 'large',
            'size' => 20,
        ],
        [
            'name' => __('XLarge', 'atora-learning'),
            'slug' => 'xlarge',
            'size' => 28,
        ],
    ]);

    // Colors
    add_theme_support('editor-color-palette', [
        [
            'name' => __('Primary', 'atora-learning'),
            'slug' => 'primary',
            'color' => '#6366f1',
        ],
        [
            'name' => __('Secondary', 'atora-learning'),
            'slug' => 'secondary',
            'color' => '#ec4899',
        ],
        [
            'name' => __('Success', 'atora-learning'),
            'slug' => 'success',
            'color' => '#10b981',
        ],
        [
            'name' => __('Warning', 'atora-learning'),
            'slug' => 'warning',
            'color' => '#f59e0b',
        ],
        [
            'name' => __('Error', 'atora-learning'),
            'slug' => 'error',
            'color' => '#ef4444',
        ],
        [
            'name' => __('Gray 900', 'atora-learning'),
            'slug' => 'gray-900',
            'color' => '#111827',
        ],
        [
            'name' => __('Gray 600', 'atora-learning'),
            'slug' => 'gray-600',
            'color' => '#4b5563',
        ],
        [
            'name' => __('White', 'atora-learning'),
            'slug' => 'white',
            'color' => '#ffffff',
        ],
    ]);

    // Gradient presets
    add_theme_support('editor-gradient-presets', [
        [
            'name' => __('Primary to Secondary', 'atora-learning'),
            'gradient' => 'linear-gradient(90deg, #6366f1 0%, #ec4899 100%)',
            'slug' => 'primary-to-secondary',
        ],
        [
            'name' => __('Success to Primary', 'atora-learning'),
            'gradient' => 'linear-gradient(135deg, #10b981 0%, #6366f1 100%)',
            'slug' => 'success-to-primary',
        ],
    ]);

    // Spacing presets
    add_theme_support('custom-spacing');
    
    // Line height
    add_theme_support('custom-line-height');
}
add_action('after_setup_theme', 'atora_add_editor_styles');

/**
 * Enqueue editor styles with custom properties
 */
function atora_enqueue_editor_styles() {
    // Main editor styles
    wp_enqueue_style('atora-editor', 
        ATORA_THEME_URI . '/assets/css/editor-styles.css', 
        [], 
        ATORA_THEME_VERSION
    );

    // Localize editor script with colors
    wp_enqueue_script('atora-editor-config', 
        ATORA_THEME_URI . '/assets/js/editor-config.js', 
        ['wp-blocks', 'wp-dom'], 
        ATORA_THEME_VERSION, 
        true
    );

    wp_localize_script('atora-editor-config', 'atoraEditorConfig', [
        'primaryColor' => '#6366f1',
        'secondaryColor' => '#ec4899',
        'successColor' => '#10b981',
        'warningColor' => '#f59e0b',
        'errorColor' => '#ef4444',
    ]);
}
add_action('enqueue_block_editor_assets', 'atora_enqueue_editor_styles');

/**
 * Restrict block types available in editor
 */
add_filter('allowed_block_types_all', function($allowed_blocks, $editor_context) {
    // Allow all blocks on core + LMS content types for maximum editing flexibility.
    if (isset($editor_context->post)) {
        $post_type = get_post_type($editor_context->post);
        if (in_array($post_type, ['post', 'page', 'lm_course', 'lm_lesson', 'lm_program', 'atora_teacher'], true)) {
            return true;
        }
    }

    // Curated block set for any other editor context.
    $recommended = [
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/quote',
        'core/pullquote',
        'core/code',
        'core/preformatted',
        'core/table',
        'core/separator',
        'core/spacer',

        'core/image',
        'core/gallery',
        'core/cover',
        'core/media-text',
        'core/video',
        'core/audio',
        'core/file',
        'core/embed',

        'core/buttons',
        'core/button',
        'core/columns',
        'core/column',
        'core/group',
        'core/stack',
        'core/row',

        'core/query',
        'core/post-template',
        'core/latest-posts',
        'core/latest-comments',
        'core/post-title',
        'core/post-excerpt',
        'core/post-content',
        'core/post-featured-image',
        'core/post-date',
        'core/post-author',
        'core/post-terms',

        'core/shortcode',
        'core/more',
        'core/nextpage',
        'core/read-more',
        'core/social-links',
        'core/navigation',
        'core/site-title',
        'core/site-logo',
        'core/site-tagline',
        'core/template-part',
        'core/search',

        'core/calendar',
        'core/categories',
        'core/tag-cloud',
        'core/rss',
        'core/archives',
        'core/page-list',
        'core/loginout',

        'core/html',
    ];

    // Keep all if $allowed_blocks is true (not restricted)
    if ($allowed_blocks === true) {
        return $recommended;
    }

    // Otherwise, use intersection
    return array_intersect($allowed_blocks, $recommended);
}, 10, 2);

/**
 * Add block category for Atora blocks
 */
add_filter('block_categories_all', function($categories, $editor_context) {
    return array_merge(
        [
            [
                'slug' => 'atora',
                'title' => __('Atora LMS', 'atora-learning'),
                'icon' => 'book',
            ],
        ],
        $categories
    );
}, 10, 2);
