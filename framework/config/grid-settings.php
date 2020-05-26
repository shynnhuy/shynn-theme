<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Grid Layout and Elements Options.
 * Options and elements' fields are described in USOF-style format.
 */
 
$elements = array(
	'post_image',
	'post_title',
	'post_date',
	'post_taxonomy',
	'post_author',
	'post_comments',
	'post_content',
	'post_custom_field',
	'btn',
	'html',
	'hwrapper',
	'vwrapper',
);
if ( class_exists( 'woocommerce' ) ) {
	$elements[] = 'product_field';
	$elements[] = 'add_to_cart';
}

return array(

	// Supported elements
	'elements' => $elements,

	// General Options
	'options' => array(
		'global' => array(
			'fixed' => array(
				'switch_text' => __( 'Set Aspect Ratio', 'us' ),
				'type' => 'switch',
				'std' => FALSE,
			),
			'ratio' => array(
				'type' => 'select',
				'options' => array(
					'4x3' => __( '4:3 (landscape)', 'us' ),
					'3x2' => __( '3:2 (landscape)', 'us' ),
					'1x1' => __( '1:1 (square)', 'us' ),
					'2x3' => __( '2:3 (portrait)', 'us' ),
					'3x4' => __( '3:4 (portrait)', 'us' ),
					'16x9' => '16:9',
					'custom' => __( 'Custom', 'us' ),
				),
				'std' => '1x1',
				'classes' => 'for_above',
				'show_if' => array( 'fixed', '=', TRUE ),
			),
			'ratio_width' => array(
				'placeholder' => us_translate( 'Width' ),
				'type' => 'text',
				'std' => '21',
				'show_if' => array(
					array( 'fixed', '=', TRUE ),
					'and',
					array( 'ratio', '=', 'custom' ),
				),
			),
			'ratio_height' => array(
				'placeholder' => us_translate( 'Height' ),
				'type' => 'text',
				'std' => '9',
				'show_if' => array(
					array( 'fixed', '=', TRUE ),
					'and',
					array( 'ratio', '=', 'custom' ),
				),
			),
			'overflow' => array(
				'switch_text' => __( 'Hide Overflowing Content', 'us' ),
				'type' => 'switch',
				'std' => FALSE,
				'show_if' => array( 'fixed', '=', FALSE ),
			),
			'color_bg' => array(
				'title' => __( 'Background Color', 'us' ),
				'type' => 'color',
				'std' => '',
				'classes' => 'clear_right',
			),
			'color_text' => array(
				'title' => __( 'Text Color', 'us' ),
				'type' => 'color',
				'std' => '',
				'classes' => 'clear_right',
			),
			'border_radius' => array(
				'title' => __( 'Corners Radius', 'us' ),
				'type' => 'slider',
				'std' => '0',
				'options' => array(
					'rem' => array(
						'min' => 0.0,
						'max' => 3.0,
						'step' => 0.1,
					),
				),
			),
			'box_shadow' => array(
				'title' => __( 'Shadow', 'us' ),
				'type' => 'slider',
				'std' => '0',
				'options' => array(
					'rem' => array(
						'min' => 0.0,
						'max' => 3.0,
						'step' => 0.1,
					),
				),
			),
			'box_shadow_hover' => array(
				'title' => __( 'Shadow on hover', 'us' ),
				'type' => 'slider',
				'std' => '0',
				'options' => array(
					'rem' => array(
						'min' => 0.0,
						'max' => 3.0,
						'step' => 0.1,
					),
				),
			),
		),
	),

);
