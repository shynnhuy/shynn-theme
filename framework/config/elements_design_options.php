<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Common Design Options
 */

$misc = us_config( 'elements_misc' );

return array(

	// For Header elements
	'hide_for_sticky' => array(
		'type' => 'switch',
		'switch_text' => __( 'Hide this element when the header is sticky', 'us' ),
		'std' => FALSE,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'header' ),
	),
	'hide_for_not_sticky' => array(
		'type' => 'switch',
		'switch_text' => __( 'Hide this element when the header is NOT sticky', 'us' ),
		'std' => FALSE,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'header' ),
	),

	// Extra CSS class
	'el_class' => array(
		'title' => __( 'Extra class', 'us' ),
		'type' => 'text',
		'std' => '',
		'shortcode_cols' => 2,
		'group' => __( 'Design Options', 'us' ),
	),

	// Element ID
	'el_id' => array(
		'title' => __( 'Element ID', 'us' ),
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'shortcode' ), // can't be added to Grid Layout
	),

	// WPB Design Options
	'css' => array(
		'type' => 'css_editor',
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'shortcode' ),
	),

	// USOF Design Options
	'design_options' => array(
		'type' => 'design_options',
		'grid_states' => array( 'default' ),
		'grid_with_position' => TRUE,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'header', 'grid' ),
	),

	// Additional options used in Grid Layout
	'color_bg' => array(
		'title' => __( 'Background Сolor', 'us' ),
		'type' => 'color',
		'std' => '',
		'cols' => 2,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'grid' ),
	),
	'color_border' => array(
		'title' => __( 'Border Сolor', 'us' ),
		'type' => 'color',
		'std' => '',
		'cols' => 2,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'grid' ),
	),
	'color_text' => array(
		'title' => __( 'Text Color', 'us' ),
		'type' => 'color',
		'std' => '',
		'cols' => 2,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'grid' ),
	),
	'hide_below' => array(
		'title' => __( 'Hide the element when the screen width is less than', 'us' ),
		'type' => 'slider',
		'std' => 0,
		'options' => array(
			'px' => array(
				'min' => 0,
				'max' => 1600,
				'step' => 20,
			),
		),
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'grid' ),
	),
	'width' => array(
		'title' => __( 'Custom Width', 'us' ),
		'description' => $misc['desc_width'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'grid' ),
	),
	'border_radius' => array(
		'title' => __( 'Corners Radius', 'us' ),
		'description' => $misc['desc_border_radius'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'group' => __( 'Design Options', 'us' ),
		'context' => array( 'grid' ),
	),

);
