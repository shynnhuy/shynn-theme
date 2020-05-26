<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Popup
 */

$popup_classes = ' animation_' . $animation;

$classes = ' align_' . $align;
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}

// Output the trigger
$output = '<div class="w-popup' . $classes . '" ' . $el_id . '>';

// Trigger
if ( $show_on == 'image' AND ! empty( $image ) AND ( $image_html = wp_get_attachment_image( $image, $image_size ) ) ) {
	$output .= '<a href="javascript:void(0)" class="w-popup-trigger type_image">' . $image_html . '</a>';
} elseif ( $show_on == 'load' ) {
	$output .= '<span class="w-popup-trigger type_load" data-delay="' . intval( $show_delay ) . '"></span>';
} elseif ( $show_on == 'selector' ) {
	$output .= '<span class="w-popup-trigger type_selector" data-selector="' . esc_attr( $trigger_selector ) . '"></span>';
} else/*if ( $show_on == 'btn' )*/ {

	if ( ! isset( $btn_size ) OR trim( $btn_size ) == ( us_get_option( 'body_fontsize' ) ) ) {
		$btn_size = '';
	}
	$btn_inline_css = us_prepare_inline_css(
		array(
			'font-size' => $btn_size,
		)
	);

	// Icon
	$icon_html = $btn_classes = '';
	if ( ! empty( $btn_icon ) ) {
		$icon_html = us_prepare_icon_tag( $btn_icon );
		$btn_classes = ' icon_at' . $btn_iconpos;
	}
	$output .= '<div class="w-btn-wrapper">';
	$output .= '<a href="javascript:void(0)" class="w-popup-trigger type_btn w-btn us-btn-style_' . $btn_style . $btn_classes . '"' . $btn_inline_css . '>';
	if ( is_rtl() ) {
		$btn_iconpos = ( $btn_iconpos == 'left' ) ? 'right' : 'left';
	}
	if ( $btn_iconpos == 'left' ) {
		$output .= $icon_html;
	}
	$output .= '<span class="w-btn-label">' . trim( strip_tags( $btn_label, '<br>' ) ) . '</span>';
	if ( $btn_iconpos == 'right' ) {
		$output .= $icon_html;
	}
	$output .= '</a>';
	$output .= '</div>';
}

// Overlay
$output .= '<div class="w-popup-overlay"';
$output .= us_prepare_inline_css(
	array(
		'background' => $overlay_bgcolor,
	)
);
$output .= '></div>';

// Popup title
$output_title = '';
if ( ! empty( $title ) ) {
	$popup_classes .= ' with_title';

	$output_title .= '<div class="w-popup-box-title"';
	$output_title .= us_prepare_inline_css(
		array(
			'color' => $title_textcolor,
			'background' => $title_bgcolor,
		)
	);
	$output_title .= '>' . esc_html( $title ) . '</div>';
} else {
	$popup_classes .= ' without_title';
}

// The Popup itself
$output .= '<div class="w-popup-wrap">';
$output .= '<div class="w-popup-box' . $popup_classes . '"';
$output .= us_prepare_inline_css(
	array(
		'border-radius' => $popup_border_radius,
		'width' => $popup_width,
	)
);
$output .= '><div class="w-popup-box-h">';
$output .= $output_title;

// Popup content
$output .= '<div class="w-popup-box-content"';
$output .= us_prepare_inline_css(
	array(
		'padding' => $popup_padding,
		'background' => $content_bgcolor,
		'color' => $content_textcolor,
	)
);
$output .= '>';
$output .= do_shortcode( wpautop( $content ) );
$output .= '</div>';
$output .= '</div></div>'; // .w-popup-box

// Popup closer
$output .= '<div class="w-popup-closer"';
$output .= us_prepare_inline_css(
	array(
		'background' => $content_bgcolor,
		'color' => $content_textcolor,
	)
);
$output .= '></div>';

$output .= '</div>'; // .w-popup-wrap
$output .= '</div>'; // .w-popup

echo $output;
