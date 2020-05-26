<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_separator
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 *
 * @param $size				 	 string Separator Height: 'small' / 'medium' / 'large' / 'huge' / 'custom'
 * @param $height  				 string Separator custom height
 * @param $show_line			 bool Show the line in the middle?
 * @param $line_width			 string Separator type: 'default' / 'fullwidth' / 'short'
 * @param $thick				 string Line thickness: '1' / '2' / '3' / '4' / '5'
 * @param $style				 string Line style: 'solid' / 'dashed' / 'dotted' / 'double'
 * @param $color				 string Color style: 'border' / 'primary' / 'secondary' / 'custom'
 * @param $bdcolor				 string Border color value
 * @param $icon					 string Icon
 * @param $text					 string Title
 * @param $title_tag			 string Title HTML tag: 'h1' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'div'
 * @param $title_size			 string Font Size
 * @param $align				 string Alignment
 * @param $link					 string Link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $el_class				 string Extra class name
 * @param $breakpoint_1_width	 string Screen Width breakpoint 1
 * @param $breakpoint_1_height	 string Separator custom height 1
 * @param $breakpoint_2_width	 string Screen Width breakpoint 2
 * @param $breakpoint_2_height	 string Separator custom height 2
 */

$classes = $inner_html = $inline_css = $link_opener = $link_closer = $responsive_styles = '';

// Set element index to apply <style> for responsive CSS
if ( $size == 'custom' AND $breakpoint_1_height != '' OR $breakpoint_2_height != '' ) {
	global $us_separator_index;
	$us_separator_index = isset( $us_separator_index ) ? ( $us_separator_index + 1 ) : 1;
	$classes .= ' us_separator_' . $us_separator_index;

	$responsive_styles = '<style>';
	if ( $breakpoint_1_height != '' AND $breakpoint_1_height != $height ) {
		$responsive_styles .= '@media(max-width:' . esc_attr( $breakpoint_1_width ) . '){.us_separator_' . $us_separator_index . '{height:' . esc_attr( $breakpoint_1_height ) . '!important}}';
	}
	if ( $breakpoint_2_height != '' AND $breakpoint_2_height != $height ) {
		$responsive_styles .= '@media(max-width:' . esc_attr( $breakpoint_2_width ) . '){.us_separator_' . $us_separator_index . '{height:' . esc_attr( $breakpoint_2_height ) . '!important}}';
	}
	$responsive_styles .= '</style>';
}

$classes .= ' size_' . $size;
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Link
$link_atts = us_generate_link_atts( $link );
if ( ! empty( $link_atts ) ) {
	$link_opener = '<a' . $link_atts . '>';
	$link_closer = '</a>';
}

// Generate separator icon and title
if ( $show_line ) {
	$classes .= ' with_line';
	$classes .= ' width_' . $line_width;
	$classes .= ' thick_' . $thick;
	$classes .= ' style_' . $style;
	$classes .= ' color_' . $color;
	$classes .= ' align_' . $align;

	if ( ! empty( $text ) ) {
		$inner_html .= '<' . $title_tag . ' class="w-separator-text">';
		$inner_html .= $link_opener;
		$inner_html .= us_prepare_icon_tag( $icon );
		$inner_html .= '<span>' . strip_tags( $text, '<br><strong>' ) . '</span>';
		$inner_html .= $link_closer;
		$inner_html .= '</' . $title_tag . '>';
	} else {
		$inner_html .= us_prepare_icon_tag( $icon );
	}

	if ( $inner_html != '' ) {
		$classes .= ' with_content';
	}

	$inline_css = us_prepare_inline_css(
		array(
			'height' => $height,
			'border-color' => $bdcolor,
			'color' => $bdcolor,
			'font-size' => $title_size,
		)
	);
} else {
	$inline_css = us_prepare_inline_css(
		array(
			'height' => $height,
		)
	);
}

// Output the element
$output = '<div class="w-separator' . $classes . '"' . $el_id . $inline_css . '>';
$output .= $responsive_styles;
if ( $show_line ) {
	$output .= '<div class="w-separator-h">' . $inner_html . '</div>';
}
$output .= '</div>';

echo $output;
