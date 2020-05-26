<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Interactive Text', 'us' ),
	'description' => __( 'Text with dynamically changing part', 'us' ),
	'icon' => 'fas fa-italic',
	'params' => array_merge( array(

		// General
		'texts' => array(
			'title' => __( 'Text States', 'us' ),
			'description' => __( 'Each value on a new line', 'us' ),
			'type' => 'textarea',
			'std' => 'We create great design' . "\n" . 'We create great websites' . "\n" . 'We create great code',
			'holder' => 'div',
		),
		'dynamic_bold' => array(
			'type' => 'switch',
			'switch_text' => __( 'Make the dynamic part bold', 'us' ),
			'std' => FALSE,
		),

	), $typography_options, array(

		// More Options
		'align' => array(
			'title' => us_translate( 'Alignment' ),
			'type' => 'select',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'center',
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'tag' => array(
			'title' => __( 'HTML tag', 'us' ),
			'type' => 'select',
			'options' => $misc['html_tag_values'],
			'std' => 'h2',
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'color' => array(
			'title' => __( 'Text Color', 'us' ),
			'type' => 'color',
			'std' => '',
			'group' => us_translate( 'Appearance' ),
		),
		'dynamic_color' => array(
			'title' => __( 'Dynamic Part Color', 'us' ),
			'type' => 'color',
			'std' => '',
			'group' => us_translate( 'Appearance' ),
		),
		'animation_type' => array(
			'title' => __( 'Animation', 'us' ),
			'type' => 'select',
			'options' => array(
				'fadeIn' => __( 'Fade in the whole part', 'us' ),
				'flipInX' => __( 'Flip the whole part', 'us' ),
				'flipInXChars' => __( 'Flip character by character', 'us' ),
				'zoomIn' => __( 'Zoom in the whole part', 'us' ),
				'zoomInChars' => __( 'Zoom in character by character', 'us' ),
			),
			'std' => 'fadeIn',
			'group' => us_translate( 'Appearance' ),
		),
		'duration' => array(
			'title' => __( 'Animation Duration (in seconds)', 'us' ),
			'type' => 'text',
			'std' => '0.3',
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'delay' => array(
			'title' => __( 'Animation Delay (in seconds)', 'us' ),
			'type' => 'text',
			'std' => '5',
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),

	), $design_options ),
);
