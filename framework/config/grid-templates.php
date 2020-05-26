<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Grid Templates
 */

return array(

/* Blog =========================================================================== */

'blog_1' => array(
	'title' => 'Image & Title',
	'group' => __( 'Blog Templates', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'design_options' => array(
				'margin_bottom_default' => '0.5rem',
			),
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '1rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'post_title:1',
			),
		),
	),
),

'blog_2' => array(
	'title' => 'Title & Date',
	'data' => array(
		'post_title:1' => array(
			'tag' => 'h4',
			'font' => 'h4',
			'font_size' => '1rem',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_title:1',
				'post_date:1',
			),
		),
	),
),

'blog_3' => array(
	'title' => 'Title only',
	'data' => array(
		'post_title:1' => array(
			'tag' => 'div',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_title:1',
			),
		),
	),
),

'blog_4' => array(
	'title' => 'Top Image (date, comments)',
	'data' => array(
		'post_image:1' => array(),
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '1.2rem',
			'design_options' => array(
				'margin_top_default' => '0.6rem',
				'margin_bottom_default' => '0.3rem',
			),
		),
		'hwrapper:1' => array(
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
			'line_height' => '1.6',
		),
		'post_comments:1' => array(
			'number' => TRUE,
			'font_size' => '14px',
			'line_height' => '1.6',
			'icon' => 'far|comments',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'post_title:1',
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_date:1',
				'post_comments:1',
			),
		),
	),
),

'blog_5' => array(
	'title' => 'Side Image (date, comments)',
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'width' => '30%',
			'design_options' => array(
				'margin_right_default' => is_rtl() ? '' : '5%',
				'margin_left_default' => is_rtl() ? '5%' : '',
			),
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '1.2rem',
			'design_options' => array(
				'margin_bottom_default' => '0.3rem',
			),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
			'line_height' => '1.6',
		),
		'post_comments:1' => array(
			'number' => TRUE,
			'font_size' => '14px',
			'line_height' => '1.6',
			'icon' => 'far|comments',
		),
		'hwrapper:1' => array(),
		'hwrapper:2' => array(
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'vwrapper:1' => array(),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'hwrapper:2',
			),
			'hwrapper:2' => array(
				'post_date:1',
				'post_comments:1',
			),
		),
	),
),

'blog_6' => array(
	'title' => 'Corner Tile 4:3 (category, date)',
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'scale_hover' => '1.1',
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '1.4rem',
			'font_size_mobiles' => '1.2rem',
		),
		'post_taxonomy:1' => array(
			'color_link' => FALSE,
			'font_size' => '14px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '0.6rem' : '',
				'margin_right_default' => is_rtl() ? '' : '0.6rem',
			),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_left_default' => is_rtl() ? '2rem' : '0',
				'position_right_default' => is_rtl() ? '0' : '2rem',
				'position_bottom_default' => '0',
				'padding_top_default' => '0.5rem',
				'padding_left_default' => is_rtl() ? '2rem' : '',
				'padding_right_default' => is_rtl() ? '' : '2rem',
			),
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
		),
		'hwrapper:1' => array(
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'hwrapper:1',
				'post_title:1',
			),
			'hwrapper:1' => array(
				'post_taxonomy:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
			'ratio' => '4x3',
		),
	),
),

'blog_7' => array(
	'title' => 'Corner Tile 2:1 (category, date)',
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'scale_hover' => '1.1',
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '1.4rem',
			'font_size_mobiles' => '1.2rem',
		),
		'post_taxonomy:1' => array(
			'color_link' => FALSE,
			'font_size' => '14px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '0.6rem' : '',
				'margin_right_default' => is_rtl() ? '' : '0.6rem',
			),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_left_default' => is_rtl() ? '2rem' : '0',
				'position_right_default' => is_rtl() ? '0' : '2rem',
				'position_bottom_default' => '0',
				'padding_top_default' => '0.5rem',
				'padding_left_default' => is_rtl() ? '2rem' : '',
				'padding_right_default' => is_rtl() ? '' : '2rem',
			),
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
		),
		'hwrapper:1' => array(
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'hwrapper:1',
				'post_title:1',
			),
			'hwrapper:1' => array(
				'post_taxonomy:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
			'ratio' => 'custom',
			'ratio_width' => '2',
			'ratio_height' => '1',
		),
	),
),

'blog_8' => array(
	'title' => 'Title over Image (category, date, excerpt)',
	'data' => array(
		'hwrapper:1' => array(),
		'post_image:1' => array(
			'placeholder' => TRUE,
			'thumbnail_size' => 'us_600_600_crop',
			'width' => '50%',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '0' : '',
				'margin_right_default' => is_rtl() ? '' : '0',
			),
			'width' => '50%',
		),
		'hwrapper:2' => array(
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_taxonomy:1' => array(
			'color_link' => FALSE,
			'font_size' => '14px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '0.8rem' : '',
				'margin_right_default' => is_rtl() ? '' : '0.8rem',
			),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
			'color_text' => us_get_color( 'color_content_faded' ),
			'hide_below' => '480px',
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '1.8rem',
			'font_size_mobiles' => '1.2rem',
			'line_height' => '1.2',
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '-35%' : '',
				'margin_right_default' => is_rtl() ? '' : '-35%',
				'margin_bottom_default' => '0',
				'padding_top_default' => '0.6rem',
				'padding_left_default' => is_rtl() ? '1.2rem' : '',
				'padding_right_default' => is_rtl() ? '' : '1.2rem',
				'padding_bottom_default' => '0.6rem',
			),
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
		),
		'post_content:1' => array(
			'length' => '24',
			'font_size' => '0.9rem',
			'line_height' => '1.6',
			'design_options' => array(
				'padding_left_default' => is_rtl() ? '1.5rem' : '',
				'padding_right_default' => is_rtl() ? '' : '1.5rem',
			),
			'hide_below' => '480px',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'vwrapper:1',
				'post_image:1',
			),
			'vwrapper:1' => array(
				'hwrapper:2',
				'post_title:1',
				'post_content:1',
			),
			'hwrapper:2' => array(
				'post_taxonomy:1',
				'post_date:1',
			),
		),
		'options' => array(
			'overflow' => TRUE,
		),
	),
),

'blog_9' => array(
	'title' => 'Card White (category, date)',
	'data' => array(
		'post_image:1' => array(
			'thumbnail_size' => 'us_600_600_crop',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'padding_top_default' => '0.8rem',
				'padding_left_default' => '1.5rem',
				'padding_right_default' => '1.5rem',
				'padding_bottom_default' => '1.2rem',
			),
		),
		'hwrapper:1' => array(
			'design_options' => array(
				'margin_bottom_default' => '0.3rem',
			),
		),
		'post_taxonomy:1' => array(
			'color_link' => FALSE,
			'font_size' => '14px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '0.6rem' : '',
				'margin_right_default' => is_rtl() ? '' : '0.6rem',
			),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_weight' => '700',
			'font_size' => '1.4rem',
			'font_size_mobiles' => '1.2rem',
			'color_text' => 'inherit',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'hwrapper:1',
				'post_title:1',
			),
			'hwrapper:1' => array(
				'post_taxonomy:1',
				'post_date:1',
			),
		),
		'options' => array(
			'overflow' => TRUE,
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
			'color_text' => us_get_color( 'color_content_heading' ),
			'border_radius' => '0.3rem',
			'box_shadow' => '0.3rem',
			'box_shadow_hover' => '1.2rem',
		),
	),
),

'blog_10' => array(
	'title' => 'Card Gradient (category)',
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
		),
		'vwrapper:1' => array(
			'bg_gradient' => TRUE,
			'design_options' => array(
				'position_left_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'padding_top_default' => '4rem',
				'padding_left_default' => '1.5rem',
				'padding_right_default' => '1.5rem',
				'padding_bottom_default' => '1.2rem',
			),
		),
		'post_taxonomy:1' => array(
			'style' => 'badge',
			'font_size' => '10px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'margin_bottom_default' => '0.5rem',
			),
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_weight' => '700',
			'font_size' => '1.4rem',
			'font_size_mobiles' => '1.2rem',
			'color_text' => '#fff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_taxonomy:1',
				'post_title:1',
			),
		),
		'options' => array(
			'overflow' => TRUE,
			'color_bg' => '#1e1e1e',
			'border_radius' => '0.3rem',
			'box_shadow' => '0.3rem',
			'box_shadow_hover' => '1.2rem',
		),
	),
),

'blog_11' => array(
	'title' => 'Title First (category, date, comments, excerpt)',
	'data' => array(
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '1.4rem',
			'font_size_mobiles' => '1.2rem',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'hwrapper:1' => array(
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_taxonomy:1' => array(
			'color_link' => FALSE,
			'font_size' => '14px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '14px',
		),
		'post_comments:1' => array(
			'number' => TRUE,
			'font_size' => '14px',
			'icon' => 'far|comments',
		),
		'post_image:1' => array(
			'media_preview' => TRUE,
			'design_options' => array(
				'margin_top_default' => '0.6rem',
			),
		),
		'post_content:1' => array(
			'length' => '50',
			'font_size' => '0.9rem',
			'line_height' => '1.7',
			'design_options' => array(
				'margin_top_default' => '0.8rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_title:1',
				'hwrapper:1',
				'post_image:1',
				'post_content:1',
			),
			'hwrapper:1' => array(
				'post_taxonomy:1',
				'post_date:1',
				'post_comments:1',
			),
		),
	),
),

'blog_12' => array(
	'title' => 'Full Content (date, comments, tags)',
	'data' => array(
		'post_title:1' => array(
			'font' => 'h1',
			'font_size' => '3rem',
			'line_height' => '1.1',
			'font_size_mobiles' => '1.5rem',
			'design_options' => array(
				'margin_bottom_default' => '0.2rem',
			),
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '0.9rem',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_content:1' => array(
			'type' => 'full_content',
			'design_options' => array(
				'margin_top_default' => '0.5rem',
				'margin_bottom_default' => '1rem',
			),
		),
		'hwrapper:1' => array(
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_comments:1' => array(
			'color_link' => FALSE,
			'font_size' => '14px',
			'icon' => 'far|comments',
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'post_tag',
			'color_link' => FALSE,
			'font_size' => '14px',
			'icon' => 'far|tags',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_title:1',
				'post_date:1',
				'post_content:1',
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_comments:1',
				'post_taxonomy:1',
			),
		),
	),
),

'blog_13' => array(
	'title' => 'Float Image Right (date, excerpt)',
	'data' => array(
		'hwrapper:1' => array(
			'valign' => 'center',
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '10%' : '',
				'margin_right_default' => is_rtl() ? '' : '10%',
				'padding_top_default' => '6%',
				'padding_bottom_default' => '6%',
			),
			'color_bg' => us_get_color( 'color_content_bg_alt', TRUE ),
			'color_text' => us_get_color( 'color_content_text' ),
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '0' : '6%',
				'margin_right_default' => is_rtl() ? '6%' : '0',
			),
			'width' => '48%',
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '0.9rem',
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_weight' => '700',
			'line_height' => '1.2',
		),
		'post_content:1' => array(
			'font_size' => '0.9rem',
		),
		'post_image:1' => array(
			'media_preview' => TRUE,
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '' : '7%',
				'margin_right_default' => is_rtl() ? '7%' : '',
			),
			'hide_below' => '480px',
			'width' => '50%',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'vwrapper:1',
				'post_image:1',
			),
			'vwrapper:1' => array(
				'post_date:1',
				'post_title:1',
				'post_content:1',
			),
		),
	),
),

'blog_14' => array(
	'title' => 'Float Image Left (date, excerpt)',
	'data' => array(
		'hwrapper:1' => array(
			'valign' => 'center',
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '' : '10%',
				'margin_right_default' => is_rtl() ? '10%' : '',
				'padding_top_default' => '6%',
				'padding_bottom_default' => '6%',
			),
			'color_bg' => us_get_color( 'color_content_bg_alt', TRUE ),
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '' : '7%',
				'margin_right_default' => is_rtl() ? '7%' : '',
			),
			'width' => '48%',
		),
		'post_date:1' => array(
			'format' => 'smart',
			'font_size' => '0.9rem',
		),
		'post_title:1' => array(
			'font' => 'h1',
			'font_weight' => '700',
			'line_height' => '1.2',
		),
		'post_content:1' => array(
			'font_size' => '0.9rem',
		),
		'post_image:1' => array(
			'media_preview' => TRUE,
			'design_options' => array(
				'margin_left_default' => is_rtl() ? '0' : '-11%',
				'margin_right_default' => is_rtl() ? '-11%' : '0',
			),
			'hide_below' => '480px',
			'width' => '50%',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_date:1',
				'post_title:1',
				'post_content:1',
			),
		),
	),
),

'blog_classic' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Classic', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'media_preview' => TRUE,
			'design_options' => array(
				'margin_bottom_default' => '1rem',
			),
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'design_options' => array(
				'margin_bottom_default' => '0.5rem',
			),
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'hwrapper:1' => array(
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'font_size' => '0.9rem',
			'icon' => 'far|folder-open',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_content:1' => array(
			'design_options' => array(
				'margin_top_default' => '0.5rem',
			),
		),
		'btn:1' => array(
			'label' => __( 'Read More', 'us' ),
			'style' => '2',
			'design_options' => array(
				'margin_top_default' => '1.5rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'post_title:1',
				'hwrapper:1',
				'post_content:1',
				'btn:1',
			),
			'hwrapper:1' => array(
				'post_date:1',
				'post_author:1',
				'post_taxonomy:1',
				'post_taxonomy:2',
				'post_comments:1',
			),
		),
	),
),

'blog_flat' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Flat', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'media_preview' => TRUE,
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
		),
		'hwrapper:2' => array(
			'alignment' => 'center',
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_content:1' => array(
			'length' => '20',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'btn:1' => array(
			'label' => __( 'Read More', 'us' ),
			'style' => '2',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'design_options' => array(
				'padding_top_default' => '1.5rem',
				'padding_right_default' => '2.5rem',
				'padding_bottom_default' => '2.5rem',
				'padding_left_default' => '2.5rem',
			),
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'font_size' => '0.9rem',
			'icon' => 'far|folder-open',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'hwrapper:2' => array(
				'post_date:1',
				'post_author:1',
				'post_taxonomy:1',
				'post_taxonomy:2',
				'post_comments:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'hwrapper:2',
				'post_content:1',
				'btn:1',
			),
		),
		'options' => array(
			'overflow' => TRUE,
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
			'box_shadow_hover' => '1.5rem',
		),
	),
),

'blog_tiles' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Tiles', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
			'hover' => TRUE,
			'scale_hover' => '1.2',
		),
		'vwrapper:1' => array(
			'valign' => 'bottom',
			'bg_gradient' => TRUE,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '5rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'opacity' => '0',
			'transition_duration' => '0.45s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'font_weight' => '700',
			'color_text' => '#ffffff',
		),
		'hwrapper:1' => array(
			'wrap' => TRUE,
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
			'color_text' => '#ffffff',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
			'color_text' => '#ffffff',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
			'color_text' => '#ffffff',
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'style' => 'badge',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'font_size' => '10px',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
			'color_text' => '#ffffff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_taxonomy:1',
				'post_title:1',
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_date:1',
				'post_author:1',
				'post_taxonomy:2',
				'post_comments:1',
			),
		),
		'options' => array(
			'overflow' => TRUE,
		),
	),
),

'blog_cards' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Cards', 'us' ),
	'data' => array(
		'post_image:1' => array(
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'font_weight' => '700',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'padding_top_default' => '9%',
				'padding_right_default' => '11%',
				'padding_bottom_default' => '11%',
				'padding_left_default' => '11%',
			),
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'style' => 'badge',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'font_size' => '10px',
			'design_options' => array(
				'position_top_default' => '1.2rem',
				'position_left_default' => '1.2rem',
				'position_right_default' => '1.2rem',
			),
		),
		'hwrapper:1' => array(
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
		'post_content:1' => array(
			'length' => '20',
		),
		'btn:1' => array(
			'label' => __( 'Read More', 'us' ),
			'style' => '2',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'post_taxonomy:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'hwrapper:1',
				'post_content:1',
				'btn:1',
			),
			'hwrapper:1' => array(
				'post_date:1',
				'post_author:1',
				'post_taxonomy:2',
				'post_comments:1',
			),
		),
		'options' => array(
			'overflow' => TRUE,
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
			'border_radius' => '0.3rem',
			'box_shadow' => '0.3rem',
			'box_shadow_hover' => '1rem',
		),
	),
),

'blog_side_image' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Side Image', 'us' ),
	'data' => array(
		'hwrapper:1' => array(
			'el_class' => 'responsive',
		),
		'post_image:1' => array(
			'placeholder' => TRUE,
			'circle' => TRUE,
			'thumbnail_size' => 'us_350_350_crop',
			'width' => '30%',
			'design_options' => array(
				'margin_right_default' => is_rtl() ? '0' : '5%',
				'margin_left_default' => is_rtl() ? '5%' : '0',
			),
		),
		'vwrapper:1' => array(
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
		),
		'hwrapper:2' => array(
			'wrap' => TRUE,
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_content:1' => array(
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
		'btn:1' => array(
			'label' => __( 'Read More', 'us' ),
			'style' => '2',
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'category',
			'font_size' => '0.9rem',
			'icon' => 'far|folder-open',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'hwrapper:2',
				'post_content:1',
				'btn:1',
			),
			'hwrapper:2' => array(
				'post_date:1',
				'post_author:1',
				'post_taxonomy:2',
				'post_taxonomy:1',
				'post_comments:1',
			),
		),
	),
),

'blog_compact' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Compact', 'us' ),
	'data' => array(
		'hwrapper:1' => array(
			'wrap' => TRUE,
		),
		'post_title:1' => array(
			'color_link' => FALSE,
			'font_size' => '1rem',
			'tag' => 'div',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_title:1',
				'post_date:1',
				'post_comments:1',
			),
		),
	),
),

/* Gallery =========================================================================== */

'gallery_default' => array(
	'title' => __( 'Image Gallery', 'us' ),
	'group' => __( 'Gallery Templates', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'hover' => TRUE,
			'opacity_hover' => '0.5',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
			),
		),
	),
),

'gallery_with_titles_below' => array(
	'title' => __( 'Image Gallery with titles BELOW the image', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'hover' => TRUE,
			'opacity_hover' => '0.5',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'design_options' => array(
				'padding_top_default' => '0.5rem',
				'padding_right_default' => '1rem',
				'padding_bottom_default' => '1rem',
				'padding_left_default' => '1rem',
			),
		),
		'post_title:1' => array(
			'link' => 'none',
			'tag' => 'div',
			'line_height' => '1.6',
			'el_class' => 'hide_if_not_first',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:1' => array(
			'key' => '_wp_attachment_image_alt',
			'hide_empty' => TRUE,
			'line_height' => '1.6',
			'el_class' => 'hide_if_not_first',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_content:1' => array(
			'type' => 'excerpt_only',
			'line_height' => '1.6',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_content:2' => array(
			'type' => 'full_content',
			'font_size' => '0.9rem',
			'line_height' => '1.6',
			'color_text' => us_get_color( 'color_content_faded' ),
			'design_options' => array(
				'margin_top_default' => '0.2rem',
				'margin_bottom_default' => '0.2rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_content:1',
				'post_custom_field:1',
				'post_title:1',
				'post_content:2',
			),
		),
	),
),

'gallery_with_titles_over' => array(
	'title' => __( 'Image Gallery with titles OVER the image', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'hover' => TRUE,
			'opacity_hover' => '0.5',
		),
		'vwrapper:1' => array(
			'bg_gradient' => TRUE,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '3rem',
				'padding_right_default' => '1rem',
				'padding_bottom_default' => '0.6rem',
				'padding_left_default' => '1rem',
			),
		),
		'post_title:1' => array(
			'link' => 'none',
			'tag' => 'div',
			'line_height' => '1.6',
			'color_text' => '#fff',
			'el_class' => 'hide_if_not_first',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:1' => array(
			'key' => '_wp_attachment_image_alt',
			'hide_empty' => TRUE,
			'line_height' => '1.6',
			'el_class' => 'hide_if_not_first',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_content:1' => array(
			'type' => 'excerpt_only',
			'line_height' => '1.6',
			'color_text' => '#fff',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_content:2' => array(
			'type' => 'full_content',
			'font_size' => '0.9rem',
			'line_height' => '1.6',
			'color_text' => 'rgba(255,255,255,0.5)',
			'design_options' => array(
				'margin_top_default' => '0.2rem',
				'margin_bottom_default' => '0.2rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_content:1',
				'post_custom_field:1',
				'post_title:1',
				'post_content:2',
			),
		),
		'options' => array(
			'overflow' => TRUE,
		),
	),
),

/* Portfolio =========================================================================== */

'portfolio_1' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 1',
	'group' => __( 'Portfolio Templates', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'translateY_hover' => '-10%',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '-1px',
				'position_left_default' => '0',
				'padding_top_default' => '1.2rem',
				'padding_right_default' => '1.5rem',
				'padding_bottom_default' => '1.2rem',
				'padding_left_default' => '1.5rem',
			),
			'color_bg' => 'inherit',
			'el_class' => 'grid_arrow_top',
			'hover' => TRUE,
			'translateY' => '101%',
			'transition_duration' => '0.35s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_custom_field:1' => array(
			'key' => 'us_tile_additional_image',
			'type' => 'image',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'translateY' => '100%',
			'transition_duration' => '0.35s',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'post_custom_field:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
			'color_text' => us_get_color( 'color_content_text' ),
		),
	),
),

'portfolio_2' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 2',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity_hover' => '0.1',
			'scale_hover' => '1.1',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'bg_gradient' => TRUE,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '4rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'transition_duration' => '0.35s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => '#ffffff',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'color_text' => '#ffffff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
			'color_bg' => '#333333',
		),
	),
),

'portfolio_3' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 3',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0.35',
			'transition_duration' => '0.4s',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => TRUE,
			'opacity_hover' => '0',
			'translateY_hover' => '-100%',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => TRUE,
			'opacity_hover' => '0',
			'translateY_hover' => '100%',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_4' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 4',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity_hover' => '0.2',
		),
		'vwrapper:1' => array(
			'valign' => 'bottom',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => TRUE,
			'opacity' => '0',
			'translateY' => '-60%',
			'transition_duration' => '0.35s',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => TRUE,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateY' => '-30%',
			'transition_duration' => '0.35s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_5' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 5',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'scale_hover' => '1.2',
			'transition_duration' => '0.4s',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '1.3rem',
				'position_right_default' => '1.3rem',
				'position_bottom_default' => '1.3rem',
				'position_left_default' => '1.3rem',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => TRUE,
			'color_bg' => 'inherit',
			'opacity' => '0',
			'opacity_hover' => '0.9',
			'scale' => '0',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
			'color_text' => us_get_color( 'color_content_text' ),
		),
	),
),

'portfolio_6' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 6',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity_hover' => '0.1',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'scale' => '1.5',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_7' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 7',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'scale' => '1.1',
			'transition_duration' => '0.4s',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2.6rem',
				'padding_right_default' => '2.6rem',
				'padding_bottom_default' => '2.6rem',
				'padding_left_default' => '2.6rem',
			),
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => TRUE,
			'opacity' => '0',
			'translateY' => '30%',
			'transition_duration' => '0.4s',
		),
		'html:1' => array(
			'design_options' => array(
				'position_top_default' => '1.3rem',
				'position_right_default' => '1.3rem',
				'position_bottom_default' => '1.3rem',
				'position_left_default' => '1.3rem',
				'border_top_default' => '2px',
				'border_right_default' => '2px',
				'border_bottom_default' => '2px',
				'border_left_default' => '2px',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'scale' => '1.1',
			'transition_duration' => '0.4s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'html:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_8' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 8',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'width' => '110%',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity_hover' => '0.1',
			'translateX' => is_rtl() ? '8%' : '-8%',
			'transition_duration' => '0.4s',
		),
		'vwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => TRUE,
			'opacity' => '0',
			'translateX' => '-33%',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => TRUE,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateX' => '40%',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_9' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 9',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity_hover' => '0',
			'scale_hover' => '4',
			'transition_duration' => '0.4s',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => TRUE,
			'scale' => '0',
			'transition_duration' => '0.5s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_10' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 10',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
		),
		'vwrapper:1' => array(
			'bg_gradient' => TRUE,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '-1px',
				'position_left_default' => '0',
				'padding_top_default' => '5rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'transition_duration' => '0.4s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => '#ffffff',
			'hover' => TRUE,
			'translateY' => '35%',
			'transition_duration' => '0.35s',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'color_text' => '#ffffff',
			'hover' => TRUE,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateY' => '100%',
			'transition_duration' => '0.35s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_11' => array (
	'title' => __( 'Portfolio', 'us' ) . ' 11',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'translateY' => '-25%',
			'transition_duration' => '0.35s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
		'html:1' => array(
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'border_bottom_default' => '10px',
			),
			'hover' => TRUE,
			'translateY' => '100%',
			'transition_duration' => '0.35s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
				'html:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_12' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 12',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '4rem',
				'padding_right_default' => '4rem',
				'padding_bottom_default' => '4rem',
				'padding_left_default' => '4rem',
			),
			'el_class' => 'grid_style_12',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => TRUE,
			'translateY' => '-50%',
			'transition_duration' => '0.35s',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => TRUE,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateY' => '75%',
			'transition_duration' => '0.35s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_13' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 13',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'design_options' => array(
				'margin_bottom_default' => '1.3rem',
			),
			'hover' => TRUE,
			'translateY' => '30%',
			'transition_duration' => '0.35s',
		),
		'post_date:1' => array(
			'design_options' => array(
				'position_left_default' => '0',
				'position_bottom_default' => '0',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'translateY' => '100%',
			'transition_duration' => '0.35s',
		),
		'html:1' => array(
			'design_options' => array(
				'border_top_default' => '4px',
				'border_bottom_default' => '0',
			),
			'width' => '100%',
			'hover' => TRUE,
			'opacity' => '0',
			'translateY' => '900%',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
				'post_date:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'html:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_14' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 14',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'scale' => '1.15',
			'translateX' => '-6%',
			'transition_duration' => '0.35s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'translateX' => '-50%',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'post_date:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_15' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 15',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0.9',
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'el_class' => 'grid_style_15',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => TRUE,
			'opacity' => '0',
			'translateY' => '60%',
			'transition_duration' => '0.35s',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => TRUE,
			'opacity' => '0',
			'transition_duration' => '0.35s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_16' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 16',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'circle' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'el_class' => 'grid_corner_image',
			'hover' => TRUE,
			'scale' => '0.3',
			'transition_duration' => '0.4s',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '8%',
				'padding_right_default' => '33%',
				'padding_left_default' => '8%',
			),
			'hover' => TRUE,
			'opacity_hover' => '0',
			'scale_hover' => '2',
			'translateX_hover' => '-50%',
			'translateY_hover' => '-50%',
			'transition_duration' => '0.4s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'font_weight' => '700',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_17' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 17',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity_hover' => '0.1',
			'scale_hover' => '1.3',
			'translateX_hover' => '-11%',
			'translateY_hover' => '-11%',
			'transition_duration' => '1s',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => TRUE,
			'translateY' => '60%',
			'transition_duration' => '0.4s',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => TRUE,
			'opacity' => '0',
			'scale' => '0.75',
			'translateX' => '50%',
			'transition_duration' => '0.4s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_18' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 18',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'opacity_hover' => '0.1',
			'scale_hover' => '1.1',
			'transition_duration' => '0.35s',
		),
		'vwrapper:1' => array(
			'bg_gradient' => TRUE,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '5rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'transition_duration' => '1s',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'color_text' => '#ffffff',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'color_text' => '#ffffff',
		),
		'post_custom_field:1' => array(
			'key' => 'us_tile_additional_image',
			'type' => 'image',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0',
			'transition_duration' => '1s',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'post_custom_field:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'post_date:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

'portfolio_compact' => array(
	'title' => __( 'Portfolio', 'us' ) . ' ' . __( 'Compact', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
				'padding_bottom_default' => '0.8rem',
				'padding_left_default' => '0.8rem',
			),
			'color_bg' => 'rgba(0,0,0,0.8)',
			'hover' => TRUE,
			'opacity' => '0',
		),
		'post_title:1' => array(
			'font_size' => '1.2rem',
			'link' => 'none',
			'font_size' => '0.7rem',
			'tag' => 'h4',
			'color_text' => '#ffffff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
		),
	),
),

/* Testimonial =========================================================================== */

'testimonial_1' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 1',
	'group' => __( 'Testimonial Templates', 'us' ),
	'data' => array(
		'vwrapper:1' => array(
			'design_options' => array(
				'border_top_default' => '2px',
				'border_right_default' => '2px',
				'border_bottom_default' => '2px',
				'border_left_default' => '2px',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'color_border' => us_get_color( 'color_content_border' ),
			'border_radius' => '0.3rem',
			'hover' => TRUE,
			'color_border_hover' => us_get_color( 'color_content_primary' ),
		),
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'margin_top_default' => '1rem',
			),
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:2' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => FALSE,
			'font_weight' => '700',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'post_custom_field:3' => array(
			'key' => 'us_testimonial_rating',
			'color_text' => us_get_color( 'color_content_primary' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_custom_field:3',
				'post_content:1',
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:2',
			),
			'vwrapper:2' => array(
				'post_custom_field:1',
				'post_custom_field:2',
			),
		),
	),
),

'testimonial_2' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 2',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'margin_top_default' => '1rem',
			),
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => FALSE,
			'font_weight' => '700',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'design_options' => array(
				'padding_top_default' => '3.5rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_custom_field:3' => array(
			'key' => 'custom',
			'font_size' => '3rem',
			'line_height' => '1',
			'icon' => 'fas|quote-left',
			'design_options' => array(
				'position_top_default' => '0',
				'position_left_default' => '0',
			),
			'color_text' => us_get_color( 'color_content_primary' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'vwrapper:2',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_custom_field:1',
				'post_custom_field:2',
			),
			'vwrapper:2' => array(
				'post_custom_field:3',
				'post_content:1',
				'hwrapper:1',
			),
		),
	),
),

'testimonial_3' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 3',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => FALSE,
			'font_weight' => '700',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'design_options' => array(
				'padding_left_default' => '2rem',
			),
		),
		'post_custom_field:3' => array(
			'key' => 'custom',
			'font_size' => '1.4rem',
			'line_height' => '1',
			'icon' => 'fas|quote-left',
			'design_options' => array(
				'position_top_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => TRUE,
			'opacity' => '0.2',
			'opacity_hover' => '0.2',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'vwrapper:2',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_custom_field:1',
				'post_custom_field:2',
			),
			'vwrapper:2' => array(
				'post_custom_field:3',
				'post_content:1',
				'hwrapper:1',
			),
		),
	),
),

'testimonial_4' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 4',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'placeholder' => TRUE,
			'circle' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'width' => '5.5rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
			'el_class' => 'with_quote_icon',
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => FALSE,
			'font_weight' => '700',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_content:1',
				'post_custom_field:1',
				'post_custom_field:2',
			),
		),
	),
),

'testimonial_5' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 5',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'width' => '7rem',
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => FALSE,
			'font_weight' => '700',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'alignment' => 'center',
		),
		'post_custom_field:3' => array(
			'key' => 'us_testimonial_rating',
			'font_size' => '1.2rem',
			'color_text' => us_get_color( 'color_content_primary' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'vwrapper:2',
			),
			'vwrapper:2' => array(
				'post_custom_field:3',
				'post_content:1',
				'post_image:1',
				'post_custom_field:1',
				'post_custom_field:2',
			),
		),
	),
),

'testimonial_6' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 6',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'padding_top_default' => '1.5rem',
				'padding_right_default' => '2.5rem',
				'padding_left_default' => '2.5rem',
			),
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => TRUE,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => FALSE,
			'font_weight' => '700',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
		'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_color( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'design_options' => array(
				'padding_top_default' => '2rem',
				'padding_right_default' => '2.5rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2.5rem',
			),
			'color_bg' => us_get_color( 'color_content_bg_alt', TRUE ),
			'color_text' => us_get_color( 'color_content_text' ),
			'border_radius' => '0.3rem',
			'el_class' => 'grid_arrow_bottom',
			'hover' => TRUE,
			'color_bg_hover' => us_get_color( 'color_content_primary', TRUE ),
			'color_text_hover' => '#fff',
		),
		'post_custom_field:3' => array(
			'key' => 'us_testimonial_rating',
			'color_text' => '#fb0',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'vwrapper:2',
				'hwrapper:1',
			),
			'hwrapper:1' => array(
				'post_image:1',
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_custom_field:1',
				'post_custom_field:2',
			),
			'vwrapper:2' => array(
				'post_custom_field:3',
				'post_content:1',
			),
		),
	),
),

/* Shop =========================================================================== */

'shop_standard' => array(
	'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ) . ' ' . __( 'Standard', 'us' ),
	'group' => __( 'Shop Templates', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
			'thumbnail_size' => 'shop_catalog',
		),
		'product_field:1' => array(
			'type' => 'sale_badge',
			'font_size' => '12px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'position_top_default' => '10px',
				'position_left_default' => '10px',
				'padding_left_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
			),
			'color_bg' => us_get_color( 'color_content_primary', TRUE ),
			'color_text' => '#fff',
			'border_radius' => '2rem',
		),
		'post_title:1' => array(
			'font_size' => '1rem',
			'design_options' => array(
				'margin_top_default' => '0.8rem',
				'margin_bottom_default' => '0.2rem',
			),
		),
		'product_field:2' => array(
			'type' => 'rating',
			'design_options' => array(
				'margin_bottom_default' => '0.2rem',
			),
		),
		'product_field:3' => array(
			'font_weight' => '700',
		),
		'add_to_cart:1' => array(
			'view_cart_link' => TRUE,
			'font_size' => '0.8rem',
			'design_options' => array(
				'margin_top_default' => '0.4rem',
			),
			'border_radius' => '0.2rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'product_field:1',
				'post_title:1',
				'product_field:2',
				'product_field:3',
				'add_to_cart:1',
			),
		),
	),
),

'shop_modern' => array(
	'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ) . ' ' . __( 'Modern', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
			'thumbnail_size' => 'shop_catalog',
		),
		'product_field:1' => array(
			'type' => 'sale_badge',
			'font_size' => '12px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'position_top_default' => '10px',
				'position_left_default' => '10px',
				'padding_left_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
			),
			'color_bg' => us_get_color( 'color_content_primary', TRUE ),
			'color_text' => '#fff',
			'border_radius' => '2rem',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'design_options' => array(
				'padding_top_default' => '1rem',
				'padding_right_default' => '1.2rem',
				'padding_bottom_default' => '1rem',
				'padding_left_default' => '1.2rem',
			),
			'color_bg' => 'inherit',
			'hover' => TRUE,
			'translateY_hover' => '-2.4rem',
			'transition_duration' => '0.2s',
		),
		'post_title:1' => array(
			'font_size' => '1rem',
			'design_options' => array(
				'margin_bottom_default' => '0.3rem',
			),
		),
		'product_field:2' => array(
			'type' => 'rating',
			'design_options' => array(
				'margin_bottom_default' => '0.3rem',
			),
		),
		'product_field:3' => array(
			'font_weight' => '700',
		),
		'add_to_cart:1' => array(
			'font_size' => '0.8rem',
			'design_options' => array(
				'position_left_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
			),
			'width' => '100%',
			'hover' => TRUE,
			'opacity' => '0',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_image:1',
				'product_field:1',
				'vwrapper:1',
				'add_to_cart:1',
			),
			'vwrapper:1' => array(
				'post_title:1',
				'product_field:2',
				'product_field:3',
			),
		),
		'options' => array(
			'overflow' => TRUE,
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
			'border_radius' => '0.3rem',
			'box_shadow' => '0.3rem',
			'box_shadow_hover' => '1rem',
		),
	),
),

'shop_trendy' => array(
	'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ) . ' ' . __( 'Trendy', 'us' ),
	'data' => array(
		'vwrapper:1' => array(
			'design_options' => array(
				'padding_top_default' => '10px',
				'padding_right_default' => '10px',
				'padding_left_default' => '10px',
			),
		),
		'post_image:1' => array(
			'placeholder' => TRUE,
			'thumbnail_size' => 'shop_catalog',
			'width' => '100%',
		),
		'product_field:1' => array(
			'type' => 'sale_badge',
			'font_size' => '12px',
			'font_weight' => '700',
			'text_transform' => 'uppercase',
			'design_options' => array(
				'position_top_default' => '10px',
				'position_left_default' => '10px',
				'padding_left_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
			),
			'color_bg' => us_get_color( 'color_content_primary', TRUE ),
			'color_text' => '#fff',
		),
		'post_title:1' => array(
			'font_size' => '1rem',
			'design_options' => array(
				'margin_bottom_default' => '0.4rem',
			),
		),
		'product_field:2' => array(
			'type' => 'rating',
			'design_options' => array(
				'margin_bottom_default' => '0.2rem',
			),
		),
		'product_field:3' => array(
			'font_weight' => '700',
		),
		'add_to_cart:1' => array(
			'font_size' => '15px',
			'design_options' => array(
				'position_left_default' => '0',
				'position_right_default' => '0',
				'position_top_default' => '100%',
			),
			'width' => '100%',
			'hide_below' => '600',
			'hover' => TRUE,
			'opacity' => '0',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'vwrapper:1',
			),
			'vwrapper:1' => array(
				'post_image:1',
				'product_field:1',
				'post_title:1',
				'product_field:2',
				'product_field:3',
				'add_to_cart:1',
			),
		),
		'options' => array(
			'color_bg' => us_get_color( 'color_content_bg', TRUE ),
			'box_shadow_hover' => '1rem',
		),
	),
),

'tile_21_right' => array(
	'title' => 'Gray Tile 2:1 with image at right',
	'data' => array(
		'post_image:1' => array(
			'placeholder' => TRUE,
			'design_options' => array(
				'position_top_default' => '0',
				'position_left_default' => '50%',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
			),
		),
		'post_title:1' => array(
			'font_size' => '1.4rem',
			'design_options' => array(
				'position_top_default' => '0',
				'position_left_default' => '0',
				'position_right_default' => '50%',
				'position_bottom_default' => '0',
				'padding_top_default' => '8%',
				'padding_left_default' => '8%',
				'padding_right_default' => '8%',
				'padding_bottom_default' => '8%',
				'margin_bottom_default' => '0',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				'post_title:1',
				'post_image:1',
			),
		),
		'options' => array(
			'fixed' => TRUE,
			'ratio' => 'custom',
			'ratio_width' => '2',
			'ratio_height' => '1',
			'color_bg' => us_get_color( 'color_content_bg_alt', TRUE ),
			'color_text' => us_get_color( 'color_content_heading' ),
		),
	),
),

);
