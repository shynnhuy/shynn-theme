<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$taxonomies_options = us_get_taxonomies();

$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Post Prev/Next Navigation', 'us' ),
	'category' => __( 'Post Elements', 'us' ),
	'icon' => 'fas fa-exchange',
	'params' => array_merge( array(

		'layout' => array(
			'title' => __( 'Layout', 'us' ),
			'type' => 'radio',
			'options' => array(
				'simple' => __( 'Simple links', 'us' ),
				'sided' => __( 'On sides of the screen', 'us' ),
			),
			'std' => 'simple',
			'admin_label' => TRUE,
		),
		'invert' => array(
			'type' => 'switch',
			'switch_text' => __( 'Invert position of previous and next', 'us' ),
			'std' => FALSE,
		),
		'in_same_term' => array(
			'type' => 'switch',
			'switch_text' => __( 'Navigate within the specified taxonomy', 'us' ),
			'std' => FALSE,
		),
		'taxonomy' => array(
			'type' => 'select',
			'options' => $taxonomies_options,
			'std' => key( $taxonomies_options ),
			'classes' => 'for_above',
			'show_if' => array( 'in_same_term', '!=', FALSE ),
		),

	), $design_options ),
);
