<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Date', 'us' ),
	'category' => __( 'Post Elements', 'us' ),
	'icon' => 'fas fa-calendar-alt',
	'params' => array_merge( array(

		'type' => array(
			'title' => us_translate( 'Show' ),
			'type' => 'radio',
			'options' => array(
				'published' => __( 'Date of creation', 'us' ),
				'modified' => __( 'Date of update', 'us' ),
			),
			'std' => 'published',
			'admin_label' => TRUE,
		),
		'format' => array(
			'title' => __( 'Format', 'us' ),
			'type' => 'select',
			'options' => array(
				'smart' => __( 'Human friendly', 'us' ),
				'default' => us_translate( 'Default' ) . ': ' . date_i18n( get_option( 'date_format' ) ),
				'jS F Y' => date_i18n( 'jS F Y' ),
				'j M, G:i' => date_i18n( 'j M, G:i' ),
				'm/d/Y' => date_i18n( 'm/d/Y' ),
				'j.m.y' => date_i18n( 'j.m.y' ),
				'custom' => __( 'Custom', 'us' ),
			),
			'std' => 'default',
			'admin_label' => TRUE,
		),
		'format_custom' => array(
			'description' => '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">' . __( 'Documentation on date and time formatting.', 'us' ) . '</a>',
			'type' => 'text',
			'std' => 'F j, Y',
			'classes' => 'for_above',
			'show_if' => array( 'format', '=', 'custom' ),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
		),
		'text_before' => array(
			'title' => __( 'Text before value', 'us' ),
			'type' => 'text',
			'std' => '',
		),

	), $typography_options, $design_options, $hover_options ),
);
