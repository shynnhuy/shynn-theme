<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Title', 'us' ),
	'category' => __( 'Post Elements', 'us' ),
	'icon' => 'fas fa-font',
	'params' => array_merge( array(

		'link' => array(
			'title' => us_translate( 'Link' ),
			'type' => 'radio',
			'options' => array(
				'post' => __( 'To a Post', 'us' ),
				'custom' => __( 'Custom', 'us' ),
				'none' => us_translate( 'None' ),
			),
			'grid_std' => 'post',
			'shortcode_std' => 'none',
		),
		'custom_link' => array(
			'placeholder' => us_translate( 'Enter the URL' ),
			'description' => $misc['desc_grid_custom_link'],
			'type' => 'link',
			'std' => array(),
			'shortcode_std' => '',
			'grid_classes' => 'desc_3',
			'show_if' => array( 'link', '=', 'custom' ),
		),
		'color_link' => array(
			'title' => __( 'Link Color', 'us' ),
			'type' => 'switch',
			'switch_text' => __( 'Inherit from text color', 'us' ),
			'std' => TRUE,
			'show_if' => array( 'link', '!=', 'none' ),
		),
		'align' => array(
			'title' => us_translate( 'Alignment' ),
			'type' => 'radio',
			'options' => array(
				'none' => us_translate( 'Default' ),
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'none',
			'admin_label' => TRUE,
		),
		'tag' => array(
			'title' => __( 'HTML tag', 'us' ),
			'type' => 'radio',
			'options' => $misc['html_tag_values'],
			'std' => 'h2',
			'admin_label' => TRUE,
		),

	), $typography_options, $design_options, $hover_options ),
);
