<?php
/**
 * category archive: same layout as the blog.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'template-parts/blog', 'ecosystem' );
get_footer();
