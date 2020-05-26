<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Outputs page's content Page Block (us_page_block)
 *
 * (!) Should be called after the current $wp_query is already defined
 *
 * @action Before the template: 'us_before_template:templates/content'
 * @action After the template: 'us_after_template:templates/content'
 * @filter Template variables: 'us_template_vars:templates/content'
 */

$page_block_content = '';
$page_block_id = us_get_page_area_id( 'content' );

if ( $page_block_id != '' ) {
	$page_block = get_post( (int) $page_block_id );

	us_open_wp_query_context();
	if ( $page_block ) {

		// Some WPML tweaks
		$translated_page_block_id = apply_filters( 'wpml_object_id', $page_block->ID, 'us_page_block', TRUE );
		if ( $translated_page_block_id != $page_block->ID ) {
			$page_block = get_post( $translated_page_block_id );
		}
		us_add_to_page_block_ids( $translated_page_block_id );
		us_add_page_shortcodes_custom_css( $translated_page_block_id );

		$page_block_content = $page_block->post_content;
	}
	us_close_wp_query_context();

	// Apply filters to Page Block content and echoing it ouside of us_open_wp_query_context,
	// so all WP widgets (like WP Nav Menu) would work as they should
	$page_block_content = apply_filters( 'us_page_block_the_content', $page_block_content );
	
	// If content has no sections, we'll create them manually
	if ( strpos( $page_block_content, ' class="l-section' ) === FALSE ) {
		$page_block_content = '<section class="l-section"><div class="l-section-h">' . $page_block_content . '</div></section>';
	}

	echo $page_block_content;

	if ( $page_block ) {
		us_remove_from_page_block_ids();
	}

}
