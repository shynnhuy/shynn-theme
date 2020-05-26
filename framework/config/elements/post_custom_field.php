<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$custom_fields_options = array_merge( us_get_custom_fields(), array( 'custom' => __( 'Custom Field', 'us' ) ) );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Custom Field', 'us' ),
	'category' => __( 'Post Elements', 'us' ),
	'icon' => 'fas fa-cog',
	'params' => array_merge( array(
		'key' => array(
			'title' => us_translate( 'Show' ),
			'type' => 'select',
			'options' => $custom_fields_options,
			'std' => key( $custom_fields_options ),
			'admin_label' => TRUE,
		),
		'custom_key' => array(
			'description' => __( 'Enter custom field name to retrieve its value.', 'us' ),
			'type' => 'text',
			'std' => '',
			'admin_label' => TRUE,
			'classes' => 'for_above',
			'show_if' => array( 'key', '=', 'custom' ),
		),
		'hide_empty' => array(
			'type' => 'switch',
			'switch_text' => __( 'Hide this element if its value is empty', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'key', '!=', 'us_testimonial_rating' ),
		),
		'link' => array(
			'title' => us_translate( 'Link' ),
			'type' => 'radio',
			'options' => array(
				'post' => __( 'To a Post', 'us' ),
				'custom' => __( 'Custom', 'us' ),
				'none' => us_translate( 'None' ),
			),
			'std' => 'none',
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
		'thumbnail_size' => array(
			'title' => __( 'Image Size', 'us' ),
			'description' => $misc['desc_img_sizes'],
			'type' => 'select',
			'options' => us_image_sizes_select_values(),
			'std' => 'large',
			'show_if' => array( 'key', '!=', 'us_testimonial_rating' ),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
			'show_if' => array( 'key', '!=', 'us_testimonial_rating' ),
		),
		'text_before' => array(
			'title' => __( 'Text before value', 'us' ),
			'type' => 'text',
			'std' => '',
		),

	), $typography_options, $design_options, $hover_options ),
);
