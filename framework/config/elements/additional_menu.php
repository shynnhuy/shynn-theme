<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Links Menu', 'us' ),
	'icon' => 'fas fa-bars',
	'params' => array_merge( array(

		'source' => array(
			'title' => us_translate( 'Menu' ),
			'description' => $misc['desc_menu_select'],
			'type' => 'select',
			'options' => us_get_nav_menus(),
			'std' => '',
		),
		'size' => array(
			'title' => __( 'Font Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 3,
			'group' => __( 'Sizes', 'us' ),
		),
		'size_tablets' => array(
			'title' => __( 'Font Size on Tablets', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 3,
			'group' => __( 'Sizes', 'us' ),
		),
		'size_mobiles' => array(
			'title' => __( 'Font Size on Mobiles', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 3,
			'group' => __( 'Sizes', 'us' ),
		),
		'indents' => array(
			'title' => __( 'Items Indents', 'us' ),
			'type' => 'slider',
			'std' => '10px',
			'options' => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
					'step' => 2,
				),
				'rem' => array(
					'min' => 0.0,
					'max' => 6.0,
					'step' => 0.1,
				),
				'em' => array(
					'min' => 0.0,
					'max' => 6.0,
					'step' => 0.1,
				),
				'vw' => array(
					'min' => 0,
					'max' => 20,
				),
			),
			'group' => __( 'Sizes', 'us' ),
		),
		'indents_tablets' => array(
			'title' => __( 'Items Indents on Tablets', 'us' ),
			'type' => 'slider',
			'std' => '10px',
			'options' => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
					'step' => 2,
				),
				'rem' => array(
					'min' => 0.0,
					'max' => 6.0,
					'step' => 0.1,
				),
				'em' => array(
					'min' => 0.0,
					'max' => 6.0,
					'step' => 0.1,
				),
				'vw' => array(
					'min' => 0,
					'max' => 20,
				),
			),
			'cols' => 2,
			'group' => __( 'Sizes', 'us' ),
		),
		'indents_mobiles' => array(
			'title' => __( 'Items Indents on Mobiles', 'us' ),
			'type' => 'slider',
			'std' => '10px',
			'options' => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
					'step' => 2,
				),
				'rem' => array(
					'min' => 0.0,
					'max' => 6.0,
					'step' => 0.1,
				),
				'em' => array(
					'min' => 0.0,
					'max' => 6.0,
					'step' => 0.1,
				),
				'vw' => array(
					'min' => 0,
					'max' => 20,
				),
			),
			'cols' => 2,
			'group' => __( 'Sizes', 'us' ),
		),

	), $design_options ),
);
