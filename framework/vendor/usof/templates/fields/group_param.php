<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Group
 *
 * Grouped options
 *
 * @var   $field array Group options
 * @var   $params_values array Group values
 *
 */

$param_content_styles = '';
$output = '<div class="usof-form-group-item">';

// Output Title block when Accordion type is set
if ( isset( $field[ 'is_accordion' ] ) AND $field[ 'is_accordion' ] ) {
	$param_title = ( ! empty( $field['title'] ) ) ? $field['title'] : '';
	foreach ( $field['params'] as $param_name => $param ) {
		if ( strpos( $param_title, '{{' . $param_name . '}}' ) !== FALSE ) {
			$param_value = isset( $params_values[$param_name] ) ? $params_values[$param_name] : $field['params'][$param_name]['std'];
			$param_value = esc_attr( trim( $param_value ) );
			$param_title = str_replace( '{{' . $param_name . '}}', $param_value, $param_title );
		}
	}
	$output .= '<div class="usof-form-group-item-title">';

	// Output Button Preview when class "for_btns" is set
	if ( isset( $field['classes'] ) AND strpos( $field['classes'], 'for_btns' ) !== FALSE ) {
		$output .= '<div class="usof-btn-preview hov_fade">';
		$output .= '<div class="usof-btn">';
		$output .= '<div class="usof-btn-before"></div>';
		$output .= '<span class="usof-btn-label">' . $param_title . '</span>';
		$output .= '<div class="usof-btn-after"></div>';
		$output .= '</div>';
		$output .= '</div>';
	} else {
		$output .= $param_title;
	}

	$output .= '</div>';

	$param_content_styles = ' style="display:none;"';
}

$output .= '<div class="usof-form-group-item-content"' . $param_content_styles . '>';
ob_start();
foreach ( $field['params'] as $param_name => $param ) {
	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => $param_name,
			'id' => 'usof_' . $param_name,
			'field' => $param,
			'values' => $params_values,
		)
	);
}
$output .= ob_get_clean();
$output .= '</div>';
$output .= '<div class="usof-form-group-item-controls">';
if ( isset( $field['is_sortable'] ) AND $field['is_sortable'] ) {
	$output .= '<div class="usof-control-move" title="' . us_translate( 'Move' ) . '"></div>';
}
$output .= '<div class="usof-control-delete" title="' . us_translate( 'Delete' ) . '"></div>';
$output .= '</div></div>';

echo $output;
