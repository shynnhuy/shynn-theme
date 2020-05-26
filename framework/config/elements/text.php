<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => us_translate( 'Text' ),
	'icon' => 'fas fa-font',
	'params' => array_merge( array(
		'text' => array(
			'title' => us_translate( 'Text' ),
			'type' => 'text',
			'std' => 'Some text',
		),
		'wrap' => array(
			'type' => 'switch',
			'switch_text' => __( 'Allow move content to the next line', 'us' ),
			'std' => FALSE,
			'classes' => 'for_above',
		),
		'link' => array(
			'title' => us_translate( 'Link' ),
			'placeholder' => us_translate( 'Enter the URL' ),
			'type' => 'link',
			'std' => array(),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
		),
		'color' => array(
			'title' => us_translate( 'Custom color' ),
			'type' => 'color',
			'std' => '',
		),
	), $typography_options, $design_options ),
);
