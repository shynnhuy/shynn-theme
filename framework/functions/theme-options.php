<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options: USOF + UpSolution extendings
 *
 * Should be included in global context.
 */

add_action( 'usof_after_save', 'us_generate_asset_files' );
add_action( 'usof_ajax_mega_menu_save_settings', 'us_generate_asset_files' );
add_action( 'update_option_siteurl', 'us_generate_asset_files' );
add_action( 'update_option_home', 'us_generate_asset_files' );
function us_generate_asset_files() {
	us_generate_asset_file( 'css' );
	us_generate_asset_file( 'js' );
}

/* Get asset file path */
function us_get_asset_file( $ext, $url = FALSE ) {
	if ( empty( $ext ) ) {
		return FALSE;
	}

	// Set file name based on site name
	$site_url_parts = parse_url( site_url() );
	$file_name = ( ! empty( $site_url_parts['host'] ) ) ? $site_url_parts['host'] : '';
	$file_name .= ( ! empty( $site_url_parts['path'] ) ) ? str_replace( '/', '_', $site_url_parts['path'] ) : '';

	$file = '';
	$upload_dir = wp_get_upload_dir();

	if ( $url ) {
		$file = $upload_dir['baseurl'] . '/us-assets/' . $file_name . '.' . $ext;
		// remove protocols for better compatibility with caching plugins and services
		$file = str_replace( array( 'http:', 'https:' ), '', $file );
	} else {
		// Create file directory
		$file_dir = wp_normalize_path( $upload_dir['basedir'] . '/us-assets' );
		if ( ! is_dir( $file_dir ) ) {
			wp_mkdir_p( trailingslashit( $file_dir ) );
		}
		$file = trailingslashit( $file_dir ) . $file_name . '.' . $ext;
	}

	return $file;
}

/* Generate single asset file with specified extension */
function us_generate_asset_file( $ext ) {
	if ( empty( $ext ) ) {
		return FALSE;
	}

	global $usof_options, $us_template_directory;
	usof_load_options_once();

	if ( isset( $usof_options['optimize_assets'] ) AND $usof_options['optimize_assets'] ) {
		$content = $first_content = '';

		// Add assets specified in Theme Options
		$assets_config = us_config( 'assets', array() );
		foreach ( $assets_config as $component => $component_atts ) {
			if ( isset( $component_atts['apply_if'] ) AND ! $component_atts['apply_if'] ) {
				continue;
			}
			if ( $component == 'lazy-load' AND ! us_get_option( 'lazy_load', 0 ) ) {
				continue;
			}
			if ( ! isset( $component_atts[ $ext ] ) OR ! $component_atts[ $ext ] ) {
				continue;
			}
			if ( ( isset( $component_atts['hidden'] ) AND $component_atts['hidden'] ) OR ! isset( $usof_options['assets'] ) OR in_array( $component, $usof_options['assets'] ) ) {
				$asset_filename = $us_template_directory . $component_atts[ $ext ];
				if ( $ext == 'js' ) {
					$asset_filename = str_replace( '.js', '.min.js', $asset_filename );
				}
				// Move assets with "order" to the top of generated file
				if ( isset( $component_atts['order'] ) AND $component_atts['order'] == 'top' ) {
					$first_content .= file_get_contents( $asset_filename );
					$first_content .= ( $ext == 'js' ) ? ';' : '';
				} else {
					$content .= file_get_contents( $asset_filename );
					$content .= ( $ext == 'js' ) ? ';' : '';
				}
			}
		}

		// Combine first content with other
		$content = $first_content . $content;

		// For CSS
		if ( $ext == 'css' ) {
			// add theme-options styles
			delete_option( 'us_theme_options_css' );
			$content .= us_get_template( 'config/theme-options.css' );
			// add responsive styles
			if ( $usof_options['responsive_layout'] ) {
				$content .= file_get_contents( $us_template_directory . '/css/responsive.css' );
			}
			// add user custom styles
			if ( ( $us_custom_css = us_get_option( 'custom_css', '' ) ) != '' ) {
				$content .= $us_custom_css;
			}
			// minify
			$content = us_minify_css( $content );
		}

		// Break if content is empty
		if ( empty( $content ) ) {
			return FALSE;
		}

		// Locate asset file
		$file = us_get_asset_file( $ext );

		// Generate file in directory
		$handle = @fopen( $file, 'w' );
		if ( $handle ) {
			if ( ! fwrite( $handle, $content ) ) {
				return FALSE;
			}
			fclose( $handle );

			return TRUE;
		}

		return FALSE;

	} elseif ( $ext == 'css' ) {
		update_option( 'us_theme_options_css', us_minify_css( us_get_template( 'config/theme-options.css' ) ), TRUE );
	}

	return FALSE;
}

// Flushing WP rewrite rules on portfolio slug changes
add_action( 'usof_before_save', 'us_maybe_flush_rewrite_rules' );
add_action( 'usof_after_save', 'us_maybe_flush_rewrite_rules' );
function us_maybe_flush_rewrite_rules( $updated_options ) {
	// The function is called twice: before and after options change
	static $old_portfolio_slug = NULL;
	static $old_portfolio_category_slug = NULL;
	$flush_rules = FALSE;
	if ( ! isset( $updated_options['portfolio_slug'] ) ) {
		$updated_options['portfolio_slug'] = NULL;
	}
	if ( ! isset( $updated_options['portfolio_category_slug'] ) ) {
		$updated_options['portfolio_category_slug'] = NULL;
	}
	if ( $old_portfolio_slug === NULL ) {
		// At first call we're storing the previous portfolio slug
		$old_portfolio_slug = us_get_option( 'portfolio_slug', 'portfolio' );
	} elseif ( $old_portfolio_slug != $updated_options['portfolio_slug'] ) {
		// At second call we're triggering flush rewrite rules at the next app execution
		// We're using transients to reduce the number of excess auto-loaded options
		$flush_rules = TRUE;
	}
	if ( $old_portfolio_category_slug === NULL ) {
		// At first call we're storing the previous portfolio slug
		$old_portfolio_category_slug = us_get_option( 'portfolio_category_slug', 'portfolio_category' );
	} elseif ( $old_portfolio_slug != $updated_options['portfolio_category_slug'] ) {
		// At second call we're triggering flush rewrite rules at the next app execution
		// We're using transients to reduce the number of excess auto-loaded options
		$flush_rules = TRUE;
	}

	if ( $flush_rules ) {
		set_transient( 'us_flush_rules', TRUE, DAY_IN_SECONDS );
	}
}

// Allow to change Site Icon via Theme Options page
add_action( 'usof_after_save', 'us_update_site_icon_from_options' );
function us_update_site_icon_from_options( $updated_options ) {
	$options_site_icon = $updated_options['site_icon'];
	$wp_site_icon = get_option( 'site_icon' );

	if ( $options_site_icon != $wp_site_icon ) {
		update_option( 'site_icon', $options_site_icon );
	}
}

// Get Site Icon to display on Theme Options page
add_filter( 'usof_load_options_once', 'us_get_site_icon_for_options' );
function us_get_site_icon_for_options( $usof_options ) {
	$wp_site_icon = get_option( 'site_icon' );

	$usof_options['site_icon'] = $wp_site_icon;

	return $usof_options;
}

// Allow upload woff, woff2 files on Theme Options page
add_filter( 'upload_mimes', 'us_mime_types' );
function us_mime_types( $mimes ) {
	$mimes['woff2'] = 'font/woff';
	$mimes['woff2'] = 'font/woff2';

	return $mimes;
}

// Using USOF for theme options
$usof_directory = $us_template_directory . '/framework/vendor/usof';
$usof_directory_uri = $us_template_directory_uri . '/framework/vendor/usof';
require $us_template_directory . '/framework/vendor/usof/usof.php';

// Exclude pages that are set as Search results / Posts page / 404 not found
add_action( 'pre_get_posts', 'us_exclude_special_pages_from_search' );
function us_exclude_special_pages_from_search( $query ) {
	if ( $query->is_search && $query->is_main_query() ) {
		$special_pages = array();
		$special_pages_names = array( 'search_page', 'posts_page', 'page_404' );
		foreach ( $special_pages_names as $special_page_name ) {
			$special_page_id = us_get_option( $special_page_name, 'default' );
			if ( $special_page_id != 'default' AND intval( $special_page_id ) > 0 ) {
				$special_pages[] = intval( $special_page_id );
			}
		}
		if ( count( $special_pages ) > 0 ) {
			$post__not_in = $query->get( 'post__not_in' );
			if ( ! is_array( $post__not_in ) ) {
				$post__not_in = array();
			}
			$post__not_in = array_merge( $post__not_in, $special_pages );
			$query->set( 'post__not_in', $post__not_in );
		}
	}
}

// Lazy Load
if ( us_get_option( 'lazy_load', 1 ) ) {
	add_filter( 'the_content', 'us_filter_content_for_lazy_load', 99, 1 );
}
function us_filter_content_for_lazy_load( $content ) {

	// Default image before loading originals
	$placeholder_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

	$matches = $search = $replace = array();
	preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );

	// Skip Images Classes
	$skip_images_classes = array( 'rev-slidebg', 'not-lazy' );
	$skip_images_regex = sprintf( '/class=".*(%s).*"/s', implode( '|', $skip_images_classes ) );

	$i = 0;
	foreach ( $matches[0] as $imgHTML ) {
		// don't to the replacement if a skip class is provided and the image has the class, or if the image is a data-uri
		if ( ! preg_match( $skip_images_regex, $imgHTML ) AND ! preg_match( "/src=['\"]data:image/is", $imgHTML ) ) {
			$i ++;
			// replace the src and add the data-src attribute
			$replaceHTML = '';
			$replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . $placeholder_url . '" data-lazy-type="image" data-src=', $imgHTML );
			$replaceHTML = preg_replace( '/<img(.*?)srcset=/is', '<img$1srcset="" data-srcset=', $replaceHTML );

			// add the lazy class to the img element
			if ( preg_match( '/class=["\']/i', $replaceHTML ) ) {
				$replaceHTML = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1lazy lazy-hidden $2$1', $replaceHTML );
			} else {
				$replaceHTML = preg_replace( '/<img/is', '<img class="lazy lazy-hidden"', $replaceHTML );
			}

			array_push( $search, $imgHTML );
			array_push( $replace, $replaceHTML );
		}
	}

	$search = array_unique( $search );
	$replace = array_unique( $replace );

	$content = str_replace( $search, $replace, $content );

	return $content;
}
