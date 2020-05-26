<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Title element
 *
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link array
 * @var $tag string 'h1' / 'h2' / 'h3' / 'h4' / 'h5' / 'h6' / 'p' / 'div'
 * @var $color string Custom color
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$classes = isset( $classes ) ? $classes : '';

if ( $align != 'none' ) {
	$classes .= ' align_' . $align;
}
if ( $us_elm_context == 'grid' AND get_post_type() == 'product' ) {
	$classes .= ' woocommerce-loop-product__title'; // needed for adding to cart 
} else {
	$classes .= ' entry-title'; // needed for Google structured data
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
		), TRUE, $tag
	);
}

// Link
if ( $link === 'post' ) {
	// Terms of selected taxonomy in Grid
	if ( $us_elm_context == 'grid_term' ) {
		global $us_grid_term;
		$link_atts = ' href="' . get_term_link( $us_grid_term ) . '"';
	} else {
		$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '"';
		// Force opening in a new tab for "Link" post format
		if ( get_post_format() == 'link' ) {
			$link_atts .= ' target="_blank"';
		}
	}
} elseif ( $link === 'custom' ) {
	$link_atts = us_generate_link_atts( $custom_link );
} else {
	$link_atts = '';
}

// Extra class for link color
if ( ! empty( $link_atts ) AND $color_link ) {
	$classes .= ' color_link_inherit';
}

// Schema.org markup
$schema_markup = '';
if ( us_get_option( 'schema_markup' ) AND $us_elm_context == 'shortcode' ) {
	$schema_markup = ' itemprop="headline"';
}

// Output the element
$output = '<' . $tag . ' class="w-post-elm post_title' . $classes . '"' . $inline_css . $el_id . $schema_markup . '>';

if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
if ( ! empty( $link_atts ) ) {
	$output .= '<a' . $link_atts . '>';
}

if ( $us_elm_context == 'grid_term' ) {
	global $us_grid_term;
	$output .= $us_grid_term->name;
} else {
	$output .= get_the_title();
}

if ( ! empty( $link_atts ) ) {
	$output .= '</a>';
}
$output .= '</' . $tag . '>';

echo $output;
