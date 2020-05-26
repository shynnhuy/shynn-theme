<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Upload
 *
 * Upload some file with the specified settings.
 *
 * @var   $name  string Field name
 * @var   $id    string Field ID
 * @var   $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['button_label'] string Upload Button label
 * @param $field ['preview_type'] string 'image' / 'text'
 * @param $field ['is_multiple'] bool
 *
 * @var   $value mixed Either full path to the file, or ID from WordPress media uploads
 */

$field['preview_type'] = isset( $field['preview_type'] ) ? $field['preview_type'] : 'image';
$field['is_multiple'] = isset( $field['is_multiple'] ) ? $field['is_multiple'] : FALSE;
$field['button_label'] = isset( $field['button_label'] ) ? $field['button_label'] : us_translate( 'Set image' );

$upload_file = '';
if ( $field['preview_type'] == 'image' ) {
	if ( ! empty( $value ) ) {
		$upload_file = usof_get_image_src( $value, 'medium' );
	}
} elseif ( $field['preview_type'] == 'text' ) {
	$files = explode( ',', $value );
	$upload_file = array();
	foreach ( $files as $file ) {
		$url = wp_get_attachment_url( $file );
		if ( $url ) {
			$upload_file[] = $url;
		}
	}
	if ( count( $upload_file ) == 0 ) {
		$upload_file = FALSE;
	}

}

$control_buttons = '<div class="usof-upload-controls">';
$control_buttons .= '<a class="usof-button type_change" href="javascript:void(0)"><span>' . us_translate( 'Change' ) . '</span></a>';
$control_buttons .= '<a class="usof-button type_remove" href="javascript:void(0)"><span>' . us_translate( 'Remove' ) . '</span></a>';
$control_buttons .= '</div>';

$output = '<div class="usof-upload preview_' . $field['preview_type'];
if ( $field['is_multiple'] ) {
	$output .= ' is_multiple';
}
$output .= '">';
$output .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';

$output .= '<button class="usof-button type_upload" type="button"' . ( $upload_file ? ' style="display:none;"' : '' ) . '>';
$output .= '<span class="usof-button-label">' . $field['button_label'] . '</span>';
$output .= '</button>';

$output .= '<div class="usof-upload-preview"' . ( $upload_file ? '' : ' style="display:none;"' ) . '>';
if ( $field['preview_type'] == 'image' ) {
	$output .= '<div class="usof-preloader"></div>';
	if ( $upload_file ) {
		$output .= '<img src="' . esc_attr( $upload_file[0] ) . '" alt="preview" />';
	} else {
		$output .= '<img src="" alt="preview" />';
	}
	$output .= $control_buttons;
} elseif ( $field['preview_type'] == 'text' ) {
	$output .= '<div class="usof-upload-file">';
	if ( $upload_file ) {
		foreach ( $upload_file as $file ) {
			$output .= '<span>' . basename( $file ) . '</span>';
		}
	}
	$output .= '</div>';
	$output .= $control_buttons;
}
$output .= '</div>';
$output .= '</div>';

echo $output;

unset( $upload_file );
