<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Modifying shortcode: vc_video
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */

$misc = us_config( 'elements_misc' );

vc_remove_param( 'vc_video', 'title' );
vc_remove_param( 'vc_video', 'el_width' );
vc_remove_param( 'vc_video', 'el_aspect' );
vc_remove_param( 'vc_video', 'css_animation' );

vc_update_shortcode_param(
	'vc_video', array(
		'param_name' => 'el_class',
		'description' => '',
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 10,
		'group' => us_translate( 'Design Options', 'js_composer' ),
	)
);
vc_update_shortcode_param(
	'vc_video', array(
		'param_name' => 'el_id',
		'description' => '',
		'edit_field_class' => 'vc_col-sm-6',
		'group' => us_translate( 'Design Options', 'js_composer' ),
	)
);

vc_add_params(
	'vc_video', array(
		array(
			'param_name' => 'link',
			'heading' => __( 'Video link', 'us' ),
			'description' => sprintf( __( 'Check supported formats on %s', 'us' ), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress Codex</a>' ),
			'type' => 'textfield',
			'std' => $config['atts']['link'],
			'admin_label' => TRUE,
			'weight' => 60,
		),
		array(
			'param_name' => 'hide_controls',
			'type' => 'checkbox',
			'value' => array( __( 'Hide YouTube controls while watching', 'us' ) => TRUE ),
			( ( $config['atts']['hide_controls'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['hide_controls'],
			'weight' => 52,
		),
		array(
			'param_name' => 'hide_video_title',
			'type' => 'checkbox',
			'value' => array( __( 'Hide Vimeo video title (only if the owner allows)', 'us' ) => TRUE ),
			( ( $config['atts']['hide_video_title'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['hide_video_title'],
			'weight' => 53,
		),
		array(
			'param_name' => 'ratio',
			'heading' => __( 'Aspect Ratio', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				'21:9' => '21x9',
				'16:9' => '16x9',
				'4:3' => '4x3',
				'3:2' => '3x2',
				'1:1' => '1x1',
			),
			'std' => $config['atts']['ratio'],
			'weight' => 50,
		),
		array(
			'param_name' => 'max_width',
			'heading' => __( 'Max Width in pixels', 'us' ),
			'description' => $misc['desc_width'],
			'type' => 'textfield',
			'std' => $config['atts']['max_width'],
			'admin_label' => TRUE,
			'weight' => 40,
		),
		array(
			'param_name' => 'align',
			'heading' => __( 'Video Alignment', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				us_translate( 'Left' ) => 'left',
				us_translate( 'Center' ) => 'center',
				us_translate( 'Right' ) => 'right',
			),
			'std' => $config['atts']['align'],
			'dependency' => array( 'element' => 'max_width', 'not_empty' => TRUE ),
			'weight' => 30,
		),
	)
);
