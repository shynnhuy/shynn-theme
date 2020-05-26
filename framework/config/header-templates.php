<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Tablets and mobiles missing settings are inherited from default state settings
 */
global $us_template_directory_uri;
return array(

	'simple_1' => array(
		'title' => 'Simple 1',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => FALSE,
				'middle_height' => '100px',
				'middle_sticky_height' => '60px',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_left' => array( 'image:1', 'text:1' ),
				'middle_right' => array( 'menu:1', 'search:1', 'cart:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '80px',
				'middle_sticky_height' => '50px',
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'middle_height' => '50px',
				'middle_sticky_height' => '50px',
			),
		),
		// Only the values that differ from the elements' defautls
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
			),
			'text:1' => array(
				'text' => 'LOGO',
			),
		),
	),
	'simple_2' => array(
		'title' => 'Simple 2',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => FALSE,
				'middle_height' => '100px',
				'middle_sticky_height' => '60px',
				'middle_fullwidth' => 1,
				'middle_centering' => TRUE,
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_left' => array( 'image:1' ),
				'middle_center' => array( 'menu:1' ),
				'middle_right' => array( 'socials:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '80px',
				'middle_sticky_height' => '50px',
			),
			'layout' => array(
				'middle_center' => array(),
				'middle_right' => array( 'menu:1', 'socials:1' ),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'top_show' => TRUE,
				'top_height' => '40px',
				'top_sticky_height' => '0px',
				'middle_height' => '50px',
			),
			'layout' => array(
				'top_center' => array( 'socials:1' ),
				'middle_right' => array( 'menu:1' ),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
				'height' => '30px',
				'height_sticky' => '30px',
				'design_options' => array(
					'margin_right_tablets' => 0,
					'margin_right_mobiles' => 0,
				),
			),
			'menu:1' => array(
				'font_size' => '18px',
				'indents' => '30px',
			),
			'socials:1' => array(
				'items' => array(
					array(
						'type' => 'facebook',
						'url' => '#',
					),
					array(
						'type' => 'twitter',
						'url' => '#',
					),
					array(
						'type' => 'google',
						'url' => '#',
					),
					array(
						'type' => 'linkedin',
						'url' => '#',
					),
					array(
						'type' => 'youtube',
						'url' => '#',
					),
				),
				'hover' => 'none',
				'size' => '20px',
				'design_options' => array(
					'margin_right_default' => '-10px',
					'margin_right_tablets' => '-8px',
				),
			),
		),
	),
	'simple_3' => array(
		'title' => 'Simple 3',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => FALSE,
				'middle_height' => '80px',
				'middle_sticky_height' => '50px',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_left' => array( 'image:1' ),
				'middle_right' => array( 'menu:1', 'btn:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '80px',
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'middle_height' => '50px',
				'middle_sticky_height' => '50px',
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
				'height' => '30px',
				'height_sticky' => '30px',
				'design_options' => array(
					'margin_right_tablets' => 0,
					'margin_right_mobiles' => 0,
				),
			),
			'menu:1' => array(
				'font_size' => '17px',
			),
			'btn:1' => array(
				'label' => 'BUY NOW',
				'link' => '#',
				'size' => '13px',
				'size_tablets' => '13px',
				'design_options' => array(
					'margin_left_mobiles' => 0,
				),
			),
		),
	),
	'simple_4' => array(
		'title' => 'Simple 4',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'transparent' => 1,
				'top_show' => FALSE,
				'middle_height' => '100px',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_left' => array( 'image:1' ),
				'middle_right' => array( 'menu:1', 'search:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'sticky' => FALSE,
				'middle_height' => '80px',
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'sticky' => FALSE,
				'middle_height' => '50px',
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'img_transparent' => $us_template_directory_uri . '/framework/img/us-logo-white.png',
				'link' => '/',
				'height' => '30px',
				'height_sticky' => '30px',
				'design_options' => array(
					'margin_right_tablets' => 0,
					'margin_right_mobiles' => 0,
				),
			),
			'menu:1' => array(
				'dropdown_font_size' => '13px',
				'mobile_dropdown_font_size' => '13px',
				'mobile_width' => '1023px',
			),
			'search:1' => array(
				'layout' => 'simple',
				'width_tablets' => '240px',
			),
		),
	),
	'extended_1' => array(
		'title' => 'Extended 1',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => TRUE,
				'top_height' => '40px',
				'top_sticky_height' => '0px',
				'middle_height' => '100px',
				'middle_sticky_height' => '60px',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'top_left' => array( 'text:2', 'text:3' ),
				'top_right' => array( 'socials:1' ),
				'middle_left' => array( 'image:1', 'text:1' ),
				'middle_right' => array( 'menu:1', 'search:1', 'cart:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '80px',
			),
			'layout' => array(
				'top_left' => array( 'text:2', 'text:3' ),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'top_show' => FALSE,
				'middle_height' => '50px',
				'middle_sticky_height' => '50px',
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
			),
			'text:1' => array(
				'text' => 'LOGO',
			),
			'text:2' => array(
				'text' => '+321 123 4567',
				'icon' => 'fas|phone',
			),
			'text:3' => array(
				'text' => 'info@test.com',
				'link' => 'mailto:info@example.com',
				'icon' => 'fas|envelope',
			),
			'socials:1' => array(
				'items' => array(
					array(
						'type' => 'facebook',
						'url' => '#',
					),
					array(
						'type' => 'twitter',
						'url' => '#',
					),
					array(
						'type' => 'google',
						'url' => '#',
					),
					array(
						'type' => 'linkedin',
						'url' => '#',
					),
					array(
						'type' => 'youtube',
						'url' => '#',
					),
				),
			),
		),
	),
	'extended_2' => array(
		'title' => 'Extended 2',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => FALSE,
				'middle_height' => '100px',
				'middle_sticky_height' => '0px',
				'bottom_show' => TRUE,
			),
			'layout' => array(
				'middle_left' => array( 'image:1', 'text:1' ),
				'middle_right' => array( 'text:2', 'text:3' ),
				'bottom_left' => array( 'menu:1' ),
				'bottom_right' => array( 'search:1', 'cart:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '50px',
				'middle_sticky_height' => '50px',
			),
			'layout' => array(
				'middle_left' => array(),
				'middle_center' => array( 'image:1', 'text:1' ),
				'middle_right' => array(),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'middle_height' => '50px',
			),
			'layout' => array(
				'middle_left' => array(),
				'middle_center' => array( 'image:1', 'text:1' ),
				'middle_right' => array(),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
			),
			'search:1' => array(
				'layout' => 'modern',
			),
			'text:1' => array(
				'text' => 'LOGO',
			),
			'text:2' => array(
				'text' => '+321 123 4567',
				'icon' => 'fas|phone',
			),
			'text:3' => array(
				'text' => 'info@test.com',
				'link' => 'mailto:info@example.com',
				'icon' => 'fas|envelope',
			),
		),
	),
	'extended_3' => array(
		'title' => 'Extended 3',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => FALSE,
				'middle_height' => '100px',
				'middle_sticky_height' => '50px',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_left' => array( 'image:1' ),
				'middle_right' => array( 'vwrapper:1' ),
				'vwrapper:1' => array( 'hwrapper:1', 'hwrapper:2' ),
				'hwrapper:1' => array( 'dropdown:1', 'text:2', 'text:3', 'socials:1' ),
				'hwrapper:2' => array( 'menu:1', 'search:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'top_show' => TRUE,
				'middle_height' => '80px',
			),
			'layout' => array(
				'top_center' => array( 'dropdown:1', 'text:2', 'text:3', 'socials:1' ),
				'middle_right' => array( 'menu:1', 'search:1' ),
				'vwrapper:1' => array(),
				'hwrapper:1' => array(),
				'hwrapper:2' => array(),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'top_show' => FALSE,
				'middle_height' => '50px',
			),
			'layout' => array(
				'top_center' => array( 'dropdown:1', 'text:2', 'text:3', 'socials:1' ),
				'middle_right' => array( 'menu:1', 'search:1' ),
				'vwrapper:1' => array(),
				'hwrapper:1' => array(),
				'hwrapper:2' => array(),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
				'height_sticky' => '25px',
			),
			'vwrapper:1' => array(
				'alignment' => 'right',
				'valign' => 'middle',
			),
			'hwrapper:1' => array(
				'alignment' => 'right',
				'valign' => 'middle',
				'hide_for_sticky' => 1,
				'design_options' => array(
					'margin_top_default' => '10px',
					'margin_bottom_default' => '10px',
				),
			),
			'hwrapper:2' => array(
				'alignment' => 'right',
			),
			'menu:1' => array(
				'font_size' => '18px',
			),
			'text:2' => array(
				'text' => 'info@test.com',
				'link' => 'mailto:info@example.com',
				'icon' => 'fas|envelope',
			),
			'text:3' => array(
				'text' => '+321 123 4567',
				'icon' => 'fas|phone',
			),
			'dropdown:1' => array(
				'link_title' => 'Dropdown',
				'links' => array(
					array(
						'label' => 'First item',
						'url' => '#',
					),
					array(
						'label' => 'Second item',
						'url' => '#',
					),
					array(
						'label' => 'Third item',
						'url' => '#',
					),
				),
			),
			'socials:1' => array(
				'size' => '16px',
				'size_tablets' => '16px',
				'size_mobiles' => '14px',
				'items' => array(
					array(
						'type' => 'facebook',
						'url' => '#',
					),
					array(
						'type' => 'twitter',
						'url' => '#',
					),
					array(
						'type' => 'google',
						'url' => '#',
					),
					array(
						'type' => 'linkedin',
						'url' => '#',
					),
					array(
						'type' => 'youtube',
						'url' => '#',
					),
				),
			),
		),
	),
	'extended_4' => array(
		'title' => 'Extended 4',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => FALSE,
				'middle_height' => '120px',
				'middle_sticky_height' => '60px',
				'bottom_show' => TRUE,
			),
			'layout' => array(
				'middle_left' => array( 'image:1' ),
				'middle_right' => array( 'vwrapper:1' ),
				'bottom_left' => array( 'menu:1' ),
				'bottom_right' => array( 'dropdown:1', 'cart:1' ),
				'vwrapper:1' => array( 'hwrapper:1', 'search:1' ),
				'hwrapper:1' => array( 'socials:1', 'text:2', 'text:3' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '60px',
			),
			'layout' => array(
				'vwrapper:1' => array( 'search:1' ),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'middle_height' => '50px',
				'middle_sticky_height' => '0px',
			),
			'layout' => array(
				'vwrapper:1' => array( 'search:1' ),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
			),
			'vwrapper:1' => array(
				'alignment' => 'right',
			),
			'hwrapper:1' => array(
				'alignment' => 'right',
				'valign' => 'middle',
				'hide_for_sticky' => 1,
			),
			'search:1' => array(
				'text' => 'In search of...',
				'layout' => 'simple',
				'width' => '538px',
				'width_tablets' => '340px',
			),
			'socials:1' => array(
				'items' => array(
					array(
						'type' => 'facebook',
						'url' => '#',
					),
					array(
						'type' => 'twitter',
						'url' => '#',
					),
					array(
						'type' => 'google',
						'url' => '#',
					),
					array(
						'type' => 'linkedin',
						'url' => '#',
					),
					array(
						'type' => 'youtube',
						'url' => '#',
					),
				),
			),
			'text:2' => array(
				'text' => 'info@test.com',
				'link' => 'mailto:info@example.com',
				'icon' => 'fas|envelope',
				'size' => '18px',
				'design_options' => array(
					'margin_left_default' => '30px',
				),
			),
			'text:3' => array(
				'text' => '+321 123 4567',
				'icon' => 'fas|phone',
				'size' => '18px',
				'design_options' => array(
					'margin_left_default' => '30px',
				),
			),
			'dropdown:1' => array(
				'link_title' => 'My Account',
				'link_icon' => 'fas|user',
				'links' => array(
					array(
						'label' => 'Orders',
						'url' => '#',
						'icon' => 'fas|cubes',
					),
					array(
						'label' => 'Favorites',
						'url' => '#',
						'icon' => 'fas|heart',
					),
					array(
						'label' => 'Sign Out',
						'url' => '#',
						'icon' => 'fas|sign-out',
					),
				),
				'size' => '16px',
			),
			'cart:1' => array(
				'size' => '24px',
				'size_tablets' => '22px',
				'design_options' => array(
					'margin_left_default' => '10px',
				),
			),
		),
	),
	'centered_1' => array(
		'title' => 'Centered 1',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => FALSE,
				'middle_height' => '100px',
				'middle_sticky_height' => '50px',
				'middle_centering' => TRUE,
				'bottom_show' => TRUE,
				'bottom_centering' => 1,
			),
			'layout' => array(
				'middle_center' => array( 'image:1', 'text:1' ),
				'bottom_center' => array( 'menu:1', 'search:1', 'cart:1' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '50px',
				'middle_sticky_height' => '0px',
			),
			'layout' => array(
				'bottom_left' => array( 'menu:1' ),
				'bottom_center' => array(),
				'bottom_right' => array( 'search:1', 'cart:1' ),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'middle_height' => '50px',
				'middle_sticky_height' => '0px',
			),
			'layout' => array(
				'bottom_left' => array( 'menu:1' ),
				'bottom_center' => array(),
				'bottom_right' => array( 'search:1', 'cart:1' ),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
			),
			'text:1' => array(
				'text' => 'LOGO',
			),
			'search:1' => array(
				'layout' => 'fullscreen',
			),
		),
	),
	'centered_2' => array(
		'title' => 'Centered 2',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'transparent' => 1,
				'top_show' => FALSE,
				'middle_height' => '120px',
				'middle_sticky_height' => '50px',
				'middle_centering' => TRUE,
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_center' => array( 'additional_menu:1', 'image:1', 'additional_menu:2' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '70px',
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'middle_height' => '50px',
			),
			'layout' => array(
				'middle_center' => array( 'additional_menu:1', 'additional_menu:2' ),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/admin/img/us-logo.png',
				'link' => '/',
				'height' => '80px',
				'height_tablets' => '60px',
				'height_sticky' => '40px',
				'height_sticky_tablets' => '40px',
				'design_options' => array(
					'margin_left_default' => '50px',
					'margin_right_default' => '50px',
					'margin_left_tablets' => '40px',
					'margin_right_tablets' => '40px',
				),
			),
			'additional_menu:1' => array(
				'source' => 'left',
				'size' => '15px',
				'size_tablets' => '15px',
				'indents' => '50px',
				'indents_tablets' => '40px',
			),
			'additional_menu:2' => array(
				'source' => 'right',
				'size' => '15px',
				'size_tablets' => '15px',
				'indents' => '50px',
				'indents_tablets' => '40px',
			),
		),
	),
	'triple_1' => array(
		'title' => 'Triple 1',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'top_show' => TRUE,
				'top_height' => '40px',
				'top_sticky_height' => '0px',
				'middle_height' => '100px',
				'middle_sticky_height' => '0px',
				'bottom_show' => TRUE,
			),
			'layout' => array(
				'top_left' => array( 'additional_menu:1' ),
				'top_right' => array( 'text:2' ),
				'middle_left' => array( 'image:1' ),
				'middle_center' => array( 'search:1' ),
				'middle_right' => array( 'vwrapper:1' ),
				'bottom_left' => array( 'menu:1' ),
				'bottom_right' => array( 'cart:1' ),
				'vwrapper:1' => array( 'text:3', 'text:4' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '80px',
				'middle_sticky_height' => '60px',
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'scroll_breakpoint' => '50px',
				'top_show' => FALSE,
				'middle_height' => '50px',
				'middle_sticky_height' => '50px',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_center' => array(),
				'middle_right' => array( 'menu:1', 'search:1', 'cart:1' ),
				'bottom_left' => array(),
				'bottom_right' => array(),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
			),
			'vwrapper:1' => array(
				'alignment' => 'right',
			),
			'search:1' => array(
				'text' => 'I\'m shopping for...',
				'layout' => 'simple',
				'width' => '440px',
				'width_tablets' => '240px',
			),
			'additional_menu:1' => array(
				'source' => 'about',
				'size' => '13px',
			),
			'text:2' => array(
				'text' => 'My Account',
				'link' => '#',
				'icon' => 'fas|user',
				'font_size' => '13px',
			),
			'text:3' => array(
				'text' => '+321 123 4567',
				'icon' => 'fas|phone',
				'font_weight' => '700',
				'font_size' => '24px',
				'font_size_tablets' => '20px',
				'design_options' => array(
					'margin_bottom_default' => 0,
					'margin_bottom_tablets' => 0,
				),
			),
			'text:4' => array(
				'text' => 'Call from 9pm to 7am (Mon-Fri)',
				'font_size' => '12px',
				'font_size_tablets' => '12px',
				'font_size_mobiles' => '12px',
			),
		),
	),
	'triple_2' => array(
		'title' => 'Triple 2',
		'default' => array(
			'options' => array(
				'orientation' => 'hor',
				'sticky' => FALSE,
				'top_show' => TRUE,
				'top_height' => '40px',
				'middle_height' => '100px',
				'middle_sticky_height' => '0px',
				'bottom_show' => TRUE,
			),
			'layout' => array(
				'top_left' => array( 'text:7' ),
				'top_center' => array( 'text:8' ),
				'top_right' => array( 'btn:1', 'btn:2' ),
				'middle_left' => array( 'image:1', 'search:1' ),
				'middle_right' => array( 'vwrapper:1', 'text:2', 'text:3', 'cart:1' ),
				'bottom_left' => array( 'menu:1' ),
				'bottom_right' => array( 'text:4' ),
				'vwrapper:1' => array( 'text:5', 'text:6' ),
			),
		),
		'tablets' => array(
			'options' => array(
				'middle_height' => '80px',
			),
			'layout' => array(
				'top_center' => array(),
				'middle_right' => array( 'text:2', 'text:3', 'cart:1' ),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'sticky' => TRUE,
				'scroll_breakpoint' => '50px',
				'top_sticky_height' => '0px',
				'middle_height' => '50px',
				'middle_sticky_height' => '50px',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'top_left' => array(),
				'top_center' => array( 'btn:1', 'btn:2' ),
				'top_right' => array(),
				'middle_left' => array( 'image:1' ),
				'middle_right' => array( 'menu:1', 'search:1', 'cart:1' ),
				'bottom_left' => array(),
				'bottom_right' => array(),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
				'design_options' => array(
					'margin_right_default' => '10%',
				),
			),
			'btn:1' => array(
				'label' => 'SIGN IN',
				'link' => '/my-account/',
				'font_size' => '11px',
				'font_size_tablets' => '11px',
			),
			'btn:2' => array(
				'label' => 'REGISTER',
				'link' => '/my-account/',
				'style' => '2',
				'font_size' => '11px',
				'font_size_tablets' => '11px',
				'design_options' => array(
					'margin_left_default' => '10px',
					'margin_left_tablets' => '10px',
					'margin_left_mobiles' => '0',
				),
			),
			'search:1' => array(
				'text' => 'I\'m shopping for...',
				'layout' => 'simple',
				'width' => '380px',
				'width_tablets' => '300px',
				'design_options' => array(
					'margin_right_default' => '0',
				),
			),
			'text:2' => array(
				'text' => '',
				'icon' => 'fas|phone',
				'size' => '2rem',
				'size_tablets' => '1.5rem',
				'design_options' => array(
					'margin_left_default' => '10%',
				),
			),
			'text:3' => array(
				'text' => '+321 123 4567<br>+321 123 4568',
				'font_weight' => '700',
				'design_options' => array(
					'margin_left_default' => '10px',
					'margin_left_tablets' => '10px',
				),
			),
			'text:4' => array(
				'text' => 'Special Offers',
				'link' => '#',
				'color' => '#f66',
			),
			'text:5' => array(
				'text' => 'Shipping & Delivery',
				'link' => '#',
				'icon' => 'fas|ship',
				'color' => '#23ccaa',
				'font_size' => '14px',
				'design_options' => array(
					'margin_bottom_default' => '4px',
					'margin_bottom_tablets' => '4px',
				),
			),
			'text:6' => array(
				'text' => 'Order Status',
				'link' => '#',
				'icon' => 'fas|truck',
				'color' => '#23ccaa',
				'font_size' => '14px',
			),
			'text:7' => array(
				'text' => 'Change Location',
				'link' => '#',
				'icon' => 'fas|map-marker',
				'font_size' => '14px',
			),
			'text:8' => array(
				'text' => 'Some short description or notification or something else',
			),
			'cart:1' => array(
				'icon' => 'fas|shopping-basket',
				'size' => '24px',
				'design_options' => array(
					'margin_left_default' => '9%',
					'margin_left_tablets' => '5%',
				),
			),
		),
	),
	'vertical_1' => array(
		'title' => 'Vertical 1',
		'default' => array(
			'options' => array(
				'orientation' => 'ver',
				'bottom_show' => FALSE,
			),
			'layout' => array(
				'middle_left' => array(
					'image:1',
					'text:1',
					'menu:1',
					'search:1',
					'cart:1',
					'text:2',
					'text:3',
				),
			),
		),
		'tablets' => array(
			'options' => array(
				'orientation' => 'hor',
				'middle_height' => '80px',
			),
			'layout' => array(
				'top_center' => array( 'text:2', 'text:3' ),
				'middle_left' => array( 'image:1', 'text:1' ),
				'middle_center' => array(),
				'middle_right' => array( 'menu:1', 'search:1', 'cart:1' ),
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'orientation' => 'hor',
				'middle_height' => '50px',
			),
			'layout' => array(
				'top_center' => array( 'text:2', 'text:3' ),
				'middle_left' => array( 'image:1', 'text:1' ),
				'middle_center' => array(),
				'middle_right' => array( 'menu:1', 'search:1', 'cart:1' ),
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/img/us-logo.png',
				'link' => '/',
				'design_options' => array(
					'margin_top_default' => '30px',
					'margin_bottom_default' => '30px',
				),
			),
			'menu:1' => array(
				'indents' => '0.7em',
				'design_options' => array(
					'margin_bottom_default' => '30px',
				),
			),
			'text:1' => array(
				'text' => 'LOGO',
			),
			'text:2' => array(
				'text' => '+321 123 4567',
				'icon' => 'fas|phone',
				'design_options' => array(
					'margin_bottom_default' => '10px',
				),
			),
			'text:3' => array(
				'text' => 'info@test.com',
				'link' => 'mailto:info@example.com',
				'icon' => 'fas|envelope',
				'design_options' => array(
					'margin_bottom_default' => '10px',
				),
			),
		),
	),
	'vertical_2' => array(
		'title' => 'Vertical 2',
		'default' => array(
			'options' => array(
				'orientation' => 'ver',
				'width' => '250px',
				'top_show' => FALSE,
				'elm_valign' => 'middle',
				'bottom_show' => TRUE,
			),
			'layout' => array(
				'middle_left' => array(
					'image:1',
					'menu:1',
					'search:1',
					'cart:1',
				),
				'bottom_left' => array(
					'text:2',
					'socials:1',
				),
			),
		),
		'tablets' => array(
			'options' => array(
				'orientation' => 'ver',
				'width' => '250px',
				'top_show' => FALSE,
				'bottom_show' => TRUE,
			),
		),
		'mobiles' => array(
			'options' => array(
				'breakpoint' => '600px',
				'orientation' => 'ver',
				'top_show' => FALSE,
				'bottom_show' => TRUE,
			),
		),
		'data' => array(
			'image:1' => array(
				'img' => $us_template_directory_uri . '/framework/admin/img/us-logo.png',
				'link' => '/',
				'height' => '90px',
				'height_tablets' => '90px',
				'height_mobiles' => '60px',
			),
			'menu:1' => array(
				'font_size' => '1.2rem',
				'indents' => '1.5vh',
				'design_options' => array(
					'margin_bottom_default' => '10px',
					'margin_bottom_tablets' => '10px',
					'margin_bottom_mobiles' => '0',
				),
			),
			'search:1' => array(
				'layout' => 'modern',
				'width' => '234px',
				'width_tablets' => '234px',
				'design_options' => array(
					'margin_bottom_default' => '10px',
					'margin_bottom_tablets' => '10px',
					'margin_bottom_mobiles' => '0',
				),
			),
			'text:2' => array(
				'text' => '+321 123 4567',
				'size' => '18px',
				'size_tablets' => '18px',
				'size_mobiles' => '16px',
			),
			'socials:1' => array(
				'items' => array(
					array(
						'type' => 'facebook',
						'url' => '#',
					),
					array(
						'type' => 'twitter',
						'url' => '#',
					),
					array(
						'type' => 'google',
						'url' => '#',
					),
				),
			),
		),
	),

);
