<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Content', 'us' ),
	'category' => __( 'Post Elements', 'us' ),
	'icon' => 'fas fa-align-justify',
	'shortcode_post_type' => array( 'us_page_block' ),
	'params' => array_merge( array(

		'type' => array(
			'title' => us_translate( 'Show' ),
			'type' => 'select',
			'options' => array(
				'excerpt_content' => __( 'Excerpt, if it\'s empty, show part of a content', 'us' ),
				'excerpt_only' => __( 'Excerpt, if it\'s empty, show nothing', 'us' ),
				'part_content' => __( 'Part of a content', 'us' ),
				'full_content' => __( 'Full content', 'us' ),
			),
			'std' => 'excerpt_content',
			'admin_label' => TRUE,
		),
		'force_fullwidth_rows' => array(
			'switch_text' => __( 'Stretch content of Rows to the full width', 'us' ),
			'type' => 'switch',
			'std' => FALSE,
			'classes' => 'for_above',
			'show_if' => array( 'type', '=', 'full_content' ),
		),
		'length' => array(
			'title' => __( 'Amount of words', 'us' ),
			'description' => __( 'All HTML tags of a content will be stripped', 'us' ),
			'type' => 'slider',
			'std' => '30',
			'options' => array(
				'' => array(
					'min' => 1,
					'max' => 100,
				),
			),
			'show_if' => array( 'type', 'in', array( 'excerpt_content', 'part_content' ) ),
		),

	), $typography_options, $design_options, $hover_options ),
);
