<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Color
 *
 * Simple color picker
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['text'] string Field additional text
 *
 * @var   $name  string Field name
 * @var   $id    string Field ID
 * @var   $field array Field options
 *
 * @var   $value string Current value
 */

if ( preg_match( '~^\#([\da-f])([\da-f])([\da-f])$~', $value, $matches ) ) {
	$value = '#' . $matches[1] . $matches[1] . $matches[2] . $matches[2] . $matches[3] . $matches[3];
}

$palette = get_option( 'usof_color_palette_' . US_THEMENAME );
if ( ! is_array( $palette ) ) {
	$palette = array();
}

$gradient = strstr( $value, 'linear-gradient' ) ? ' with_gradient' : '';

$output = '<div class="usof-color' . $gradient . '">';
$output .= '<div class="usof-color-preview" style="background: ' . $value . '"></div>';
$output .= '<input class="usof-color-value" type="text" name="' . $name . '" value="' . esc_attr( $value ) . '" />';
$output .= '<div class="usof-color-clear" title="' . us_translate( 'Clear' ) . '"></div>';

$output .= '<div class="usof_colpick_wrap">';
$output .= '<div class="usof_colpick_palette">';

// Fill palette with default colors
if ( empty( $palette ) ) {
	global $usof_options;

	$options = $usof_options;
	$predefined_colors = array(
		$options['color_content_primary'],
		$options['color_content_secondary'],
		$options['color_content_heading'],
		$options['color_content_text'],
		$options['color_content_faded'],
		$options['color_content_border'],
		$options['color_content_bg_alt'],
		$options['color_content_bg'],
	);

	update_option( 'usof_color_palette_' . US_THEMENAME, $predefined_colors );
}

foreach ( $palette as $color ) {
	$output .= '<div class="usof_colpick_palette_value"><span style="background:' . $color . '" title="' . esc_attr( $color ) . '"></span><div class="usof_colpick_palette_delete" title="' . us_translate( 'Delete' ) . '"></div></div>';
}
$output .= '<div class="usof_colpick_palette_add" title="' . __( 'Add the current color to the palette', 'us' ) . '"></div>';
$output .= '</div></div></div>';

if ( isset( $field['text'] ) AND ! empty( $field['text'] ) ) {
	$output .= '<div class="usof-color-text">' . $field['text'] . '</div>';
}
echo $output;
