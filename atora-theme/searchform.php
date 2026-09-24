<?php
/**
 * Search form.
 *
 * @package Atora_Learning
 */

$search_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'search-input-' ) : 'search-input';
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-form-row">
		<label class="screen-reader-text" for="<?php echo esc_attr( $search_id ); ?>">
			<?php esc_html_e( 'Search for:', 'atora-learning' ); ?>
		</label>
		<input
			type="search"
			id="<?php echo esc_attr( $search_id ); ?>"
			class="search-field"
			name="s"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			placeholder="<?php esc_attr_e( 'Search courses, products, posts or podcast episodes...', 'atora-learning' ); ?>"
		>
		<button type="submit" class="search-submit">
			<?php esc_html_e( 'Search', 'atora-learning' ); ?>
		</button>
	</div>
</form>
