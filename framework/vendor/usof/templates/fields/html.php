<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Html
 *
 * Simple textarea field.
 *
 * @var   $name  string Field name
 * @var   $id    string Field ID
 * @var   $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['encoded'] bool Value is encoded
 *
 * @var   $value string Current value
 */
global $usof_directory_uri;

$data = array(
	'ace_path' => $usof_directory_uri . '/js/ace/ace.js',
	'ace_ext_sarch_path' => $usof_directory_uri . '/js/ace/ext-searchbox.js',
);
if ( isset( $field['encoded'] ) AND $field['encoded'] ) {
	$data['encoded'] = 1;
}

$output = '<div class="usof-form-row-control-ace"></div>';
$output .= '<div class="usof-form-row-control-param"';
$output .= us_pass_data_to_js($data);
$output .= '></div>';

$output .= '<textarea name="' . $name . '">' . esc_textarea( $value ) . '</textarea>';
// $output .= '<div class="usof-form-row-resize"><div class="usof-form-row-resize-knob"><span></span></div></div>';

echo $output;

