<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

global $usof_directory, $usof_directory_uri;
if ( ! isset( $usof_directory ) ) {
	$usof_directory = get_template_directory() . '/framework/vendor/usof';
}
if ( ! isset( $usof_directory_uri ) ) {
	$usof_directory_uri = get_template_directory_uri() . '/framework/vendor/usof';
}

if ( is_admin() ) {
	if ( ! defined( 'DOING_AJAX' ) OR ! DOING_AJAX ) {
		// Front-end interface
		require $usof_directory . '/functions/interface.php';
		require $usof_directory . '/functions/meta-box.php';
		require $usof_directory . '/functions/mega-menu.php';
	} elseif ( ( isset( $_POST['action'] ) AND substr( $_POST['action'], 0, 5 ) == 'usof_' ) OR ( isset( $_GET['action'] ) AND substr( $_GET['action'], 0, 5 ) == 'usof_' ) ) {
		// Ajax methods
		require $usof_directory . '/functions/ajax.php';
		require $usof_directory . '/functions/ajax-mega-menu.php';
	}
}

/**
 * Get theme option or return default value
 *
 * @param string $name
 * @param mixed $default_value
 *
 * @return mixed
 */
function usof_get_option( $name, $default_value = NULL ) {
	global $usof_options;
	usof_load_options_once();

	if ( $default_value === NULL ) {
		$default_value = usof_defaults( $name );
	}

	$value = isset( $usof_options[ $name ] ) ? $usof_options[ $name ] : $default_value;

	return apply_filters( 'usof_get_option_' . $name, $value );
}

/**
 * Get default value for a certain USOF field
 * @param array $field
 * @return mixed
 */
function usof_get_default( &$field ) {

	$no_values_types = array(
		'backup',
		'heading',
		'message',
		'transfer',
		'wrapper_start',
		'wrapper_end',
	);

	$selectable_types = array(
		'imgradio',
		'radio',
		'select',
		'style_scheme',
	);

	if ( ! isset( $field['type'] ) OR in_array( $field['type'], $no_values_types ) ) {
		return NULL;
	}

	// Using first value as standard for selectable types
	if ( ! isset( $field['std'] ) AND in_array( $field['type'], $selectable_types ) ) {
		if ( isset( $field['options'] ) AND is_array( $field['options'] ) AND ! empty( $field['options'] ) ) {
			$field['std'] = key( $field['options'] );
			reset( $field['options'] );
		}
	}

	return isset( $field['std'] ) ? $field['std'] : '';
}

/**
 * Get default values
 *
 * @param string $key If set, retreive only one default value
 *
 * @return mixed Array of values or a single value if the $key is specified
 */
function usof_defaults( $key = NULL ) {
	$config = us_config( 'theme-options' );

	$values = array();
	foreach ( $config as &$section ) {
		if ( ! isset( $section['fields'] ) ) {
			continue;
		}
		foreach ( $section['fields'] as $field_id => &$field ) {
			if ( $key !== NULL AND $field_id != $key ) {
				continue;
			}
			if ( isset( $values[ $field_id ] ) ) {
				continue;
			}

			if ( isset( $field['type'] ) AND $field['type'] == 'style_scheme' ) {
				$options = array_keys( us_config( 'style-schemes' ) );
				if ( empty( $options ) ) {
					continue;
				}
				$field['std'] = isset( $field['std'] ) ? $field['std'] : $options[0];
				// If theme has default style scheme, it's values will be used as standard as well
				$values = array_merge( $values, us_config( 'style-schemes.' . $field['std'] . '.values' ) );
			}

			$default_value = usof_get_default( $field );
			if ( $default_value !== NULL ) {
				$values[ $field_id ] = $default_value;
			}
		}
	}

	if ( $key !== NULL ) {
		return isset( $values[ $key ] ) ? $values[ $key ] : NULL;
	}

	return $values;
}

/**
 * If the options were not loaded, load them
 */
function usof_load_options_once() {
	global $usof_options;
	if ( isset( $usof_options ) ) {
		return;
	}
	$usof_options = get_option( 'usof_options_' . US_THEMENAME );
	if ( $usof_options === FALSE ) {
		// Trying to fetch the old good SMOF options
		$usof_options = get_option( US_THEMENAME . '_options' );
		if ( $usof_options !== FALSE ) {
			// Disabling the old options autoload
			update_option( US_THEMENAME . '_options', $usof_options, FALSE );
		} else {
			// Not defined yet, using default values
			$usof_options = usof_defaults();
		}
		update_option( 'usof_options_' . US_THEMENAME, $usof_options, TRUE );
	}

	$usof_options = apply_filters( 'usof_load_options_once', $usof_options );
}

/**
 * Save current usof options values from global $usof_options variable to database
 *
 * @param array $updated_options Array of the new options values
 */
function usof_save_options( $updated_options ) {

	if ( ! is_array( $updated_options ) OR empty( $updated_options ) ) {
		return;
	}

	global $usof_options;
	usof_load_options_once();

	do_action( 'usof_before_save', $updated_options );
	$usof_options = $updated_options;
	update_option( 'usof_options_' . US_THEMENAME, $usof_options, TRUE );

	do_action( 'usof_after_save', $updated_options );
}

/**
 * Get uploaded image from USOF field value
 *
 * @param string $value Upload field value in "123|full" format
 *
 * @return array [url, width, height]
 */
function usof_get_image_src( $value, $size = NULL ) {
	if ( preg_match( '~^(\d+)(\|(.+))?$~', $value, $matches ) ) {
		// Image size
		if ( $size == NULL ) {
			$matches[3] = empty( $matches[3] ) ? 'full' : $matches[3];
		} else {
			$matches[3] = $size;
		}
		$result = wp_get_attachment_image_src( $matches[1], $matches[3] );
		if ( is_array( $result ) AND count( $result ) > 2 AND preg_match( '~\.svg$~', $result[0] ) ) {
			// SVG images have no specific dimensions
			$result[1] = $result[2] = '';
		}
	} else {
		$value = str_replace( '[site_url]', site_url(), $value );
		$result = array( $value, '', '' );
	}

	return $result;
}

/**
 * @param array $value
 *
 * @return array
 */
function usof_get_link_atts( $value ) {
	if ( ! is_array( $value ) ) {
		$value = array(
			'url' => is_string( $value ) ? $value : '',
		);
	}
	$atts = array();
	if ( isset( $value['url'] ) AND ! empty( $value['url'] ) ) {
		$atts['href'] = $value['url'];

		if ( strpos( $atts['href'], '[lang]' ) !== FALSE ) {
			$atts['href'] = str_replace( '[lang]', usof_get_lang(), $atts['href'] );
		}

		if ( isset( $value['target'] ) AND ! empty( $value['target'] ) ) {
			$atts['target'] = $value['target'];
		}
	}

	return $atts;
}

/**
 * Checks if the showing condition is true
 *
 * Note: at any possible syntax error we choose to show the field so it will be functional anyway.
 *
 * @param array $condition Showing condition
 * @param array $values Current values
 *
 * @return bool
 */
function usof_execute_show_if( $condition, &$values = NULL ) {
	if ( ! is_array( $condition ) OR count( $condition ) < 3 ) {
		// Wrong condition
		$result = TRUE;
	} elseif ( in_array( strtolower( $condition[1] ), array( 'and', 'or' ) ) ) {
		// Complex or / and statement
		$result = usof_execute_show_if( $condition[0], $values );
		$index = 2;
		while ( isset( $condition[ $index ] ) ) {
			$condition[ $index - 1 ] = strtolower( $condition[ $index - 1 ] );
			if ( $condition[ $index - 1 ] == 'and' ) {
				$result = ( $result AND usof_execute_show_if( $condition[ $index ], $values ) );
			} elseif ( $condition[ $index - 1 ] == 'or' ) {
				$result = ( $result OR usof_execute_show_if( $condition[ $index ], $values ) );
			}
			$index = $index + 2;
		}
	} else {
		if ( ! isset( $values[ $condition[0] ] ) ) {
			if ( $condition[1] == '=' AND ( ! in_array( $condition[2], array( 0, '', FALSE, NULL ) ) ) ) {
				return FALSE;
			} elseif ( $condition[1] == '!=' AND in_array( $condition[2], array( 0, '', FALSE, NULL ) ) ) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
		$value = $values[ $condition[0] ];
		if ( $condition[1] == '=' ) {
			$result = ( $value == $condition[2] );
		} elseif ( $condition[1] == '!=' OR $condition[1] == '<>' ) {
			$result = ( $value != $condition[2] );
		} elseif ( $condition[1] == 'in' ) {
			$result = ( ! is_array( $condition[2] ) OR in_array( $value, $condition[2] ) );
		} elseif ( $condition[1] == 'not in' ) {
			$result = ( ! is_array( $condition[2] ) OR ! in_array( $value, $condition[2] ) );
		} elseif ( $condition[1] == 'has' ) {
			$result = ( ! is_array( $value ) OR in_array( $condition[2], $value ) );
		} elseif ( $condition[1] == '<=' ) {
			$result = ( $value <= $condition[2] );
		} elseif ( $condition[1] == '<' ) {
			$result = ( $value < $condition[2] );
		} elseif ( $condition[1] == '>' ) {
			$result = ( $value > $condition[2] );
		} elseif ( $condition[1] == '>=' ) {
			$result = ( $value >= $condition[2] );
		} else {
			$result = TRUE;
		}
	}

	return $result;
}

function usof_get_lang() {
	if ( function_exists( 'wpml_get_current_language' ) ) {
		// WPML
		global $sitepress;
		$default_language = $sitepress->get_default_language();
		if ( $default_language != ICL_LANGUAGE_CODE ) {
			return wpml_get_current_language();
		}
	} elseif ( function_exists( 'pll_current_language' ) ) {
		// Polylang
		return pll_current_language();
	} elseif ( function_exists( 'qtrans_getLanguage' ) ) {
		// qTranslate
		return qtrans_getLanguage();
	}

	// No supported translation plugins found
	return '';
}

/**
 * Output preview for color scheme used by ajax and style_scheme
 *
 * @param array $scheme
 *
 * @return string
 */
function usof_color_scheme_preview( $scheme ) {
	if ( empty( $scheme ) ) {
		return '';
	}
	$preview = '<div class="usof-scheme-preview">';
	// Header
	$preview .= '<div class="preview_header" style="background:' . $scheme['values']['color_header_middle_bg'] . ';"></div>';
	// Content
	$preview .= '<div class="preview_content" style="background:' . $scheme['values']['color_content_bg'] . ';">';
	// Heading
	$preview .= '<div class="preview_heading" style="color:' . us_gradient2hex( $scheme['values']['color_content_heading'] ) . ';">' . trim( esc_html( $scheme['title'] ) ) . '</div>';
	// Text
	$preview .= '<div class="preview_text" style="color:' . us_gradient2hex( $scheme['values']['color_content_text'] ) . ';">';
	$preview .= 'Lorem ipsum dolor sit amet, <span style="color:' . us_gradient2hex( $scheme['values']['color_content_link'] ) . ';">consectetur</span> adipiscing elit. Maecenas arcu lectus, sollicitudin dictum dapibus sit amet.';
	$preview .= '</div>';
	// Primary
	$preview .= '<div class="preview_primary" style="background:' . $scheme['values']['color_content_primary'] . ';"></div>';
	// Secondary
	$preview .= '<div class="preview_secondary" style="background:' . $scheme['values']['color_content_secondary'] . ';"></div>';
	$preview .= '</div>';
	// Footer
	$preview .= '<div class="preview_footer" style="background:' . $scheme['values']['color_footer_bg'] . ';"></div>';
	$preview .= '</div>';

	return $preview;
}