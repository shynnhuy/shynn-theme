<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Horizontal Wrapper
 */

$classes = ' align_' . $alignment;
$classes .= ' valign_' . $valign;
$classes .= ( $wrap ) ? ' wrap' : '';

if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Output the element
$output = '<div class="w-hwrapper' . $classes . '"' . $el_id . '>';
$output .= do_shortcode( $content );
$output .= '</div>';

echo $output;
