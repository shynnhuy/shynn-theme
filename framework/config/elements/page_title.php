<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Page Title', 'us' ),
	'icon' => 'fas fa-font',
	'params' => array_merge( array(

		'tag' => array(
			'title' => __( 'HTML tag', 'us' ),
			'type' => 'select',
			'options' => $misc['html_tag_values'],
			'std' => 'h1',
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
			'admin_label' => TRUE,
		),
		'color' => array(
			'title' => us_translate( 'Color' ),
			'type' => 'color',
			'std' => '',
		),
		'inline' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show the next text in the same line', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'align', '=', array( 'left', 'right' ) ),
		),
		'description' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show archive pages description', 'us' ),
			'std' => FALSE,
		),

	), $typography_options, $design_options ),
);
