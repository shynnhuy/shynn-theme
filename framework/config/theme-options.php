<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options
 *
 * @filter us_config_theme-options
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

global $us_template_directory, $us_template_directory_uri, $usof_img_sizes, $usof_enable_portfolio, $usof_sidebar_titlebar;

if ( ! isset( $usof_img_sizes ) ) {
	$usof_img_sizes = array();
}
if ( ! isset( $usof_enable_portfolio ) ) {
	$usof_enable_portfolio = 1;
}
if ( ! isset( $usof_sidebar_titlebar ) ) {
	$usof_sidebar_titlebar = 0;
}

// Get Pages and order alphabetically
$us_page_list = us_get_posts_titles_for( 'page' );

// Get Headers
$us_headers_list = us_get_posts_titles_for( 'us_header' );

// Get Page Blocks
$us_page_blocks_list = us_get_posts_titles_for( 'us_page_block' );

// Get Sidebars
$sidebars_list = us_get_sidebars();

// Descriptions
$misc = us_config( 'elements_misc' );
$misc['headers_description'] .= '<br><img src="' . $us_template_directory_uri . '/framework/admin/img/usof/l-header.png">';
$misc['content_description'] .= '<br><img src="' . $us_template_directory_uri . '/framework/admin/img/usof/l-content.png">';
$misc['footers_description'] .= '<br><img src="' . $us_template_directory_uri . '/framework/admin/img/usof/l-footer.png">';

// Get CSS & JS assets
$usof_assets = array();
$assets_config = us_config( 'assets', array() );
foreach ( $assets_config as $component => $component_atts ) {
	if ( isset( $component_atts['hidden'] ) AND $component_atts['hidden'] ) {
		continue;
	}
	$usof_assets[ $component ] = array(
		'title' => $component_atts['title'],
		'group' => isset( $component_atts['group'] ) ? $component_atts['group'] : NULL,
	);
	if ( isset( $component_atts['apply_if'] ) ) {
		$usof_assets[ $component ]['apply_if'] = $component_atts['apply_if'];
	}
	if ( isset( $component_atts['css'] ) ) {
		$usof_assets[ $component ]['css_size'] = file_exists( $us_template_directory . $component_atts['css'] ) ? number_format( ( filesize( $us_template_directory . $component_atts['css'] ) / 1024 ) * 0.8, 1 ) : NULL;
	}
	if ( isset( $component_atts['js'] ) ) {
		$js_filename = str_replace( '.js', '.min.js', $us_template_directory . $component_atts['js'] );
		$usof_assets[ $component ]['js_size'] = file_exists( $js_filename ) ? number_format( filesize( $js_filename ) / 1024, 1 ) : NULL;
	}
}
$optimize_assets_add_class = ' blocked';
$optimize_assets_alert_add_class = '';
$upload_dir = wp_upload_dir();
if ( wp_is_writable( $upload_dir['basedir'] ) ) {
	$optimize_assets_add_class = '';
	$optimize_assets_alert_add_class = ' hidden';
}

// Get public CPTs
$public_cpt = us_get_public_cpt();

// Predefine types for 'Pages Layout' options
$pages_layout_predefined_types = array(
	// Example: type => title
	'post' => us_translate_x( 'Posts', 'post type general name' ),
	'portfolio' => __( 'Portfolio Pages', 'us' ),
);
if ( ! $usof_enable_portfolio ) {
	unset( $pages_layout_predefined_types['portfolio'] );
}

// Add public CPTs for 'Pages Layout' options
$pages_layout_predefined_types = us_array_merge( $pages_layout_predefined_types, $public_cpt );

// Generate 'Pages Layout' options
$pages_layout_config = array();
foreach ( $pages_layout_predefined_types as $type => $title ) {

	// Change empty content name for WooCommerce products
	if ( $type == 'product' ) {
		$empty_content_template_name = '&ndash; ' . __( 'Default WooCommerce template', 'us' ) . ' &ndash;';
	} else {
		$empty_content_template_name = '&ndash; ' . __( 'Show content as is', 'us' ) . ' &ndash;';
	}

	$pages_layout_config = array_merge(
		$pages_layout_config, array(
			'h_' . $type => array(
				'title' => $title,
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			// Header
			'header_' . $type . '_id' => array(
				'title' => _x( 'Header', 'site top area', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_header',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), $us_headers_list
				),
				'std' => '__defaults__',
				'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
			),
			// Titlebar
			'titlebar_' . $type . '_id' => array(
				'title' => __( 'Titlebar', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_page_block',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), $us_page_blocks_list
				),
				'std' => '__defaults__',
				'place_if' => $usof_sidebar_titlebar,
			),
			// Content
			'content_' . $type . '_id' => array(
				'title' => __( 'Content template', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_page_block',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
						'' => $empty_content_template_name,
					), $us_page_blocks_list
				),
				'std' => '__defaults__',
			),
			// Sidebar
			'sidebar_' . $type . '_id' => array(
				'title' => __( 'Sidebar', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), $sidebars_list
				),
				'std' => '__defaults__',
				'place_if' => $usof_sidebar_titlebar,
			),
			// Sidebar Position
			'sidebar_' . $type . '_pos' => array(
				'type' => 'radio',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'right',
				'classes' => 'for_above',
				'show_if' => array( 'sidebar_' . $type . '_id', 'not in', array( '', '__defaults__' ) ),
				'place_if' => $usof_sidebar_titlebar,
			),
			// Footer
			'footer_' . $type . '_id' => array(
				'title' => __( 'Footer', 'us' ),
				'type' => 'select',
				'hints_for' => 'us_page_block',
				'options' => us_array_merge(
					array(
						'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
						'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
					), $us_page_blocks_list
				),
				'std' => '__defaults__',
			),
		)
	);
}

// Generate 'Archives Layout' options
$archives_layout_config = $shop_layout_config = array();
$public_taxonomies = us_get_taxonomies( TRUE, FALSE );
foreach ( $public_taxonomies as $type => $title ) {

	// Change default name for WooCommerce taxonomies
	if ( in_array( $type, array( 'product_cat', 'product_tag' ) ) ) {
		$shop_layout_config = array_merge(
			$shop_layout_config, array(
				'h_tax_' . $type => array(
					'title' => $title,
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				// Header
				'header_tax_' . $type . '_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_header',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Shop Page', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_headers_list
					),
					'std' => '__defaults__',
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				// Titlebar
				'titlebar_tax_' . $type . '_id' => array(
					'title' => __( 'Titlebar', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Shop Page', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				// Content
				'content_tax_' . $type . '_id' => array(
					'title' => __( 'Content template', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Shop Page', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),
				// Sidebar
				'sidebar_tax_' . $type . '_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'type' => 'select',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Shop Page', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $sidebars_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				// Sidebar Position
				'sidebar_tax_' . $type . '_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_tax_' . $type . '_id', 'not in', array( '', '__defaults__' ) ),
					'place_if' => $usof_sidebar_titlebar,
				),
				// Footer
				'footer_tax_' . $type . '_id' => array(
					'title' => __( 'Footer', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Shop Page', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),
			)
		);
	} else {
		$archives_layout_config = array_merge(
			$archives_layout_config, array(
				'h_tax_' . $type => array(
					'title' => $title,
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				// Header
				'header_tax_' . $type . '_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_header',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_headers_list
					),
					'std' => '__defaults__',
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				// Titlebar
				'titlebar_tax_' . $type . '_id' => array(
					'title' => __( 'Titlebar', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				// Content
				'content_tax_' . $type . '_id' => array(
					'title' => __( 'Content template', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),
				// Sidebar
				'sidebar_tax_' . $type . '_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'type' => 'select',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $sidebars_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				// Sidebar Position
				'sidebar_tax_' . $type . '_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_tax_' . $type . '_id', 'not in', array( '', '__defaults__' ) ),
					'place_if' => $usof_sidebar_titlebar,
				),
				// Footer
				'footer_tax_' . $type . '_id' => array(
					'title' => __( 'Footer', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),
			)
		);
	}

}

// Custom Images Sizes description
$img_size_info = sprintf( __( 'To change the default image sizes, go to %s.', 'us' ), '<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">' . us_translate( 'Media Settings' ) . '</a>' );
// Add link to Customizing > WooCommerce > Product Images
if ( class_exists( 'woocommerce' ) ) {
	$img_size_info .= ' ' . sprintf(
			__( 'To change the Product image sizes, go to %s.', 'us' ), '<a target="_blank" href="' . esc_url(
				add_query_arg(
					array(
						'autofocus' => array(
							'panel' => 'woocommerce',
							'section' => 'woocommerce_product_images',
						),
						'url' => wc_get_page_permalink( 'shop' ),
					), admin_url( 'customize.php' )
				)
			) . '">' . us_translate( 'WooCommerce settings', 'woocommerce' ) . '</a>'
		);
}
$img_size_info .= ' ' . sprintf( __( 'Read about %simage sizes and how to use them%s.', 'us' ), '<a target="_blank" href="https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/general/images/">', '</a>' );
$img_size_description = '<a href="#image_sizes">' . __( 'Edit image sizes', 'us' ) . '</a>.';

// Specify "Background Position" control values
$usof_bg_pos_values = array(
	'top left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
	'top center' => '<span class="dashicons dashicons-arrow-up-alt"></span>',
	'top right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
	'center left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
	'center center' => '<span class="dashicons dashicons-marker"></span>',
	'center right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
	'bottom left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
	'bottom center' => '<span class="dashicons dashicons-arrow-down-alt"></span>',
	'bottom right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
);

// Generate Typography Headings
$headings_typography_config = array();
for ( $i = 1; $i <= 6; $i ++ ) {

	$default_fontsize = 1 + 2 / $i;
	$default_fontsize = number_format( $default_fontsize, 1 );

	$headings_typography_config = array_merge(
		$headings_typography_config, array(
			'h' . $i . '_font_family' => array(
				'type' => 'font',
				'preview' => array(
					'text' => sprintf( __( 'Heading %s Preview', 'us' ), $i ),
					'size_field' => 'h' . $i . '_fontsize',
					'weight_field' => 'h' . $i . '_fontweight',
					'letterspacing_field' => 'h' . $i . '_letterspacing',
					'transform_field' => 'h' . $i . '_transform',
					'color_field' => 'h' . $i . '_color',
					'for_heading' => TRUE,
					'get_h1' => ( $i == 1 ) ? FALSE : TRUE,
				),
				'std' => ( $i == 1 ) ? 'none|' : 'get_h1|',
			),
			'h' . $i . '_left_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_font col_first',
			),
			'h' . $i . '_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'std' => $default_fontsize . 'rem',
				'options' => array(
					'px' => array(
						'min' => 10,
						'max' => 60,
					),
					'em' => array(
						'min' => 1.0,
						'max' => 5.0,
						'step' => 0.1,
					),
					'rem' => array(
						'min' => 1.0,
						'max' => 5.0,
						'step' => 0.1,
					),
				),
				'classes' => 'inline slider_below',
			),
			'h' . $i . '_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'std' => '1.2',
				'options' => array(
					'' => array(
						'min' => 1.00,
						'max' => 2.00,
						'step' => 0.01,
					),
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'classes' => 'inline slider_below',
			),
			'h' . $i . '_fontweight' => array(
				'description' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'std' => '400',
				'options' => array(
					'' => array(
						'min' => 100,
						'max' => 900,
						'step' => 100,
					),
				),
				'classes' => 'inline slider_below',
			),
			'h' . $i . '_letterspacing' => array(
				'description' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'std' => '0',
				'options' => array(
					'em' => array(
						'min' => - 0.10,
						'max' => 0.20,
						'step' => 0.01,
					),
				),
				'classes' => 'inline slider_below',
			),
			'h' . $i . '_left_end' => array(
				'type' => 'wrapper_end',
			),
			'h' . $i . '_right_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_font',
			),
			'h' . $i . '_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'std' => $default_fontsize . 'rem',
				'options' => array(
					'px' => array(
						'min' => 10,
						'max' => 60,
					),
					'em' => array(
						'min' => 1.0,
						'max' => 5.0,
						'step' => 0.1,
					),
					'rem' => array(
						'min' => 1.0,
						'max' => 5.0,
						'step' => 0.1,
					),
				),
				'classes' => 'inline slider_below',
			),
			'h' . $i . '_bottom_indent' => array(
				'description' => __( 'Bottom indent', 'us' ),
				'type' => 'slider',
				'std' => '1.5rem',
				'options' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
					'em' => array(
						'min' => 0.1,
						'max' => 5.0,
						'step' => 0.1,
					),
					'rem' => array(
						'min' => 0.1,
						'max' => 5.0,
						'step' => 0.1,
					),
				),
				'classes' => 'inline slider_below',
			),
			'h' . $i . '_transform' => array(
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
				'classes' => 'inline',
			),
			'h' . $i . '_color' => array(
				'type' => 'color',
				'text' => us_translate( 'Color' ),
				'std' => '',
				'classes' => 'inline clear_left',
			),
			'h' . $i . '_right_end' => array(
				'type' => 'wrapper_end',
			),
		)
	);
}

// Theme Options Config
return array(
	'general' => array(
		'title' => us_translate_x( 'General', 'settings screen' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/mixer',
		'fields' => array(

			'maintenance_mode' => array(
				'title' => __( 'Maintenance Mode', 'us' ),
				'description' => __( 'When this option is ON, all not logged in users will see only specific page selected by you. This is useful when your site is under construction.', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Show for site visitors only specific page', 'us' ),
				'std' => 0,
				'classes' => 'color_yellow desc_3',
			),
			'maintenance_page' => array(
				'type' => 'select',
				'options' => $us_page_list,
				'std' => '',
				'hints_for' => 'page',
				'classes' => 'for_above',
				'show_if' => array( 'maintenance_mode', '=', TRUE ),
			),
			'maintenance_503' => array(
				'description' => __( 'When this option is ON, your site will send HTTP 503 response to search engines. Use this option only for short period of time.', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Enable "503 Service Unavailable" status', 'us' ),
				'std' => 0,
				'classes' => 'for_above desc_3',
				'show_if' => array( 'maintenance_mode', '=', TRUE ),
			),
			'search_page' => array(
				'title' => __( 'Search Results Page', 'us' ),
				'description' => __( 'Selected page must contain Grid element showing items of the current query.', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array( 'default' => '&ndash; ' . __( 'Show results via Grid element with defaults', 'us' ) . ' &ndash;' ), $us_page_list
				),
				'std' => 'default',
				'hints_for' => 'page',
				'classes' => 'desc_3',
			),
			// Posts page is set in Settings > Reading
			'posts_page' => array(
				'title' => us_translate( 'Posts page' ),
				'description' => __( 'Selected page must contain Grid element showing items of the current query.', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array( 'default' => '&ndash; ' . __( 'Show results via Grid element with defaults', 'us' ) . ' &ndash;' ), $us_page_list
				),
				'std' => 'default',
				'hints_for' => 'page',
				'classes' => 'desc_3',
			),
			'page_404' => array(
				'title' => __( 'Page "404 Not Found"', 'us' ),
				'description' => __( 'Selected page will be shown instead of the "Page not found" message.', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array( 'default' => '&ndash; ' . us_translate( 'Default' ) . ' &ndash;' ), $us_page_list
				),
				'std' => 'default',
				'hints_for' => 'page',
				'classes' => 'desc_3',
			),
			'site_icon' => array(
				'title' => us_translate( 'Site Icon' ),
				'description' => us_translate( 'Site Icons are what you see in browser tabs, bookmark bars, and within the WordPress mobile apps. Upload one here!' ) . '<br>' . sprintf( us_translate( 'Site Icons should be square and at least %s pixels.' ), '<strong>512</strong>' ),
				'type' => 'upload',
				'classes' => 'desc_3',
			),
			'preloader' => array(
				'title' => __( 'Preloader Screen', 'us' ),
				'type' => 'select',
				'options' => array(
					'disabled' => us_translate( 'None' ),
					'1' => sprintf( __( 'Shows Preloader %d', 'us' ), 1 ),
					'2' => sprintf( __( 'Shows Preloader %d', 'us' ), 2 ),
					'3' => sprintf( __( 'Shows Preloader %d', 'us' ), 3 ),
					'4' => sprintf( __( 'Shows Preloader %d', 'us' ), 4 ),
					'5' => sprintf( __( 'Shows Preloader %d', 'us' ), 5 ),
					'custom' => __( 'Shows Custom Image', 'us' ),
				),
				'std' => 'disabled',
			),
			'preloader_image' => array(
				'title' => '',
				'type' => 'upload',
				'classes' => 'for_above',
				'show_if' => array( 'preloader', '=', 'custom' ),
			),
			'rounded_corners' => array(
				'title' => __( 'Rounded Corners', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Enable rounded corners of theme elements', 'us' ),
				'std' => 1,
			),
			'links_underline' => array(
				'title' => __( 'Links Underline', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Underline text links on hover', 'us' ),
				'std' => 0,
			),
			'keyboard_accessibility' => array(
				'title' => __( 'Keyboard Accessibility', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Highlight theme elements on focus', 'us' ),
				'std' => 0,
			),
			'back_to_top' => array(
				'title' => __( '"Back to top" Button', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Enable button which scrolls a page back to the top', 'us' ),
				'std' => 1,
			),
			'wrapper_back_to_top_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'force_right',
				'show_if' => array( 'back_to_top', '=', TRUE ),
			),
			'back_to_top_pos' => array(
				'title' => __( 'Button Position', 'us' ),
				'type' => 'radio',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'right',
				'classes' => 'width_full cols_2',
			),
			'back_to_top_color' => array(
				'type' => 'color',
				'title' => __( 'Button Color', 'us' ),
				'std' => 'rgba(0,0,0,0.3)',
				'classes' => 'width_full cols_2',
			),
			'back_to_top_display' => array(
				'title' => __( 'Show Button after page is scrolled to', 'us' ),
				'description' => __( '1vh equals 1% of the screen height', 'us' ),
				'type' => 'slider',
				'std' => '100vh',
				'options' => array(
					'vh' => array(
						'min' => 10,
						'max' => 200,
						'step' => 10,
					),
				),
				'classes' => 'width_full desc_3',
			),
			'wrapper_back_to_top_end' => array(
				'type' => 'wrapper_end',
			),
			'smooth_scroll_duration' => array(
				'title' => __( 'Smooth Scroll Duration', 'us' ),
				'type' => 'slider',
				'std' => '1000ms',
				'options' => array(
					'ms' => array(
						'min' => 0,
						'max' => 3000,
						'step' => 100,
					),
				),
			),
			'gmaps_api_key' => array(
				'title' => __( 'Google Maps API Key', 'us' ),
				'description' => __( 'The API key is required for the domains created after June 22, 2016.', 'us' ) . ' <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">' . __( 'Get API key', 'us' ) . '</a>',
				'type' => 'text',
				'std' => '',
				'classes' => 'desc_3',
			),
		),
	),
	'layout' => array(
		'title' => __( 'Site Layout', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/layout',
		'fields' => array(
			'canvas_layout' => array(
				'title' => __( 'Site Canvas Layout', 'us' ),
				'type' => 'imgradio',
				'options' => array(
					'wide' => 'framework/admin/img/usof/canvas-wide',
					'boxed' => 'framework/admin/img/usof/canvas-boxed',
				),
				'std' => 'wide',
			),
			'color_body_bg' => array(
				'type' => 'color',
				'title' => __( 'Body Background Color', 'us' ),
				'std' => '#eeeeee',
				'show_if' => array( 'canvas_layout', '=', 'boxed' ),
			),
			'body_bg_image' => array(
				'title' => __( 'Body Background Image', 'us' ),
				'type' => 'upload',
				'show_if' => array( 'canvas_layout', '=', 'boxed' ),
			),
			'wrapper_body_bg_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'force_right',
				'show_if' => array(
					array( 'canvas_layout', '=', 'boxed' ),
					'and',
					array( 'body_bg_image', '!=', '' ),
				),
			),
			'body_bg_image_size' => array(
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
			'body_bg_image_repeat' => array(
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
			'body_bg_image_position' => array(
				'title' => __( 'Background Image Position', 'us' ),
				'type' => 'radio',
				'options' => $usof_bg_pos_values,
				'std' => 'top left',
				'classes' => 'bgpos width_full',
			),
			'body_bg_image_attachment' => array(
				'type' => 'switch',
				'switch_text' => us_translate( 'Scroll with Page' ),
				'std' => 1,
				'classes' => 'width_full',
			),
			'wrapper_body_bg_end' => array(
				'type' => 'wrapper_end',
			),
			'site_canvas_width' => array(
				'title' => __( 'Site Canvas Width', 'us' ),
				'type' => 'slider',
				'std' => '1300px',
				'options' => array(
					'px' => array(
						'min' => 1000,
						'max' => 1700,
						'step' => 10,
					),
				),
				'show_if' => array( 'canvas_layout', '=', 'boxed' ),
			),
			'site_content_width' => array(
				'title' => __( 'Site Content Width', 'us' ),
				'type' => 'slider',
				'std' => '1140px',
				'options' => array(
					'px' => array(
						'min' => 900,
						'max' => 1600,
						'step' => 10,
					),
				),
			),
			'sidebar_width' => array(
				'title' => __( 'Sidebar Width', 'us' ),
				'type' => 'slider',
				'std' => '25%',
				'options' => array(
					'%' => array(
						'min' => 20,
						'max' => 40,
						'step' => 0.1,
					),
				),
				'place_if' => $usof_sidebar_titlebar,
			),
			'text_bottom_indent' => array(
				'title' => __( 'Text Blocks bottom indent', 'us' ),
				'type' => 'slider',
				'std' => '1.5rem',
				'options' => array(
					'rem' => array(
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					),
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
			),
			'row_height' => array(
				'title' => __( 'Row Height by default', 'us' ),
				'type' => 'select',
				'options' => array(
					'auto' => __( 'Equals the content height', 'us' ),
					'small' => __( 'Small', 'us' ),
					'medium' => __( 'Medium', 'us' ),
					'large' => __( 'Large', 'us' ),
					'huge' => __( 'Huge', 'us' ),
					'full' => __( 'Full Screen', 'us' ),
				),
				'std' => 'medium',
			),
			'disable_effects_width' => array(
				'title' => __( 'Effects Disabling Width', 'us' ),
				'description' => __( 'When screen width is less than this value, vertical parallax and animation of elements appearance will be disabled.', 'us' ),
				'type' => 'slider',
				'std' => '900px',
				'options' => array(
					'px' => array(
						'min' => 300,
						'max' => 1025,
					),
				),
				'classes' => 'desc_3',
			),
			'responsive_layout' => array(
				'title' => __( 'Responsive Layout', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Enable responsive layout', 'us' ),
				'std' => TRUE,
			),
			'columns_stacking_width' => array(
				'title' => __( 'Columns Stacking Width', 'us' ),
				'description' => __( 'When screen width is less than this value, all columns within a row will become a single column.', 'us' ),
				'type' => 'slider',
				'std' => '768px',
				'options' => array(
					'px' => array(
						'min' => 768,
						'max' => 1025,
					),
				),
				'classes' => 'desc_3',
				'show_if' => array( 'responsive_layout', '=', TRUE ),
			),
		),
	),

	// Header options, appear when Header Builder is disabled
	'header' => array(
		'title' => _x( 'Header', 'site top area', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/header',
		'fields' => array(
			'header_info' => array(
				'description' => sprintf( __( 'To add more elements into your website header, install %sHeader Builder addon%s.', 'us' ), '<strong><a href="' . admin_url( 'admin.php?page=us-addons' ) . '" target="_blank">', '</strong></a>' ),
				'type' => 'message',
				'classes' => 'width_full color_blue',
			),
			'header_layout' => array(
				'type' => 'imgradio',
				'options' => array(
					'simple_1' => 'framework/admin/img/usof/ht-standard',
					'extended_1' => 'framework/admin/img/usof/ht-extended',
					'extended_2' => 'framework/admin/img/usof/ht-advanced',
					'centered_1' => 'framework/admin/img/usof/ht-centered',
					'vertical_1' => 'framework/admin/img/usof/ht-sided',
				),
				'std' => 'simple_1',
				'classes' => 'width_full',
			),
			'header_sticky' => array(
				'title' => __( 'Sticky Header', 'us' ),
				'type' => 'checkboxes',
				'options' => array(
					'default' => __( 'On Desktops', 'us' ),
					'tablets' => __( 'On Tablets', 'us' ),
					'mobiles' => __( 'On Mobiles', 'us' ),
				),
				'description' => __( 'Fix the header at the top of a page during scroll', 'us' ),
				'std' => array( 'default', 'tablets', 'mobiles' ),
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_transparent' => array(
				'title' => __( 'Transparent Header', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Make the header transparent at its initial position', 'us' ),
				'std' => 0,
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_fullwidth' => array(
				'title' => __( 'Full Width Content', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Stretch header content to the screen width', 'us' ),
				'std' => 0,
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_top_height' => array(
				'title' => __( 'Top Area Height', 'us' ),
				'type' => 'slider',
				'std' => '40px',
				'options' => array(
					'px' => array(
						'min' => 36,
						'max' => 300,
					),
				),
				'show_if' => array( 'header_layout', '=', 'extended_1' ),
			),
			'header_top_sticky_height' => array(
				'title' => __( 'Top Sticky Area Height', 'us' ),
				'type' => 'slider',
				'std' => '0px',
				'options' => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'show_if' => array(
					array( 'header_sticky', 'has', 'default' ),
					'and',
					array( 'header_layout', '=', 'extended_1' ),
				),
			),
			'header_middle_height' => array(
				'title' => __( 'Main Area Height', 'us' ),
				'type' => 'slider',
				'std' => '100px',
				'options' => array(
					'px' => array(
						'min' => 50,
						'max' => 300,
					),
				),
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_middle_sticky_height' => array(
				'title' => __( 'Main Sticky Area Height', 'us' ),
				'type' => 'slider',
				'std' => '50px',
				'options' => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'show_if' => array(
					array( 'header_sticky', 'has', 'default' ),
					'and',
					array( 'header_layout', '!=', 'vertical_1' ),
				),
			),
			'header_bottom_height' => array(
				'title' => __( 'Bottom Area Height', 'us' ),
				'type' => 'slider',
				'std' => '50px',
				'options' => array(
					'px' => array(
						'min' => 36,
						'max' => 300,
					),
				),
				'show_if' => array( 'header_layout', 'in', array( 'extended_2', 'centered_1' ) ),
			),
			'header_bottom_sticky_height' => array(
				'title' => __( 'Bottom Sticky Area Height', 'us' ),
				'type' => 'slider',
				'std' => '50px',
				'options' => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'show_if' => array(
					array( 'header_sticky', 'has', 'default' ),
					'and',
					array( 'header_layout', 'in', array( 'extended_2', 'centered_1' ) ),
				),
			),
			'header_main_width' => array(
				'title' => __( 'Header Width', 'us' ),
				'type' => 'slider',
				'std' => '300px',
				'options' => array(
					'px' => array(
						'min' => 200,
						'max' => 400,
					),
				),
				'show_if' => array( 'header_layout', '=', 'vertical_1' ),
			),
			'header_invert_logo_pos' => array(
				'title' => __( 'Inverted Logo Position', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Place Logo to the right side of the Header', 'us' ),
				'std' => 0,
				'show_if' => array( 'header_layout', 'in', array( 'simple_1', 'extended_1', 'extended_2' ) ),
			),

			// Header elements
			'h_header_2' => array(
				'title' => __( 'Header Elements', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'header_search_show' => array(
				'type' => 'switch',
				'switch_text' => us_translate( 'Search' ),
				'std' => 1,
				'classes' => 'width_full',
			),
			'wrapper_search_start' => array(
				'type' => 'wrapper_start',
				'show_if' => array( 'header_search_show', '=', TRUE ),
			),
			'header_search_layout' => array(
				'title' => __( 'Layout', 'us' ),
				'type' => 'radio',
				'options' => array(
					'simple' => __( 'Simple', 'us' ),
					'modern' => __( 'Modern', 'us' ),
					'fullwidth' => __( 'Full Width', 'us' ),
					'fullscreen' => __( 'Full Screen', 'us' ),
				),
				'std' => 'fullscreen',
			),
			'wrapper_search_end' => array(
				'type' => 'wrapper_end',
			),
			'header_contacts_show' => array(
				'type' => 'switch',
				'switch_text' => us_translate( 'Contact Info' ),
				'std' => 0,
				'show_if' => array( 'header_layout', 'not in', array( 'simple_1', 'centered_1' ) ),
				'classes' => 'width_full',
			),
			'wrapper_contacts_start' => array(
				'type' => 'wrapper_start',
				'show_if' => array(
					array( 'header_layout', 'not in', array( 'simple_1', 'centered_1' ) ),
					'and',
					array( 'header_contacts_show', '=', TRUE ),
				),
			),
			'header_contacts_phone' => array(
				'title' => __( 'Phone Number', 'us' ),
				'type' => 'text',
				'classes' => 'cols_2 width_full',
			),
			'header_contacts_email' => array(
				'title' => us_translate( 'Email' ),
				'type' => 'text',
				'classes' => 'cols_2 width_full',
			),
			'wrapper_contacts_end' => array(
				'type' => 'wrapper_end',
			),

			// Logo
			'h_header_3' => array(
				'title' => __( 'Logo', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'logo_type' => array(
				'type' => 'radio',
				'options' => array(
					'text' => us_translate( 'Text' ),
					'img' => us_translate( 'Image' ),
				),
				'std' => 'text',
				'classes' => 'width_full',
			),
			'logo_text' => array(
				'title' => us_translate( 'Text' ),
				'type' => 'text',
				'std' => 'LOGO',
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_font_size' => array(
				'title' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'std' => '26px',
				'options' => array(
					'px' => array(
						'min' => 12,
						'max' => 50,
					),
				),
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_font_size_tablets' => array(
				'title' => __( 'Font Size on Tablets', 'us' ),
				'description' => sprintf( __( 'Applied when the screen width is less than %s', 'us' ), '900px' ),
				'type' => 'slider',
				'std' => '24px',
				'options' => array(
					'px' => array(
						'min' => 12,
						'max' => 50,
					),
				),
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_font_size_mobiles' => array(
				'title' => __( 'Font Size on Mobiles', 'us' ),
				'description' => sprintf( __( 'Applied when the screen width is less than %s', 'us' ), '600px' ),
				'type' => 'slider',
				'std' => '20px',
				'options' => array(
					'px' => array(
						'min' => 12,
						'max' => 50,
					),
				),
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_image' => array(
				'title' => us_translate( 'Image' ),
				'type' => 'upload',
				'show_if' => array( 'logo_type', '=', 'img' ),
			),
			'logo_height' => array(
				'title' => us_translate( 'Height' ),
				'type' => 'slider',
				'std' => '60px',
				'options' => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
				),
				'show_if' => array( 'logo_type', '=', 'img' ),
			),
			'logo_height_sticky' => array(
				'title' => __( 'Height in the Sticky Header', 'us' ),
				'type' => 'slider',
				'std' => '60px',
				'options' => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
				),
				'show_if' => array(
					array( 'logo_type', '=', 'img' ),
					'and',
					array( 'header_layout', '!=', 'vertical_1' ),
				),
			),
			'logo_height_tablets' => array(
				'title' => __( 'Height on Tablets', 'us' ),
				'description' => sprintf( __( 'Applied when the screen width is less than %s', 'us' ), '900px' ),
				'type' => 'slider',
				'std' => '40px',
				'options' => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
				),
				'show_if' => array( 'logo_type', '=', 'img' ),
			),
			'logo_height_mobiles' => array(
				'title' => __( 'Height on Mobiles', 'us' ),
				'description' => sprintf( __( 'Applied when the screen width is less than %s', 'us' ), '600px' ),
				'type' => 'slider',
				'std' => '30px',
				'options' => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
				),
				'show_if' => array( 'logo_type', '=', 'img' ),
			),

			// Menu
			'h_header_4' => array(
				'title' => us_translate( 'Menu' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'menu_source' => array(
				'title' => us_translate( 'Menu' ),
				'description' => sprintf( __( 'Add or edit a menu on the %s page', 'us' ), '<a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . us_translate( 'Menus' ) . '</a>' ),
				'type' => 'select',
				'options' => us_get_nav_menus(),
				'std' => 'main',
			),
			'menu_fontsize' => array(
				'title' => __( 'Main Items Font Size', 'us' ),
				'type' => 'slider',
				'std' => '16px',
				'options' => array(
					'px' => array(
						'min' => 12,
						'max' => 50,
					),
				),
			),
			'menu_indents' => array(
				'title' => __( 'Distance Between Main Items', 'us' ),
				'type' => 'slider',
				'std' => '40px',
				'options' => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
						'step' => 2,
					),
				),
			),
			'menu_height' => array(
				'title' => __( 'Main Items Height', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Stretch menu items to the full height of the header', 'us' ),
				'std' => 0,
			),
			'menu_sub_fontsize' => array(
				'title' => __( 'Dropdown Font Size', 'us' ),
				'type' => 'slider',
				'std' => '15px',
				'options' => array(
					'px' => array(
						'min' => 12,
						'max' => 50,
					),
				),
			),
			'menu_mobile_width' => array(
				'title' => __( 'Enable mobile layout when screen width is less than', 'us' ),
				'type' => 'slider',
				'std' => '900px',
				'options' => array(
					'px' => array(
						'min' => 300,
						'max' => 2000,
					),
				),
			),
			'menu_togglable_type' => array(
				'title' => __( 'Dropdown Behavior', 'us' ),
				'description' => __( 'When this option is OFF, mobile menu dropdown will be shown by click on an arrow only.', 'us' ),
				'type' => 'switch',
				'switch_text' => __( 'Show dropdown by click on menu item title', 'us' ),
				'std' => 1,
			),
		),
	),

	// Pages Layout
	'pages_layout' => array(
		'title' => __( 'Pages Layout', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/page',
		'fields' => array_merge(
			array(
				'header_info_2' => array(
					'description' => sprintf( __( 'To display different website headers based on page type, install %sHeader Builder addon%s.', 'us' ), '<strong><a href="' . admin_url( 'admin.php?page=us-addons' ) . '" target="_blank">', '</strong></a>' ),
					'type' => 'message',
					'classes' => 'width_full color_blue',
					'place_if' => ! is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),

				// Pages
				'h_defaults' => array(
					'title' => us_translate_x( 'Pages', 'post type general name' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'header_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'description' => $misc['headers_description'],
					'type' => 'select',
					'hints_for' => 'us_header',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_headers_list
					),
					'std' => key( $us_headers_list ),
					'classes' => 'desc_3',
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				'titlebar_id' => array(
					'title' => __( 'Titlebar', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '',
					'place_if' => $usof_sidebar_titlebar,
				),
				'content_id' => array(
					'title' => __( 'Content template', 'us' ),
					'description' => $usof_sidebar_titlebar ? '' : $misc['content_description'],
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Show content as is', 'us' ) . ' &ndash;' ), $us_page_blocks_list
					),
					'std' => '',
					'classes' => 'desc_3',
				),
				'sidebar_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'type' => 'select',
					'options' => us_array_merge(
						array(
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $sidebars_list
					),
					'std' => '',
					'place_if' => $usof_sidebar_titlebar,
				),
				'sidebar_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_id', '!=', '' ),
					'place_if' => $usof_sidebar_titlebar,
				),
				'footer_id' => array(
					'title' => __( 'Footer', 'us' ),
					'description' => $misc['footers_description'],
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_page_blocks_list
					),
					'std' => '',
					'classes' => 'desc_3',
				),

			), $pages_layout_config
		),
	),

	// Archives Layout
	'archives_layout' => array(
		'title' => __( 'Archives Layout', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/blogs',
		'fields' => array_merge(
			array(
				'header_info_3' => array(
					'description' => sprintf( __( 'To display different website headers based on page type, install %sHeader Builder addon%s.', 'us' ), '<strong><a href="' . admin_url( 'admin.php?page=us-addons' ) . '" target="_blank">', '</strong></a>' ),
					'type' => 'message',
					'classes' => 'width_full color_blue',
					'place_if' => ! is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),

				// Archives
				'h_archive_defaults' => array(
					'title' => us_translate( 'Archives' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'header_archive_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'description' => $misc['headers_description'],
					'type' => 'select',
					'hints_for' => 'us_header',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_headers_list
					),
					'std' => key( $us_headers_list ),
					'classes' => 'desc_3',
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				'titlebar_archive_id' => array(
					'title' => __( 'Titlebar', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				'content_archive_id' => array(
					'title' => __( 'Content template', 'us' ),
					'description' => $usof_sidebar_titlebar ? '' : $misc['content_description'],
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Show results via Grid element with defaults', 'us' ) . ' &ndash;' ), $us_page_blocks_list
					),
					'std' => '',
					'classes' => 'desc_3',
				),
				'sidebar_archive_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'type' => 'select',
					'options' => us_array_merge(
						array(
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $sidebars_list
					),
					'std' => '',
					'place_if' => $usof_sidebar_titlebar,
				),
				'sidebar_archive_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_archive_id', '!=', '' ),
					'place_if' => $usof_sidebar_titlebar,
				),
				'footer_archive_id' => array(
					'title' => __( 'Footer', 'us' ),
					'description' => $misc['footers_description'],
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_page_blocks_list
					),
					'std' => '',
					'classes' => 'desc_3',
				),

			), $archives_layout_config, array(

				// Authors
				'h_authors' => array(
					'title' => __( 'Authors', 'us' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'header_author_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_header',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_headers_list
					),
					'std' => '__defaults__',
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				'titlebar_author_id' => array(
					'title' => __( 'Titlebar', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				'content_author_id' => array(
					'title' => __( 'Content template', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),
				'sidebar_author_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'type' => 'select',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $sidebars_list
					),
					'std' => '',
					'place_if' => $usof_sidebar_titlebar,
				),
				'sidebar_author_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_author_id', 'not in', array( '', '__defaults__' ) ),
					'place_if' => $usof_sidebar_titlebar,
				),
				'footer_author_id' => array(
					'title' => __( 'Footer', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Archives', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),

			)

		),
	),

	// Colors
	'colors' => array(
		'title' => us_translate( 'Colors' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/colors',
		'fields' => array(

			// Color Schemes
			'color_style' => array(
				'type' => 'style_scheme',
			),

			'change_colors_start' => array(
				'type' => 'wrapper_start',
			),

			// Header colors
			'change_header_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_1' => array(
				'title' => __( 'Header colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'color_header_top_bg' => array(
				'type' => 'color',
				'text' => __( 'Top Area', 'us' ) . ': ' . us_translate( 'Background' ),
			),
			'color_header_top_text' => array(
				'type' => 'color',
				'text' => __( 'Top Area', 'us' ) . ': ' . us_translate( 'Text' ),
			),
			'color_header_top_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Top Area', 'us' ) . ': ' . __( 'Link on hover', 'us' ),
			),
			'color_header_middle_bg' => array(
				'type' => 'color',
				'text' => __( 'Main Area', 'us' ) . ': ' . us_translate( 'Background' ),
			),
			'color_header_middle_text' => array(
				'type' => 'color',
				'text' => __( 'Main Area', 'us' ) . ': ' . us_translate( 'Text' ),
			),
			'color_header_middle_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Main Area', 'us' ) . ': ' . __( 'Link on hover', 'us' ),
			),
			'color_header_bottom_bg' => array(
				'type' => 'color',
				'text' => __( 'Bottom Area', 'us' ) . ': ' . us_translate( 'Background' ),
			),
			'color_header_bottom_text' => array(
				'type' => 'color',
				'text' => __( 'Bottom Area', 'us' ) . ': ' . us_translate( 'Text' ),
			),
			'color_header_bottom_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Bottom Area', 'us' ) . ': ' . __( 'Link on hover', 'us' ),
			),
			'color_header_transparent_text' => array(
				'type' => 'color',
				'text' => __( 'Text in transparent header', 'us' ),
			),
			'color_header_transparent_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Link on hover in transparent header', 'us' ),
			),
			'color_header_search_bg' => array(
				'type' => 'color',
				'text' => __( 'Search Field Background', 'us' ),
			),
			'color_header_search_text' => array(
				'type' => 'color',
				'text' => __( 'Search Field Text', 'us' ),
			),
			'color_chrome_toolbar' => array(
				'type' => 'color',
				'text' => __( 'Toolbar in Chrome for Android', 'us' ),
			),
			'change_header_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Header Menu colors
			'change_menu_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_2' => array(
				'title' => __( 'Header Menu colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'color_menu_active_bg' => array(
				'type' => 'color',
				'text' => __( 'Active Menu Item Background', 'us' ),
			),
			'color_menu_active_text' => array(
				'type' => 'color',
				'text' => __( 'Active Menu Item Text', 'us' ),
			),
			'color_menu_transparent_active_bg' => array(
				'type' => 'color',
				'text' => __( 'Active Menu Item Background in transparent header', 'us' ),
			),
			'color_menu_transparent_active_text' => array(
				'type' => 'color',
				'text' => __( 'Active Menu Item Text in transparent header', 'us' ),
			),
			'color_menu_hover_bg' => array(
				'type' => 'color',
				'text' => __( 'Menu Item Background on hover', 'us' ),
			),
			'color_menu_hover_text' => array(
				'type' => 'color',
				'text' => __( 'Menu Item Text on hover', 'us' ),
			),
			'color_drop_bg' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Background', 'us' ),
			),
			'color_drop_text' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Text', 'us' ),
			),
			'color_drop_hover_bg' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Item Background on hover', 'us' ),
			),
			'color_drop_hover_text' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Item Text on hover', 'us' ),
			),
			'color_drop_active_bg' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Active Item Background', 'us' ),
			),
			'color_drop_active_text' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Active Item Text', 'us' ),
			),
			'color_menu_button_bg' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Background', 'us' ),
			),
			'color_menu_button_text' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Text', 'us' ),
			),
			'color_menu_button_hover_bg' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Hover Background', 'us' ),
			),
			'color_menu_button_hover_text' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Hover Text', 'us' ),
			),
			'change_menu_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Content colors
			'change_content_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_3' => array(
				'title' => __( 'Content colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'color_content_bg' => array(
				'type' => 'color',
				'text' => us_translate( 'Background' ),
			),
			'color_content_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background', 'us' ),
			),
			'color_content_border' => array(
				'type' => 'color',
				'text' => us_translate( 'Border' ),
			),
			'color_content_heading' => array(
				'type' => 'color',
				'text' => __( 'Headings', 'us' ),
			),
			'color_content_text' => array(
				'type' => 'color',
				'text' => us_translate( 'Text' ),
			),
			'color_content_link' => array(
				'type' => 'color',
				'text' => us_translate( 'Link' ),
			),
			'color_content_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link on hover', 'us' ),
			),
			'color_content_primary' => array(
				'type' => 'color',
				'text' => __( 'Primary Color', 'us' ),
			),
			'color_content_secondary' => array(
				'type' => 'color',
				'text' => __( 'Secondary Color', 'us' ),
			),
			'color_content_faded' => array(
				'type' => 'color',
				'text' => __( 'Faded Text', 'us' ),
			),
			'change_content_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Alternate Content colors
			'change_alt_content_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_4' => array(
				'title' => __( 'Alternate Content colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'color_alt_content_bg' => array(
				'type' => 'color',
				'text' => us_translate( 'Background' ),
			),
			'color_alt_content_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background', 'us' ),
			),
			'color_alt_content_border' => array(
				'type' => 'color',
				'text' => us_translate( 'Border' ),
			),
			'color_alt_content_heading' => array(
				'type' => 'color',
				'text' => __( 'Headings', 'us' ),
			),
			'color_alt_content_text' => array(
				'type' => 'color',
				'text' => us_translate( 'Text' ),
			),
			'color_alt_content_link' => array(
				'type' => 'color',
				'text' => us_translate( 'Link' ),
			),
			'color_alt_content_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link on hover', 'us' ),
			),
			'color_alt_content_primary' => array(
				'type' => 'color',
				'text' => __( 'Primary Color', 'us' ),
			),
			'color_alt_content_secondary' => array(
				'type' => 'color',
				'text' => __( 'Secondary Color', 'us' ),
			),
			'color_alt_content_faded' => array(
				'type' => 'color',
				'text' => __( 'Faded Text', 'us' ),
			),
			'change_alt_content_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Top Footer colors
			'change_subfooter_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_5' => array(
				'title' => __( 'Top Footer colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'color_subfooter_bg' => array(
				'type' => 'color',
				'text' => us_translate( 'Background' ),
			),
			'color_subfooter_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background', 'us' ),
			),
			'color_subfooter_border' => array(
				'type' => 'color',
				'text' => us_translate( 'Border' ),
			),
			'color_subfooter_text' => array(
				'type' => 'color',
				'text' => us_translate( 'Text' ),
			),
			'color_subfooter_link' => array(
				'type' => 'color',
				'text' => us_translate( 'Link' ),
			),
			'color_subfooter_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link on hover', 'us' ),
			),
			'change_subfooter_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Bottom Footer colors
			'change_footer_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_6' => array(
				'title' => __( 'Bottom Footer colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'color_footer_bg' => array(
				'type' => 'color',
				'text' => us_translate( 'Background' ),
			),
			'color_footer_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background', 'us' ),
			),
			'color_footer_border' => array(
				'type' => 'color',
				'text' => us_translate( 'Border' ),
			),
			'color_footer_text' => array(
				'type' => 'color',
				'text' => us_translate( 'Text' ),
			),
			'color_footer_link' => array(
				'type' => 'color',
				'text' => us_translate( 'Link' ),
			),
			'color_footer_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link on hover', 'us' ),
			),
			'change_footer_colors_end' => array(
				'type' => 'wrapper_end',
			),
			'change_colors_end' => array(
				'type' => 'wrapper_end',
			),
		),
	),

	// Typography
	'typography' => array(
		'title' => __( 'Typography', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/style',
		'fields' => array_merge(
			array(

				// Global Text
				'body_font_family' => array(
					'type' => 'font',
					'preview' => array(
						'text' => __( 'This is how your site will show the text by default, while you can change the typography settings for most elements separately. Note the font size will affect all elements in "rem" units, that is, almost all areas of your site.', 'us' ),
						'size_field' => 'body_fontsize',
						'lineheight_field' => 'body_lineheight',
					),
					'std' => 'Georgia, serif',
				),
				'body_text_start' => array(
					'type' => 'wrapper_start',
					'classes' => 'for_font col_first',
				),
				'body_fontsize' => array(
					'description' => __( 'Font Size', 'us' ),
					'type' => 'slider',
					'std' => '16px',
					'options' => array(
						'px' => array(
							'min' => 10,
							'max' => 30,
						),
					),
					'classes' => 'inline slider_below',
				),
				'body_lineheight' => array(
					'description' => __( 'Line height', 'us' ),
					'type' => 'slider',
					'std' => '28px',
					'options' => array(
						'px' => array(
							'min' => 15,
							'max' => 35,
						),
					),
					'classes' => 'inline slider_below',
				),
				'body_text_end' => array(
					'type' => 'wrapper_end',
				),
				'body_text_mobiles_start' => array(
					'type' => 'wrapper_start',
					'classes' => 'for_font',
				),
				'body_fontsize_mobile' => array(
					'description' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'slider',
					'std' => '15px',
					'options' => array(
						'px' => array(
							'min' => 10,
							'max' => 30,
						),
					),
					'classes' => 'inline slider_below',
				),
				'body_lineheight_mobile' => array(
					'description' => __( 'Line height on Mobiles', 'us' ),
					'type' => 'slider',
					'std' => '26px',
					'options' => array(
						'px' => array(
							'min' => 15,
							'max' => 35,
						),
					),
					'classes' => 'inline slider_below',
				),
				'body_text_mobiles_end' => array(
					'type' => 'wrapper_end',
				),

			), $headings_typography_config, array(

				// Additional Google Fonts
				'h_typography_3' => array(
					'title' => __( 'Additional Google Fonts', 'us' ),
					'description' => __( 'In case when you need more Google Fonts in theme elements.', 'us' ),
					'type' => 'heading',
				),
				'custom_font' => array(
					'type' => 'group',
					'params' => array(
						'font_family' => array(
							'type' => 'font',
							'only_google' => TRUE,
							'preview' => array(
								'text' => __( 'Google Font Preview', 'us' ),
							),
							'std' => 'Open Sans',
						),
					),
				),

				// Google Fonts Subset
				'h_typography_5' => array(
					'title' => __( 'Google Fonts Subset', 'us' ),
					'description' => sprintf( __( 'Check available languages for needed fonts on %s website.', 'us' ), '<a href="https://fonts.google.com/" target="_blank">Google Fonts</a>' ),
					'type' => 'heading',
				),
				'font_subset' => array(
					'type' => 'select',
					'options' => array(
						'arabic' => 'arabic',
						'cyrillic' => 'cyrillic',
						'cyrillic-ext' => 'cyrillic-ext',
						'greek' => 'greek',
						'greek-ext' => 'greek-ext',
						'hebrew' => 'hebrew',
						'khmer' => 'khmer',
						'latin' => 'latin',
						'latin-ext' => 'latin-ext',
						'vietnamese' => 'vietnamese',
					),
					'std' => 'latin',
					'classes' => 'width_full for_above',
				),

				// Uploaded Fonts
				'h_typography_4' => array(
					'title' => __( 'Uploaded Fonts', 'us' ),
					'description' => sprintf( __( 'Add custom fonts via uploading %s files.', 'us' ), '<strong>woff</strong>, <strong>woff2</strong>' ) . ' <a target="_blank" href="https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/options/typography/#uploaded-fonts">' . __( 'Read about usage of uploaded fonts', 'us' ) . '</a>.',
					'type' => 'heading',
				),
				'uploaded_fonts' => array(
					'type' => 'group',
					'classes' => 'with_wrapper',
					'params' => array(
						'uploaded_font_start' => array(
							'type' => 'wrapper_start',
						),
						'name' => array(
							'title' => __( 'Font Name', 'us' ),
							'type' => 'text',
							'std' => 'Uploaded Font',
							'classes' => 'width_full cols_2',
						),
						'weight' => array(
							'title' => __( 'Font Weight', 'us' ),
							'type' => 'slider',
							'std' => 400,
							'options' => array(
								'' => array(
									'min' => 100,
									'max' => 900,
									'step' => 100,
								),
							),
							'classes' => 'width_full cols_2',
						),
						'files' => array(
							'title' => __( 'Font Files', 'us' ),
							'type' => 'upload',
							'is_multiple' => TRUE,
							'preview_type' => 'text',
							'button_label' => us_translate( 'Select Files' ),
							'classes' => 'width_full',
						),
						'uploaded_font_end' => array(
							'type' => 'wrapper_end',
						),
					),
				),
			)
		),
	),
	'buttons' => array(
		'title' => __( 'Buttons', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/buttons',
		'fields' => array(
			'buttons' => array(
				'type' => 'group',
				'classes' => 'for_btns', // required class for buttons preview
				'is_accordion' => TRUE,
				'title' => '{{name}}', // get value from "name" param
				'params' => array(

					'id' => array(
						'type' => 'text',
						'std' => NULL,
						'classes' => 'hidden',
					),
					'name' => array(
						'title' => __( 'Button Style Name', 'us' ),
						'type' => 'text',
						'std' => us_translate( 'Style' ),
						'classes' => 'cols_2',
					),
					'hover' => array(
						'title' => __( 'Hover Style', 'us' ),
						'description' => __( '"Slide background from the top" may not work with buttons of 3rd-party plugins.', 'us' ),
						'type' => 'select',
						'options' => array(
							'fade' => __( 'Simple color change', 'us' ),
							'slide' => __( 'Slide background from the top', 'us' ),
						),
						'std' => 'fade',
						'classes' => 'cols_2 desc_4',
					),

					// Button Colors
					'color_bg' => array(
						'title' => us_translate( 'Colors' ),
						'type' => 'color',
						'std' => '#008ec2',
						'text' => us_translate( 'Background' ),
						'classes' => 'cols_2',
					),
					'color_bg_hover' => array(
						'title' => __( 'Colors on hover', 'us' ),
						'type' => 'color',
						'std' => '',
						'text' => us_translate( 'Background' ),
						'classes' => 'cols_2',
					),
					'color_border' => array(
						'type' => 'color',
						'std' => '',
						'text' => us_translate( 'Border' ),
						'classes' => 'cols_2',
					),
					'color_border_hover' => array(
						'type' => 'color',
						'std' => '#008ec2',
						'text' => us_translate( 'Border' ),
						'classes' => 'cols_2',
					),
					'color_text' => array(
						'type' => 'color',
						'std' => '#fff',
						'text' => us_translate( 'Text' ),
						'classes' => 'cols_2',
					),
					'color_text_hover' => array(
						'type' => 'color',
						'std' => '#008ec2',
						'text' => us_translate( 'Text' ),
						'classes' => 'cols_2',
					),
					'shadow' => array(
						'title' => __( 'Shadow', 'us' ),
						'type' => 'slider',
						'std' => 0,
						'options' => array(
							'em' => array(
								'min' => 0.0,
								'max' => 2.0,
								'step' => 0.1,
							),
						),
						'classes' => 'cols_2 leave_padding',
					),
					'shadow_hover' => array(
						'title' => __( 'Shadow on hover', 'us' ),
						'type' => 'slider',
						'std' => 0,
						'options' => array(
							'em' => array(
								'min' => 0.0,
								'max' => 2.0,
								'step' => 0.1,
							),
						),
						'classes' => 'cols_2 leave_padding',
					),

					// Typography & Sizes
					'font' => array(
						'title' => __( 'Font', 'us' ),
						'type' => 'select',
						'options' => us_get_fonts(),
						'std' => 'body',
						'classes' => 'cols_2',
					),
					'height' => array(
						'title' => __( 'Relative Height', 'us' ),
						'type' => 'slider',
						'std' => '0.8em',
						'options' => array(
							'em' => array(
								'min' => 0.0,
								'max' => 2.0,
								'step' => 0.1,
							),
						),
						'classes' => 'cols_2',
					),
					'text_style' => array(
						'title' => __( 'Text Styles', 'us' ),
						'type' => 'checkboxes',
						'options' => array(
							'uppercase' => __( 'Uppercase', 'us' ),
							'italic' => __( 'Italic', 'us' ),
						),
						'std' => array(),
						'classes' => 'cols_2',
					),
					'width' => array(
						'title' => __( 'Relative Width', 'us' ),
						'type' => 'slider',
						'std' => '1.8em',
						'options' => array(
							'em' => array(
								'min' => 0.0,
								'max' => 5.0,
								'step' => 0.1,
							),
						),
						'classes' => 'cols_2',
					),
					'font_weight' => array(
						'title' => __( 'Font Weight', 'us' ),
						'type' => 'slider',
						'std' => 400,
						'options' => array(
							'' => array(
								'min' => 100,
								'max' => 900,
								'step' => 100,
							),
						),
						'classes' => 'cols_2',
					),
					'border_radius' => array(
						'title' => __( 'Corners Radius', 'us' ),
						'type' => 'slider',
						'std' => '0.3em',
						'options' => array(
							'em' => array(
								'min' => 0.0,
								'max' => 4.0,
								'step' => 0.1,
							),
						),
						'classes' => 'cols_2',
					),
					'letter_spacing' => array(
						'title' => __( 'Letter Spacing', 'us' ),
						'type' => 'slider',
						'std' => '0',
						'options' => array(
							'em' => array(
								'min' => - 0.10,
								'max' => 0.20,
								'step' => 0.01,
							),
						),
						'classes' => 'cols_2',
					),
					'border_width' => array(
						'title' => __( 'Border Width', 'us' ),
						'type' => 'slider',
						'std' => '2px',
						'options' => array(
							'px' => array(
								'min' => 0,
								'max' => 10,
							),
						),
						'classes' => 'cols_2',
					),
				),
				'std' => array(
					array(
						'id' => 1,
						'name' => __( 'Default Button', 'us' ),
						'hover' => 'fade',
						// predefined colors after options reset
						'color_bg' => '#008ec2',
						'color_bg_hover' => '#00b9eb',
						'color_border' => '',
						'color_border_hover' => '',
						'color_text' => '#fff',
						'color_text_hover' => '#fff',
						'shadow' => 0,
						'shadow_hover' => 0,
						'font' => 'body',
						'text_style' => array(),
						'font_weight' => 700,
						'letter_spacing' => 0,
						'height' => '0.8em',
						'width' => '1.8em',
						'border_radius' => '0.3em',
						'border_width' => '2px',
					),
					array(
						'id' => 2,
						'name' => __( 'Button', 'us' ) . ' 2',
						'hover' => 'fade',
						// predefined colors after options reset
						'color_bg' => '#e8e8e8',
						'color_bg_hover' => '#333',
						'color_border' => '',
						'color_border_hover' => '',
						'color_text' => '#333',
						'color_text_hover' => '#fff',
						'shadow' => 0,
						'shadow_hover' => 0,
						'font' => 'body',
						'text_style' => array(),
						'font_weight' => 700,
						'letter_spacing' => 0,
						'height' => '0.8em',
						'width' => '1.8em',
						'border_radius' => '0.3em',
						'border_width' => '2px',
					),
				),
			),

		),
	),
	'portfolio' => array(
		'title' => __( 'Portfolio', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/portfolio',
		'place_if' => ( $usof_enable_portfolio == 1 ),
		'fields' => array(
			'portfolio_breadcrumbs_page' => array(
				'title' => __( 'Intermediate Breadcrumbs Page', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array( '' => '&ndash; ' . us_translate( 'None' ) . ' &ndash;' ), $us_page_list
				),
				'std' => '',
			),
			'portfolio_slug' => array(
				'title' => __( 'Portfolio Page Slug', 'us' ),
				'type' => 'text',
				'std' => 'portfolio',
			),
			'portfolio_category_slug' => array(
				'title' => __( 'Portfolio Category Slug', 'us' ),
				'type' => 'text',
				'std' => 'portfolio_category',
			),
			'portfolio_tag_slug' => array(
				'title' => __( 'Portfolio Tag Slug', 'us' ),
				'type' => 'text',
				'std' => 'portfolio_tag',
			),
		),
	),

	// Shop
	'woocommerce' => array(
		'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/cart',
		'place_if' => class_exists( 'woocommerce' ),
		'fields' => array_merge(
			array(

				// Global Settings
				'h_more' => array(
					'title' => us_translate( 'Global Settings' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'shop_catalog' => array(
					'title' => __( 'Catalog Mode', 'us' ),
					'type' => 'switch',
					'switch_text' => sprintf( __( 'Remove "%s" buttons', 'us' ), us_translate( 'Add to cart', 'woocommerce' ) ),
					'std' => 0,
				),
				'shop_primary_btn_style' => array(
					'title' => __( 'Primary Buttons Style', 'us' ),
					'description' => '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons">' . __( 'Edit Button Styles', 'us' ) . '</a>',
					'type' => 'select',
					'options' => us_get_btn_styles(),
					'std' => '1',
				),
				'shop_secondary_btn_style' => array(
					'title' => __( 'Secondary Buttons Style', 'us' ),
					'description' => '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons">' . __( 'Edit Button Styles', 'us' ) . '</a>',
					'type' => 'select',
					'options' => us_get_btn_styles(),
					'std' => '2',
				),
				'product_gallery' => array(
					'title' => us_translate( 'Product gallery', 'woocommerce' ),
					'type' => 'checkboxes',
					'options' => array(
						'slider' => __( 'Display as slider', 'us' ),
						'zoom' => __( 'Zoom images on hover', 'us' ),
						'lightbox' => __( 'Allow Full Screen view', 'us' ),
					),
					'std' => array( 'slider', 'zoom', 'lightbox' ),
					'classes' => 'vertical desc_3',
				),

				// Products
				'h_product' => array(
					'title' => us_translate( 'Products', 'woocommerce' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'header_product_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_header',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_headers_list
					),
					'std' => '__defaults__',
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				'titlebar_product_id' => array(
					'title' => __( 'Titlebar', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				'content_product_id' => array(
					'title' => __( 'Content template', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'' => '&ndash; ' . __( 'Default WooCommerce template', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '',
				),
				'sidebar_product_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'type' => 'select',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $sidebars_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				'sidebar_product_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_product_id', 'not in', array( '', '__defaults__' ) ),
					'place_if' => $usof_sidebar_titlebar,
				),
				'footer_product_id' => array(
					'title' => __( 'Footer', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),

				// Shop page
				'h_shop' => array(
					'title' => us_translate( 'Shop Page', 'woocommerce' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'header_shop_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_header',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_headers_list
					),
					'std' => '__defaults__',
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				'titlebar_shop_id' => array(
					'title' => __( 'Titlebar', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				'content_shop_id' => array(
					'title' => __( 'Content template', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'' => '&ndash; ' . __( 'Default WooCommerce template', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '',
				),
				'wrapper_shop_start' => array(
					'type' => 'wrapper_start',
					'classes' => 'force_right',
					'show_if' => array( 'content_shop_id', '=', '' ),
				),
				'shop_columns' => array(
					'title' => us_translate( 'Columns' ),
					'type' => 'radio',
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'std' => '3',
					'classes' => 'width_full',
				),
				'wrapper_shop_end' => array(
					'type' => 'wrapper_end',
				),
				'sidebar_shop_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'type' => 'select',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $sidebars_list
					),
					'std' => '__defaults__',
					'place_if' => $usof_sidebar_titlebar,
				),
				'sidebar_shop_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_shop_id', 'not in', array( '', '__defaults__' ) ),
					'place_if' => $usof_sidebar_titlebar,
				),
				'footer_shop_id' => array(
					'title' => __( 'Footer', 'us' ),
					'type' => 'select',
					'hints_for' => 'us_page_block',
					'options' => us_array_merge(
						array(
							'__defaults__' => '&ndash; ' . __( 'As in Pages', 'us' ) . ' &ndash;',
							'' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;',
						), $us_page_blocks_list
					),
					'std' => '__defaults__',
				),

			), $shop_layout_config, array(

				// Cart page
				'h_cart' => array(
					'title' => us_translate( 'Cart Page', 'woocommerce' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'shop_cart' => array(
					'title' => __( 'Layout', 'us' ),
					'type' => 'radio',
					'options' => array(
						'standard' => __( 'Standard', 'us' ),
						'compact' => __( 'Compact', 'us' ),
					),
					'std' => 'compact',
				),
				'product_related_qty' => array(
					'title' => us_translate( 'Cross-sells', 'woocommerce' ),
					'type' => 'radio',
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'std' => '3',
				),
			)

		),
	),

	// Image Sizes
	'image_sizes' => array(
		'title' => __( 'Image Sizes', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/images',
		'fields' => array(

			'img_size_info' => array(
				'description' => $img_size_info,
				'type' => 'message',
				'classes' => 'width_full color_blue for_above',
			),
			'img_size' => array(
				'type' => 'group',
				'classes' => 'for_inline',
				'params' => array(
					'width' => array(
						'title' => us_translate( 'Width' ),
						'type' => 'slider',
						'std' => '600px',
						'options' => array(
							'px' => array(
								'min' => 0,
								'max' => 1000,
							),
						),
						'classes' => 'inline slider_below',
					),
					'height' => array(
						'title' => us_translate( 'Height' ),
						'type' => 'slider',
						'std' => '400px',
						'options' => array(
							'px' => array(
								'min' => 0,
								'max' => 1000,
							),
						),
						'classes' => 'inline slider_below',
					),
					'crop' => array(
						'type' => 'checkboxes',
						'options' => array(
							'crop' => __( 'Crop to exact dimensions', 'us' ),
						),
						'std' => array( '0' => 'crop' ),
						'classes' => 'inline',
					),
				),
				'std' => array(
					array(
						'width' => 350,
						'height' => 350,
						'crop' => array( '0' => 'crop' ),
					),
					array(
						'width' => 600,
						'height' => 600,
						'crop' => array( '0' => 'crop' ),
					),
				),
			),
		),
	),

	// Advanced
	'advanced' => array(
		'title' => _x( 'Advanced', 'Advanced Settings', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/cog',
		'fields' => array(
			'h_advanced_1' => array(
				'title' => __( 'Theme Modules', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'enable_portfolio' => array(
				'type' => 'switch',
				'switch_text' => __( 'Portfolio', 'us' ),
				'std' => 1,
				'classes' => 'width_full',
			),
			'enable_testimonials' => array(
				'type' => 'switch',
				'switch_text' => __( 'Testimonials', 'us' ),
				'std' => 1,
				'classes' => 'width_full for_above',
			),
			'media_category' => array(
				'type' => 'switch',
				'switch_text' => __( 'Media Categories', 'us' ),
				'std' => 0,
				'classes' => 'width_full for_above beta',
			),
			'og_enabled' => array(
				'type' => 'switch',
				'switch_text' => __( 'Open Graph meta tags', 'us' ),
				'std' => 1,
				'classes' => 'width_full for_above',
			),
			'schema_markup' => array(
				'type' => 'switch',
				'switch_text' => __( 'Schema.org markup', 'us' ),
				'std' => 1,
				'classes' => 'width_full for_above',
			),
			'enable_sidebar_titlebar' => array(
				'type' => 'switch',
				'switch_text' => __( 'Titlebars & Sidebars', 'us' ),
				'std' => 0,
				'classes' => 'width_full for_above',
			),

			'h_advanced_2' => array(
				'title' => __( 'Website Performance', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'lazy_load' => array(
				'type' => 'switch',
				'switch_text' => __( 'Lazy Load', 'us' ),
				'description' => __( 'When this option is ON, your site will load images when they\'re in the viewport only.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => FALSE,
				'classes' => 'width_full desc_2 beta',
			),
			'lazyload_fonts' => array(
				'type' => 'switch',
				'switch_text' => __( 'Defer Google Fonts loading', 'us' ),
				'description' => __( 'When this option is ON, Google Fonts files will be loaded after page content.', 'us' ),
				'std' => FALSE,
				'classes' => 'width_full desc_2 for_above',
			),
			'disable_jquery_migrate' => array(
				'type' => 'switch',
				'switch_text' => __( 'Disable jQuery migrate script', 'us' ),
				'description' => __( 'When this option is ON, "jquery-migrate.min.js" file won\'t be loaded in front-end.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => TRUE,
				'classes' => 'width_full desc_2 for_above',
			),
			'jquery_footer' => array(
				'type' => 'switch',
				'switch_text' => __( 'Move jQuery scripts to the footer', 'us' ),
				'description' => __( 'When this option is ON, jQuery library files will be loaded after page content.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => TRUE,
				'classes' => 'width_full desc_2 for_above',
			),
			'ajax_load_js' => array(
				'type' => 'switch',
				'switch_text' => __( 'Dynamically load theme JS components', 'us' ),
				'description' => __( 'When this option is ON, theme components JS files will be loaded dynamically without additional external requests.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => TRUE,
				'classes' => 'width_full desc_2 for_above',
			),
			'disable_extra_vc' => array(
				'type' => 'switch',
				'switch_text' => __( 'Disable extra features of WPBakery Page Builder', 'us' ),
				'description' => __( 'When this option is ON, original CSS and JS files of WPBakery Page Builder won\'t be loaded in front-end.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => TRUE,
				'place_if' => class_exists( 'Vc_Manager' ),
				'classes' => 'width_full desc_2 for_above',
			),
			'remove_protocol' => array(
				'type' => 'switch',
				'switch_text' => __( 'Remove "http/https" from paths to files', 'us' ),
				'description' => __( 'For better compatibility with caching plugins.', 'us' ) . ' ' . __( 'If your site uses both HTTP and HTTPS, turn OFF this option.', 'us' ),
				'std' => TRUE,
				'classes' => 'width_full desc_2 for_above',
			),
			'optimize_assets' => array(
				'type' => 'switch',
				'switch_text' => __( 'Optimize JS and CSS size', 'us' ),
				'description' => __( 'When this option is ON, your site will load single JS file and single CSS file. You can disable unused components to reduce its sizes.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => FALSE,
				'classes' => 'width_full desc_2 for_above' . $optimize_assets_add_class,
			),
			'optimize_assets_alert' => array(
				'description' => __( 'Your uploads folder is not writable. Change your server permissions to use this option.', 'us' ),
				'type' => 'message',
				'classes' => 'width_full string' . $optimize_assets_alert_add_class,
			),
			'optimize_assets_start' => array(
				'type' => 'wrapper_start',
				'show_if' => array( 'optimize_assets', '=', TRUE ),
			),
			'assets' => array(
				'type' => 'check_table',
				'options' => $usof_assets,
				'std' => array_keys( $usof_assets ),
				'classes' => 'width_full',
			),
			'optimize_assets_end' => array(
				'type' => 'wrapper_end',
			),

		),
	),

	// Custom Code
	'code' => array(
		'title' => __( 'Custom Code', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/tag',
		'fields' => array(
			'custom_css' => array(
				'title' => __( 'Custom CSS', 'us' ),
				'description' => sprintf( __( 'CSS code from this field will overwrite theme styles. It will be located inside the %s tags just before the %s tag of every site page.', 'us' ), '<code>&lt;style&gt;&lt;/style&gt;</code>', '<code>&lt;/head&gt;</code>' ),
				'type' => 'css',
				'classes' => 'width_full desc_4',
			),
			'custom_html_head' => array(
				'title' => sprintf( __( 'Code before %s', 'us' ), '&lt;/head&gt;' ),
				'description' => sprintf( __( 'Use this field for Google Analytics code or other tracking code. If you paste custom JavaScript, use it inside the %s tags.', 'us' ), '<code>&lt;script&gt;&lt;/script&gt;</code>' ) . '<br><br>' . sprintf( __( 'Content from this field will be located just before the %s tag of every site page.', 'us' ), '<code>&lt;/head&gt;</code>' ),
				'type' => 'html',
				'classes' => 'width_full desc_4',
			),
			'custom_html' => array(
				'title' => sprintf( __( 'Code before %s', 'us' ), '&lt;/body&gt;' ),
				'description' => sprintf( __( 'Use this field for Google Analytics code or other tracking code. If you paste custom JavaScript, use it inside the %s tags.', 'us' ), '<code>&lt;script&gt;&lt;/script&gt;</code>' ) . '<br><br>' . sprintf( __( 'Content from this field will be located just before the %s tag of every site page.', 'us' ), '<code>&lt;/body&gt;</code>' ),
				'type' => 'html',
				'classes' => 'width_full desc_4',
			),
		),
	),
	'manage' => array(
		'title' => __( 'Manage Options', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/backups',
		'fields' => array(
			'of_reset' => array(
				'title' => __( 'Reset Theme Options', 'us' ),
				'type' => 'reset',
			),
			'of_backup' => array(
				'title' => __( 'Backup Theme Options', 'us' ),
				'type' => 'backup',
			),
			'of_transfer' => array(
				'title' => __( 'Transfer Theme Options', 'us' ),
				'type' => 'transfer',
				'description' => __( 'You can transfer the saved options data between different installations by copying the text inside the text box. To import data from another installation, replace the data in the text box with the one from another installation and click "Import Options".', 'us' ),
				'classes' => 'desc_3',
			),
		),
	),
);
