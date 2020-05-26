<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => us_translate( 'Search' ),
	'icon' => 'fas fa-search',
	'params' => array_merge( array(

		'text' => array(
			'title' => __( 'Placeholder', 'us' ),
			'type' => 'text',
			'std' => us_translate( 'Search' ),
		),
		'layout' => array(
			'title' => __( 'Layout', 'us' ),
			'type' => 'radio',
			'options' => array(
				'simple' => __( 'Simple', 'us' ),
				'modern' => __( 'Modern', 'us' ),
				'fullwidth' => __( 'Full Width', 'us' ),
				'fullscreen' => __( 'Full Screen', 'us' ),
			),
			'std' => 'fullwidth',
		),
		'field_width' => array(
			'title' => __( 'Field Width', 'us' ),
			'type' => 'slider',
			'std' => '240px',
			'options' => array(
				'px' => array(
					'min' => 200,
					'max' => 800,
				),
				'rem' => array(
					'min' => 10,
					'max' => 60,
				),
				'vw' => array(
					'min' => 10,
					'max' => 60,
				),
			),
			'cols' => 2,
			'show_if' => array( 'layout', 'in', array( 'simple', 'modern' ) ),
		),
		'field_width_tablets' => array(
			'title' => __( 'Field Width on Tablets', 'us' ),
			'type' => 'slider',
			'std' => '200px',
			'options' => array(
				'px' => array(
					'min' => 200,
					'max' => 600,
				),
				'rem' => array(
					'min' => 10,
					'max' => 40,
				),
				'vw' => array(
					'min' => 10,
					'max' => 40,
				),
			),
			'cols' => 2,
			'show_if' => array( 'layout', 'in', array( 'simple', 'modern' ) ),
		),
		'product_search' => array(
			'type' => 'switch',
			'switch_text' => __( 'Search Shop Products only', 'us' ),
			'std' => FALSE,
			'place_if' => class_exists( 'woocommerce' ),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => 'fas|search',
			'group' => __( 'Icon', 'us' ),
		),
		'icon_size' => array(
			'title' => __( 'Icon Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '18px',
			'cols' => 3,
			'group' => __( 'Icon', 'us' ),
		),
		'icon_size_tablets' => array(
			'title' => __( 'Icon Size on Tablets', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '18px',
			'cols' => 3,
			'group' => __( 'Icon', 'us' ),
		),
		'icon_size_mobiles' => array(
			'title' => __( 'Icon Size on Mobiles', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '18px',
			'cols' => 3,
			'group' => __( 'Icon', 'us' ),
		),

	), $design_options ),
);
