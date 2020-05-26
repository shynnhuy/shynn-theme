<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Vertical Wrapper', 'us' ),
	'category' => __( 'Post Elements', 'us' ),
	'icon' => 'fas fa-ellipsis-v',
	'params' => array_merge( array(

		'alignment' => array(
			'title' => __( 'Content Horizontal Alignment', 'us' ),
			'type' => 'radio',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'left',
			'grid_cols' => 2,
		),
		'valign' => array(
			'title' => __( 'Content Vertical Alignment', 'us' ),
			'type' => 'radio',
			'options' => array(
				'top' => us_translate( 'Top' ),
				'middle' => us_translate( 'Middle' ),
				'bottom' => us_translate( 'Bottom' ),
			),
			'std' => 'top',
			'cols' => 2,
			'context' => array( 'grid' ),
		),
		'bg_gradient' => array(
			'switch_text' => __( 'Add a transparent gradient to the background', 'us' ),
			'type' => 'switch',
			'std' => FALSE,
			'context' => array( 'grid' ),
		),
		'color_grad' => array(
			'type' => 'color',
			'std' => 'rgba(30,30,30,0.8)',
			'show_if' => array( 'bg_gradient', '=', TRUE ),
			'context' => array( 'grid' ),
		),

	), $design_options, $hover_options ),
);
