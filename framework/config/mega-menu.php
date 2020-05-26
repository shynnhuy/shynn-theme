<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Mega Menu Settings options
 *
 * @filter us_config_mega-menu
 */

return array(
	'columns' => array(
		'title' => __( 'Dropdown Columns', 'us' ),
		'type' => 'radio',
		'options' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
		),
		'std' => '1',
	),
	'width' => array(
		'title' => __( 'Dropdown Width', 'us' ),
		'type' => 'radio',
		'options' => array(
			'full' => __( 'Full Width', 'us' ),
			'custom' => __( 'Custom Width', 'us' ),
		),
		'std' => 'full',
		'show_if' => array( 'columns', '!=', '1' ),
	),
	'custom_width' => array(
		'type' => 'slider',
		'std' => '600px',
		'options' => array(
			'px' => array(
				'min' => 200,
				'max' => 1000,
			),
			'rem' => array(
				'min' => 10,
				'max' => 60,
			),
			'em' => array(
				'min' => 10,
				'max' => 60,
			),
			'vw' => array(
				'min' => 20,
				'max' => 100,
			),
		),
		'show_if' => array(
			array( 'columns', '!=', '1' ),
			'and',
			array( 'width', '=', 'custom' ),
		),
	),
	'direction' => array(
		'title' => __( 'Dropdown Direction', 'us' ),
		'type' => 'switch',
		'switch_text' => __( 'Invert dropdown direction', 'us' ),
		'std' => FALSE,
		'show_if' => array(
			array( 'columns', '=', '1' ),
			'or',
			array( 'width', '!=', 'full' ),
		),
	),
	'padding' => array(
		'title' => __( 'Dropdown Padding', 'us' ),
		'type' => 'slider',
		'std' => '1rem',
		'options' => array(
			'px' => array(
				'min' => 0,
				'max' => 50,
			),
			'rem' => array(
				'min' => 0.0,
				'max' => 6.0,
				'step' => 0.1,
			),
			'em' => array(
				'min' => 0.0,
				'max' => 6.0,
				'step' => 0.1,
			),
			'vw' => array(
				'min' => 0,
				'max' => 10,
			),
			'vh' => array(
				'min' => 0,
				'max' => 10,
			),
		),
	),
	'color_bg' => array(
		'type' => 'color',
		'title' => __( 'Background Color', 'us' ),
		'std' => '',
	),
	'color_text' => array(
		'type' => 'color',
		'title' => __( 'Text Color', 'us' ),
		'std' => '',
	),
	'bg_image' => array(
		'title' => __( 'Background Image', 'us' ),
		'type' => 'upload',
	),
	'wrapper_bg_start' => array(
		'type' => 'wrapper_start',
		'classes' => 'force_right',
		'show_if' => array( 'bg_image', '!=', '' ),
	),
	'bg_image_size' => array(
		'title' => __( 'Background Image Size', 'us' ),
		'type' => 'radio',
		'options' => array(
			'cover' => __( 'Fill Area', 'us' ),
			'contain' => __( 'Fit to Area', 'us' ),
			'initial' => __( 'Initial', 'us' ),
		),
		'std' => 'cover',
		'classes' => 'width_full',
	),
	'bg_image_repeat' => array(
		'title' => __( 'Background Image Repeat', 'us' ),
		'type' => 'radio',
		'options' => array(
			'repeat' => __( 'Repeat', 'us' ),
			'repeat-x' => __( 'Horizontally', 'us' ),
			'repeat-y' => __( 'Vertically', 'us' ),
			'no-repeat' => us_translate( 'None' ),
		),
		'std' => 'repeat',
		'classes' => 'width_full',
	),
	'bg_image_position' => array(
		'title' => __( 'Background Image Position', 'us' ),
		'type' => 'radio',
		'options' => array(
			'top left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
			'top center' => '<span class="dashicons dashicons-arrow-up-alt"></span>',
			'top right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
			'center left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
			'center center' => '<span class="dashicons dashicons-marker"></span>',
			'center right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
			'bottom left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
			'bottom center' => '<span class="dashicons dashicons-arrow-down-alt"></span>',
			'bottom right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
		),
		'std' => 'top left',
		'classes' => 'bgpos width_full',
	),
	'wrapper_bg_end' => array(
		'type' => 'wrapper_end',
	),
);
