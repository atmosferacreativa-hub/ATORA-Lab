<?php
/**
 * Meridian editor integration, patterns and presentation controls.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ATORA_PRESENTATION_META = '_atora_meridian_presentation';

/**
 * Default presentation settings for editable screens.
 *
 * @return array<string,mixed>
 */
function atora_get_presentation_defaults() {
	return array(
		'eyebrow'               => '',
		'intro'                 => '',
		'primary_label'         => '',
		'primary_url'           => '',
		'secondary_label'       => '',
		'secondary_url'         => '',
		'hide_hero'             => false,
		'hide_sidebar'          => false,
		'show_curated_sections' => false,
	);
}

/**
 * Get saved presentation settings for a post.
 *
 * @param int $post_id Post ID.
 * @return array<string,mixed>
 */
function atora_get_presentation_settings( $post_id = 0 ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$saved    = get_post_meta( $post_id, ATORA_PRESENTATION_META, true );
	$defaults = atora_get_presentation_defaults();

	if ( ! is_array( $saved ) ) {
		return $defaults;
	}

	$settings = wp_parse_args( $saved, $defaults );

	$settings['hide_hero']             = ! empty( $settings['hide_hero'] );
	$settings['hide_sidebar']          = ! empty( $settings['hide_sidebar'] );
	$settings['show_curated_sections'] = ! empty( $settings['show_curated_sections'] );

	return $settings;
}

/**
 * Normalize and sanitize presentation settings before persistence.
 *
 * @param array<string,mixed> $raw Raw settings.
 * @return array<string,mixed>
 */
function atora_sanitize_presentation_settings( array $raw ) {
	$defaults = atora_get_presentation_defaults();

	return array(
		'eyebrow'               => isset( $raw['eyebrow'] ) ? sanitize_text_field( (string) $raw['eyebrow'] ) : $defaults['eyebrow'],
		'intro'                 => isset( $raw['intro'] ) ? sanitize_textarea_field( (string) $raw['intro'] ) : $defaults['intro'],
		'primary_label'         => isset( $raw['primary_label'] ) ? sanitize_text_field( (string) $raw['primary_label'] ) : $defaults['primary_label'],
		'primary_url'           => isset( $raw['primary_url'] ) ? esc_url_raw( (string) $raw['primary_url'] ) : $defaults['primary_url'],
		'secondary_label'       => isset( $raw['secondary_label'] ) ? sanitize_text_field( (string) $raw['secondary_label'] ) : $defaults['secondary_label'],
		'secondary_url'         => isset( $raw['secondary_url'] ) ? esc_url_raw( (string) $raw['secondary_url'] ) : $defaults['secondary_url'],
		'hide_hero'             => ! empty( $raw['hide_hero'] ),
		'hide_sidebar'          => ! empty( $raw['hide_sidebar'] ),
		'show_curated_sections' => ! empty( $raw['show_curated_sections'] ),
	);
}

/**
 * Determine whether a settings payload contains real visual overrides.
 *
 * @param array<string,mixed> $settings Presentation settings.
 * @return bool
 */
function atora_presentation_has_visual_overrides( array $settings ) {
	$defaults   = atora_get_presentation_defaults();
	$normalized = wp_parse_args( $settings, $defaults );

	$normalized['hide_hero']             = ! empty( $normalized['hide_hero'] );
	$normalized['hide_sidebar']          = ! empty( $normalized['hide_sidebar'] );
	$normalized['show_curated_sections'] = ! empty( $normalized['show_curated_sections'] );

	return ! empty( array_diff_assoc( $normalized, $defaults ) );
}

/**
 * Detect whether the user explicitly wants Meridian to render a visual shell.
 *
 * @param array<string,mixed> $settings Presentation settings.
 * @return bool
 */
function atora_presentation_requests_visual_shell( array $settings ) {
	return ! empty( $settings['eyebrow'] )
		|| ! empty( $settings['intro'] )
		|| ! empty( $settings['show_curated_sections'] )
		|| ( ! empty( $settings['primary_label'] ) && ! empty( $settings['primary_url'] ) )
		|| ( ! empty( $settings['secondary_label'] ) && ! empty( $settings['secondary_url'] ) );
}

/**
 * Render a normalized CTA model using defaults when custom values are missing.
 *
 * @param array<string,mixed> $settings Presentation settings.
 * @param array<int,array<string,string>> $defaults Default CTA list.
 * @return array<int,array<string,string>>
 */
function atora_get_presentation_actions( array $settings, array $defaults ) {
	$actions = array();

	if ( ! empty( $settings['primary_label'] ) && ! empty( $settings['primary_url'] ) ) {
		$actions[] = array(
			'label' => (string) $settings['primary_label'],
			'url'   => (string) $settings['primary_url'],
			'kind'  => 'primary',
		);
	}

	if ( ! empty( $settings['secondary_label'] ) && ! empty( $settings['secondary_url'] ) ) {
		$actions[] = array(
			'label' => (string) $settings['secondary_label'],
			'url'   => (string) $settings['secondary_url'],
			'kind'  => 'ghost',
		);
	}

	return ! empty( $actions ) ? $actions : $defaults;
}

/**
 * Detect whether a post has meaningful editor content, including media-only layouts.
 *
 * @param int|WP_Post|null $post Post object or ID.
 * @return bool
 */
function atora_post_has_editor_content( $post = null ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	$content = trim( (string) $post->post_content );
	if ( '' === $content ) {
		return false;
	}

	$content_without_comments = preg_replace( '/<!--[\s\S]*?-->/', '', $content );
	$content_without_comments = is_string( $content_without_comments ) ? trim( $content_without_comments ) : '';

	return '' !== $content_without_comments;
}

/**
 * Register editor supports and patterns.
 *
 * @return void
 */
function atora_register_editor_integration() {
	add_post_type_support( 'page', 'excerpt' );

	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Meridian Blue', 'atora-learning' ),
				'slug'  => 'meridian-blue',
				'color' => '#1e5eff',
			),
			array(
				'name'  => __( 'Meridian Sand', 'atora-learning' ),
				'slug'  => 'meridian-sand',
				'color' => '#f6f2eb',
			),
			array(
				'name'  => __( 'Meridian Ink', 'atora-learning' ),
				'slug'  => 'meridian-ink',
				'color' => '#141a27',
			),
			array(
				'name'  => __( 'Meridian Clay', 'atora-learning' ),
				'slug'  => 'meridian-clay',
				'color' => '#ece4d8',
			),
			array(
				'name'  => __( 'White', 'atora-learning' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
		)
	);

	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Small', 'atora-learning' ),
				'size' => 14,
				'slug' => 'small',
			),
			array(
				'name' => __( 'Base', 'atora-learning' ),
				'size' => 18,
				'slug' => 'base',
			),
			array(
				'name' => __( 'Large', 'atora-learning' ),
				'size' => 24,
				'slug' => 'large',
			),
			array(
				'name' => __( 'Display', 'atora-learning' ),
				'size' => 48,
				'slug' => 'display',
			),
		)
	);

	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'atora-meridian',
			array(
				'label' => __( 'Meridian', 'atora-learning' ),
			)
		);
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern(
			'atora-meridian/landing-hero',
			array(
				'title'       => __( 'Meridian Landing Hero', 'atora-learning' ),
				'description' => __( 'Commercial hero with dual CTA for pages and landing screens.', 'atora-learning' ),
				'categories'  => array( 'atora-meridian' ),
				'content'     => sprintf(
					'<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"48px","bottom":"48px","right":"40px","left":"40px"},"blockGap":"24px"},"border":{"radius":"28px"}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-white-background-color has-background" style="border-radius:28px;padding-top:48px;padding-right:40px;padding-bottom:48px;padding-left:40px"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em","fontSize":"12px"}},"textColor":"meridian-blue"} -->
<p class="has-meridian-blue-color has-text-color" style="font-size:12px;letter-spacing:0.08em;text-transform:uppercase">%1$s</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">%2$s</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"base"} -->
<p class="has-base-font-size">%3$s</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"meridian-blue","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-meridian-blue-background-color has-text-color has-background wp-element-button">%4$s</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">%5$s</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
					esc_html__( 'Meridian landing', 'atora-learning' ),
					esc_html__( 'A premium offer deserves a sharper narrative from the first screen.', 'atora-learning' ),
					esc_html__( 'Use this pattern as the opening block of a landing or commercial page and adapt the copy from the editor.', 'atora-learning' ),
					esc_html__( 'Primary CTA', 'atora-learning' ),
					esc_html__( 'Secondary CTA', 'atora-learning' )
				),
			)
		);

		register_block_pattern(
			'atora-meridian/editorial-intro',
			array(
				'title'       => __( 'Meridian Editorial Intro', 'atora-learning' ),
				'description' => __( 'Editorial introduction for blog, podcast and institutional pages.', 'atora-learning' ),
				'categories'  => array( 'atora-meridian' ),
				'content'     => sprintf(
					'<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"40px","bottom":"40px","right":"32px","left":"32px"},"blockGap":"20px"},"border":{"radius":"24px"}},"backgroundColor":"meridian-sand","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-meridian-sand-background-color has-background" style="border-radius:24px;padding-top:40px;padding-right:32px;padding-bottom:40px;padding-left:32px"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em","fontSize":"12px"}},"textColor":"meridian-blue"} -->
<p class="has-meridian-blue-color has-text-color" style="font-size:12px;letter-spacing:0.08em;text-transform:uppercase">%1$s</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size">%2$s</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"base"} -->
<p class="has-base-font-size">%3$s</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
					esc_html__( 'Editorial rhythm', 'atora-learning' ),
					esc_html__( 'Lead with a clear thesis, not a generic title block.', 'atora-learning' ),
					esc_html__( 'This pattern works well for blog indexes, post introductions, podcast pages and branded announcements.', 'atora-learning' )
				),
			)
		);

		register_block_pattern(
			'atora-meridian/cta-band',
			array(
				'title'       => __( 'Meridian CTA Band', 'atora-learning' ),
				'description' => __( 'Light conversion band for closing a page.', 'atora-learning' ),
				'categories'  => array( 'atora-meridian' ),
				'content'     => sprintf(
					'<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"32px","bottom":"32px","right":"32px","left":"32px"},"blockGap":"16px"},"border":{"radius":"24px"}},"backgroundColor":"meridian-blue","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-white-color has-meridian-blue-background-color has-text-color has-background" style="border-radius:24px;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">%1$s</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>%2$s</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"meridian-blue","backgroundColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-meridian-blue-color has-white-background-color has-text-color has-background wp-element-button">%3$s</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
					esc_html__( 'Ready to move this page into action?', 'atora-learning' ),
					esc_html__( 'Swap the copy, point the CTA to your offer, and keep the rest of the storytelling inside the editor.', 'atora-learning' ),
					esc_html__( 'Call to action', 'atora-learning' )
				),
			)
		);
	}
}
add_action( 'after_setup_theme', 'atora_register_editor_integration', 20 );

/**
 * Restrict academic ATORA blocks on standard editorial screens.
 *
 * WordPress exposes `allowed_block_types_all` for narrowing the inserter per
 * editor context; here we keep ATORA LMS blocks available on academic post
 * types while removing them from standard pages and posts.
 *
 * @param bool|string[]          $allowed_blocks Existing allowlist.
 * @param WP_Block_Editor_Context $editor_context Block editor context.
 * @return bool|string[]
 */
function atora_filter_allowed_block_types( $allowed_blocks, $editor_context ) {
	if ( empty( $editor_context->post ) || ! $editor_context->post instanceof WP_Post ) {
		return $allowed_blocks;
	}

	$post_type = $editor_context->post->post_type;
	if ( ! in_array( $post_type, array( 'page', 'post' ), true ) ) {
		return $allowed_blocks;
	}

	$registry = WP_Block_Type_Registry::get_instance()->get_all_registered();
	$current  = true === $allowed_blocks ? array_keys( $registry ) : ( is_array( $allowed_blocks ) ? $allowed_blocks : array() );

	$filtered = array_values(
		array_filter(
			$current,
			static function ( $block_name ) {
				return 0 !== strpos( (string) $block_name, 'atora-lms/' );
			}
		)
	);

	return $filtered;
}
add_filter( 'allowed_block_types_all', 'atora_filter_allowed_block_types', 20, 2 );

/**
 * Remove ATORA LMS blocks from a parsed block tree.
 *
 * @param array<int,array<string,mixed>> $blocks Parsed blocks.
 * @return array<int,array<string,mixed>>
 */
function atora_strip_lms_blocks_from_tree( array $blocks ) {
	$clean_blocks = array();

	foreach ( $blocks as $block ) {
		$block_name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';
		if ( '' !== $block_name && 0 === strpos( $block_name, 'atora-lms/' ) ) {
			continue;
		}

		if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			$block['innerBlocks'] = atora_strip_lms_blocks_from_tree( $block['innerBlocks'] );
		}

		$clean_blocks[] = $block;
	}

	return $clean_blocks;
}

/**
 * Strip ATORA LMS blocks from standard editorial content on save.
 *
 * @param array<string,mixed> $data Sanitized post data.
 * @param array<string,mixed> $postarr Raw submitted post data.
 * @return array<string,mixed>
 */
function atora_strip_lms_blocks_from_editorial_content( array $data, array $postarr ) {
	if ( empty( $data['post_type'] ) || ! in_array( $data['post_type'], array( 'page', 'post' ), true ) ) {
		return $data;
	}

	if ( empty( $data['post_content'] ) || false === strpos( (string) $data['post_content'], '<!-- wp:atora-lms/' ) ) {
		return $data;
	}

	if ( ! function_exists( 'parse_blocks' ) || ! function_exists( 'serialize_blocks' ) ) {
		return $data;
	}

	$parsed_blocks        = parse_blocks( (string) $data['post_content'] );
	$data['post_content'] = serialize_blocks( atora_strip_lms_blocks_from_tree( $parsed_blocks ) );

	return $data;
}
add_filter( 'wp_insert_post_data', 'atora_strip_lms_blocks_from_editorial_content', 20, 2 );

/**
 * Hide the raw custom fields metabox on editor screens.
 *
 * @param mixed $screen Optional screen context from WordPress hooks.
 * @return void
 */
function atora_hide_legacy_custom_fields_box( $screen = null ) {
	foreach ( array( 'page', 'post' ) as $post_type ) {
		if ( post_type_supports( $post_type, 'custom-fields' ) ) {
			remove_post_type_support( $post_type, 'custom-fields' );
		}

		remove_meta_box( 'postcustom', $post_type, 'normal' );
	}
}
add_action( 'admin_init', 'atora_hide_legacy_custom_fields_box' );
add_action( 'add_meta_boxes', 'atora_hide_legacy_custom_fields_box', 100 );
add_action( 'do_meta_boxes', 'atora_hide_legacy_custom_fields_box', 1000 );

/**
 * Hide raw legacy custom fields in the editor if another component re-adds them.
 *
 * @return void
 */
function atora_hide_legacy_custom_fields_styles() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || ! in_array( $screen->post_type, array( 'page', 'post' ), true ) ) {
		return;
	}
	?>
	<style>
		#postcustom,
		#postcustomstuff,
		.editor-post-meta-boxes-area #postcustom {
			display: none !important;
		}
	</style>
	<?php
}
add_action( 'admin_head-post.php', 'atora_hide_legacy_custom_fields_styles' );
add_action( 'admin_head-post-new.php', 'atora_hide_legacy_custom_fields_styles' );

/**
 * Load a dedicated editor stylesheet instead of the frontend bundle.
 *
 * @return void
 */
function atora_register_editor_stylesheet() {
	add_editor_style( 'assets/css/editor-meridian.css' );
}
add_action( 'after_setup_theme', 'atora_register_editor_stylesheet', 25 );

/**
 * Register the Meridian presentation metabox.
 *
 * @return void
 */
function atora_register_presentation_metabox() {
	foreach ( array( 'page', 'post' ) as $post_type ) {
		add_meta_box(
			'atora-meridian-presentation',
			__( 'Meridian Visual', 'atora-learning' ),
			'atora_render_presentation_metabox',
			$post_type,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'atora_register_presentation_metabox' );

/**
 * Render the Meridian presentation metabox.
 *
 * @param WP_Post $post Post object.
 * @return void
 */
function atora_render_presentation_metabox( $post ) {
	$settings = atora_get_presentation_settings( $post->ID );
	wp_nonce_field( 'atora_save_presentation', 'atora_presentation_nonce' );
	?>
	<p>
		<label for="atora-presentation-eyebrow"><strong><?php esc_html_e( 'Eyebrow', 'atora-learning' ); ?></strong></label>
		<input type="text" id="atora-presentation-eyebrow" name="atora_presentation[eyebrow]" value="<?php echo esc_attr( (string) $settings['eyebrow'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-intro"><strong><?php esc_html_e( 'Hero text', 'atora-learning' ); ?></strong></label>
		<textarea id="atora-presentation-intro" name="atora_presentation[intro]" rows="4" class="widefat"><?php echo esc_textarea( (string) $settings['intro'] ); ?></textarea>
	</p>
	<p>
		<label for="atora-presentation-primary-label"><strong><?php esc_html_e( 'Primary button text', 'atora-learning' ); ?></strong></label>
		<input type="text" id="atora-presentation-primary-label" name="atora_presentation[primary_label]" value="<?php echo esc_attr( (string) $settings['primary_label'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-primary-url"><strong><?php esc_html_e( 'Primary button URL', 'atora-learning' ); ?></strong></label>
		<input type="url" id="atora-presentation-primary-url" name="atora_presentation[primary_url]" value="<?php echo esc_attr( (string) $settings['primary_url'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-secondary-label"><strong><?php esc_html_e( 'Secondary button text', 'atora-learning' ); ?></strong></label>
		<input type="text" id="atora-presentation-secondary-label" name="atora_presentation[secondary_label]" value="<?php echo esc_attr( (string) $settings['secondary_label'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-secondary-url"><strong><?php esc_html_e( 'Secondary button URL', 'atora-learning' ); ?></strong></label>
		<input type="url" id="atora-presentation-secondary-url" name="atora_presentation[secondary_url]" value="<?php echo esc_attr( (string) $settings['secondary_url'] ); ?>" class="widefat">
	</p>
	<p>
		<label>
			<input type="checkbox" name="atora_presentation[hide_hero]" value="1" <?php checked( ! empty( $settings['hide_hero'] ) ); ?>>
			<?php esc_html_e( 'Hide hero on this entry', 'atora-learning' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="atora_presentation[hide_sidebar]" value="1" <?php checked( ! empty( $settings['hide_sidebar'] ) ); ?>>
			<?php esc_html_e( 'Use full-width content', 'atora-learning' ); ?>
		</label>
	</p>
	<?php if ( 'page' === $post->post_type ) : ?>
	<p>
		<label>
			<input type="checkbox" name="atora_presentation[show_curated_sections]" value="1" <?php checked( ! empty( $settings['show_curated_sections'] ) ); ?>>
			<?php esc_html_e( 'Keep Meridian curated sections', 'atora-learning' ); ?>
		</label>
	</p>
	<?php endif; ?>
	<p class="description"><?php esc_html_e( 'If the page already has block content, Meridian now respects that layout and does not force a separate hero. Use these fields only when you want to add a light visual frame on top.', 'atora-learning' ); ?></p>
	<?php
}

/**
 * Save presentation settings.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function atora_save_presentation_metabox( $post_id ) {
	if ( ! isset( $_POST['atora_presentation_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atora_presentation_nonce'] ) ), 'atora_save_presentation' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );
	if ( ! in_array( $post_type, array( 'page', 'post' ), true ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw      = isset( $_POST['atora_presentation'] ) && is_array( $_POST['atora_presentation'] ) ? wp_unslash( $_POST['atora_presentation'] ) : array();
	$settings = atora_sanitize_presentation_settings( $raw );

	if ( ! atora_presentation_has_visual_overrides( $settings ) ) {
		delete_post_meta( $post_id, ATORA_PRESENTATION_META );
		return;
	}

	update_post_meta( $post_id, ATORA_PRESENTATION_META, $settings );
}
add_action( 'save_post', 'atora_save_presentation_metabox' );

/**
 * Localize theme page templates dynamically.
 *
 * @param array<string,string> $templates Existing templates.
 * @return array<string,string>
 */
function atora_localize_page_templates( $templates ) {
	if ( isset( $templates['page-templates/landing-meridian.php'] ) ) {
		$templates['page-templates/landing-meridian.php'] = __( 'Meridian Landing', 'atora-learning' );
	}

	if ( isset( $templates['page-student-dashboard.php'] ) ) {
		$templates['page-student-dashboard.php'] = __( 'Student Dashboard', 'atora-learning' );
	}

	return $templates;
}
add_filter( 'theme_page_templates', 'atora_localize_page_templates' );

/**
 * Re-label LMS presets from the theme side so the builder feels Meridian-native.
 *
 * @return void
 */
function atora_register_meridian_ui_presets() {
	if ( ! class_exists( 'CLMS_UI_Template_Presets', false ) ) {
		return;
	}

	$label_map = array(
		'default'                      => array(
			'label'       => __( 'Meridian Base', 'atora-learning' ),
			'description' => __( 'Balanced preset for most academic and commercial experiences.', 'atora-learning' ),
		),
		'minimal'                      => array(
			'label'       => __( 'Meridian Compact', 'atora-learning' ),
			'description' => __( 'Essential hierarchy only: hero, curriculum and conversion point.', 'atora-learning' ),
		),
		'course-commercial-premium'    => array(
			'label'       => __( 'Meridian Showcase', 'atora-learning' ),
			'description' => __( 'Commercial showcase with stronger narrative pacing, credibility and CTA flow.', 'atora-learning' ),
		),
		'landing'                      => array(
			'label'       => __( 'Meridian Conversion', 'atora-learning' ),
			'description' => __( 'Focused conversion preset for course sales pages.', 'atora-learning' ),
		),
		'course-academic-classic'      => array(
			'label'       => __( 'Meridian Academic', 'atora-learning' ),
			'description' => __( 'Formal academic layout with a cleaner premium frame.', 'atora-learning' ),
		),
		'course-bootcamp'              => array(
			'label'       => __( 'Meridian Sprint', 'atora-learning' ),
			'description' => __( 'Dense, accelerated presentation for bootcamps and intensive offers.', 'atora-learning' ),
		),
		'lesson-focus-player'          => array(
			'label'       => __( 'Meridian Lesson Focus', 'atora-learning' ),
			'description' => __( 'Minimal lesson interface centered on the learning asset.', 'atora-learning' ),
		),
		'lesson-academic-sidebar'      => array(
			'label'       => __( 'Meridian Lesson Complete', 'atora-learning' ),
			'description' => __( 'Full academic lesson with resources, evaluation and progression context.', 'atora-learning' ),
		),
		'program-commercial-authority' => array(
			'label'       => __( 'Meridian Authority', 'atora-learning' ),
			'description' => __( 'Program preset for premium offers with strong academic authority.', 'atora-learning' ),
		),
	);

	foreach ( $label_map as $preset_key => $copy ) {
		$preset = CLMS_UI_Template_Presets::get( $preset_key );
		if ( ! $preset ) {
			continue;
		}

		$preset['label']       = $copy['label'];
		$preset['description'] = $copy['description'];

		CLMS_UI_Template_Presets::register( $preset_key, $preset );
	}
}
add_action( 'clms_ui_register_presets', 'atora_register_meridian_ui_presets', 20 );
