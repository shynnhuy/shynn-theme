<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Content element
 *
 * @var $type string Show: 'excerpt_only' / 'excerpt_content' / 'part_content' / 'full_content'
 * @var $length int Amount of words
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( get_post_format() == 'link' OR ( is_admin() AND ( ! defined( 'DOING_AJAX' ) OR ! DOING_AJAX ) ) ) {
	return;
}

// Calculate amount of usage the element with full content to avoid infinite recursion
global $us_full_content_stack;
if ( isset( $us_full_content_stack ) AND $us_full_content_stack > 10 AND $type == 'full_content' ) {
	die( '<h5 style="text-align:center; margin-top:20vh; padding:5%;">Post Content outputs itself infinitely. Fix layout of this page.</h5>' );
}

// Find Post Image element with media preview in Page Block
global $us_page_block_ids;
$strip_from_the_content = FALSE;
if ( ! empty( $us_page_block_ids ) ) {
	$page_block = get_post( $us_page_block_ids[0] );

	// Find Post Image element
	if ( preg_match( '~\[us_post_image.+media_preview="1".+?\]~', $page_block->post_content ) ) {
		$strip_from_the_content = TRUE;
	}
}

us_add_to_page_block_ids( get_the_ID() );

if ( $type == 'full_content' ) {
	$us_full_content_stack = ( empty( $us_full_content_stack ) ) ? 1 : $us_full_content_stack + 1;
}

$classes = isset( $classes ) ? $classes : '';
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Prepare inline CSS for shortcode
$inline_css = '';
if ( $us_elm_context == 'shortcode' ) {
	$inline_css .= us_prepare_inline_css(
		array(
			'font-family' => $font,
			'font-weight' => $font_weight,
			'text-transform' => $text_transform,
			'font-style' => $font_style,
			'font-size' => $font_size,
			'line-height' => $line_height,
		)
	);
}

// Default case
$the_content = '';

// Get term description as "Excerpt" for Grid terms
if ( $us_elm_context == 'grid_term' ) {
	global $us_grid_term;
	$the_content = $us_grid_term->description;

	// Get term description as "Excerpt" for archive pages
} elseif ( $us_elm_context == 'shortcode' AND ( is_category() OR is_tag() OR is_tax() ) ) {
	if ( $type == 'excerpt_only' ) { // for "Excerpt only" case
		$the_content = term_description();
	}

	// Post excerpt is not empty
} elseif ( in_array( $type, array( 'excerpt_content', 'excerpt_only' ) ) AND has_excerpt() ) {
	$the_content = apply_filters( 'the_excerpt', get_the_excerpt() );

	// Either the excerpt is empty and we show the content instead or we show the content only
} elseif ( in_array( $type, array( 'excerpt_content', 'part_content', 'full_content' ) ) ) {
	global $us_is_search_page_block;

	if ( get_post_type() == 'attachment' ) {
		$the_content = get_the_content();
	} else {

		// Get WooCommerce Shop Page content
		if ( function_exists( 'is_shop' ) AND is_shop() ) {
			if ( is_search() ) {
				$the_content = '';
			} else {
				$shop_page = get_post( wc_get_page_id( 'shop' ) );
				if ( $shop_page ) {
					$the_content = $shop_page->post_content;
				}
			}

		} elseif ( isset( $us_is_search_page_block ) AND $us_is_search_page_block AND $us_elm_context == 'shortcode' ) {
			$us_page = get_post( us_get_option( 'search_page' ) );
			if ( $us_page ) {
				$us_page = get_post( apply_filters( 'wpml_object_id', $us_page->ID, 'page', TRUE ) );
				$the_content = $us_page->post_content;
			}
			$us_is_search_page_block = FALSE;

		} else {
			$the_content = get_the_content();
		}

		// Force fullwidth for all [vc_row] if set
		if ( $force_fullwidth_rows ) {
			$the_content = str_replace( '[vc_row]', '[vc_row width="full"]', $the_content );
			$the_content = str_replace( '[vc_row ', '[vc_row width="full" ', $the_content );
		}

		// Remove video, audio, gallery from the content for relevant post formats
		us_get_post_preview( $the_content, $strip_from_the_content );

		$the_content = apply_filters( 'the_content', $the_content );

		// Limit the amount of words for the corresponding types
		if ( in_array( $type, array( 'excerpt_content', 'part_content' ) ) AND intval( $length ) > 0 ) {
			$the_content = wp_trim_words( $the_content, intval( $length ) );
		}
	}
}

// Schema.org markup
$schema_markup = '';
if ( us_get_option( 'schema_markup' ) AND $us_elm_context == 'shortcode' ) {
	$schema_markup = ' itemprop="text"';
}

// Output the element
$output = '<div class="w-post-elm post_content' . $classes . '"' . $inline_css . $el_id . $schema_markup . '>';
$output .= $the_content;
$output .= '</div>';

if ( $type == 'full_content' ) {
	$us_full_content_stack --;
}
us_remove_from_page_block_ids();

// Output empty string for "Link" post format OR when no content
// Do not remove to avoid bug https://github.com/upsolution/wp/issues/407
if ( $the_content == '' ) {
	return;
} else {
	echo $output;
}
