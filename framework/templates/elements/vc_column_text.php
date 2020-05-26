<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode attributes
 *
 * @var $el_class
 * @var $css_animation
 * @var $css
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column_text
 */

$classes = '';

if ( function_exists( 'vc_shortcode_custom_css_class' ) AND ! empty( $css ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}

if ( ! empty( $el_class ) ) {
	$classes .= ' ' . $el_class;
}

$el_id_string = '';
if ( $el_id != '' ) {
	$el_id_string = ' id="' . esc_attr( $el_id ) . '"';
}

$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );

$output = '
	<div class="wpb_text_column ' . esc_attr( $classes ) . '"' . $el_id_string . '>
		<div class="wpb_wrapper">
			' . do_shortcode( shortcode_unautop( $content ) ) . '
		</div>
	</div>
';

echo $output;
