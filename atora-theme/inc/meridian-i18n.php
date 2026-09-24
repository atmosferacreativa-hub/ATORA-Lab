<?php
/**
 * Meridian i18n fallbacks and locale helpers.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve the active Meridian locale bucket.
 *
 * @return string
 */
function atora_get_meridian_locale() {
	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();

	if ( 0 === strpos( $locale, 'es' ) ) {
		return 'es';
	}

	return $locale;
}

/**
 * Load translation catalog for the active locale.
 *
 * @return array<string,mixed>
 */
function atora_get_meridian_translation_catalog() {
	static $catalogs = array();

	$locale = atora_get_meridian_locale();

	if ( isset( $catalogs[ $locale ] ) ) {
		return $catalogs[ $locale ];
	}

	$catalog = array(
		'strings'        => array(),
		'context'        => array(),
		'plural'         => array(),
		'plural_context' => array(),
	);

	$file = ATORA_THEME_DIR . '/languages/' . $locale . '.php';
	if ( file_exists( $file ) ) {
		$data = require $file;
		if ( is_array( $data ) ) {
			$catalog = array_merge( $catalog, $data );
		}
	}

	$catalogs[ $locale ] = $catalog;

	return $catalog;
}

/**
 * Translate plain strings for the theme as a locale-aware fallback.
 *
 * @param string $translation Existing translation.
 * @param string $text        Source string.
 * @param string $domain      Text domain.
 * @return string
 */
function atora_theme_gettext_fallback( $translation, $text, $domain ) {
	if ( 'atora-learning' !== $domain || $translation !== $text ) {
		return $translation;
	}

	$catalog = atora_get_meridian_translation_catalog();

	return isset( $catalog['strings'][ $text ] ) ? (string) $catalog['strings'][ $text ] : $translation;
}
add_filter( 'gettext', 'atora_theme_gettext_fallback', 20, 3 );

/**
 * Translate contextual strings for the theme as a locale-aware fallback.
 *
 * @param string $translation Existing translation.
 * @param string $text        Source string.
 * @param string $context     Translation context.
 * @param string $domain      Text domain.
 * @return string
 */
function atora_theme_gettext_with_context_fallback( $translation, $text, $context, $domain ) {
	if ( 'atora-learning' !== $domain || $translation !== $text ) {
		return $translation;
	}

	$catalog = atora_get_meridian_translation_catalog();

	return isset( $catalog['context'][ $context ][ $text ] ) ? (string) $catalog['context'][ $context ][ $text ] : $translation;
}
add_filter( 'gettext_with_context', 'atora_theme_gettext_with_context_fallback', 20, 4 );

/**
 * Translate plural strings for the theme as a locale-aware fallback.
 *
 * @param string $translation Existing translation.
 * @param string $single      Singular source string.
 * @param string $plural      Plural source string.
 * @param int    $number      Count.
 * @param string $domain      Text domain.
 * @return string
 */
function atora_theme_ngettext_fallback( $translation, $single, $plural, $number, $domain ) {
	if ( 'atora-learning' !== $domain || ( $translation !== $single && $translation !== $plural ) ) {
		return $translation;
	}

	$catalog = atora_get_meridian_translation_catalog();

	if ( isset( $catalog['plural'][ $single ] ) && is_array( $catalog['plural'][ $single ] ) ) {
		$forms = $catalog['plural'][ $single ];
		return ( 1 === (int) $number )
			? (string) ( $forms[0] ?? $translation )
			: (string) ( $forms[1] ?? $translation );
	}

	return $translation;
}
add_filter( 'ngettext', 'atora_theme_ngettext_fallback', 20, 5 );

/**
 * Translate contextual plural strings for the theme as a locale-aware fallback.
 *
 * @param string $translation Existing translation.
 * @param string $single      Singular source string.
 * @param string $plural      Plural source string.
 * @param int    $number      Count.
 * @param string $context     Translation context.
 * @param string $domain      Text domain.
 * @return string
 */
function atora_theme_ngettext_with_context_fallback( $translation, $single, $plural, $number, $context, $domain ) {
	if ( 'atora-learning' !== $domain || ( $translation !== $single && $translation !== $plural ) ) {
		return $translation;
	}

	$catalog = atora_get_meridian_translation_catalog();

	if ( isset( $catalog['plural_context'][ $context ][ $single ] ) && is_array( $catalog['plural_context'][ $context ][ $single ] ) ) {
		$forms = $catalog['plural_context'][ $context ][ $single ];
		return ( 1 === (int) $number )
			? (string) ( $forms[0] ?? $translation )
			: (string) ( $forms[1] ?? $translation );
	}

	return $translation;
}
add_filter( 'ngettext_with_context', 'atora_theme_ngettext_with_context_fallback', 20, 6 );
