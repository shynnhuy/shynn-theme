<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Taxonomy element
 *
 * @var $taxonomy_name string Taxonomy name
 * @var $link string Link type: 'post' / 'archive' / 'custom' / 'none'
 * @var $custom_link array
 * @var $color string Custom color
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( empty( $taxonomy_name ) OR ! taxonomy_exists( $taxonomy_name ) OR ! is_object_in_taxonomy( get_post_type(), $taxonomy_name ) ) {
	return FALSE;
}
$terms = get_the_terms( get_the_ID(), $taxonomy_name );
if ( ! is_array( $terms ) OR count( $terms ) == 0 ) {
	return FALSE;
}

$classes = isset( $classes ) ? $classes : '';
$classes .= ' style_' . $style;
if ( $color_link ) {
	$classes .= ' color_link_inherit';
}
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

// Link
if ( $link === 'post' ) {
	$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '"';
} elseif ( $link === 'custom' ) {
	$link_atts = us_generate_link_atts( $custom_link );
} else {
	$link_atts = '';
}

$text_before = ( trim( $text_before ) != '' ) ? '<span class="w-post-elm-before">' . trim( $text_before ) . ' </span>' : '';

// Set Button Style class
$btn_classes = '';
if ( $style == 'badge' ) {
	$btn_classes = ' class="w-btn us-btn-style_' . $btn_style . '"';
}

// Output the element
$output = '<div class="w-post-elm post_taxonomy' . $classes . '"' . $inline_css . $el_id . '>';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
$output .= $text_before;
if ( $style == 'badge' AND count( $terms ) > 1 ) {
	$output .= '<div class="w-post-elm-list">';
}

$i = 1;
foreach ( $terms as $term ) {
	if ( $link === 'archive' ) {
		$link_atts = ' href="' . get_term_link( $term ) . '"';
		// Output "rel" attribute for Posts tags
		if ( $taxonomy_name == 'post_tag' ) {
			$link_atts .= ' rel="tag"';
		}
	}
	if ( ! empty( $link_atts ) ) {
		$output .= '<a' . $btn_classes . $link_atts . '>' . $term->name . '</a>';
	} else {
		$output .= '<span' . $btn_classes . '>' . $term->name . '</span>';
	}
	// Output comma after anchor except the last one
	if ( $style != 'badge' AND $i != count( $terms ) ) {
		$output .= $separator;
	}
	$i++;
}

if ( $style == 'badge' AND count( $terms ) > 1 ) {
	$output .= '</div>';
}
$output .= '</div>';

echo $output;
