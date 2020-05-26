<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Custom Field element
 *
 * @var $key string custom field key
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link array
 * @var $type string 'text' / 'image'
 * @var $thumbnail_size string Image WordPress size
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$postID = get_the_ID();
if ( ! $postID ) {
	return;
}

$classes = isset( $classes ) ? $classes : '';
if ( $color_link ) {
	$classes .= ' color_link_inherit';
}
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

$tag = 'div';
$type = 'text';

// Force "image" type for relevant meta keys
if ( $key == 'us_tile_additional_image' ) {
	$type = 'image';
}

// Force "image" type for ACF images fields
if ( function_exists( 'acf_get_fields' ) ) {
	$fields = acf_get_fields( '' ); // empty string to get all fields
	foreach ( $fields as $field ) {
		if ( $field['name'] == $key AND $field['type'] == 'image' ) {
			$type = 'image';
		}
	}
}

$classes .= ' type_' . $type;

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
		), TRUE, $tag
	);
}

// Retrieve meta key value
if ( $key != 'custom' ) {
	$value = get_post_meta( $postID, $key, TRUE );
} elseif ( ! empty( $custom_key ) ) {
	$value = get_post_meta( $postID, $custom_key, TRUE );
} else {
	$value = '';
}

// Don't output the element, when its value is empty
if ( ( $hide_empty AND $value == '' ) OR is_object( $value ) ) {
	return;
}

// Generate image semantics
if ( $type == 'image' ) {
	$value = intval( $value );

	if ( $value ) {
		global $us_grid_img_size;
		if ( ! empty( $us_grid_img_size ) AND $us_grid_img_size != 'default' ) {
			$thumbnail_size = $us_grid_img_size;
		}

		$image = wp_get_attachment_image_src( $value, $thumbnail_size );

		if ( is_array( $image ) ) {
			$value = us_get_attachment_image( $value, $thumbnail_size );
		} else {
			return;
		}
	} else {
		return;
	}

} elseif ( is_array( $value ) ) {
	$value = implode( ' ', $value );
} else {
	$value = wpautop( $value ); // add <p> and <br> if custom field has WYSIWYG
}

// Link
if ( $link === 'post' ) {
	$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '"';
} elseif ( $link === 'custom' ) {
	$link_atts = us_generate_link_atts( $custom_link );
} else {
	$link_atts = '';
}

// Generate semantics for Testimonial Rating
if ( $key == 'us_testimonial_rating' ) {
	$rating_value = (int) strip_tags( $value );

	if ( $rating_value == 0 ) {
		return;
	} else {
		$value = '<div class="w-testimonial-rating">';
		for ( $i = 1; $i <= $rating_value; $i ++ ) {
			$value .= '<i></i>';
		}
		$value .= '</div>';
	}
}

$text_before = ( trim( $text_before ) != '' ) ? '<span class="w-post-elm-before">' . trim( $text_before ) . ' </span>' : '';

// Output the element
$output = '<' . $tag . ' class="w-post-elm post_custom_field' . $classes . '"' . $inline_css . $el_id . '>';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
$output .= $text_before;

if ( ! empty( $link_atts ) ) {
	$output .= '<a' . $link_atts . '>';
}
$output .= $value;
if ( ! empty( $link_atts ) ) {
	$output .= '</a>';
}
$output .= '</' . $tag . '>';

echo $output;
