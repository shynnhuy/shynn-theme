<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Image element
 *
 * @var $thumbnail_size string Image WordPress size
 * @var $placeholder bool Use placeholder if post has no thumbnail?
 * @var $media_preview bool Show media preview for video and gallery posts?
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link array
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( is_admin() AND ( ! defined( 'DOING_AJAX' ) OR ! DOING_AJAX ) ) {
	return;
}

global $us_grid_img_size, $_wp_additional_image_sizes, $us_post_img_ratio, $us_post_slider_size;

$classes = isset( $classes ) ? $classes : '';
$classes .= ( isset( $circle ) AND $circle ) ? ' as_circle' : '';
$classes .= ( isset( $stretch ) AND $stretch ) ? ' stretched' : '';
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Overwrite thumbnail_size from [us_grid] shortcode if set
if ( ! empty( $us_grid_img_size ) AND $us_grid_img_size != 'default' ) {
	$thumbnail_size = $us_grid_img_size;
}

// Calculate aspect ratio for media preview and for placeholder
if ( isset( $_wp_additional_image_sizes[ $thumbnail_size ] ) AND $_wp_additional_image_sizes[ $thumbnail_size ]['width'] != 0 AND $_wp_additional_image_sizes[ $thumbnail_size ]['height'] != 0 ) {
	$us_post_img_ratio = number_format( $_wp_additional_image_sizes[ $thumbnail_size ]['height'] / $_wp_additional_image_sizes[ $thumbnail_size ]['width'] * 100, 4 );
}

// Link
if ( $link === 'post' ) {
	//	Terms of selected taxonomy in Grid
	if ( $us_elm_context == 'grid_term' ) {
		global $us_grid_term;
		$link_atts = ' href="' . get_term_link( $us_grid_term ) . '"';
	} else {
		$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '"';
	}

} elseif ( $link === 'custom' ) {
	$link_atts = us_generate_link_atts( $custom_link );
} else {
	$link_atts = '';
}

$_post_preview = '';

// Output image of taxonomy term (works for WooCommerce product categories)
if ( $us_elm_context == 'grid_term' ) {
	global $us_grid_term;
	$term_thumbnail_id = get_term_meta( $us_grid_term->term_id, 'thumbnail_id', TRUE );
	if ( $term_thumbnail_id ) {
		$_post_preview = wp_get_attachment_image( $term_thumbnail_id, $thumbnail_size );
	}
}

// Generate media preview
if ( $_post_preview == '' AND $media_preview AND ! post_password_required() ) {
	if ( get_post_type() == 'product' ) { // for products with gallery
		$postID = get_the_ID();
		$product_images = get_post_meta( $postID, '_product_image_gallery', TRUE );
		if ( $product_images ) {
			$slider_shortcode = '[us_image_slider ids="' . get_post_thumbnail_id() . ',' . $product_images . '" img_size="' . $thumbnail_size . '" arrows="hover"]';
			$_post_preview = do_shortcode( $slider_shortcode );
		}
	} else { // for posts with Video, Audio, Gallery formats
		$us_post_slider_size = $thumbnail_size;
		$the_content = get_the_content();
		$_post_preview = us_get_post_preview( $the_content );
	}

	if ( $_post_preview != '' ) {
		$classes .= ' media_preview'; // add CSS class for media preview
		$link_atts = ''; // remove link for media preview
	}
}

// Output image of attachment post type
if ( $_post_preview == '' AND get_post_type() == 'attachment' ) {
	$_post_preview = wp_get_attachment_image( get_the_ID(), $thumbnail_size );
}

// Output Featured image
if ( $_post_preview == '' AND has_post_thumbnail() ) {
	$_post_preview = get_the_post_thumbnail( get_the_ID(), $thumbnail_size );
}

// Output the first image from the content of Gallery format
if ( $_post_preview == '' AND get_post_format() == 'gallery' ) {
	$the_content = get_the_content();
	if ( preg_match( '~\[us_gallery.+?\]|\[us_image_slider.+?\]|\[gallery.+?\]~', $the_content, $matches ) ) {
		$gallery = preg_replace( '~(vc_gallery|us_gallery|gallery)~', 'us_image_slider', $matches[0] );
		preg_match( '~\[us_image_slider(.+?)\]~', $gallery, $matches2 );
		$shortcode_atts = shortcode_parse_atts( $matches2[1] );
		if ( ! empty( $shortcode_atts['ids'] ) ) {
			$ids = explode( ',', $shortcode_atts['ids'] );
			if ( count( $ids ) > 0 ) {
				$_post_preview = wp_get_attachment_image( $ids[0], $thumbnail_size );
			}
		}
	}
}

// Output placeholder if enabled
if ( $_post_preview == '' AND $placeholder ) {
	$classes .= ' with_placeholder';

	// Use WooCommerce placeholder if enabled
	if ( ( $us_elm_context == 'grid_term' OR get_post_type() == 'product' ) AND function_exists( 'wc_placeholder_img_src' ) ) {
		$_post_preview = '<img src="' . wc_placeholder_img_src( $thumbnail_size ) . '" alt=""/>';
	} else {
		$_post_preview = '<div class="w-post-elm-placeholder" style="padding-bottom:' . ( empty( $us_post_img_ratio ) ? 100 : $us_post_img_ratio ) . '%;"></div>';
	}
}

// Don't output the element without any content
if ( $_post_preview == '' ) {
	return;
}

$output = '<div class="w-post-elm post_image' . $classes . '"' . $el_id . '>';
if ( ! empty( $link_atts ) ) {
	$output .= '<a' . $link_atts . ' aria-label="' . esc_attr( get_the_title() ) . '">';
}

$output .= $_post_preview;

if ( ! empty( $link_atts ) ) {
	$output .= '</a>';
}
$output .= '</div>';

echo $output;
