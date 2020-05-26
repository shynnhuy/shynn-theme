<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Prepare a proper icon tag from user's custom input
 *
 * @param {String} $icon
 *
 * @return mixed|string
 */
function us_prepare_icon_tag( $icon ) {
	$icon = apply_filters( 'us_icon_class', $icon );
	$icon_arr = explode( '|', $icon );
	if ( count( $icon_arr ) != 2 ) {
		return '';
	}

	$icon_arr[1] = strtolower( sanitize_text_field( $icon_arr[1] ) );
	if ( $icon_arr[0] == 'material' ) {
		$icon_tag = '<i class="material-icons">' . str_replace( array( ' ', '-' ), '_', $icon_arr[1] ) . '</i>';
	} else {
		if ( substr( $icon_arr[1], 0, 3 ) == 'fa-' ) {
			$icon_tag = '<i class="' . $icon_arr[0] . ' ' . $icon_arr[1] . '"></i>';
		} else {
			$icon_tag = '<i class="' . $icon_arr[0] . ' fa-' . $icon_arr[1] . '"></i>';
		}
	}

	return apply_filters( 'us_icon_tag', $icon_tag );
}

/**
 * Search for some file in child theme, in parent theme and in framework
 *
 * @param string $filename Relative path to filename with extension
 * @param bool $all List an array of found files
 *
 * @return mixed Single mode: full path to file or FALSE if no file was found
 * @return array All mode: array or all the found files
 */
function us_locate_file( $filename, $all = FALSE ) {
	global $us_template_directory, $us_stylesheet_directory, $us_files_search_paths, $us_file_paths;
	if ( ! isset( $us_files_search_paths ) ) {
		$us_files_search_paths = array();
		if ( is_child_theme() ) {
			// Searching in child theme first
			$us_files_search_paths[] = trailingslashit( $us_stylesheet_directory );
		}
		// Parent theme
		$us_files_search_paths[] = trailingslashit( $us_template_directory );
		// The framework with files common for all themes
		$us_files_search_paths[] = $us_template_directory . '/framework/';
		// Can be overloaded if you decide to overload something from certain plugin
		$us_files_search_paths = apply_filters( 'us_files_search_paths', $us_files_search_paths );
	}
	if ( ! $all ) {
		if ( ! isset( $us_file_paths ) ) {
			$us_file_paths = apply_filters( 'us_file_paths', array() );
		}
		$filename = untrailingslashit( $filename );
		if ( ! isset( $us_file_paths[ $filename ] ) ) {
			$us_file_paths[ $filename ] = FALSE;
			foreach ( $us_files_search_paths as $search_path ) {
				if ( file_exists( $search_path . $filename ) ) {
					$us_file_paths[ $filename ] = $search_path . $filename;
					break;
				}
			}
		}

		return $us_file_paths[ $filename ];
	} else {
		$found = array();

		foreach ( $us_files_search_paths as $search_path ) {
			if ( file_exists( $search_path . $filename ) ) {
				$found[] = $search_path . $filename;
			}
		}

		return $found;
	}
}

/**
 * Load some specified template and pass variables to it's scope.
 *
 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
 *
 * @param string $template_name Template name to include (ex: 'templates/form/form')
 * @param array $vars Array of variables to pass to a included templated
 */
function us_load_template( $template_name, $vars = NULL ) {

	// Searching for the needed file in a child theme, in the parent theme and, finally, in the framework
	$file_path = us_locate_file( $template_name . '.php' );

	// Template not found
	if ( $file_path === FALSE ) {
		do_action( 'us_template_not_found:' . $template_name, $vars );

		return;
	}

	$vars = apply_filters( 'us_template_vars:' . $template_name, (array) $vars );
	if ( is_array( $vars ) AND count( $vars ) > 0 ) {
		extract( $vars, EXTR_SKIP );
	}

	do_action( 'us_before_template:' . $template_name, $vars );

	include $file_path;

	do_action( 'us_after_template:' . $template_name, $vars );
}

/**
 * Get some specified template output with variables passed to it's scope.
 *
 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
 *
 * @param string $template_name Template name to include (ex: 'templates/form/form')
 * @param array $vars Array of variables to pass to a included templated
 *
 * @return string
 */
function us_get_template( $template_name, $vars = NULL ) {
	ob_start();
	us_load_template( $template_name, $vars );

	return ob_get_clean();
}

/**
 * Get theme option or return default value
 *
 * @param string $name
 * @param mixed $default_value
 *
 * @return mixed
 */
function us_get_option( $name, $default_value = NULL ) {
	return usof_get_option( $name, $default_value );
}

/**
 * @var $us_query array Allows to use different global $wp_query in different context safely
 */
$us_wp_queries = array();

/**
 * Opens a new context to use a new custom global $wp_query
 *
 * (!) Don't forget to close it!
 */
function us_open_wp_query_context() {
	if ( is_array( $GLOBALS ) AND isset( $GLOBALS['wp_query'] ) ) {
		array_unshift( $GLOBALS['us_wp_queries'], $GLOBALS['wp_query'] );
	}
}

/**
 * Closes last context with a custom
 */
function us_close_wp_query_context() {
	if ( isset( $GLOBALS['us_wp_queries'] ) AND count( $GLOBALS['us_wp_queries'] ) > 0 ) {
		$GLOBALS['wp_query'] = array_shift( $GLOBALS['us_wp_queries'] );
		wp_reset_postdata();
	} else {
		// In case someone forgot to open the context
		wp_reset_query();
	}
}

/**
 * Opens a new page block context
 *
 */
function us_add_to_page_block_ids( $page_block_id = NULL ) {

	global $us_page_block_ids;
	if ( empty( $us_page_block_ids ) ) {
		$us_page_block_ids = array();
	}
	if ( $page_block_id != NULL ) {
		array_unshift( $us_page_block_ids, $page_block_id );
	}

}

/**
 * Closes last page_block context
 */
function us_remove_from_page_block_ids() {

	global $us_page_block_ids;

	return array_shift( $us_page_block_ids );
}

/**
 * Get a value from multidimensional array by path
 *
 * @param array $arr
 * @param string|array $path <key1>[.<key2>[...]]
 * @param mixed $default
 *
 * @return mixed
 */
function us_arr_path( &$arr, $path, $default = NULL ) {
	$path = is_string( $path ) ? explode( '.', $path ) : $path;
	foreach ( $path as $key ) {
		if ( ! is_array( $arr ) OR ! isset( $arr[ $key ] ) ) {
			return $default;
		}
		$arr = &$arr[ $key ];
	}

	return $arr;
}

/**
 * Load and return some specific config or it's part
 *
 * @param string $path <config_name>[.<key1>[.<key2>[...]]]
 *
 * @oaram mixed $default Value to return if no data is found
 *
 * @return mixed
 */
function us_config( $path, $default = NULL, $reload = FALSE ) {
	global $us_template_directory;
	// Caching configuration values in a inner static value within the same request
	static $configs = array();
	// Defined paths to configuration files
	$config_name = strtok( $path, '.' );
	if ( ! isset( $configs[ $config_name ] ) OR $reload ) {
		$config_paths = array_reverse( us_locate_file( 'config/' . $config_name . '.php', TRUE ) );
		if ( empty( $config_paths ) ) {
			if ( WP_DEBUG ) {
				wp_die( 'Config not found: ' . $config_name );
			}
			$configs[ $config_name ] = array();
		} else {
			us_maybe_load_theme_textdomain();
			// Parent $config data may be used from a config file
			$config = array();
			foreach ( $config_paths as $config_path ) {
				$config = require $config_path;
				// Config may be forced not to be overloaded from a config file
				if ( isset( $final_config ) AND $final_config ) {
					break;
				}
			}
			$configs[ $config_name ] = apply_filters( 'us_config_' . $config_name, $config );
		}
	}

	$path = substr( $path, strlen( $config_name ) + 1 );
	if ( $path == '' ) {
		return $configs[ $config_name ];
	}

	return us_arr_path( $configs[ $config_name ], $path, $default );
}

/**
 * Get image size information as an array
 *
 * @param string $size_name
 *
 * @return array
 */
function us_get_intermediate_image_size( $size_name ) {
	global $_wp_additional_image_sizes;
	if ( isset( $_wp_additional_image_sizes[ $size_name ] ) ) {
		// Getting custom image size
		return $_wp_additional_image_sizes[ $size_name ];
	} else {
		// Getting standard image size
		return array(
			'width' => get_option( "{$size_name}_size_w" ),
			'height' => get_option( "{$size_name}_size_h" ),
			'crop' => get_option( "{$size_name}_crop" ),
		);
	}
}

/**
 * Transform some variable to elm's onclick attribute, so it could be obtained from JavaScript as:
 * var data = elm.onclick()
 *
 * @param mixed $data Data to pass
 *
 * @return string Element attribute ' onclick="..."'
 */
function us_pass_data_to_js( $data ) {
	return ' onclick=\'return ' . htmlspecialchars( json_encode( $data ), ENT_QUOTES, 'UTF-8' ) . '\'';
}

/**
 * Try to get variable from JSON-encoded post variable
 *
 * Note: we pass some params via json-encoded variables, as via pure post some data (ex empty array) will be absent
 *
 * @param string $name $_POST's variable name
 *
 * @return array
 */
function us_maybe_get_post_json( $name = 'template_vars' ) {
	if ( isset( $_POST[ $name ] ) AND is_string( $_POST[ $name ] ) ) {
		$result = json_decode( stripslashes( $_POST[ $name ] ), TRUE );
		if ( ! is_array( $result ) ) {
			$result = array();
		}

		return $result;
	} else {
		return array();
	}
}

/**
 * Load theme's textdomain
 *
 * @param string $domain
 * @param string $path Relative path to seek in child theme and theme
 *
 * @return bool
 */
function us_maybe_load_theme_textdomain( $domain = 'us', $path = '/languages' ) {
	if ( is_textdomain_loaded( $domain ) ) {
		return TRUE;
	}
	$locale = apply_filters( 'theme_locale', is_admin() ? get_user_locale() : get_locale(), $domain );
	$filepath = us_locate_file( trailingslashit( $path ) . $locale . '.mo' );
	if ( $filepath === FALSE ) {
		return FALSE;
	}

	return load_textdomain( $domain, $filepath );
}

/**
 * Merge arrays, inserting $arr2 into $arr1 before/after certain key
 *
 * @param array $arr Modifyed array
 * @param array $inserted Inserted array
 * @param string $position 'before' / 'after' / 'top' / 'bottom'
 * @param string $key Associative key of $arr1 for before/after insertion
 *
 * @return array
 */
function us_array_merge_insert( array $arr, array $inserted, $position = 'bottom', $key = NULL ) {
	if ( $position == 'top' ) {
		return array_merge( $inserted, $arr );
	}
	$key_position = ( $key === NULL ) ? FALSE : array_search( $key, array_keys( $arr ) );
	if ( $key_position === FALSE OR ( $position != 'before' AND $position != 'after' ) ) {
		return array_merge( $arr, $inserted );
	}
	if ( $position == 'after' ) {
		$key_position ++;
	}

	return array_merge( array_slice( $arr, 0, $key_position, TRUE ), $inserted, array_slice( $arr, $key_position, NULL, TRUE ) );
}

/**
 * Recursively merge two or more arrays in a proper way
 *
 * @param array $array1
 * @param array $array2
 * @param array ...
 *
 * @return array
 */
function us_array_merge( $array1, $array2 ) {
	$keys = array_keys( $array2 );
	// Is associative array?
	if ( array_keys( $keys ) !== $keys ) {
		foreach ( $array2 as $key => $value ) {
			if ( is_array( $value ) AND isset( $array1[ $key ] ) AND is_array( $array1[ $key ] ) ) {
				$array1[ $key ] = us_array_merge( $array1[ $key ], $value );
			} else {
				$array1[ $key ] = $value;
			}
		}
	} else {
		foreach ( $array2 as $value ) {
			if ( ! in_array( $value, $array1, TRUE ) ) {
				$array1[] = $value;
			}
		}
	}

	if ( func_num_args() > 2 ) {
		foreach ( array_slice( func_get_args(), 2 ) as $array2 ) {
			$array1 = us_array_merge( $array1, $array2 );
		}
	}

	return $array1;
}

/**
 * Combine user attributes with known attributes and fill in defaults from config when needed.
 *
 * @param array $atts Passed attributes
 * @param string $shortcode Shortcode name
 * @param string $param_name Shortcode's config param to take pairs from
 *
 * @return array
 */
function us_shortcode_atts( $atts, $shortcode ) {
	if ( substr( $shortcode, 0, 3 ) == 'us_' ) {
		$element = substr( $shortcode, 3 );
		$pairs = array();
		if ( in_array( $element, us_config( 'shortcodes.theme_elements', array() ) ) ) {
			$element_config = us_config( 'elements/' . $element, array() );
			if ( ! empty( $element_config['params'] ) ) {
				foreach ( $element_config['params'] as $param_name => $param_config ) {
					if ( isset( $param_config['shortcode_std'] ) ) {
						$param_config['std'] = $param_config['shortcode_std'];
					}
					if ( $param_config['type'] == 'checkboxes' AND isset( $param_config['std'] ) AND is_array( $param_config['std'] ) ) {
						$param_config['std'] = implode( ',', $param_config['std'] );
					}
					$pairs[ $param_name ] = ( isset( $param_config['std'] ) ) ? $param_config['std'] : NULL;
				}
			}
		}
	} else {
		$pairs = us_config( 'shortcodes.modified.' . $shortcode . '.' . 'atts', array() );
	}

	$atts = shortcode_atts( $pairs, $atts, $shortcode );
	return apply_filters( 'us_shortcode_atts', $atts, $shortcode );
}

/**
 * Get number of shares of the provided URL.
 *
 * @param string $url The url to count shares
 * @param array $providers Possible array values: 'facebook', 'pinterest', 'vk'
 *
 * Dev note: keep in mind that list of providers may differ for the same URL in different function calls.
 *
 * @return array Associative array of providers => share counts
 */
function us_get_sharing_counts( $url, $providers ) {
	$transient = 'us_sharing_count_' . md5( $url );
	// Will be used for array keys operations
	$flipped = array_flip( $providers );
	$cached_counts = get_transient( $transient );
	if ( is_array( $cached_counts ) ) {
		$counts = array_intersect_key( $cached_counts, $flipped );
		if ( count( $counts ) == count( $providers ) ) {
			// The data exists and is complete
			return $counts;
		}
	} else {
		$counts = array();
	}

	// Facebook share count
	if ( in_array( 'facebook', $providers ) AND ! isset( $counts['facebook'] ) ) {
		$remote_get_url = 'https://graph.facebook.com/?ids=' . $url;
		$result = wp_remote_get( $remote_get_url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) ) {
			$data = json_decode( $result['body'], TRUE );
		} else {
			$data = NULL;
		}
		if ( is_array( $data ) AND isset( $data[ $url ] ) AND isset( $data[ $url ]['share'] ) AND isset( $data[ $url ]['share']['share_count'] ) ) {
			$counts['facebook'] = use_letters_for_numbers( $data[ $url ]['share']['share_count'] );
		} else {
			$counts['facebook'] = '0';
		}
	}
	// Pinterest share count
	if ( in_array( 'pinterest', $providers ) AND ! isset( $counts['pinterest'] ) ) {
		$result = wp_remote_get( 'https://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) ) {
			$data = json_decode( rtrim( str_replace( 'receiveCount(', '', $result['body'] ), ')' ), TRUE );
		} else {
			$data = NULL;
		}
		$counts['pinterest'] = isset( $data['count'] ) ? use_letters_for_numbers( $data['count'] ) : '0';
	}

	// VK share count
	if ( in_array( 'vk', $providers ) AND ! isset( $counts['vk'] ) ) {
		$result = wp_remote_get( 'http://vkontakte.ru/share.php?act=count&index=1&url=' . $url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) ) {
			$data = intval( trim( str_replace( ');', '', str_replace( 'VK.Share.count(1, ', '', $result['body'] ) ) ) );
		} else {
			$data = NULL;
		}
		$counts['vk'] = ( ! empty( $data ) ) ? use_letters_for_numbers( $data ) : '0';
	}

	// Caching the result for the next 2 hours
	set_transient( $transient, $counts, 2 * HOUR_IN_SECONDS );

	return $counts;
}

/**
 * Replace millions and thousands for "M" and "K" in numbers
 */
function use_letters_for_numbers( $value ) {

	if ( (int) $value > 1000000 ) {
		$value = number_format( $value / 1000000, 1 ) . 'M';
	} elseif ( (int) $value > 1000 ) {
		$value = number_format( $value / 1000, 1 ) . 'К';
	}

	return $value;
}

/**
 * Call language function with string existing in WordPress or supported plugins and prevent those strings from going into theme .po/.mo files
 *
 * @return string Translated text.
 */
function us_translate( $text, $domain = NULL ) {
	if ( $domain == NULL ) {
		return __( $text );
	} else {
		return __( $text, $domain );
	}
}

function us_translate_x( $text, $context, $domain = NULL ) {
	if ( $domain == NULL ) {
		return _x( $text, $context );
	} else {
		return _x( $text, $context, $domain );
	}
}

function us_translate_n( $single, $plural, $number, $domain = NULL ) {
	if ( $domain == NULL ) {
		return _n( $single, $plural, $number );
	} else {
		return _n( $single, $plural, $number, $domain );
	}
}

/**
 * Prepare a proper inline-css string from given css property
 *
 * @param array $props
 * @param bool $style_attr
 * @param string $tag
 *
 * @return string
 */
function us_prepare_inline_css( $props, $style_attr = TRUE, $tag = 'div' ) {
	$result = '';

	foreach ( $props as $prop => $value ) {
		$value = is_string( $value ) ? trim( $value ) : $value;

		// Do not apply if a value is empty string or contains double minus --
		if ( $value == '' OR ( is_string( $value ) AND strpos( $value, '--' ) !== FALSE ) ) {
			continue;
		}
		switch ( $prop ) {

			// Font-family exceptions
			case 'font-family':
				// check h1-h6 tags to avoid duplicating styles
				if ( in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
					if ( $tag == $value ) {
						break;
					} elseif ( $value == 'h1' AND us_get_option( $tag . '_font_family' ) == 'get_h1|' ) {
						break;
					} else {
						$result .= us_get_font_css( $value );
					}
				} elseif ( $value != 'body' ) {
					$result .= us_get_font_css( $value );
				}
				break;

			// Properties with image values
			case 'background-image':
				if ( is_numeric( $value ) ) {
					$image = wp_get_attachment_image_src( $value, 'full' );
					if ( $image ) {
						$result .= $prop . ':url("' . $image[0] . '");';
					}
				} else {
					$result .= $prop . ':url("' . $value . '");';
				}
				break;

			// All other properties
			default:
				$result .= $prop . ':' . $value . ';';
				break;
		}
	}
	if ( $style_attr AND ! empty( $result ) ) {
		$result = ' style="' . esc_attr( $result ) . '"';
	}

	return $result;
}

/**
 * Prepares a minified version of CSS file
 *
 * @link http://manas.tungare.name/software/css-compression-in-php/
 * @param string $css
 *
 * @return string
 */
function us_minify_css( $css ) {
	// Remove comments
	$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

	// Remove space around opening bracket
	$css = str_replace( array( ' {', '{ ' ), '{', $css );

	// Remove space after colons
	$css = str_replace( ': ', ':', $css );

	// Remove spaces
	$css = str_replace( ' > ', '>', $css );
	$css = str_replace( ' ~ ', '~', $css );
	$css = str_replace( '; ', ';', $css );

	// Remove whitespace
	$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );

	// Remove semicolon before closing bracket
	$css = str_replace( array( ';}', '; }', ' }' ), '}', $css );

	return $css;
}

/**
 * Perform request to US Portal API
 *
 * @param $url
 *
 * @return array|bool|mixed|object
 */
function us_api_remote_request( $url ) {

	if ( empty( $url ) ) {
		return FALSE;
	}

	$args = array(
		'headers' => array( 'Accept-Encoding' => '' ),
		//		'sslverify' => FALSE,
		'timeout' => 300,
		'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36',
	);
	$request = wp_remote_request( $url, $args );

	if ( is_wp_error( $request ) ) {
		//		echo $request->get_error_message();
		return FALSE;
	}

	$data = json_decode( $request['body'] );

	return $data;
}

/**
 * Get metabox option value
 *
 * @return string|array
 */
function usof_meta( $key, $args = array(), $post_id = NULL ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$value = '';
	if ( ! empty( $key ) ) {
		$value = get_post_meta( $post_id, $key, TRUE );
	}

	return $value;
}

/**
 * Clear square brackets from extra html tags
 *
 * @return string
 */
function us_paragraph_fix( $content ) {
	$array = array(
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']',
		']<br>' => ']',
	);

	$content = strtr( $content, $array );

	return $content;
}

/**
 * Get preloader numbers
 *
 * @return array
 */
function us_get_preloader_numeric_types() {
	$config = us_config( 'theme-options' );
	$result = array();

	if ( isset( $config['general']['fields']['preloader']['options'] ) ) {
		$options = $config['general']['fields']['preloader']['options'];
	} else {
		return array();
	}

	if ( is_array( $options ) ) {
		foreach ( $options as $option => $title ) {
			if ( intval( $option ) != 0 ) {
				$result[] = $option;
			}
		}

		return $result;
	} else {
		return array();
	}
}

/**
 * Shade color https://stackoverflow.com/a/13542669
 *
 * @return string
 */
function us_shade_color( $color, $percent = '0.2' ) {
	$default = '';

	if ( empty( $color ) ) {
		return $default;
	}
	// TODO: make RGBA values appliable
	$color = str_replace( '#', '', $color );

	if ( strlen( $color ) == 6 ) {
		$RGB = str_split( $color, 2 );
		$R = hexdec( $RGB[0] );
		$G = hexdec( $RGB[1] );
		$B = hexdec( $RGB[2] );
	} elseif ( strlen( $color ) == 3 ) {
		$RGB = str_split( $color, 1 );
		$R = hexdec( $RGB[0] );
		$G = hexdec( $RGB[1] );
		$B = hexdec( $RGB[2] );
	} else {
		return $default;
	}

	// Determine color lightness (from 0 to 255)
	$lightness = $R * 0.213 + $G * 0.715 + $B * 0.072;

	// Make result lighter, when initial color lightness is low
	$t = $lightness < 60 ? 255 : 0;

	// Correct shade percent regarding color lightness
	$percent = $percent * ( 1.3 - $lightness / 255 );

	$output = 'rgb(';
	$output .= round( ( $t - $R ) * $percent ) + $R . ',';
	$output .= round( ( $t - $G ) * $percent ) + $G . ',';
	$output .= round( ( $t - $B ) * $percent ) + $B . ')';

	$output = us_rgba2hex( $output );

	// Return HEX color
	return $output;
}

/**
 * Convert HEX to RGBA
 *
 * @return string
 */
function us_hex2rgba( $color, $opacity = FALSE ) {
	$default = 'rgb(0,0,0)';

	// Return default if no color provided
	if ( empty( $color ) ) {
		return $default;
	}

	// Sanitize $color if "#" is provided
	if ( $color[0] == '#' ) {
		$color = substr( $color, 1 );
	}

	// Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) == 6 ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	// Convert hexadec to rgb
	$rgb = array_map( 'hexdec', $hex );

	// Check if opacity is set(rgba or rgb)
	if ( $opacity ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ",", $rgb ) . ')';
	}

	// Return rgb(a) color string
	return $output;
}

/**
 * Extract first value from linear-gradient
 *
 * @param $color String linear-gradient value
 * @return String hex value
 */
function us_gradient2hex( $color = '' ) {
	if ( preg_match( '~linear-gradient\(([^,]+),([^,]+),([^)]+)\)~', $color, $matches ) ) {
		$color = (string) $matches[2];

		if ( ( strpos( $color, 'rgb' ) !== FALSE ) AND preg_match( '~rgba?\([^)]+\)~', $matches[0], $rgba ) ) {
			$color = (string) $rgba[0];
			$color = us_rgba2hex( $color );
		}
	}

	return $color;
}

/**
 * Convert RGBA to HEX
 *
 * @return string
 */
function us_rgba2hex( $color ) {
	// Returns HEX in case of RGB is provided, otherwise returns as is
	$default = "#000000";

	if ( empty( $color ) ) {
		return $default;
	}

	$rgb = array();
	$regex = '#\((([^()]+|(?R))*)\)#';

	if ( preg_match_all( $regex, $color, $matches ) ) {
		$rgba = explode( ',', implode( ' ', $matches[1] ) );
		// Cuts first 3 values for RGB
		$rgb = array_slice( $rgba, 0, 3 );
	} else {
		return (string) $color;
	}

	$output = "#";

	foreach ( $rgb as $color ) {
		$hex_val = dechex( intval( $color ) );
		if ( strlen( $hex_val ) === 1 ) {
			$output .= '0' . $hex_val;
		} else {
			$output .= $hex_val;
		}
	}

	return $output;
}

/**
 * Return filtered color value
 *
 * @param $value String
 * @param $allow_gradient Bool
 *
 * @return String
 */
function us_get_color( $value = '', $allow_gradient = FALSE ) {

	if ( strpos( $value, 'color' ) !== FALSE ) {
		$color = us_get_option( $value, '' ); // if the value has "color" string, get the color from Theme Options > Colors
	} elseif ( strpos( $value, '_' ) === 0 ) {
		$color = us_get_option( 'color' . $value, '' ); // if the value begins with "_" string, get the color from Theme Options > Colors
	} else {
		$color = $value; // in other cases use value as color
	}

	return ( $allow_gradient ) ? $color : us_gradient2hex( $color );
}

/**
 * Grid function
 */
function us_grid_query_offset( &$query ) {
	if ( ! isset( $query->query['_id'] ) OR $query->query['_id'] !== 'us_grid' ) {
		return;
	}

	global $us_grid_items_offset;

	$posts_per_page = ( ! empty( $query->query['posts_per_page'] ) ) ? $query->query['posts_per_page'] : get_option( 'posts_per_page' );

	if ( $query->is_paged ) {
		$page_offset = $us_grid_items_offset + ( ( $query->query_vars['paged'] - 1 ) * $posts_per_page );

		// Apply adjust page offset
		$query->set( 'offset', $page_offset );

	} else {
		// This is the first page. Just use the offset...
		$query->set( 'offset', $us_grid_items_offset );

	}

	remove_action( 'pre_get_posts', 'us_grid_query_offset' );
}

/**
 * Grid function
 */
function us_grid_adjust_offset_pagination( $found_posts, $query ) {
	if ( ! isset( $query->query['_id'] ) OR $query->query['_id'] !== 'us_grid' ) {
		return $found_posts;
	}

	global $us_grid_items_offset;
	remove_filter( 'found_posts', 'us_grid_adjust_offset_pagination' );

	// Reduce WordPress's found_posts count by the offset...
	return $found_posts - $us_grid_items_offset;
}

/**
 * Get taxonomies for selection
 *
 * @param string $titles_format Titles format
 *
 * @return array: slug => title (plural label)
 */
function us_get_taxonomies( $public_only = FALSE, $show_slug = TRUE ) {
	$result = array();

	$args = array( 'show_ui' => TRUE );
	if ( $public_only ) {
		$args['public'] = TRUE;
		$args['publicly_queryable'] = TRUE;
	}

	$taxonomies = get_taxonomies( $args, 'object' );
	foreach ( $taxonomies as $taxonomy ) {

		// Exclude taxonomy which is not linked to any post type
		if ( empty( $taxonomy->object_type ) OR empty( $taxonomy->object_type[0] ) ) {
			continue;
		}

		// Exclude empty taxonomy (without terms)
		$terms_count = wp_count_terms( $taxonomy->name );
		if ( empty( $terms_count ) ) {
			continue;
		}

		$taxonomy_title = $taxonomy->labels->name;

		// Show slug if set
		if ( $show_slug ) {
			$taxonomy_title .= ' (' . $taxonomy->name . ')';
		}

		$result[ $taxonomy->name ] = $taxonomy_title;
	}

	return $result;
}

/**
 * Get custom fields for selection
 *
 * @return array
 */
function us_get_custom_fields() {

	// Predefined options
	$custom_fields = array(
		'us_tile_additional_image' => __( 'Custom appearance in Grid', 'us' ) . ': ' . __( 'Additional Image', 'us' ),
		'us_testimonial_author' => __( 'Testimonial', 'us' ) . ': ' . __( 'Author Name', 'us' ),
		'us_testimonial_role' => __( 'Testimonial', 'us' ) . ': ' . __( 'Author Role', 'us' ),
		'us_testimonial_company' => __( 'Testimonial', 'us' ) . ': ' . __( 'Author Company', 'us' ),
		'us_testimonial_rating' => __( 'Testimonial', 'us' ) . ': ' . __( 'Rating', 'us' ),
		'_wp_attachment_image_alt' => us_translate( 'Image' ) . ': ' . us_translate( 'Alt Text' ),
	);

	// Get options from Advanced Custom Fields plugin
	$acf_groups = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : apply_filters( 'acf/get_field_groups', array() );
	if ( ! empty( $acf_groups ) ) {
		foreach ( $acf_groups as $group ) {
			$id = isset( $group['id'] ) ? 'id' : ( isset( $group['ID'] ) ? 'ID' : 'id' );
			$fields = function_exists( 'acf_get_fields' ) ? acf_get_fields( $group[ $id ] ) : apply_filters( 'acf/field_group/get_fields', array(), $group[ $id ] );
			foreach ( $fields as $field ) {
				$custom_fields[ $field['name'] ] = $group['title'] . ': ' . $field['label'];
			}
		}

	}

	return $custom_fields;
}

/**
 * Make the provided grid settings value consistent and proper
 *
 * @param $value array
 *
 * @return array
 */
function us_fix_grid_settings( $value ) {
	if ( empty( $value ) OR ! is_array( $value ) ) {
		$value = array();
	}
	if ( ! isset( $value['data'] ) OR ! is_array( $value['data'] ) ) {
		$value['data'] = array();
	}

	$options_defaults = array();
	foreach ( us_config( 'grid-settings.options', array() ) as $option_name => $option_group ) {
		foreach ( $option_group as $option_name => $option_field ) {
			$options_defaults[ $option_name ] = usof_get_default( $option_field );
		}
	}

	$elements_defaults = array();
	foreach ( us_config( 'grid-settings.elements', array() ) as $element_name ) {
		$element_settings = us_config( 'elements/' . $element_name );
		$elements_defaults[ $element_name ] = array();
		foreach ( $element_settings['params'] as $param_name => $param_field ) {
			$elements_defaults[ $element_name ][ $param_name ] = usof_get_default( $param_field );
		}
	}

	foreach ( $options_defaults as $option_name => $option_default ) {
		if ( ! isset( $value['default']['options'][ $option_name ] ) ) {
			$value['default']['options'][ $option_name ] = $option_default;
		}
	}
	foreach ( $value['data'] as $element_name => $element_values ) {
		$element_type = strtok( $element_name, ':' );
		if ( ! isset( $elements_defaults[ $element_type ] ) ) {
			continue;
		}
		foreach ( $elements_defaults[ $element_type ] as $param_name => $param_default ) {
			if ( ! isset( $value['data'][ $element_name ][ $param_name ] ) ) {
				$value['data'][ $element_name ][ $param_name ] = $param_default;
			}
		}
	}

	foreach ( array( 'default' ) as $state ) {
		if ( ! isset( $value[ $state ] ) OR ! is_array( $value[ $state ] ) ) {
			$value[ $state ] = array();
		}
		if ( ! isset( $value[ $state ]['layout'] ) OR ! is_array( $value[ $state ]['layout'] ) ) {
			if ( $state != 'default' AND isset( $value['default']['layout'] ) ) {
				$value[ $state ]['layout'] = $value['default']['layout'];
			} else {
				$value[ $state ]['layout'] = array();
			}
		}
		$state_elms = array();
		foreach ( $value[ $state ]['layout'] as $place => $elms ) {
			if ( ! is_array( $elms ) ) {
				$elms = array();
			}
			foreach ( $elms as $index => $elm_id ) {
				if ( ! is_string( $elm_id ) OR strpos( $elm_id, ':' ) == - 1 ) {
					unset( $elms[ $index ] );
				} else {
					$state_elms[] = $elm_id;
					if ( ! isset( $value['data'][ $elm_id ] ) ) {
						$value['data'][ $elm_id ] = array();
					}
				}
			}
			$value[ $state ]['layout'][ $place ] = array_values( $elms );
		}
		if ( ! isset( $value[ $state ]['layout']['hidden'] ) OR ! is_array( $value[ $state ]['layout']['hidden'] ) ) {
			$value[ $state ]['layout']['hidden'] = array();
		}
		$value[ $state ]['layout']['hidden'] = array_merge( $value[ $state ]['layout']['hidden'], array_diff( array_keys( $value['data'] ), $state_elms ) );
		// Fixing options
		if ( ! isset( $value[ $state ]['options'] ) OR ! is_array( $value[ $state ]['options'] ) ) {
			$value[ $state ]['options'] = array();
		}
		$value[ $state ]['options'] = array_merge( $options_defaults, ( $state != 'default' ) ? $value['default']['options'] : array(), $value[ $state ]['options'] );
	}

	return $value;
}

/**
 * Get fonts for selection
 *
 * @return array
 */
function us_get_fonts( $without_groups = FALSE ) {
	$options = array();

	// Regular Text
	$body_font = explode( '|', us_get_option( 'body_font_family', 'none' ), 2 );
	if ( $body_font[0] != 'none' ) {
		$options['body'] = $body_font[0] . ' (' . __( 'used as default font', 'us' ) . ')';
	} else {
		$options['body'] = __( 'No font specified', 'us' );
	}

	// Headings
	for ( $i = 1; $i <= 6; $i ++ ) {
		$heading_font = explode( '|', us_get_option( 'h' . $i . '_font_family', 'none' ), 2 );
		if ( ! in_array( $heading_font[0], array( 'none', 'get_h1' ) ) ) {
			$options[ 'h' . $i ] = $heading_font[0] . ' (' . sprintf( __( 'used in Heading %s', 'us' ), $i ) . ')';
		}
	}

	// Uploaded Fonts
	$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
	if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
		if ( ! $without_groups ) {
			$options[] = array(
				'optgroup' => TRUE,
				'title' => __( 'Uploaded Fonts', 'us' ),
			);
		}
		$uploaded_font_families = array();
		foreach ( $uploaded_fonts as $uploaded_font ) {
			$uploaded_font_name = strip_tags( $uploaded_font['name'] );
			if ( $uploaded_font_name == '' OR in_array( $uploaded_font_name, $uploaded_font_families ) OR empty( $uploaded_font['files'] ) ) {
				continue;
			}
			$uploaded_font_families[] = $uploaded_font_name;
			$options[ $uploaded_font_name ] = $uploaded_font_name;
		}
	}

	// Additional Google Fonts
	$custom_fonts = us_get_option( 'custom_font', array() );
	if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
		if ( ! $without_groups ) {
			$options[] = array(
				'optgroup' => TRUE,
				'title' => __( 'Google Fonts (loaded from Google servers)', 'us' ),
			);
		}
		foreach ( $custom_fonts as $custom_font ) {
			$font_options = explode( '|', $custom_font['font_family'], 2 );
			$options[ $font_options[0] ] = $font_options[0];
		}
	}

	// Web Safe Fonts
	if ( ! $without_groups ) {
		$options[] = array(
			'optgroup' => TRUE,
			'title' => __( 'Web safe font combinations (do not need to be loaded)', 'us' ),
		);
	}
	$web_safe_fonts = us_config( 'web-safe-fonts' );
	foreach ( $web_safe_fonts as $web_safe_font ) {
		$options[ $web_safe_font ] = $web_safe_font;
	}

	return $options;
}

/**
 * Generate CSS font-family & font-weight of selected font
 *
 * @param string $font_name
 * @param bool $with_weight
 *
 * @return string
 */
function us_get_font_css( $font_name, $with_weight = FALSE ) {
	if ( empty( $font_name ) ) {
		return '';
	}
	static $font_css;
	if ( empty( $font_css ) ) {
		$font_options = $font_css = array();

		// Add Regular Text font
		$font_options['body'] = explode( '|', us_get_option( 'body_font_family', 'none' ), 2 );

		// Add Headings fonts
		for ( $i = 1; $i <= 6; $i ++ ) {
			if ( us_get_option( 'h' . $i . '_font_family', 'none' ) == 'get_h1|' ) {
				$font_options[ 'h' . $i ] = explode( '|', us_get_option( 'h1_font_family', 'none' ), 2 );
			} else {
				$font_options[ 'h' . $i ] = explode( '|', us_get_option( 'h' . $i . '_font_family', 'none' ), 2 );
			}
		}

		// Add Additional Google fonts
		$custom_fonts = us_get_option( 'custom_font', array() );
		if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
			foreach ( $custom_fonts as $custom_font ) {
				$font_option = explode( '|', $custom_font['font_family'], 2 );
				$font_options[ $font_option[0] ] = $font_option;
			}
		}

		// Add Uploaded fonts
		$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
		if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
			foreach ( $uploaded_fonts as $uploaded_font ) {
				$font_options[ $uploaded_font['name'] ] = array(
					0 => strip_tags( $uploaded_font['name'] ),
					1 => $uploaded_font['weight'],
				);
			}
		}

		// Add Websafe fonts
		$web_safe_fonts = us_config( 'web-safe-fonts' );
		foreach ( $web_safe_fonts as $web_safe_font ) {
			$font_options[ $web_safe_font ] = array( $web_safe_font );
		}

		foreach ( $font_options as $prefix => $font ) {
			if ( $font[0] == 'none' ) {
				$font_css[ $prefix ][0] = '';
			} elseif ( strpos( $font[0], ',' ) === FALSE ) {
				$fallback_font_family = us_config( 'google-fonts.' . $font[0] . '.fallback', 'sans-serif' );
				$font_css[ $prefix ][0] = 'font-family:\'' . $font[0] . '\', ' . $fallback_font_family . ';';
				// Fault tolerance for missing font-variants
				if ( ! isset( $font[1] ) OR empty( $font[1] ) ) {
					$font[1] = '400,700';
				}
				// The first active font-weight will be used for "normal" weight
				$font_css[ $prefix ][1] = intval( $font[1] );
			} else {
				// Web-safe font combination
				$font_css[ $prefix ][0] = 'font-family:' . $font[0] . ';';
				$font_css[ $prefix ][1] = '400';
			}
		}
	}

	if ( isset( $font_css[ $font_name ] ) AND ! empty( $font_css[ $font_name ][0] ) ) {
		$result = $font_css[ $font_name ][0];

		if ( $with_weight AND ! empty( $font_css[ $font_name ][1] ) ) {
			$result .= 'font-weight: ' . $font_css[ $font_name ][1] . ';';
		}

		return $result;
	} else {
		return '';
	}
}

/**
 * Get the remote IP address
 *
 * @return string
 */
function us_get_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return apply_filters( 'us_get_ip', $ip );
}

/**
 * Get Sidebars for selection
 *
 * @return array
 */
function us_get_sidebars() {
	$sidebars = array();
	global $wp_registered_sidebars;

	if ( is_array( $wp_registered_sidebars ) AND ! empty( $wp_registered_sidebars ) ) {
		foreach ( $wp_registered_sidebars as $sidebar ) {
			if ( $sidebar['id'] == 'default_sidebar' ) {
				// Add Default Sidebar to the beginning
				$sidebars = array_merge( array( $sidebar['id'] => $sidebar['name'] ), $sidebars );
			} else {
				$sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}
		}
	}

	return $sidebars;
}

/**
 * Get post types for selection in Grid element
 *
 * @return array
 */
function us_grid_available_post_types( $reload = FALSE ) {
	static $available_posts_types = array();

	if ( empty( $available_posts_types ) OR $reload ) {
		$posts_types_params = array(
			'show_in_menu' => TRUE,
		);
		$skip_post_types = array(
			'us_header',
			'us_page_block',
			'us_grid_layout',
			'shop_order',
			'shop_coupon',
		);
		foreach ( get_post_types( $posts_types_params, 'objects', 'and' ) as $post_type_name => $post_type ) {
			if ( in_array( $post_type_name, $skip_post_types ) ) {
				continue;
			}
			$available_posts_types[ $post_type_name ] = $post_type->labels->name . ' (' . $post_type_name . ')';
		}
	}

	return $available_posts_types;
}

/**
 * Get post taxonomies for selection in Grid element
 *
 * @return array
 */
function us_grid_available_taxonomies() {
	$available_taxonomies = array();
	$available_posts_types = us_grid_available_post_types();

	foreach ( $available_posts_types as $post_type => $name ) {
		$post_taxonomies = array();
		$object_taxonomies = get_object_taxonomies( $post_type, 'objects' );
		foreach ( $object_taxonomies as $tax_object ) {
			if ( ( $tax_object->public ) AND ( $tax_object->show_ui ) ) {
				$post_taxonomies[] = $tax_object->name;
			}
		}
		if ( is_array( $post_taxonomies ) AND count( $post_taxonomies ) > 0 ) {
			$available_taxonomies[ $post_type ] = array();
			foreach ( $post_taxonomies as $post_taxonomy ) {
				$available_taxonomies[ $post_type ][] = $post_taxonomy;
			}
		}
	}

	return $available_taxonomies;
}

/**
 * Get Custom Post Types (CPT), which have frontend appearance
 *
 * @return array: name => title (plural label)
 */
function us_get_public_cpt() {
	$public_cpt = array();

	// Fetch all post types with specified arguments
	$args = array(
		'public' => TRUE,
		'publicly_queryable' => TRUE,
		'_builtin' => FALSE,
	);
	$post_types = get_post_types( $args, 'objects' );

	// Skip some predefined post types
	$skip_post_types = array(
		// Theme
		'us_portfolio',
		'us_testimonial',
		// WooCommerce
		'product',
		// bbPress
		'reply',
	);

	foreach ( $post_types as $post_type_name => $post_type ) {
		if ( ! in_array( $post_type_name, $skip_post_types ) ) {
			$public_cpt[ $post_type_name ] = $post_type->labels->name;
		}
	}

	return $public_cpt;
}

/**
 * Get value of specified area ID for current page
 *
 * @param string $area : header / content template / footer
 *
 * @return string
 */
function us_get_page_area_id( $area ) {
	if ( empty( $area ) ) {
		return FALSE;
	}

	$public_cpt = array_keys( us_get_public_cpt() );

	// Default from Theme Options
	$area_id = us_get_option( $area . '_id', '' );

	// Portfolio Pages
	if ( is_singular( array( 'us_portfolio' ) ) ) {
		$area_id = us_get_option( $area . '_portfolio_id' );

		// Posts
	} elseif ( is_singular( array( 'post', 'attachment' ) ) ) {
		$area_id = us_get_option( $area . '_post_id' );

		// Products
	} elseif ( function_exists( 'is_product' ) AND is_product() ) {
		$area_id = us_get_option( $area . '_product_id' );

		// WooCommerce Search
	} elseif ( is_post_type_archive( 'product' ) AND is_search() ) {
		$area_id = us_get_option( $area . '_shop_id' );

		// Shop
	} elseif ( function_exists( 'is_shop' ) AND is_shop() ) {
		$area_id = us_get_option( $area . '_shop_id' );

		// WooCommerce Categories
	} elseif ( function_exists( 'is_product_category' ) AND is_product_category() ) {
		$area_id = us_get_option( $area . '_tax_product_cat_id', '__defaults__' );

		if ( $area_id == '__defaults__' ) {
			$area_id = us_get_option( $area . '_shop_id' );
		}

		// WooCommerce Tags
	} elseif ( function_exists( 'is_product_tag' ) AND is_product_tag() ) {
		$area_id = us_get_option( $area . '_tax_product_tag_id' );

		if ( $area_id == '__defaults__' ) {
			$area_id = us_get_option( $area . '_shop_id' );
		}

		// Author Pages
	} elseif ( is_author() ) {
		$area_id = us_get_option( $area . '_author_id' );

		if ( $area_id == '__defaults__' ) {
			$area_id = us_get_option( $area . '_archive_id' );
		}

		// Archives
	} elseif ( is_archive() OR is_search() ) {
		$public_taxonomies = array_keys( us_get_taxonomies( TRUE ) );

		$area_id = us_get_option( $area . '_archive_id' );

		$current_tax = NULL;
		if ( is_category() ) {
			$current_tax = 'category';
		} elseif ( is_tag() ) {
			$current_tax = 'post_tag';
		} elseif ( is_tax() ) {
			$current_tax = get_query_var( 'taxonomy' );
		}

		if ( ! empty( $current_tax ) AND in_array( $current_tax, $public_taxonomies ) ) {
			$area_id = us_get_option( $area . '_tax_' . $current_tax . '_id', '__defaults__' );
		}

		if ( $area_id == '__defaults__' ) {
			$area_id = us_get_option( $area . '_archive_id' );
		}

		// Custom Post Types
	} elseif ( ! empty( $public_cpt ) AND is_singular( $public_cpt ) ) {
		if ( is_singular( array( 'tribe_events' ) ) ) {
			$post_type = 'tribe_events'; // Events Calendar fix
		} else {
			$post_type = get_post_type();
		}
		$area_id = us_get_option( $area . '_' . $post_type . '_id' );
	}

	// Forums archive page
	if ( is_post_type_archive( 'forum' ) ) {
		$area_id = us_get_option( $area . '_forum_id' );
	}
	// Events calendar archive page
	if ( is_post_type_archive( 'tribe_events' ) ) {
		$area_id = us_get_option( $area . '_tax_tribe_events_cat_id', '__defaults__' );

		if ( $area_id == '__defaults__' ) {
			$area_id = us_get_option( $area . '_archive_id' );
		}
	}
	// Search Results page
	if ( is_search() AND ! is_post_type_archive( 'product' ) AND $postID = us_get_option( 'search_page' ) AND $postID != 'default' ) {
		$area_id = usof_meta( 'us_' . $area . '_id', array(), $postID );
	}

	// Posts page
	if ( is_home() AND $postID = us_get_option( 'posts_page' ) AND $postID != 'default' ) {
		$area_id = usof_meta( 'us_' . $area . '_id', array(), $postID );
	}

	// 404 page
	if ( is_404() AND $postID = us_get_option( 'page_404' ) AND $postID != 'default' ) {
		$area_id = usof_meta( 'us_' . $area . '_id', array(), $postID );
	}

	// Specific page
	if ( is_singular() ) {
		$postID = get_the_ID();
		if ( $postID AND metadata_exists( 'post', $postID, 'us_' . $area . '_id' ) AND usof_meta( 'us_' . $area . '_id', array(), $postID ) != '__defaults__' ) {
			$area_id = usof_meta( 'us_' . $area . '_id', array(), $postID );
		}
	}

	// Reset Pages defaults
	if ( $area_id == '__defaults__' ) {
		$area_id = us_get_option( $area . '_id', '' );
	}

	return $area_id;
}

/**
 * Get Page Blocks content of the current page
 */
function us_get_current_page_block_content() {
	$content = '';

	$footer_id = us_get_page_area_id( 'footer' );
	$content_id = us_get_page_area_id( 'content' );

	// Output content of Page Block (us_page_block) posts
	if ( $footer_id != '' ) {
		$footer = get_post( (int) $footer_id );

		if ( $footer ) {
			$translated_footer_id = apply_filters( 'wpml_object_id', $footer->ID, 'us_page_block', TRUE );
			if ( $translated_footer_id != $footer->ID ) {
				$footer = get_post( $translated_footer_id );
			}
			$content .= $footer->post_content;
		}
	}
	if ( $content_id != '' ) {
		$page_block = get_post( (int) $content_id );

		if ( $page_block ) {
			$translated_page_block_id = apply_filters( 'wpml_object_id', $page_block->ID, 'us_page_block', TRUE );
			if ( $translated_page_block_id != $page_block->ID ) {
				$page_block = get_post( $translated_page_block_id );
			}
			$content .= $page_block->post_content;
		}
	}

	return $content;
}

/**
 * Get Button Styles created on Theme Options > Buttons
 *
 * @param bool $for_shortcode : Use for WPBakery shortcode? TODO: remove $for_shortcode param
 *
 * @return array: id => name
 */
function us_get_btn_styles( $for_shortcode = FALSE ) {

	$btn_styles_list = array();
	$btn_styles = us_get_option( 'buttons', array() );

	if ( is_array( $btn_styles ) ) {
		foreach ( $btn_styles as $btn_style ) {
			$btn_name = trim( $btn_style['name'] );
			if ( $btn_name == '' ) {
				$btn_name = us_translate( 'Style' ) . ' ' . $btn_style['id'];
			}
			if ( $for_shortcode ) {
				$btn_name .= ' '; // fix for case when Button Style names have digits only
				$btn_styles_list[ $btn_name ] = $btn_style['id'];
			} else {
				$btn_styles_list[ $btn_style['id'] ] = esc_html( $btn_name );
			}
		}
	}

	return $btn_styles_list;
}

/**
 * Get uploaded image alt attribute
 * Dev note: algorithm is based on wp_get_attachment_image function
 *
 * @param string $value
 *
 * @return string
 */
function us_get_image_alt( $value ) {
	if ( ! preg_match( '~^(\d+)(\|(.+))?$~', $value, $matches ) ) {
		return '';
	}
	$attachment_id = intval( $matches[1] );
	$alt = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', TRUE ) ) );

	return $alt;
}

/**
 * Get corrected <img> tag of attachment_image including SVG
 *
 * @param string $img_id
 * @param string $img_size
 * @param string $img_attr
 *
 * @return string
 */
function us_get_attachment_image( $img_id, $img_size = 'thumbnail', $img_attr = '' ) {

	// Use default WP function
	$result = wp_get_attachment_image( $img_id, $img_size, FALSE, $img_attr );

	// And correct width and height attributes, if image is SVG
	$img_src = wp_get_attachment_image_url( $img_id );
	if ( preg_match( '~\.svg$~', $img_src ) ) {

		// Replace width="1" with the real width value
		$size_array = us_get_intermediate_image_size( $img_size );
		$width = $size_array['width'];
		$result = str_replace( 'width="1"', ( 'width="' . $width . '"' ), $result );

		// Remove height attribute to avoid incorrect proportions
		$result = preg_replace( '~(height)="\d+"~', '', $result );
	}

	return $result;
}

/**
 * Get image size values for selection
 *
 * @param array [$size_names] List of size names
 *
 * @return array
 */
function us_image_sizes_select_values( $size_names = NULL ) {
	$image_sizes = array();

	// Default WordPress image sizes
	if ( $size_names === NULL ) {
		$size_names = array( 'full', 'large', 'medium', 'thumbnail' );
	}

	// Add WooCommerce image sizes if enabled
	if ( class_exists( 'woocommerce' ) ) {
		$size_names[] = 'shop_single';
		$size_names[] = 'shop_catalog';
	}

	// For translation purposes
	$size_titles = array(
		'full' => us_translate( 'Full Size' ),
	);

	foreach ( $size_names as $size_name ) {
		$size_title = isset( $size_titles[ $size_name ] ) ? $size_titles[ $size_name ] : ucwords( $size_name );
		if ( $size_name != 'full' ) {

			// Detecting size
			$size = us_get_intermediate_image_size( $size_name );

			$size_title = ( ( $size['width'] == 0 ) ? __( 'any', 'us' ) : $size['width'] );
			$size_title .= ' x ';
			$size_title .= ( $size['height'] == 0 ) ? __( 'any', 'us' ) : $size['height'];
			if ( $size['crop'] ) {
				$size_title .= ' ' . __( 'cropped', 'us' );
			}
		}
		$image_sizes[ $size_name ] = $size_title;
	}

	// Custom sizes
	$custom_tnail_sizes = us_get_option( 'img_size' );
	if ( is_array( $custom_tnail_sizes ) ) {
		foreach ( $custom_tnail_sizes as $size_index => $size ) {
			$crop = ( ! empty( $size['crop'][0] ) );
			$crop_str = ( $crop ) ? '_crop' : '';
			$width = ( ! empty( $size['width'] ) AND intval( $size['width'] ) > 0 ) ? intval( $size['width'] ) : 0;
			$height = ( ! empty( $size['height'] ) AND intval( $size['height'] ) > 0 ) ? intval( $size['height'] ) : 0;
			$size_name = 'us_' . $width . '_' . $height . $crop_str;

			$size_title = ( $width == 0 ) ? __( 'any', 'us' ) : $width;
			$size_title .= ' x ';
			$size_title .= ( $height == 0 ) ? __( 'any', 'us' ) : $height;
			if ( $crop ) {
				$size_title .= ' ' . __( 'cropped', 'us' );
			}

			if ( ! in_array( $size_title, $image_sizes ) ) {
				$image_sizes[ $size_name ] = $size_title;
			}
		}
	}

	return apply_filters( 'us_image_sizes_select_values', $image_sizes );
}

/**
 * Change '{{field_name}}' string to the custom field value
 */
function us_get_link_from_custom_field( $link_array ) {

	if ( isset( $link_array['url'] ) AND preg_match( "#{{([^}]+)}}#", trim( $link_array['url'] ), $matches ) ) {
		$postID = get_the_ID();
		$meta_value = trim( get_post_meta( $postID, $matches[1], TRUE ) );

		if ( ! empty( $meta_value ) ) {
			if ( substr( strval( $meta_value ), 0, 1 ) === '{' ) {
				try {
					$meta_value_array = json_decode( $meta_value, TRUE );
					if ( is_array( $meta_value_array ) ) {
						$link_array['url'] = $meta_value_array['url'];
						// Override "target" only if it was empty
						if ( empty( $link_array['target'] ) ) {
							$link_array['target'] = $meta_value_array['target'];
						}
						// Force "nofollow" for metabox URLs
						$link_array['rel'] = 'nofollow';
					}
				}
				catch ( Exception $e ) {
				}
			} elseif ( is_string( $meta_value ) ) {
				$link_array['url'] = $meta_value;
			}
		} else {
			$link_array['url'] = '';
		}
	}

	return $link_array;
}

/**
 * Generate attributes for link tag based on elements options
 *
 * @param mixed $link
 * @param string $us_elm_context : shortcode / grid / grid-term / header / widget
 *
 * @return string
 */
function us_generate_link_atts( $link ) {
	if ( ! isset( $link ) ) {
		return '';
	}

	// Default array
	$link_array = array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' );

	// Check the type of provided value
	if ( is_array( $link ) ) {
		$link_array = $link;

		// If it is string, use WPBakery way to create array
	} else {

		$params_pairs = explode( '|', $link );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = explode( ':', $pair, 2 );
				if ( ! empty( $param[0] ) AND isset( $param[1] ) ) {
					$link_array[ $param[0] ] = rawurldecode( $param[1] );
				}
			}
		}
	}

	// Check for custom fields values
	$link_array = us_get_link_from_custom_field( $link_array );

	// Replace [lang] with current language code
	if ( ! empty( $link_array['url'] ) AND strpos( $link_array['url'], '[lang]' ) !== FALSE ) {
		$link_array['url'] = str_replace( '[lang]', usof_get_lang(), $link_array['url'] );
	}

	if ( isset( $link_array['url'] ) AND $link_array['url'] != '' ) {
		$result = ' href="' . esc_url( $link_array['url'] ) . '"';
		$result .= ( ! empty( $link_array['title'] ) ) ? ( ' title="' . esc_attr( $link_array['title'] ) . '"' ) : '';
		$result .= ( ! empty( $link_array['rel'] ) ) ? ( ' rel="' . esc_attr( $link_array['rel'] ) . '"' ) : '';
		$result .= ( ! empty( $link_array['target'] ) ) ? ' target="_blank"' : '';
	} else {
		$result = '';
	}

	return $result;
}

/**
 * Return date and time in Human readable format
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to Optional. Unix timestamp to end the time difference. Default becomes current_time() if not set.
 *
 * @return string Human readable date and time.
 */
function us_get_smart_date( $from, $to = '' ) {
	if ( empty( $to ) ) {
		$to = current_time( 'U' );
	}

	$diff = (int) abs( $to - $from );

	$time_string = date( 'G:i', $from );
	$day = (int) date( 'jmY', $from );
	$current_day = (int) date( 'jmY', $to );
	$yesterday = (int) date( 'jmY', strtotime( 'yesterday', $to ) );
	$year = (int) date( 'Y', $from );
	$current_year = (int) ( date( 'Y', $to ) );

	if ( $diff < HOUR_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 ) {
			$mins = 1;
		}
		// 1-59 minutes ago
		$mins_string = sprintf( us_translate_n( '%s min', '%s mins', $mins ), $mins );
		$result = sprintf( us_translate( '%s ago' ), $mins_string );
	} elseif ( $diff <= ( HOUR_IN_SECONDS * 4 ) ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 ) {
			$hours = 1;
		}
		// 1-4 hours ago
		$hours_string = sprintf( us_translate_n( '%s hour', '%s hours', $hours ), $hours );
		$result = sprintf( us_translate( '%s ago' ), $hours_string );
	} elseif ( $current_day == $day ) {
		// Today at 9:30
		$result = sprintf( us_translate( '%1$s at %2$s' ), us_translate( 'Today' ), $time_string );
	} elseif ( $yesterday == $day ) {
		// Yesterday at 9:30
		$result = sprintf( us_translate( '%1$s at %2$s' ), __( 'Yesterday', 'us' ), $time_string );
	} elseif ( $current_year == $year ) {
		// 23 Jan at 12:30
		$result = sprintf( us_translate( '%1$s at %2$s' ), date_i18n( 'j M', $from ), $time_string );
	} else {
		// 18 Dec 2018
		$result = date_i18n( 'j M Y', $from );
	}

	return $result;
}

/**
 * Change '{{comment_count}}' string to comments amount of the current page
 */
function us_replace_comment_count_var( $string ) {

	if ( strpos( $string, '{{comment_count}}' ) !== FALSE ) {
		global $post;
		if ( $post ) {
			$comments_amount = get_comment_count( $post->ID );
			$string = str_replace( '{{comment_count}}', $comments_amount['approved'], $string );
		} else {
			$string = str_replace( '{{comment_count}}', '0', $string );
		}
	}

	return $string;
}
