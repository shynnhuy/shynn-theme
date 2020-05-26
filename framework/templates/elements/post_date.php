<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Date element
 *
 * @var $type string Date type: 'published' / 'modified'
 * @var $format string Date format selected from preset
 * @var $format_custom string Date custom format
 * @var $icon string Icon name
 * @var $tag string 'h1' / 'h2' / 'h3' / 'h4' / 'h5' / 'h6' / 'p' / 'div'
 * @var $color string Custom color
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( $us_elm_context == 'grid_term' ) {
	return;
}

$classes = isset( $classes ) ? $classes : '';

// Classes for Google structured data
$classes .= ' entry-date';
if ( $type == 'modified' ) {
	$classes .= ' updated';
} else {
	$classes .= ' published';
}
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

$tag = 'time';
$elm_attr = '';

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
		),
		$style_attr = TRUE,
		$tag
	);
}

$smart_date = FALSE;
// Generate date format
if ( $format == 'default' ) {
	$format = get_option( 'date_format' );
} elseif ( $format == 'custom' ) {
	$format = $format_custom;
} elseif ( $format == 'smart' ) {
	$format = 'U';
	$smart_date = TRUE;
}

if ( $type == 'modified' ) {
	$date = get_the_modified_date( $format );

	$elm_attr .= ' datetime="' . get_the_modified_date( 'c' ) . '"'; // needed datetime attribute for <time> tag
	if ( $smart_date ) {
		$elm_attr .= ' title="' . sprintf( us_translate( '%1$s at %2$s' ), get_the_modified_date( 'j F Y' ), get_the_modified_date( 'H:i:s e' ) ) . '"';
	}
} else {
	$date = get_the_date( $format );

	$elm_attr .= ' datetime="' . get_the_date( 'c' ) . '"'; // needed datetime attribute for <time> tag
	if ( $smart_date ) {
		$elm_attr .= ' title="' . sprintf( us_translate( '%1$s at %2$s' ), get_the_date( 'j F Y' ), get_the_date( 'H:i:s e' ) ) . '"';
	}
}

// Generate date in smart format
if ( $smart_date ) {
	$date = us_get_smart_date( $date );
}

// Schema.org markup
if ( us_get_option( 'schema_markup' ) AND $us_elm_context == 'shortcode' ) {
	$elm_attr .= ( $type == 'modified' ) ? ' itemprop="dateModified"' : ' itemprop="datePublished"';
}

$text_before = ( trim( $text_before ) != '' ) ? '<span class="w-post-elm-before">' . trim( $text_before ) . ' </span>' : '';

// Output the element
$output = '<' . $tag . ' class="w-post-elm post_date' . $classes . '"';
$output .= $inline_css . $el_id . $elm_attr;
$output .= '>';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
$output .= $text_before;
$output .= $date;
$output .= '</' . $tag . '>';

echo $output;
