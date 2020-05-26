<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Common Typography options used for several elements
 */

$misc = us_config( 'elements_misc' );

return array(
	'font' => array(
		'title' => __( 'Font', 'us' ),
		'type' => 'select',
		'options' => us_get_fonts(),
		'shortcode_options' => us_get_fonts( 'without_groups' ),
		'std' => 'body',
		'group' => __( 'Typography', 'us' ),
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
		'group' => __( 'Typography', 'us' ),
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
		'group' => __( 'Typography', 'us' ),
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
		'group' => __( 'Typography', 'us' ),
	),
	'font_size' => array(
		'title' => __( 'Font Size', 'us' ),
		'description' => $misc['desc_font_size'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
	),
	'font_size_tablets' => array(
		'title' => __( 'Size on Tablets', 'us' ),
		'description' => $misc['desc_font_size'],
		'type' => 'text',
		'std' => '',
		'cols' => 3,
		'group' => __( 'Typography', 'us' ),
		'context' => array( 'header' ),
	),
	'font_size_mobiles' => array(
		'title' => __( 'Font Size on Mobiles', 'us' ),
		'description' => $misc['desc_font_size'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
		'context' => array( 'header', 'grid' ),
	),
	'line_height' => array(
		'title' => __( 'Line height', 'us' ),
		'description' => $misc['desc_line_height'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
	),
	'line_height_tablets' => array(
		'title' => __( 'Line height on Tablets', 'us' ),
		'description' => $misc['desc_line_height'],
		'type' => 'text',
		'std' => '',
		'cols' => 3,
		'group' => __( 'Typography', 'us' ),
		'context' => array( 'header' ),
	),
	'line_height_mobiles' => array(
		'title' => __( 'Line height on Mobiles', 'us' ),
		'description' => $misc['desc_line_height'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
		'context' => array( 'header', 'grid' ),
	),
);
