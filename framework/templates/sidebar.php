<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Outputs Sidebar HTML
 *
 * @filter Template variables: 'us_template_vars:templates/content'
 */

$sidebar_id = us_get_page_area_id( 'sidebar' );
if ( ! isset( $place ) OR $sidebar_id == '' ) {
	return;
}

// Get Sidebar position for the current page (based on "us_get_page_area_id" function)
$public_cpt = array_keys( us_get_public_cpt() );
$position = us_get_option( 'sidebar_pos', 'right' );
if ( is_singular( array( 'us_portfolio' ) ) ) {
	$position = us_get_option( 'sidebar_portfolio_pos' );

} elseif ( is_singular( array( 'post', 'attachment' ) ) ) {
	$position = us_get_option( 'sidebar_post_pos' );

} elseif ( function_exists( 'is_product' ) AND is_product() ) {
	$position = us_get_option( 'sidebar_product_pos' );

} elseif ( is_post_type_archive( 'product' ) AND is_search() ) {
	$position = us_get_option( 'sidebar_shop_pos' );

} elseif ( function_exists( 'is_shop' ) AND is_shop() ) {
	$position = us_get_option( 'sidebar_shop_pos' );

} elseif ( function_exists( 'is_product_category' ) AND is_product_category() ) {
	if ( us_get_option( 'sidebar_tax_product_cat_id' ) == '__defaults__' ) {
		$position = us_get_option( 'sidebar_shop_pos' );
	} else {
		$position = us_get_option( 'sidebar_tax_product_cat_pos' );
	}

} elseif ( function_exists( 'is_product_tag' ) AND is_product_tag() ) {
	if ( us_get_option( 'sidebar_tax_product_tag_id' ) == '__defaults__' ) {
		$position = us_get_option( 'sidebar_shop_pos' );
	} else {
		$position = us_get_option( 'sidebar_tax_product_tag_pos' );
	}

} elseif ( is_author() ) {
	if ( us_get_option( 'sidebar_author_id' ) != '__defaults__' ) {
		$position = us_get_option( 'sidebar_author_pos' );
	}

} elseif ( is_archive() OR is_search() ) {
	$public_taxonomies = array_keys( us_get_taxonomies( TRUE ) );

	$position = us_get_option( 'sidebar_archive_pos' );

	$current_tax = NULL;
	if ( is_category() ) {
		$current_tax = 'category';
	} elseif ( is_tag() ) {
		$current_tax = 'post_tag';
	} elseif ( is_tax() ) {
		$current_tax = get_query_var( 'taxonomy' );
	}
	if ( ! empty( $current_tax ) AND in_array( $current_tax, $public_taxonomies ) ) {
		if ( us_get_option( 'sidebar_tax_' . $current_tax . '_id' ) != '__defaults__' ) {
			$position = us_get_option( 'sidebar_tax_' . $current_tax . '_pos' );
		}
	}

} elseif ( ! empty( $public_cpt ) AND is_singular( $public_cpt ) ) {
	if ( is_singular( array( 'tribe_events' ) ) ) {
		$post_type = 'tribe_events'; // Events Calendar fix
	} else {
		$post_type = get_post_type();
	}
	$position = us_get_option( 'sidebar_' . $post_type . '_pos' );
}
if ( is_search() AND ! is_post_type_archive( 'product' ) AND $postID = us_get_option( 'search_page' ) AND $postID != 'default' ) {
	$position = usof_meta( 'us_sidebar_pos', array(), $postID );
}
if ( is_home() AND $postID = us_get_option( 'posts_page' ) AND $postID != 'default' ) {
	$position = usof_meta( 'us_sidebar_pos', array(), $postID );
}
if ( is_404() AND $postID = us_get_option( 'page_404' ) AND $postID != 'default' ) {
	$position = usof_meta( 'us_sidebar_pos', array(), $postID );
}
if ( is_singular() ) {
	$postID = get_the_ID();
	if ( $postID AND usof_meta( 'us_sidebar_id', array(), $postID ) != '__defaults__' ) {
		$position = usof_meta( 'us_sidebar_pos', array(), $postID );
	}
}

// Generate column for Content area
$content_column_start = '<div class="vc_col-sm-9 vc_column_container l-content">'; // TODO: make width be changeable
$content_column_start .= '<div class="vc_column-inner"><div class="wpb_wrapper">';

// Generate column for Sidebar
$sidebar_column_start = '<div class="vc_col-sm-3 vc_column_container l-sidebar">'; // TODO: make width be changeable
$sidebar_column_start .= '<div class="vc_column-inner"><div class="wpb_wrapper">';

// Outputs HTML regarding place value
if ( $place == 'before' ) {

	echo '<section class="l-section height_auto for_sidebar at_' . $position . '"><div class="l-section-h">';
	echo '<div class="g-cols type_default valign_top">';

	// Content column
	echo $content_column_start;

} elseif ( $place == 'after' ) {

	echo '</div></div></div>';

	// Sidebar column
	echo $sidebar_column_start;

	dynamic_sidebar( $sidebar_id );

	echo '</div></div></div>';
	echo '</div></div></section>';
}
