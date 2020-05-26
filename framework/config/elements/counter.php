<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Counter', 'us' ),
	'description' => __( 'Animated number with text', 'us' ),
	'params' => array_merge( array(

		// General
		'initial' => array(
			'title' => __( 'Initial counting value', 'us' ),
			'description' => __( 'With all the prefixes, suffixes and decimal marks if needed.', 'us' ) . ' ' . __( 'Examples:', 'us' ) . ' 0, $0, 1%, 0.001, 1kg',
			'type' => 'text',
			'std' => '1',
		),
		'final' => array(
			'title' => __( 'Final counting value', 'us' ),
			'description' => __( 'The way it should look like, when the animation ends.', 'us' ) . ' ' . __( 'Examples:', 'us' ) . ' 100, $70, 98%, 0.374, 35kg',
			'type' => 'text',
			'std' => '99',
			'holder' => 'div',
		),
		'color' => array(
			'title' => us_translate( 'Color' ),
			'type' => 'select',
			'options' => array(
				'primary' => __( 'Primary (theme color)', 'us' ),
				'secondary' => __( 'Secondary (theme color)', 'us' ),
				'heading' => __( 'Heading (theme color)', 'us' ),
				'text' => __( 'Text (theme color)', 'us' ),
				'custom' => us_translate( 'Custom' ),
			),
			'std' => 'primary',
			'cols' => 2,
		),
		'size' => array(
			'title' => us_translate( 'Size' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '5rem',
			'cols' => 2,
		),
		'custom_color' => array(
			'type' => 'color',
			'std' => '',
			'show_if' => array( 'color', '=', 'custom' ),
		),
		'font' => array(
			'title' => __( 'Font', 'us' ),
			'type' => 'select',
			'options' => us_get_fonts( 'without_groups' ),
			'std' => 'body',
		),
		'font_weight' => array(
			'title' => __( 'Font Weight', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'Default' ),
				'100' => '100 ' . __( 'thin', 'us' ),
				'200' => '200 ' . __( 'extra-light', 'us' ),
				'300' => '300 ' . __( 'light', 'us' ),
				'400' => '400 ' . __( 'normal', 'us' ),
				'500' => '500 ' . __( 'medium', 'us' ),
				'600' => '600 ' . __( 'semi-bold', 'us' ),
				'700' => '700 ' . __( 'bold', 'us' ),
				'800' => '800 ' . __( 'extra-bold', 'us' ),
				'900' => '900 ' . __( 'ultra-bold', 'us' ),
			),
			'std' => '',
			'cols' => 3,
		),
		'text_transform' => array(
			'title' => __( 'Text Transform', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'Default' ),
				'none' => us_translate( 'None' ),
				'uppercase' => 'UPPERCASE',
				'lowercase' => 'lowercase',
				'capitalize' => 'Capitalize',
			),
			'std' => '',
			'cols' => 3,
		),
		'font_style' => array(
			'title' => __( 'Font Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'Default' ),
				'normal' => __( 'normal', 'us' ),
				'italic' => __( 'italic', 'us' ),
			),
			'std' => '',
			'cols' => 3,
		),

		// More Options
		'title' => array(
			'title' => us_translate( 'Title' ),
			'type' => 'text',
			'std' => __( 'Projects completed', 'us' ),
			'holder' => 'div',
			'group' => __( 'More Options', 'us' ),
		),
		'title_size' => array(
			'title' => __( 'Title Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 2,
			'group' => __( 'More Options', 'us' ),
		),
		'title_tag' => array(
			'title' => __( 'Title HTML tag', 'us' ),
			'type' => 'select',
			'options' => $misc['html_tag_values'],
			'std' => 'h6',
			'cols' => 2,
			'group' => __( 'More Options', 'us' ),
		),
		'title_color' => array(
			'title' => __( 'Title Color', 'us' ),
			'type' => 'color',
			'std' => '',
			'group' => __( 'More Options', 'us' ),
		),
		'align' => array(
			'title' => us_translate( 'Alignment' ),
			'type' => 'select',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'center',
			'group' => __( 'More Options', 'us' ),
		),
		'duration' => array(
			'title' => __( 'Animation Duration (in seconds)', 'us' ),
			'type' => 'text',
			'std' => '2',
			'group' => __( 'More Options', 'us' ),
		),

	), $design_options ),
);
