<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Button element
 */

// Default variables
$output = $wrapper_classes = $wrapper_inline_css = $btn_inline_css = $responsive_css = '';

// Button classes & inline styles
$btn_classes = 'w-btn us-btn-style_' . $style;
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

if ( $us_elm_context == 'shortcode' ) {

	$wrapper_classes .= ' width_' . $width_type;
	if ( $width_type != 'full' ) {
		$wrapper_classes .= ' align_' . $align;
	}
	if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $css );
	}

	$wrapper_inline_css = us_prepare_inline_css(
		array(
			'width' => ( $width_type == 'custom' AND $align != 'center' ) ? $custom_width : NULL,
			'max-width' => ( $width_type == 'max' AND $align != 'center' ) ? $custom_width : NULL,
		)
	);

	if ( ! isset( $font_size ) OR trim( $font_size ) == us_get_option( 'body_fontsize', '16px' ) ) {
		$font_size = '';
	}
	$btn_inline_css = us_prepare_inline_css(
		array(
			'font-size' => $font_size,
			'width' => ( $width_type == 'custom' AND $align == 'center' ) ? $custom_width : NULL,
			'max-width' => ( $width_type == 'max' AND $align == 'center' ) ? $custom_width : NULL,
		)
	);
	if ( ! empty( $font_size_mobiles ) ) {
		global $us_btn_index;
		$us_btn_index = isset( $us_btn_index ) ? ( $us_btn_index + 1 ) : 1;
		$btn_classes .= ' us_btn_' . $us_btn_index;
		$responsive_css = '<style>@media(max-width:600px){.us_btn_' . $us_btn_index . '{font-size:' . $font_size_mobiles . '!important}}</style>';
	}
	$btn_classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
} else {
	$btn_classes .= isset( $classes ) ? $classes : '';
}

// Icon
$icon_html = '';
if ( ! empty( $icon ) ) {
	$icon_html = us_prepare_icon_tag( $icon );
	$btn_classes .= ' icon_at' . $iconpos;
}
if ( is_rtl() ) { // swap icon position for RTL
	$iconpos = ( $iconpos == 'left' ) ? 'right' : 'left';
}

// Text
$text = trim( strip_tags( $label, '<br>' ) );
if ( $text == '' ) {
	$btn_classes .= ' text_none';
}

// Link
if ( in_array( $us_elm_context, array( 'shortcode', 'header' ) ) ) {
	$link_type = 'custom';
}
if ( $link_type === 'post' ) {
	// Terms of selected taxonomy in Grid
	if ( $us_elm_context == 'grid_term' ) {
		global $us_grid_term;
		$link_atts = ' href="' . get_term_link( $us_grid_term ) . '"';
	} else {
		$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '"';
	}
} elseif ( $link_type === 'custom' ) {
	$link_atts = us_generate_link_atts( $link );
} else {
	$link_atts = '';
}
if ( ! empty( $link_atts ) ) {
	$btn_tag = 'a';
} else {
	$btn_tag = 'div';
}

// Output the element
if ( $us_elm_context == 'shortcode' ) {
	$output .= '<div class="w-btn-wrapper' . $wrapper_classes . '"' . $wrapper_inline_css . '>';
	$output .= $responsive_css;
}
$output .= '<' . $btn_tag . ' class="' . $btn_classes . '"';
$output .= $link_atts . $btn_inline_css . $el_id;
$output .= '>';

if ( $iconpos == 'left' ) {
	$output .= $icon_html;
}
if ( $text != '' ) {
	$output .= '<span class="w-btn-label">' . $text . '</span>';
}
if ( $iconpos == 'right' ) {
	$output .= $icon_html;
}
$output .= '</' . $btn_tag . '>';
if ( $us_elm_context == 'shortcode' ) {
	$output .= '</div>';
}

echo $output;
