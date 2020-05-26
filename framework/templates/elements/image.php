<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Image element
 */

$img_html = $img_shadow = $inline_css = '';

$classes = isset( $classes ) ? $classes : '';

$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';
$css = ! empty( $css ) ? $css : '';

if ( class_exists( 'SitePress' ) ) {
	$image = apply_filters( 'wpml_object_id', $image );
}

// Classes & inline styles
if ( $us_elm_context == 'shortcode' ) {

	$img = $image;

	$classes .= ' align_' . $align;
	$classes .= ( ! empty( $style ) ) ? ' style_' . $style : '';
	$classes .= ( $meta ) ? ' meta_' . $meta_style : '';
	if ( ! empty( $animate ) ) {
		$classes .= ' animate_' . $animate;
		if ( ! empty( $animate_delay ) ) {
			$inline_css = us_prepare_inline_css(
				array(
					'animation-delay' => floatval( $animate_delay ) . 's',
				)
			);
		}
	}
	if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$classes .= ' ' . vc_shortcode_custom_css_class( $css );
	}
	$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
}

// Get the image
$img_arr = explode( '|', $img );
$img_html .= us_get_attachment_image( $img_arr[0], $size );

// Get the image for transparent header if set
if ( ! empty( $img_transparent ) ) {
	$classes .= ' with_transparent';
	$img_arr = explode( '|', $img_transparent );
	$img_html .= us_get_attachment_image( $img_arr[0], $size );
}

if ( $us_elm_context == 'shortcode' AND $img_html ) {
	if ( $meta ) {
		$attachment = get_post( $img );

		// Use the Caption as a Title
		$title = trim( strip_tags( $attachment->post_excerpt ) );
		if ( empty( $title ) ) {
			// If not, Use the Alt
			$title = trim( strip_tags( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', TRUE ) ) );
		}
		if ( empty( $title ) ) {
			// If no Alt, use the Title
			$title = trim( strip_tags( $attachment->post_title ) );
		}

		$img_html .= '<div class="w-image-meta">';
		$img_html .= ( ! empty( $title ) ) ? '<div class="w-image-title">' . $title . '</div>' : '';
		$img_html .= ( ! empty( $attachment->post_content ) ) ? '<div class="w-image-description">' . $attachment->post_content . '</div>' : '';
		$img_html .= '</div>';
	}

	// Get url to the image to immitate shadow
	$img_src = wp_get_attachment_image_url( $img, $size );
	if ( $style == 'shadow-2' ) {
		$img_shadow = '<div class="w-image-shadow" style="background-image:url(' . $img_src . ');"></div>';
	}
}

// Link
if ( $onclick == 'lightbox' ) {
	$link_atts = ' href="' . wp_get_attachment_image_url( $img, 'full' ) . '" ref="magnificPopup"';
} else {
	$link_atts = us_generate_link_atts( $link );
}
if ( ! empty( $link_atts ) ) {
	$tag = 'a';
} else {
	$tag = 'div';
}

if ( empty( $img_html ) ) {
	// Check if image ID is URL
	if ( strpos( $img, 'http' ) !== FALSE ) {
		$img_html = '<img src="' . esc_attr( $img ) . '" alt="image" />';
	} else {
		$classes .= ' no_image';
		$img_html = '<div class="g-placeholder"></div>';
	}
}

// Output the element
$output = '<div class="w-image' . $classes . '"' . $el_id . $inline_css . '>';
$output .= '<' . $tag . ' class="w-image-h"' . $link_atts . '>';
$output .= $img_shadow;
$output .= $img_html;
$output .= '</' . $tag . '>';
$output .= '</div>';

echo $output;
