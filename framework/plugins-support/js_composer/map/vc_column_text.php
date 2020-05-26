<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Modifying shortcode: vc_column_text
 *
 * @var $shortcode string Current shortcode name
 * @var $config    array Shortcode's config
 */

vc_remove_param( 'vc_column_text', 'css_animation' );

vc_update_shortcode_param(
	'vc_column_text', array(
		'param_name' => 'content',
		'heading' => '',
		'weight' => 20,
	)
);
vc_update_shortcode_param(
	'vc_column_text', array(
		'param_name' => 'el_class',
		'description' => '',
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 10,
		'group' => us_translate( 'Design Options', 'js_composer' ),
	)
);
vc_update_shortcode_param(
	'vc_column_text', array(
		'param_name' => 'el_id',
		'description' => '',
		'edit_field_class' => 'vc_col-sm-6',
		'group' => us_translate( 'Design Options', 'js_composer' ),
	)
);

// Setting proper shortcode order in VC shortcodes listing
vc_map_update( 'vc_column_text', array( 'weight' => 380 ) );
