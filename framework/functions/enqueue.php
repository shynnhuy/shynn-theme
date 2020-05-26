<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Embed Google Fonts
 */
if ( us_get_option( 'lazyload_fonts', 0 ) == 1 ) {
	add_action( 'wp_footer', 'us_enqueue_fonts' );
} else {
	add_action( 'wp_enqueue_scripts', 'us_enqueue_fonts' );
}
function us_enqueue_fonts() {
	$prefixes = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'body' );
	$font_options = $fonts = array();

	$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
	$uploaded_font_names = array( 'get_h1' );
	if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
		foreach ( $uploaded_fonts as $uploaded_font ) {
			$uploaded_font_names[] = esc_attr( strip_tags( $uploaded_font['name'] ) );
		}
	}

	foreach ( $prefixes as $prefix ) {
		$font_option = explode( '|', us_get_option( $prefix . '_font_family', 'none' ), 2 );
		if ( in_array( $font_option[0], $uploaded_font_names ) ) {
			continue;
		}
		$font_options[] = $font_option;
	}

	$custom_fonts = us_get_option( 'custom_font', array() );
	if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
		foreach ( $custom_fonts as $custom_font ) {
			$font_options[] = explode( '|', $custom_font['font_family'], 2 );
		}
	}

	foreach ( $font_options as $font ) {
		if ( ! isset( $font[1] ) OR empty( $font[1] ) ) {
			// Fault tolerance for missing font-variants
			$font[1] = '400,700';
		}
		$selected_font_variants = explode( ',', $font[1] );
		// Empty font or web safe combination selected
		if ( $font[0] == 'none' OR strpos( $font[0], ',' ) !== FALSE ) {
			continue;
		}

		$font[0] = str_replace( ' ', '+', $font[0] );
		if ( ! isset( $fonts[ $font[0] ] ) ) {
			$fonts[ $font[0] ] = array();
		}

		foreach ( $selected_font_variants as $font_variant ) {
			$fonts[ $font[0] ][] = $font_variant;
		}
	}

	$subset = '&subset=' . us_get_option( 'font_subset', 'latin' );
	$font_family = '';

	foreach ( $fonts as $font_name => $font_variants ) {
		if ( count( $font_variants ) == 0 ) {
			continue;
		}
		$font_variants = array_unique( $font_variants );
		if ( $font_family != '' ) {
			$font_family .= urlencode( '|' );
		}
		$font_family .= $font_name . ':' . implode( ',', $font_variants );
	}
	if ( $font_family != '' ) {
		$font_url = 'https://fonts.googleapis.com/css?family=' . $font_family . $subset;
		wp_enqueue_style( 'us-fonts', $font_url );
	}
}

/**
 * Embed CSS files
 */
add_action( 'wp_enqueue_scripts', 'us_styles', 12 );
function us_styles() {
	global $us_template_directory_uri;

	$assets_config = us_config( 'assets', array() );

	// Embed all CSS components, when DEV mode is enabled
	if ( defined( 'US_DEV' ) AND US_DEV ) {
		foreach ( $assets_config as $component => $component_atts ) {
			if ( isset( $component_atts['css'] ) ) {
				wp_enqueue_style( 'us-' . $component, $us_template_directory_uri . $component_atts['css'], array(), US_THEMEVERSION, 'all' );
			}
		}
		// Generate and embed single CSS file
	} elseif ( us_get_option( 'optimize_assets', 0 ) == 1 ) {

		// Locate asset file
		$css_file = us_get_asset_file( 'css' );

		// If the file doesn't exist
		if ( ! file_exists( $css_file ) ) {

			// try to create the styles file
			us_generate_asset_file( 'css' );

			// if create attempt failed
			if ( ! file_exists( $css_file ) ) {

				// switch the Optimize option off
				global $usof_options;
				usof_load_options_once();
				$updated_options = $usof_options;
				$updated_options['optimize_assets'] = 0;
				usof_save_options( $updated_options );

				// and load all styles to make sure site looks as it should
				foreach ( $assets_config as $component => $component_atts ) {
					wp_enqueue_style( 'us-' . $component, $us_template_directory_uri . $component_atts['css'], array(), US_THEMEVERSION, 'all' );
				}
			}
		}

		// Embed generated file
		if ( file_exists( $css_file ) ) {
			$css_file_url = us_get_asset_file( 'css', TRUE );
			wp_enqueue_style( 'us-theme', $css_file_url, array(), US_THEMEVERSION, 'all' );
		}

		// Remove WP Block Editor styles
		wp_dequeue_style( 'wp-block-library' );

	} else { // Embed default CSS file in other cases
		wp_register_style( 'us-style', $us_template_directory_uri . '/css/style.min.css', array(), US_THEMEVERSION, 'all' );
		wp_enqueue_style( 'us-style' );
	}
}

// RTL styles
add_action( 'wp_enqueue_scripts', 'us_rtl_styles', 15 );
function us_rtl_styles() {
	global $us_template_directory_uri;

	$min_ext = ( ! ( defined( 'US_DEV' ) AND US_DEV ) ) ? '.min' : '';
	if ( is_rtl() ) {
		wp_register_style( 'us-rtl', $us_template_directory_uri . '/css/rtl' . $min_ext . '.css', array(), US_THEMEVERSION, 'all' );
		wp_enqueue_style( 'us-rtl' );
	}
}

// Responsive styles
if ( us_get_option( 'responsive_layout', TRUE ) AND ( ( defined( 'US_DEV' ) AND US_DEV ) OR us_get_option( 'optimize_assets', 0 ) == 0 ) ) {
	add_action( 'wp_enqueue_scripts', 'us_responsive_styles', 16 );
	function us_responsive_styles() {
		global $us_template_directory_uri;

		$min_ext = ( ! ( defined( 'US_DEV' ) AND US_DEV ) ) ? '.min' : '';
		wp_register_style( 'us-responsive', $us_template_directory_uri . '/css/responsive' . $min_ext . '.css', array(), US_THEMEVERSION, 'all' );
		wp_enqueue_style( 'us-responsive' );
	}
}

// Child theme styles
add_action( 'wp_enqueue_scripts', 'us_custom_styles', 18 );
function us_custom_styles() {
	if ( is_child_theme() ) {
		global $us_stylesheet_directory_uri;
		wp_enqueue_style( 'theme-style', $us_stylesheet_directory_uri . '/style.css', array(), US_THEMEVERSION, 'all' );
	}
}

// Disable jQuery migrate script
if ( us_get_option( 'disable_jquery_migrate', 1 ) == 1 ) {
	add_action( 'wp_default_scripts', 'us_dequeue_jquery_migrate' );
}
function us_dequeue_jquery_migrate( &$wp_scripts ) {
	if ( is_admin() ) {
		return;
	}
	$jquery_core_obj = $wp_scripts->registered['jquery-core'];
	$wp_scripts->remove( 'jquery' );
	$wp_scripts->add( 'jquery', FALSE, array( 'jquery-core' ), $jquery_core_obj->ver );
}

// Move jQuery scripts to the footer
if ( us_get_option( 'jquery_footer', 1 ) == 1 ) {
	add_action( 'wp_default_scripts', 'us_move_jquery_to_footer' );
}
function us_move_jquery_to_footer( $wp_scripts ) {
	if ( is_admin() ) {
		return;
	}
	$wp_scripts->add_data( 'jquery', 'group', 1 );
	$wp_scripts->add_data( 'jquery-core', 'group', 1 );
	$wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
}

/**
 * Embed JS files
 */
add_action( 'wp_enqueue_scripts', 'us_jscripts' );
function us_jscripts() {
	global $us_template_directory_uri;

	// Link Google Maps API key
	if ( us_get_option( 'gmaps_api_key', '' ) != '' ) {
		wp_register_script( 'us-google-maps', '//maps.googleapis.com/maps/api/js?key=' . trim( esc_attr( us_get_option( 'gmaps_api_key', '' ) ) ), array(), NULL, FALSE );
	} else {
		wp_register_script( 'us-google-maps', '//maps.googleapis.com/maps/api/js', array(), '', FALSE );
	}

	// Embed vendor JS components
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_register_script( 'us-isotope', $us_template_directory_uri . '/framework/js/vendor/isotope.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
		wp_register_script( 'us-royalslider', $us_template_directory_uri . '/framework/js/vendor/royalslider.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
		wp_register_script( 'us-owl', $us_template_directory_uri . '/framework/js/vendor/owl.carousel.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
		wp_register_script( 'us-gmap', $us_template_directory_uri . '/framework/js/vendor/gmaps.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
		wp_register_script( 'us-lmap', $us_template_directory_uri . '/framework/js/vendor/leaflet.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
		wp_register_script( 'us-magnific-popup', $us_template_directory_uri . '/framework/js/vendor/magnific-popup.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
		wp_enqueue_script( 'us-magnific-popup' );
	}

	// Embed all JS components, when DEV mode is enabled
	if ( defined( 'US_DEV' ) AND US_DEV ) {
		$assets_config = us_config( 'assets', array() );
		foreach ( $assets_config as $component => $component_atts ) {
			if ( isset( $component_atts['js'] ) AND isset( $component_atts['order'] ) AND $component_atts['order'] == 'top' ) {
				wp_enqueue_script( 'us-' . $component, $us_template_directory_uri . $component_atts['js'], array( 'jquery' ), US_THEMEVERSION, TRUE );
			}
		}
		foreach ( $assets_config as $component => $component_atts ) {
			if ( isset( $component_atts['js'] ) AND ! isset( $component_atts['order'] ) ) {
				wp_enqueue_script( 'us-' . $component, $us_template_directory_uri . $component_atts['js'], array( 'jquery' ), US_THEMEVERSION, TRUE );
			}
		}
		// Generate and embed single JS file
	} elseif ( us_get_option( 'optimize_assets', 0 ) == 1 ) {

		// Locate asset file
		$js_file = us_get_asset_file( 'js' );

		// If the file doesn't exist
		if ( ! file_exists( $js_file ) ) {

			// try to create the styles file
			us_generate_asset_file( 'js' );

			// if create attempt failed
			if ( ! file_exists( $js_file ) ) {

				// switch the Optimize option off
				global $usof_options;
				usof_load_options_once();
				$updated_options = $usof_options;
				$updated_options['optimize_assets'] = 0;
				usof_save_options( $updated_options );

				// and load default core file to make sure site works
				wp_enqueue_script( 'us-core', $us_template_directory_uri . '/js/us.core.min.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
			}
		}

		// Embed generated file
		if ( file_exists( $js_file ) ) {
			$js_file_url = us_get_asset_file( 'js', TRUE );
			wp_register_script( 'us-core', $js_file_url, array( 'jquery' ), US_THEMEVERSION, TRUE );
		} else {
			wp_register_script( 'us-core', $us_template_directory_uri . '/js/us.core.min.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
		}
		wp_enqueue_script( 'us-core' );

	} else { // Embed default core file in other cases
		wp_enqueue_script( 'us-core', $us_template_directory_uri . '/js/us.core.min.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
	}
}

// Output Custom HTML before </body>
add_action( 'wp_footer', 'us_custom_html_output', 99 );
function us_custom_html_output() {
	echo us_get_option( 'custom_html', '' );
}

/**
 * Generate and cache theme options css data
 *
 * @return string
 */
function us_get_theme_options_css() {
	if ( ( $styles_css = get_option( 'us_theme_options_css' ) ) === FALSE OR ( defined( 'US_DEV' ) AND US_DEV ) ) {
		$styles_css = us_minify_css( us_get_template( 'config/theme-options.css' ) );
		if ( ! defined( 'US_DEV' ) OR ! US_DEV ) {
			update_option( 'us_theme_options_css', $styles_css, TRUE );
		}
	}

	return $styles_css;
}
