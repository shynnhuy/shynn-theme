<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: design_options
 *
 * Design options.
 *
 * @var $name string Field name
 * @var $id string Field ID
 * @var $field array Field options
 * @var $field['states'] array Available states: array( 'default', 'mobiles', 'tablets' )
 * @var $field['with_position'] bool Show position controls
 *
 * @var $value array Current value
 */

if ( ! is_array( $value ) ) {
	$value = array();
}

$directions = array( 'top', 'right', 'bottom', 'left' );
$states = ( isset( $field['states'] ) AND is_array( $field['states'] ) ) ? $field['states'] : array( 'default', 'tablets', 'mobiles' );
$states_titles = array(
	'default' => us_translate( 'Default' ),
	'tablets' => __( 'Tablets', 'us' ),
	'mobiles' => __( 'Mobiles', 'us' ),
);
$with_position = ( isset( $field['with_position'] ) AND $field['with_position'] );

$output = '<div class="usof-design">';
foreach ( $states as $state ) {
	$state_title = us_arr_path( $states_titles, $state, $state );
	$output .= '<div class="usof-design-control for_' . $state . '">';
	$output .= '<div class="usof-design-control-title">' . $state_title . '</div>';

	if ( $with_position ) {
		// Checking if position is enabled
		$pos_enabled = FALSE;
		foreach ( $directions as $part ) {
			$subname = 'position_' . $part . '_' . $state;
			if ( isset( $value[ $subname ] ) AND $value[ $subname ] !== '' ) {
				$pos_enabled = TRUE;
				break;
			}
		}

		// Positioning Switcher
		$output .= '<div class="usof-switcher">';
		$output .= '<input type="hidden" name="pos_abs_' . $state . '" value="0" />';
		$output .= '<input type="checkbox" id="' . $id . '_pos_abs_' . $state . '" name="pos_abs_' . $state . '" value="1"' . ( $pos_enabled ? ' checked="checked"' : '' ) . '>';
		$output .= '<label for="' . $id . '_pos_abs_' . $state . '">';
		$output .= '<span class="usof-switcher-box"><i></i></span>';
		$output .= '<span class="usof-switcher-text">' . __( 'Absolute Positioning', 'us' ) . '</span>';
		$output .= '</label></div>';

		// Position
		$output .= '<div class="usof-design-position' . ( $pos_enabled ? '' : ' pos_off' ) . '">';
		$output .= '<div class="usof-design-attr">Position</div>';
		foreach ( $directions as $part ) {
			$subname = 'position_' . $part . '_' . $state;
			$subvalue = isset( $value[ $subname ] ) ? $value[ $subname ] : '';
			if ( preg_match( '~^\d+$~', $subvalue ) ) {
				$subvalue = $subvalue . 'px';
			}
			$output .= '<input class="' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
		}
	}

	// Margin
	$output .= '<div class="usof-design-margin">';
	$output .= '<div class="usof-design-attr">Margin</div>';
	foreach ( $directions as $part ) {
		$subname = 'margin_' . $part . '_' . $state;
		$subvalue = isset( $value[ $subname ] ) ? $value[ $subname ] : '';
		if ( preg_match( '~^\d+$~', $subvalue ) ) {
			$subvalue = $subvalue . 'px';
		}
		$output .= '<input class="' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
	}

	// Border
	$output .= '<div class="usof-design-border">';
	$output .= '<div class="usof-design-attr">Border</div>';
	foreach ( $directions as $part ) {
		$subname = 'border_' . $part . '_' . $state;
		$subvalue = isset( $value[ $subname ] ) ? $value[ $subname ] : '';
		if ( preg_match( '~^\d+$~', $subvalue ) ) {
			$subvalue = $subvalue . 'px';
		}
		$output .= '<input class="' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
	}

	// Padding
	$output .= '<div class="usof-design-padding">';
	$output .= '<div class="usof-design-attr">Padding</div>';
	foreach ( $directions as $part ) {
		$subname = 'padding_' . $part . '_' . $state;
		$subvalue = isset( $value[ $subname ] ) ? $value[ $subname ] : '';
		if ( preg_match( '~^\d+$~', $subvalue ) ) {
			$subvalue = $subvalue . 'px';
		}
		$output .= '<input class="' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
	}
	$output .= '</div>';

	// .usof-design-border
	$output .= '</div>';

	// .usof-design-margin
	$output .= '</div>';

	if ( $with_position ) {
		// .usof-design-position
		$output .= '</div>';
	}

	// .usof-design-control
	$output .= '</div>';
}

$output .= '</div>';

echo $output;
