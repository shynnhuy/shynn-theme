<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Check Table
 *
 * Multiple selector as table
 *
 * @var   $id    string Field ID
 * @var   $name  string Field name
 * @var   $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['options'] array List of key => title pairs
 *
 * @var   $value array List of checked keys
 */

if ( ! is_array( $value ) ) {
	$value = array();
}
if ( isset( $is_metabox ) AND $is_metabox ) {
	$name .= '[]';
}

$output = '<ul class="usof-checkbox-list">';
foreach ( $field['options'] as $key => $option ) {

	if ( $option['group'] != NULL ) {
		$output .= '</ul><ul class="usof-checkbox-list"><li class="usof-checkbox-list-title">' . $option['group'] . '</li>';
	}
	if ( isset( $option['apply_if'] ) AND ! $option['apply_if'] ) {
		continue;
	}
	$output .= '<li class="usof-checkbox for_' . $key . '"><label>';
	$output .= '<input type="checkbox" name="' . $name . '" value="' . esc_attr( $key ) . '"';
	if ( in_array( $key, $value ) ) {
		$output .= ' checked';
	}
	$output .= '>';
	$output .= '<span class="usof-checkbox-icon"></span>';
	$output .= '<span class="usof-checkbox-text"><span>' . $option['title'] . '</span></span>';
	$output .= '<span class="usof-checkbox-size for_js">';
	if ( isset( $option['js_size'] ) AND $option['js_size'] ) {
		$output .= $option['js_size'];
	} else {
		$output .= '-';
	}
	$output .= '</span>';
	$output .= '<span class="usof-checkbox-size for_css">';
	if ( isset( $option['css_size'] ) AND $option['css_size'] ) {
		$output .= $option['css_size'];
	} else {
		$output .= '-';
	}
	$output .= '</span>';
	$output .= '</label>';
	$output .= '<div class="usof-checkbox-description"></div>';
	$output .= '</li>';
}
$output .= '</ul>';
$output .= '<div class="usof-checkbox-list-desc">' . __( 'All sizes in kilobytes', 'us' ) . '</div>';

echo $output;
