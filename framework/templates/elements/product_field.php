<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * WooCommerce Product data
 */

global $product;
if ( ! class_exists( 'woocommerce' ) OR ! $product OR $us_elm_context == 'grid_term' ) {
	return;
}

$classes = isset( $classes ) ? $classes : '';
$classes .= isset( $type ) ? ( ' ' . $type ) : '';
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Get product data value
$value = '';
if ( $type == 'price' ) {
	$value .= $product->get_price_html();
} elseif ( $type == 'sku' AND $product->get_sku() ) {
	$value .= '<span class="w-post-elm-before">' . us_translate( 'SKU', 'woocommerce' ) . ': </span>';
	$value .= $product->get_sku();
} elseif ( $type == 'rating' AND get_option( 'woocommerce_enable_reviews', 'yes' ) === 'yes' ) {
	$value .= wc_get_rating_html( $product->get_average_rating() );
} elseif ( $type == 'sale_badge' AND $product->is_on_sale() ) {
	$classes .= ' onsale';
	$value .= strip_tags( $sale_text );
} elseif ( $type == 'weight' AND $product->has_weight() ) {
	$value .= '<span class="w-post-elm-before">' . us_translate( 'Weight', 'woocommerce' ) . ': </span>';
	$value .= esc_html( wc_format_weight( $product->get_weight() ) );
} elseif ( $type == 'dimensions' AND $product->has_dimensions()  ) {
	$value .= '<span class="w-post-elm-before">' . us_translate( 'Dimensions', 'woocommerce' ) . ': </span>';
	$value .= esc_html( wc_format_dimensions( $product->get_dimensions( FALSE ) ) );
} elseif ( $product_attribute_values = wc_get_product_terms( $product->get_id(), $type, array( 'fields' => 'names' ) ) ) {
	$value .= '<span class="w-post-elm-before">' . wc_attribute_label( $type ) . ': </span>';
	$value .= implode( ', ', $product_attribute_values );
}

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
		), TRUE
	);
}

// Output the element
$output = '<div class="w-post-elm product_field' . $classes . '"';
$output .= $el_id . $inline_css;
$output .= '>';
$output .= $value;
$output .= '</div>';

if ( $value != '' ) {
	echo $output;
}
