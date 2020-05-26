<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcodes
 *
 * @filter us_config_shortcodes
 */

$elements = array(
	'page_block',
	'image',
	'image_slider',
	'separator',
	'btn',
	'iconbox',
	'cta',
	'counter',
	'sharing',
	'socials',
	'grid',
	'carousel',
	'popup',
	'flipbox',
	'itext',
	'gmaps',
	'person',
	'pricing',
	'progbar',
	'cform',
	'contacts',
	'message',
	'scroller',
	'page_title',
	'breadcrumbs',

	// Post Elements
	'post_content',
	'post_title',
	'post_image',
	'post_date',
	'post_author',
	'post_taxonomy',
	'post_comments',
	'post_navigation',
	'post_custom_field',
	'hwrapper',
);
if ( class_exists( 'woocommerce' ) ) {
	$elements[] = 'product_gallery';
	$elements[] = 'product_field';
	$elements[] = 'add_to_cart';
	$elements[] = 'product_ordering';
}

return array(

	// Theme elements, which are available for WPB as shortcodes. Used with "us_" prefix. The order affects on "Add New Element" window
	'theme_elements' => $elements,

	// Shortcodes, which are changed by similar theme elements
	'alias' => array(
		'vc_column_inner' => 'vc_column',
		'vc_tta_accordion' => 'vc_tta_tabs',
		'vc_tta_tour' => 'vc_tta_tabs',
		'us_carousel' => 'us_grid',
	),

	// WPB shortcodes, which are disabled by default
	'disabled' => array(
		'vc_btn',
		'vc_cta',
		'vc_gallery',
		'vc_single_image',
		'vc_message',
		'vc_gmaps',
		'vc_icon',
		'vc_facebook',
		'vc_tweetmeme',
		'vc_googleplus',
		'vc_pinterest',
		'vc_flickr',
		'vc_tta_pageable',
		'vc_toggle',
		'vc_tour',
		'vc_posts_slider',
		'vc_progress_bar',
		'vc_pie',
		'vc_basic_grid',
		'vc_media_grid',
		'vc_images_carousel',
		'vc_masonry_grid',
		'vc_masonry_media_grid',
		'vc_section',
		'vc_button2',
		'vc_separator',
		'vc_empty_space',
		'vc_text_separator',
		'vc_zigzag',
		'vc_hoverbox',
		'vc_tabs',
		'vc_accordion',
		'vc_tab',
		'vc_accordion_tab',
		'vc_gutenberg',
		'vc_acf',

		// WooCommerce
		'product',
		'products',
		'product_category',
		'product_categories',
		'top_rated_products',
		'best_selling_products',
		'recent_products',
		'featured_products',
		'sale_products',
	),

	// WPB shortcodes, which are modified via theme custom options
	'modified' => array(
		'gallery' => array(
			'atts' => array(
				'ids' => '',
				'columns' => 3,
				'orderby' => FALSE,
				'indents' => FALSE,
				'meta' => FALSE,
				'link' => FALSE,
				'masonry' => FALSE,
				'size' => 'thumbnail',
			),
		),
		'vc_column' => array(
			'atts' => array(
				'link' => '',
				'text_color' => '',
				'animate' => '',
				'animate_delay' => '',
				'width' => '1/1',
				'offset' => '',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_column_inner' => array(
			'atts' => array(
				'link' => '',
				'text_color' => '',
				'animate' => '',
				'animate_delay' => '',
				'width' => '1/1',
				'offset' => '',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_column_text' => array(
			'atts' => array(
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_custom_heading' => array(
			'overload' => FALSE,
		),
		'vc_row' => array(
			'atts' => array(
				'content_placement' => 'top',
				'gap' => '',
				'columns_type' => FALSE,
				'columns_reverse' => FALSE,
				'height' => 'default',
				'valign' => 'top',
				'width' => '',
				'color_scheme' => '',
				'us_bg_color' => '',
				'us_text_color' => '',
				'us_bg_image_source' => 'none',
				'us_bg_image' => '',
				'us_bg_size' => 'cover',
				'us_bg_repeat' => 'repeat',
				'us_bg_pos' => 'center center',
				'us_bg_parallax' => '',
				'us_bg_parallax_width' => '130',
				'us_bg_parallax_reverse' => FALSE,
				'us_bg_video' => '',
				'us_bg_slider' => '',
				'us_bg_overlay_color' => '',
				'sticky' => FALSE,
				'sticky_disable_width' => '900px',
				'disable_element' => '',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_row_inner' => array(
			'atts' => array(
				'content_placement' => 'top',
				'gap' => '',
				'columns_type' => FALSE,
				'columns_reverse' => FALSE,
				'disable_element' => '',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_tta_accordion' => array(
			'atts' => array(
				'toggle' => FALSE,
				'c_align' => 'left',
				'c_icon' => 'chevron',
				'c_position' => 'right',
				'title_tag' => 'h5',
				'title_size' => '1.2rem',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_tta_section' => array(
			'atts' => array(
				'title' => '',
				'tab_id' => '',
				'icon' => '',
				'i_position' => 'left',
				'active' => FALSE,
				'indents' => '',
				'bg_color' => '',
				'text_color' => '',
				'c_position' => 'right',
				'title_tag' => 'h5',
				'title_size' => '',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_tta_tabs' => array(
			'atts' => array(
				'layout' => 'default',
				'stretch' => FALSE,
				'title_font' => 'heading',
				'title_weight' => '',
				'title_transform' => '',
				'title_size' => '',
				'title_tag' => 'div',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
				// Default values for alised shortcodes
				'toggle' => FALSE,
				'c_align' => 'left',
				'c_icon' => 'chevron',
				'c_position' => 'right',
				'tab_position' => 'left',
				'controls_size' => 'auto',
			),
		),
		'vc_tta_tour' => array(
			'atts' => array(
				'c_align' => 'left',
				'tab_position' => 'left',
				'controls_size' => 'auto',
				'title_font' => 'heading',
				'title_weight' => '',
				'title_transform' => '',
				'title_size' => '',
				'title_tag' => 'div',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_video' => array(
			'atts' => array(
				'link' => 'https://youtu.be/XuWr9gJa6P0',
				'hide_video_title' => FALSE,
				'hide_controls' => FALSE,
				'ratio' => '16x9',
				'max_width' => '',
				'align' => 'left',
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
		'vc_wp_custommenu' => array(
			'atts' => array(
				'title' => '',
				'layout' => 'ver',
				'align' => 'left',
				'font_size' => '',
				'nav_menu' => NULL,
				'el_id' => '',
				'el_class' => '',
				'css' => '',
			),
		),
	),

);
