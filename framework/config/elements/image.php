<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => us_translate( 'Image' ),
	'icon' => 'icon-wpb-single-image',
	'params' => array_merge( array(

		// General
		'img' => array(
			'type' => 'upload',
			'extension' => 'png,jpg,jpeg,gif,svg',
			'context' => array( 'header', 'grid' ),
		),
		'image' => array(
			'type' => 'upload',
			'extension' => 'png,jpg,jpeg,gif,svg',
			'shortcode_cols' => 2,
			'context' => array( 'shortcode' ),
		),
		'size' => array(
			'title' => __( 'Image Size', 'us' ),
			'description' => $misc['desc_img_sizes'],
			'type' => 'select',
			'options' => us_image_sizes_select_values(),
			'std' => 'large',
			'cols' => 2,
			'admin_label' => TRUE,
			'context' => array( 'shortcode' ),
		),
		'align' => array(
			'title' => us_translate( 'Alignment' ),
			'type' => 'select',
			'options' => array(
				'none' => us_translate( 'None' ),
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'none',
			'cols' => 2,
			'admin_label' => TRUE,
			'context' => array( 'shortcode' ),
		),
		'style' => array(
			'title' => __( 'Image Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'None' ),
				'circle' => __( 'Circle', 'us' ),
				'outlined' => __( 'Outlined', 'us' ),
				'shadow-1' => __( 'Simple Shadow', 'us' ),
				'shadow-2' => __( 'Colored Shadow', 'us' ),
				'phone6-1' => __( 'Phone 6 Black Realistic', 'us' ),
				'phone6-2' => __( 'Phone 6 White Realistic', 'us' ),
				'phone6-3' => __( 'Phone 6 Black Flat', 'us' ),
				'phone6-4' => __( 'Phone 6 White Flat', 'us' ),
			),
			'std' => '',
			'cols' => 2,
			'context' => array( 'shortcode' ),
		),
		'meta' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show image title and description', 'us' ),
			'std' => FALSE,
			'context' => array( 'shortcode' ),
		),
		'meta_style' => array(
			'title' => __( 'Title and Description Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'simple' => __( 'Below the image', 'us' ),
				'modern' => __( 'Over the image', 'us' ),
			),
			'std' => 'simple',
			'show_if' => array( 'meta', '!=', FALSE ),
			'context' => array( 'shortcode' ),
		),
		'onclick' => array(
			'title' => __( 'On click action', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'None' ),
				'lightbox' => __( 'Open original image in a popup', 'us' ),
				'custom_link' => __( 'Open custom link', 'us' ),
			),
			'std' => '',
			'context' => array( 'shortcode' ),
		),
		'link' => array(
			'header_title' => us_translate( 'Link' ),
			'placeholder' => us_translate( 'Enter the URL' ),
			'type' => 'link',
			'std' => array(),
			'shortcode_std' => '',
			'shortcode_show_if' => array( 'onclick', '=', 'custom_link' ),
		),
		'img_transparent' => array(
			'title' => __( 'Different Image for Transparent Header', 'us' ),
			'type' => 'upload',
			'extension' => 'png,jpg,jpeg,gif,svg',
			'context' => array( 'header' ),
		),
		'animate' => array(
			'title' => __( 'Animation', 'us' ),
			'description' => __( 'Selected animation will be applied to this element, when it enters into the browsers viewport.', 'us' ),
			'type' => 'select',
			'options' => array(
				'' => us_translate( 'None' ),
				'fade' => __( 'Fade', 'us' ),
				'afc' => __( 'Appear From Center', 'us' ),
				'afl' => __( 'Appear From Left', 'us' ),
				'afr' => __( 'Appear From Right', 'us' ),
				'afb' => __( 'Appear From Bottom', 'us' ),
				'aft' => __( 'Appear From Top', 'us' ),
				'hfc' => __( 'Height From Center', 'us' ),
				'wfc' => __( 'Width From Center', 'us' ),
			),
			'std' => '',
			'admin_label' => TRUE,
			'context' => array( 'shortcode' ),
		),
		'animate_delay' => array(
			'title' => __( 'Animation Delay (in seconds)', 'us' ),
			'type' => 'text',
			'std' => '',
			'show_if' => array( 'animate', '!=', '' ),
			'admin_label' => TRUE,
			'context' => array( 'shortcode' ),
		),

		// Sizes
		'heading_1' => array(
			'title' => __( 'Default Sizes', 'us' ),
			'type' => 'heading',
			'group' => __( 'Sizes', 'us' ),
			'context' => array( 'header' ),
		),
		'height' => array(
			'title' => us_translate( 'Height' ),
			'type' => 'slider',
			'std' => '35px',
			'options' => array(
				'px' => array(
					'min' => 20,
					'max' => 300,
				),
				'vh' => array(
					'min' => 1,
					'max' => 20,
				),
			),
			'group' => __( 'Sizes', 'us' ),
			'context' => array( 'header' ),
		),
		'height_tablets' => array(
			'title' => __( 'Height on Tablets', 'us' ),
			'type' => 'slider',
			'std' => '30px',
			'options' => array(
				'px' => array(
					'min' => 20,
					'max' => 300,
				),
				'vh' => array(
					'min' => 1,
					'max' => 20,
				),
			),
			'cols' => 2,
			'group' => __( 'Sizes', 'us' ),
			'context' => array( 'header' ),
		),
		'height_mobiles' => array(
			'title' => __( 'Height on Mobiles', 'us' ),
			'type' => 'slider',
			'std' => '20px',
			'options' => array(
				'px' => array(
					'min' => 20,
					'max' => 300,
				),
				'vh' => array(
					'min' => 1,
					'max' => 20,
				),
			),
			'cols' => 2,
			'group' => __( 'Sizes', 'us' ),
			'context' => array( 'header' ),
		),
		'heading_2' => array(
			'title' => __( 'Sizes in the Sticky Header', 'us' ),
			'type' => 'heading',
			'group' => __( 'Sizes', 'us' ),
		),
		'height_sticky' => array(
			'title' => us_translate( 'Height' ),
			'type' => 'slider',
			'std' => '35px',
			'options' => array(
				'px' => array(
					'min' => 20,
					'max' => 300,
				),
				'vh' => array(
					'min' => 1,
					'max' => 20,
				),
			),
			'group' => __( 'Sizes', 'us' ),
			'context' => array( 'header' ),
		),
		'height_sticky_tablets' => array(
			'title' => __( 'Height on Tablets', 'us' ),
			'type' => 'slider',
			'std' => '30px',
			'options' => array(
				'px' => array(
					'min' => 20,
					'max' => 300,
				),
				'vh' => array(
					'min' => 1,
					'max' => 20,
				),
			),
			'cols' => 2,
			'group' => __( 'Sizes', 'us' ),
			'context' => array( 'header' ),
		),
		'height_sticky_mobiles' => array(
			'title' => __( 'Height on Mobiles', 'us' ),
			'type' => 'slider',
			'std' => '20px',
			'options' => array(
				'px' => array(
					'min' => 20,
					'max' => 300,
				),
				'vh' => array(
					'min' => 1,
					'max' => 20,
				),
			),
			'cols' => 2,
			'group' => __( 'Sizes', 'us' ),
			'context' => array( 'header' ),
		),

	), $design_options ),
);
