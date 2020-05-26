<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Extending shortcode: vc_row
 */

vc_remove_param( 'vc_row', 'full_width' );
vc_remove_param( 'vc_row', 'full_height' );
vc_remove_param( 'vc_row', 'content_placement' );
vc_remove_param( 'vc_row', 'video_bg' );
vc_remove_param( 'vc_row', 'video_bg_url' );
vc_remove_param( 'vc_row', 'video_bg_parallax' );
vc_remove_param( 'vc_row', 'columns_placement' );
vc_remove_param( 'vc_row', 'equal_height' );
vc_remove_param( 'vc_row', 'parallax_speed_video' );
vc_remove_param( 'vc_row', 'parallax_speed_bg' );
vc_remove_param( 'vc_row', 'css_animation' );
vc_remove_param( 'vc_row', 'rtl_reverse' );

if ( ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_row', 'parallax' );
	vc_remove_param( 'vc_row', 'parallax_image' );
}

vc_update_shortcode_param(
	'vc_row', array(
		'heading' => __( 'Additional gap', 'us' ),
		'param_name' => 'gap',
		'type' => 'textfield',
		'description' => __( 'Examples:', 'us' ) . ' <span class="usof-example">2px</span>, <span class="usof-example">1.5rem</span>, <span class="usof-example">1vw</span>',
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 9,
		'group' => us_translate( 'Columns' ),
	)
);
vc_update_shortcode_param(
	'vc_row', array(
		'param_name' => 'el_class',
		'description' => '',
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 1,
		'group' => us_translate( 'Design Options', 'js_composer' ),
	)
);
vc_update_shortcode_param(
	'vc_row', array(
		'param_name' => 'el_id',
		'description' => '',
		'edit_field_class' => 'vc_col-sm-6',
		'group' => us_translate( 'Design Options', 'js_composer' ),
	)
);

vc_add_params(
	'vc_row', array(
	array(
		'param_name' => 'content_placement',
		'heading' => __( 'Columns Content Position', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			us_translate( 'Top' ) => 'top',
			us_translate( 'Middle' ) => 'middle',
			us_translate( 'Bottom' ) => 'bottom',
		),
		'std' => $config['atts']['content_placement'],
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 10,
		'group' => us_translate( 'Columns' ),
	),
	array(
		'param_name' => 'columns_type',
		'description' => __( 'Improves apperance of columns with background', 'us' ),
		'type' => 'checkbox',
		'value' => array( __( 'Add extra padding around columns content', 'us' ) => TRUE ),
		( ( $config['atts']['columns_type'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['columns_type'],
		'weight' => 8,
		'group' => us_translate( 'Columns' ),
	),
	array(
		'param_name' => 'columns_reverse',
		'description' => __( 'The last column will be shown on the top.', 'us' ) . ' ' . sprintf( __( 'Applied when the screen width is less than %s', 'us' ), '<a target="_blank" href="' . admin_url( 'admin.php?page=us-theme-options' ) . '#layout" title="' . __( 'edit in Theme Options', 'us' ) . '">' . us_get_option( 'columns_stacking_width' ) . '</a>' ),
		'type' => 'checkbox',
		'value' => array( __( 'Reverse order for columns stacking', 'us' ) => TRUE ),
		( ( $config['atts']['columns_reverse'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['columns_reverse'],
		'weight' => 7,
		'group' => us_translate( 'Columns' ),
	),
	array(
		'param_name' => 'height',
		'heading' => __( 'Row Height', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			__( 'Default from Theme Options', 'us' ) => 'default',
			__( 'Equals the content height', 'us' ) => 'auto',
			__( 'Small', 'us' ) => 'small',
			__( 'Medium', 'us' ) => 'medium',
			__( 'Large', 'us' ) => 'large',
			__( 'Huge', 'us' ) => 'huge',
			__( 'Full Screen', 'us' ) => 'full',
		),
		'std' => $config['atts']['height'],
		'weight' => 170,
	),
	array(
		'param_name' => 'valign',
		'heading' => __( 'Row Content Position', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			us_translate( 'Top' ) => 'top',
			us_translate( 'Middle' ) => 'center',
			us_translate( 'Bottom' ) => 'bottom',
		),
		'std' => $config['atts']['valign'],
		'dependency' => array( 'element' => 'height', 'value' => 'full' ),
		'weight' => 160,
	),
	array(
		'param_name' => 'width',
		'heading' => __( 'Full Width Content', 'us' ),
		'type' => 'checkbox',
		'value' => array( __( 'Stretch content of this row to the screen width', 'us' ) => 'full' ),
		( ( $config['atts']['width'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['width'],
		'weight' => 150,
	),
	array(
		'param_name' => 'color_scheme',
		'heading' => __( 'Row Color Style', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			__( 'Content colors', 'us' ) => '',
			__( 'Alternate Content colors', 'us' ) => 'alternate',
			__( 'Primary bg & White text', 'us' ) => 'primary',
			__( 'Secondary bg & White text', 'us' ) => 'secondary',
			__( 'Top Footer colors', 'us' ) => 'footer-top',
			__( 'Bottom Footer colors', 'us' ) => 'footer-bottom',
			__( 'Custom colors', 'us' ) => 'custom',
		),
		'std' => $config['atts']['color_scheme'],
		'weight' => 140,
	),
	array(
		'param_name' => 'us_bg_color',
		'heading' => __( 'Background Color', 'us' ),
		'type' => 'colorpicker',
		'std' => $config['atts']['us_bg_color'],
		'edit_field_class' => 'vc_col-sm-6',
		'dependency' => array( 'element' => 'color_scheme', 'value' => 'custom' ),
		'weight' => 130,
	),
	array(
		'param_name' => 'us_text_color',
		'heading' => __( 'Text Color', 'us' ),
		'type' => 'colorpicker',
		'std' => $config['atts']['us_text_color'],
		'dependency' => array( 'element' => 'color_scheme', 'value' => 'custom' ),
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 120,
	),
	array(
		'param_name' => 'us_bg_image_source',
		'heading' => __( 'Background Image', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			us_translate( 'None' ) => 'none',
			us_translate( 'Custom' ) => 'media',
			us_translate( 'Featured Image' ) => 'featured',
		),
		'std' => $config['atts']['us_bg_image_source'],
		'weight' => 110,
	),
	array(
		'param_name' => 'us_bg_image',
		'type' => 'attach_image',
		'std' => $config['atts']['us_bg_image'],
		'dependency' => array( 'element' => 'us_bg_image_source', 'value' => 'media' ),
		'weight' => 100,
	),
	array(
		'param_name' => 'us_bg_size',
		'heading' => __( 'Background Image Size', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			__( 'Fill Area', 'us' ) => 'cover',
			__( 'Fit to Area', 'us' ) => 'contain',
			__( 'Initial', 'us' ) => 'initial',
		),
		'std' => $config['atts']['us_bg_size'],
		'dependency' => array( 'element' => 'us_bg_image_source', 'value' => array( 'media', 'custom', 'featured' ) ),
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 90,
	),
	array(
		'param_name' => 'us_bg_pos',
		'heading' => __( 'Background Image Position', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			us_translate( 'Top Left' ) => 'top left',
			us_translate( 'Top' ) => 'top center',
			us_translate( 'Top Right' ) => 'top right',
			us_translate( 'Left' ) => 'center left',
			us_translate( 'Center' ) => 'center center',
			us_translate( 'Right' ) => 'center right',
			us_translate( 'Bottom Left' ) => 'bottom left',
			us_translate( 'Bottom' ) => 'bottom center',
			us_translate( 'Bottom Right' ) => 'bottom right',
		),
		'std' => $config['atts']['us_bg_pos'],
		'dependency' => array( 'element' => 'us_bg_image_source', 'value' => array( 'media', 'custom', 'featured' ) ),
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 85,
	),
	array(
		'param_name' => 'us_bg_parallax',
		'heading' => __( 'Parallax Effect', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			us_translate( 'None' ) => '',
			__( 'Vertical Parallax', 'us' ) => 'vertical',
			__( 'Horizontal Parallax', 'us' ) => 'horizontal',
			__( 'Fixed', 'us' ) => 'still',
		),
		'std' => $config['atts']['us_bg_parallax'],
		'dependency' => array( 'element' => 'us_bg_image_source', 'value' => array( 'media', 'custom', 'featured' ) ),
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 82,
	),
	array(
		'param_name' => 'us_bg_repeat',
		'heading' => __( 'Background Image Repeat', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			__( 'Repeat', 'us' ) => 'repeat',
			__( 'Horizontally', 'us' ) => 'repeat-x',
			__( 'Vertically', 'us' ) => 'repeat-y',
			us_translate( 'None' ) => 'no-repeat',
		),
		'std' => $config['atts']['us_bg_repeat'],
		'dependency' => array( 'element' => 'us_bg_image_source', 'value' => array( 'media', 'custom', 'featured' ) ),
		'edit_field_class' => 'vc_col-sm-6',
		'weight' => 80,
	),
	array(
		'param_name' => 'us_bg_parallax_width',
		'heading' => __( 'Parallax Background Width', 'us' ),
		'type' => 'dropdown',
		'value' => array(
			'110%' => '110',
			'120%' => '120',
			'130%' => '130',
			'140%' => '140',
			'150%' => '150',
		),
		'std' => $config['atts']['us_bg_parallax_width'],
		'dependency' => array( 'element' => 'us_bg_parallax', 'value' => 'horizontal' ),
		'weight' => 70,
	),
	array(
		'param_name' => 'us_bg_parallax_reverse',
		'type' => 'checkbox',
		'value' => array( __( 'Reverse Vertical Parallax Effect', 'us' ) => TRUE ),
		( ( $config['atts']['us_bg_parallax_reverse'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['us_bg_parallax_reverse'],
		'dependency' => array( 'element' => 'us_bg_parallax', 'value' => 'vertical' ),
		'weight' => 60,
	),
	array(
		'param_name' => 'us_bg_video',
		'heading' => __( 'Background Video', 'us' ),
		'description' => __( 'Link to YouTube, Vimeo or video file (mp4, webm, ogg)', 'us' ),
		'type' => 'textfield',
		'std' => $config['atts']['us_bg_video'],
		'weight' => 50,
	),
	array(
		'param_name' => 'us_bg_overlay_color',
		'heading' => __( 'Background Overlay', 'us' ),
		'type' => 'colorpicker',
		'std' => $config['atts']['us_bg_overlay_color'],
		'holder' => 'div',
		'weight' => 40,
	),
	array(
		'param_name' => 'sticky',
		'heading' => __( 'Sticky Row', 'us' ),
		'type' => 'checkbox',
		'value' => array( __( 'Fix this row at the top of a page during scroll', 'us' ) => TRUE ),
		( ( $config['atts']['sticky'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['sticky'],
		'weight' => 30,
	),
	array(
		'param_name' => 'sticky_disable_width',
		'heading' => __( 'Disable Sticky Row at width', 'us' ),
		'description' => __( 'When screen width is less than this value, sticky row becomes not sticky.', 'us' ),
		'type' => 'textfield',
		'std' => $config['atts']['sticky_disable_width'],
		'dependency' => array( 'element' => 'sticky', 'not_empty' => TRUE ),
		'weight' => 20,
	),
)
);
if ( class_exists( 'Ultimate_VC_Addons' ) ) {
	vc_add_param(
		'vc_row', array(
			'param_name' => 'us_notification',
			'type' => 'ult_param_heading',
			'text' => __( 'Background Image, Background Video, Background Overlay settings located below will override the settings located at "Background" and "Effect" tabs.', 'us' ),
			'edit_field_class' => 'ult-param-important-wrapper ult-dashicon vc_column vc_col-sm-12',
			'weight' => 110,
		)
	);
}

// Add option to set Rev Slider as row background
if ( class_exists( 'RevSlider' ) ) {
	$slider = new RevSlider();
	$arrSliders = $slider->getArrSliders();
	$revsliders = array();
	if ( $arrSliders ) {
		foreach ( $arrSliders as $slider ) {
			$revsliders[ $slider->getTitle() ] = $slider->getAlias();
		}
	}
	vc_add_param(
		'vc_row', array(
			'param_name' => 'us_bg_slider',
			'heading' => __( 'Background Slider', 'us' ),
			'description' => us_translate( 'Select your Revolution Slider.', 'js_composer' ),
			'type' => 'dropdown',
			'value' => array_merge( array( '– ' . us_translate( 'None' ) . ' –' => '' ), $revsliders ),
			'std' => $config['atts']['us_bg_slider'],
			'weight' => 45,
		)
	);
}

// Setting proper shortcode order in VC shortcodes listing
vc_map_update( 'vc_row', array( 'weight' => 390 ) );
