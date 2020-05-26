<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$social_links = array_merge( us_config( 'social_links' ), array( 'custom' => __( 'Custom Icon', 'us' ) ) );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Social Links', 'us' ),
	'icon' => 'icon-wpb-balloon-facebook-left',
	'params' => array_merge( array(

		// General
		'items' => array(
			'type' => 'group',
			'is_sortable' => TRUE,
			'params' => array(
				'type' => array(
					'shortcode_title' => us_translate( 'Type' ),
					'type' => 'select',
					'is_advanced' => TRUE,
					'options' => $social_links,
					'std' => 's500px',
					'cols' => 2,
					'classes' => 'for_social',
					'admin_label' => TRUE,
				),
				'url' => array(
					'shortcode_title' => us_translate( 'Enter the URL' ),
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'text',
					'std' => '',
					'cols' => 2,
				),
				'custom_start' => array(
					'type' => 'wrapper_start',
					'show_if' => array( 'type', '=', 'custom' ),
				),
				'icon' => array(
					'type' => 'icon',
					'std' => 'fab|apple',
					'show_if' => array( 'type', '=', 'custom' ),
				),
				'title' => array(
					'shortcode_title' => us_translate( 'Title' ),
					'placeholder' => us_translate( 'Title' ),
					'type' => 'text',
					'std' => '',
					'cols' => 2,
					'show_if' => array( 'type', '=', 'custom' ),
				),
				'color' => array(
					'shortcode_title' => us_translate( 'Color' ),
					'type' => 'color',
					'std' => '#999',
					'cols' => 2,
					'show_if' => array( 'type', '=', 'custom' ),
				),
				'custom_end' => array(
					'type' => 'wrapper_end',
				),
			),
			'std' => array(
				array(
					'type' => 'facebook',
					'url' => '#',
				),
				array(
					'type' => 'twitter',
					'url' => '#',
				),
			),
		),

		// Appearance
		'style' => array(
			'title' => __( 'Icons Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'default' => __( 'Simple', 'us' ),
				'outlined' => __( 'With outline', 'us' ),
				'solid' => __( 'With light background', 'us' ),
				'colored' => __( 'With colored background', 'us' ),
			),
			'std' => 'default',
			'cols' => 2,
			'admin_label' => TRUE,
			'group' => us_translate( 'Appearance' ),
		),
		'shape' => array(
			'title' => __( 'Icons Shape', 'us' ),
			'type' => 'select',
			'options' => array(
				'square' => __( 'Square', 'us' ),
				'rounded' => __( 'Rounded Square', 'us' ),
				'circle' => __( 'Circle', 'us' ),
			),
			'std' => 'square',
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'color' => array(
			'title' => __( 'Icons Color', 'us' ),
			'type' => 'select',
			'options' => array(
				'brand' => __( 'Default brands colors', 'us' ),
				'text' => __( 'Text (theme color)', 'us' ),
				'link' => __( 'Link (theme color)', 'us' ),
			),
			'std' => 'brand',
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'hover' => array(
			'title' => __( 'Hover Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'fade' => __( 'Fade', 'us' ),
				'slide' => __( 'Slide', 'us' ),
				'none' => us_translate( 'None' ),
			),
			'std' => 'fade',
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'size' => array(
			'title' => us_translate( 'Size' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '20px',
			'shortcode_cols' => 2,
			'header_cols' => 3,
			'admin_label' => TRUE,
			'group' => us_translate( 'Appearance' ),
		),
		'size_tablets' => array(
			'title' => __( 'Size on Tablets', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '18px',
			'cols' => 3,
			'context' => array( 'header' ),
			'group' => us_translate( 'Appearance' ),
		),
		'size_mobiles' => array(
			'title' => __( 'Size on Mobiles', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '16px',
			'cols' => 3,
			'context' => array( 'header' ),
			'group' => us_translate( 'Appearance' ),
		),
		'gap' => array(
			'title' => __( 'Gap between Icons', 'us' ),
			'shortcode_description' => __( 'Examples:', 'us' ) . ' <span class="usof-example">2px</span>, <span class="usof-example">0.1em</span>',
			'type' => 'slider',
			'std' => '0',
			'options' => array(
				'px' => array(
					'min' => 0,
					'max' => 50,
				),
				'em' => array(
					'min' => 0.0,
					'max' => 2.0,
					'step' => 0.1,
				),
			),
			'shortcode_cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'align' => array(
			'title' => us_translate( 'Alignment' ),
			'type' => 'select',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'left',
			'context' => array( 'shortcode' ),
			'group' => us_translate( 'Appearance' ),
		),

	), $design_options ),
);
