<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$receiver_email = get_option( 'admin_email' );
$body_fontsize = us_get_option( 'body_fontsize', '16px' );
$btn_styles = us_get_btn_styles();

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

// Default Form Fields
$default_fields = array(
	array(
		'type' => 'text',
		'label' => '',
		'placeholder' => us_translate( 'Name' ),
	),
	array(
		'type' => 'email',
		'label' => '',
		'placeholder' => us_translate( 'Email' ),
	),
	array(
		'type' => 'textarea',
		'label' => '',
		'placeholder' => us_translate( 'Message' ),
	),
);

return array(
	'title' => __( 'Contact Form', 'us' ),
	'icon' => 'fas fa-envelope',
	'params' => array_merge(

		array(
			// Fields
			'items' => array(
				'type' => 'group',
				'group' => __( 'Fields', 'us' ),
				'is_sortable' => TRUE,
				'params' => array(
					'type' => array(
						'title' => us_translate( 'Type' ),
						'type' => 'select',
						'options' => array(
							'text' => us_translate( 'Text' ) . ' ' . __( '(single line)', 'us' ),
							'textarea' => us_translate( 'Text' ) . ' ' . __( '(multiple lines)', 'us' ),
							'email' => us_translate( 'Email' ),
							'select' => __( 'Dropdown', 'us' ),
							'checkboxes' => __( 'Checkboxes', 'us' ),
							'radio' => __( 'Radio buttons', 'us' ),
							'info' => us_translate( 'Text Block', 'js_composer' ),
							'agreement' => __( 'Agreement checkbox', 'us' ),
							'captcha' => __( 'Captcha', 'us' ),
						),
						'std' => 'text',
						'admin_label' => TRUE,
					),
					'label' => array(
						'title' => us_translate( 'Title' ),
						'description' => __( 'Shown above the field', 'us' ),
						'type' => 'text',
						'std' => '',
						'cols' => 2,
						'show_if' => array( 'type', '!=', 'info' ),
						'admin_label' => TRUE,
					),
					'description' => array(
						'title' => us_translate( 'Description' ),
						'description' => __( 'Shown below the field', 'us' ),
						'type' => 'text',
						'std' => '',
						'cols' => 2,
						'show_if' => array( 'type', '!=', 'info' ),
					),
					'placeholder' => array(
						'title' => __( 'Placeholder', 'us' ),
						'description' => __( 'Shown inside the field', 'us' ),
						'type' => 'text',
						'std' => '',
						'show_if' => array( 'type', 'in', array( 'text', 'email', 'textarea' ) ),
						'admin_label' => TRUE,
					),
					'values' => array(
						'title' => __( 'Values', 'us' ),
						'description' => __( 'Each value on a new line', 'us' ),
						'type' => 'textarea',
						'encoded' => TRUE,
						'std' => '',
						'show_if' => array( 'type', 'in', array( 'select', 'checkboxes', 'radio' ) ),
					),
					'value' => array(
						'title' => us_translate( 'Text' ),
						'type' => 'text',
						'std' => '',
						'show_if' => array( 'type', 'in', array( 'info', 'agreement' ) ),
					),
					'required' => array(
						'switch_text' => __( 'Required field', 'us' ),
						'type' => 'switch',
						'std' => FALSE,
						'show_if' => array(
							'type',
							'in',
							array( 'text', 'email', 'textarea', 'checkboxes' ),
						),
					),
					'icon' => array(
						'title' => __( 'Icon', 'us' ),
						'type' => 'icon',
						'std' => '',
						'show_if' => array(
							'type',
							'in',
							array( 'text', 'email', 'textarea', 'select', 'captcha' ),
						),
					),
				),
				'std' => urlencode( json_encode( $default_fields ) ),
			),

			// Button
			'button_text' => array(
				'title' => __( 'Button Label', 'us' ),
				'type' => 'text',
				'std' => us_translate( 'Submit' ),
				'group' => __( 'Button', 'us' ),
			),
			'button_style' => array(
				'title' => us_translate( 'Style' ),
				'description' => $misc['desc_btn_styles'],
				'type' => 'select',
				'options' => $btn_styles,
				'std' => '1',
				'group' => __( 'Button', 'us' ),
			),
			'button_size' => array(
				'title' => us_translate( 'Size' ),
				'description' => $misc['desc_font_size'],
				'type' => 'text',
				'std' => $body_fontsize,
				'cols' => 2,
				'group' => __( 'Button', 'us' ),
			),
			'button_size_mobiles' => array(
				'title' => __( 'Size on Mobiles', 'us' ),
				'description' => $misc['desc_font_size'],
				'type' => 'text',
				'std' => '',
				'cols' => 2,
				'group' => __( 'Button', 'us' ),
			),
			'button_fullwidth' => array(
				'type' => 'switch',
				'switch_text' => __( 'Stretch to the full width', 'us' ),
				'std' => FALSE,
				'group' => __( 'Button', 'us' ),
			),
			'button_align' => array(
				'title' => __( 'Button Alignment', 'us' ),
				'type' => 'select',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'center' => us_translate( 'Center' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'left',
				'group' => __( 'Button', 'us' ),
			),
			'icon' => array(
				'title' => __( 'Icon', 'us' ),
				'type' => 'icon',
				'std' => '',
				'group' => __( 'Button', 'us' ),
			),
			'iconpos' => array(
				'title' => __( 'Icon Position', 'us' ),
				'type' => 'select',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'left',
				'group' => __( 'Button', 'us' ),
			),

			// More Options
			'receiver_email' => array(
				'title' => __( 'Receiver Email', 'us' ),
				'description' => __( 'Requests will be sent to this email. You can insert multiple comma-separated emails as well.', 'us' ),
				'type' => 'text',
				'std' => $receiver_email,
				'admin_label' => TRUE,
				'group' => __( 'More Options', 'us' ),
			),
			'success_message' => array(
				'title' => __( 'Message after sending', 'us' ),
				'type' => 'text',
				'std' => __( 'Thank you! Your message was sent.', 'us' ),
				'group' => __( 'More Options', 'us' ),
			),

		), $design_options
	),
);
