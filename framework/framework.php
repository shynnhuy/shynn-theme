<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * UpSolution Themes Framework
 *
 * Should be included in global context.
 */
global $us_template_directory, $us_stylesheet_directory, $us_template_directory_uri, $us_stylesheet_directory_uri;
$us_template_directory = get_template_directory();
$us_stylesheet_directory = get_stylesheet_directory();
$us_template_directory_uri = get_template_directory_uri();
$us_stylesheet_directory_uri = get_stylesheet_directory_uri();

if ( ! defined( 'US_THEMENAME' ) OR ! defined( 'US_THEMEVERSION' ) ) {
	$us_theme = wp_get_theme();
	if ( is_child_theme() ) {
		$us_theme = wp_get_theme( $us_theme->get( 'Template' ) );
	}
	if ( ! defined( 'US_THEMENAME' ) ) {
		define( 'US_THEMENAME', $us_theme->get( 'Name' ) );
	}
	if ( ! defined( 'US_THEMEVERSION' ) ) {
		define( 'US_THEMEVERSION', $us_theme->get( 'Version' ) );
	}
	if ( ! defined( 'US_THEME_BETA' ) AND strpos( $us_theme->get( 'Version' ), 'beta' ) !== FALSE ) {
		define( 'US_THEME_BETA', TRUE );
	}
	unset( $us_theme );
}

if ( ! isset( $us_theme_supports ) ) {
	$us_theme_supports = array();
}

// Upsolution helper functions
require $us_template_directory . '/framework/functions/helpers.php';

// UpSolution Header definitions
require $us_template_directory . '/framework/functions/header.php';

// Post formats
require $us_template_directory . '/framework/functions/post.php';

// Theme Options
require $us_template_directory . '/framework/functions/theme-options.php';

// Performing fallback compatibility and migrations when needed
require $us_template_directory . '/framework/functions/migration.php';

// Load shortcodes
require $us_template_directory . '/framework/functions/shortcodes.php';

// UpSolution Layout definitions
require $us_template_directory . '/framework/functions/layout.php';

// Breadcrumbs function
require $us_template_directory . '/framework/functions/breadcrumbs.php';

// Custom Post types
require $us_template_directory . '/framework/functions/post-types.php';

// Page Meta Tags
require $us_template_directory . '/framework/functions/meta-tags.php';

// Menu and it's custom markup
require $us_template_directory . '/framework/functions/menu.php';
// Comments custom markup
require $us_template_directory . '/framework/functions/comments.php';

// Sidebars init
require $us_template_directory . '/framework/functions/widget_areas.php';

// Media Categories
if ( us_get_option( 'media_category' ) ) {
	require $us_template_directory . '/framework/functions/media.php';
}

// Plugins activation
if ( is_admin() ) {
	// Admin specific functions
	require $us_template_directory . '/framework/admin/functions/functions.php';
	require $us_template_directory . '/framework/admin/functions/updater.php';
	require $us_template_directory . '/framework/admin/functions/theme-updater.php';
	require $us_template_directory . '/framework/admin/functions/grid-builder.php';
} else {
	//Removing protocols from URLs for better compatibility with caching plugins and services if enabled
	$remove_protocol = us_get_option( 'remove_protocol', 0 );
	if ( $remove_protocol ) {
		$us_template_directory_uri = str_replace( array( 'http:', 'https:' ), '', get_template_directory_uri() );
		$us_stylesheet_directory_uri = str_replace( array( 'http:', 'https:' ), '', get_stylesheet_directory_uri() );
	}
	// Frontent CSS and JS enqueue
	require $us_template_directory . '/framework/functions/enqueue.php';
}

// Widgets
require $us_template_directory . '/framework/functions/widgets.php';
add_filter( 'widget_text', 'do_shortcode' );

if ( is_admin() ) {
	// Addons
	require $us_template_directory . '/framework/admin/functions/addons.php';
	// Demo Import
	require $us_template_directory . '/framework/admin/functions/demo-import.php';
	// About page
	require $us_template_directory . '/framework/admin/functions/about.php';
}

if ( defined( 'DOING_AJAX' ) AND DOING_AJAX ) {
	require $us_template_directory . '/framework/functions/ajax/grid.php';
	require $us_template_directory . '/framework/functions/ajax/cform.php';
	require $us_template_directory . '/framework/functions/ajax/cart.php';
	require $us_template_directory . '/framework/functions/ajax/grid_builder.php';
	require $us_template_directory . '/framework/functions/ajax/us_login.php';
}

// Including plugins support files
if ( ! isset( $us_theme_supports['plugins'] ) ) {
	$us_theme_supports['plugins'] = array();
}
foreach ( $us_theme_supports['plugins'] AS $us_plugin_name => $us_plugin_path ) {
	if ( $us_plugin_path === NULL ) {
		continue;
	}
	include $us_template_directory . $us_plugin_path;
}

/**
 * Theme Setup
 */
add_action( 'after_setup_theme', 'us_theme_setup' );
function us_theme_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'link' ) );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	// Custom image sizes
	$custom_tnail_sizes = us_get_option( 'img_size' );
	if ( is_array( $custom_tnail_sizes ) ) {
		foreach ( $custom_tnail_sizes as $size_index => $size ) {
			$crop = ( ! empty( $size['crop'][0] ) );
			$crop_str = ( $crop ) ? '_crop' : '';
			$width = ( ! empty( $size['width'] ) AND intval( $size['width'] ) > 0 ) ? intval( $size['width'] ) : 0;
			$height = ( ! empty( $size['height'] ) AND intval( $size['height'] ) > 0 ) ? intval( $size['height'] ) : 0;
			add_image_size( 'us_' . $width . '_' . $height . $crop_str, $width, $height, $crop );
		}
	}

	// Remove [...] from excerpt
	add_filter( 'excerpt_more', 'us_excerpt_more' );
	function us_excerpt_more( $more ) {
		return '...';
	}

	// Theme localization
	us_maybe_load_theme_textdomain();
}

// Remove unchangeable WP thumb size
add_filter( 'intermediate_image_sizes', 'delete_intermediate_image_sizes' );
function delete_intermediate_image_sizes( $sizes ) {
	return array_diff( $sizes, array( 'medium_large' ) );
}

// Disable CSS file of WPML plugin
if ( ! defined( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS' ) ) {
	define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', TRUE );
}

// Additional file types for uploading to Media Library
add_filter( 'upload_mimes', 'us_upload_file_types' );
function us_upload_file_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['woff'] = 'application/font-woff';
	$mimes['woff2'] = 'application/font-woff2';

	return $mimes;
}

// SVG previews in Media Library
add_filter( 'wp_prepare_attachment_for_js', 'us_response_for_svg', 10, 3 );
function us_response_for_svg( $response, $attachment, $meta ) {

	if ( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {
		$svg_path = get_attached_file( $attachment->ID );

		if ( ! file_exists( $svg_path ) ) {
			// If SVG is external, use the URL instead of the path
			$svg_path = $response['url'];
		}

		$dimensions = us_get_svg_dimensions( $svg_path );

		$response['sizes'] = array(
			'full' => array(
				'url' => $response['url'],
				'width' => $dimensions->width,
				'height' => $dimensions->height,
				'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait',
			),
		);
	}

	return $response;

}

function us_get_svg_dimensions( $svg ) {
	$svg = simplexml_load_file( $svg );

	if ( $svg === FALSE ) {
		$width = '0';
		$height = '0';

	} else {
		$attributes = $svg->attributes();
		$width = (string) $attributes->width;
		$height = (string) $attributes->height;

	}

	return (object) array( 'width' => $width, 'height' => $height );

}