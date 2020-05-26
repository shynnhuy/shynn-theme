<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$color_bg = us_get_color( 'color_menu_button_bg', TRUE );
$color_text = us_get_color( 'color_menu_button_text' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Cart', 'us' ),
	'icon' => 'fas fa-shopping-cart',
	'place_if' => class_exists( 'woocommerce' ),
	'params' => array_merge( array(

		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => 'fas|shopping-cart',
		),
		'size' => array(
			'title' => __( 'Icon Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '20px',
			'cols' => 3,
		),
		'size_tablets' => array(
			'title' => __( 'Icon Size on Tablets', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '20px',
			'cols' => 3,
		),
		'size_mobiles' => array(
			'title' => __( 'Icon Size on Mobiles', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '20px',
			'cols' => 3,
		),
		'quantity_color_bg' => array(
			'title' => __( 'Quantity Badge Background', 'us' ),
			'type' => 'color',
			'std' => $color_bg,
			'cols' => 2,
		),
		'quantity_color_text' => array(
			'title' => __( 'Quantity Badge Text', 'us' ),
			'type' => 'color',
			'std' => $color_text,
			'cols' => 2,
		),
		'vstretch' => array(
			'title' => us_translate( 'Height' ),
			'type' => 'switch',
			'switch_text' => __( 'Stretch to the full available height', 'us' ),
			'std' => TRUE,
		),
		'dropdown_effect' => array(
			'title' => __( 'Dropdown Effect', 'us' ),
			'type' => 'select',
			'options' => $misc['dropdown_effect_values'],
			'std' => 'height',
		),

	), $design_options ),
);
