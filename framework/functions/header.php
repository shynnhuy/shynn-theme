<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Get header option for the specified state
 *
 * @param string $name Option name
 * @param string $state Header state: 'default' / 'tablets' / 'mobiles'
 * @param string $default
 *
 * @return string
 */
function us_get_header_option( $name, $state = 'default', $default = NULL ) {
	global $us_header_settings;
	us_load_header_settings_once();
	$shared_options = array( 'top_fullwidth', 'middle_fullwidth', 'bottom_fullwidth' );
	if ( $state != 'default' AND ( ! isset( $us_header_settings[ $state ]['options'][ $name ] ) OR in_array( $name, $shared_options ) ) ) {
		$state = 'default';
	}

	return isset( $us_header_settings[ $state ]['options'][ $name ] ) ? $us_header_settings[ $state ]['options'][ $name ] : $default;
}

/**
 * Get header layout for the specified state
 *
 * @param $state
 *
 * @return array
 */
function us_get_header_layout( $state = 'default' ) {
	global $us_header_settings;
	us_load_header_settings_once();
	$layout = array(
		'top_left' => array(),
		'top_center' => array(),
		'top_right' => array(),
		'middle_left' => array(),
		'middle_center' => array(),
		'middle_right' => array(),
		'bottom_left' => array(),
		'bottom_center' => array(),
		'bottom_right' => array(),
		'hidden' => array(),
	);
	if ( $state != 'default' AND isset( $us_header_settings['default']['layout'] ) AND is_array( $us_header_settings['default']['layout'] ) ) {
		$layout = array_merge( $layout, $us_header_settings['default']['layout'] );
	}
	if ( isset( $us_header_settings[ $state ]['layout'] ) AND is_array( $us_header_settings[ $state ]['layout'] ) ) {
		$layout = array_merge( $layout, $us_header_settings[ $state ]['layout'] );
	}

	return $layout;
}

/**
 * Load the current header settings for all possible responsive states
 */
function us_load_header_settings_once() {

	global $us_header_settings;

	if ( isset( $us_header_settings ) ) {
		return;
	}
	// Basic structure
	$us_header_settings = array(
		'default' => array( 'options' => array(), 'layout' => array() ),
		'tablets' => array( 'options' => array(), 'layout' => array() ),
		'mobiles' => array( 'options' => array(), 'layout' => array() ),
		'data' => array(),
	);
	$us_header_settings = apply_filters( 'us_load_header_settings', $us_header_settings );
}

/**
 * Translating the v.2 USOF header settings to v.3 header-builder compatible ones
 */
add_filter( 'us_load_header_settings', 'us_load_usof_header_settings' );
function us_load_usof_header_settings( $header_settings ) {

	global $usof_options;
	usof_load_options_once();

	// Side options defaults from theme-options.php config
	$side_options_config = us_config( 'header-settings.options', array() );
	foreach ( $side_options_config as $section_name => $section_options ) {
		foreach ( $section_options as $field_name => $field ) {
			$field_value = isset( $field['std'] ) ? $field['std'] : '';
			$header_settings['default']['options'][ $field_name ] = $field_value;
			$header_settings['tablets']['options'][ $field_name ] = $field_value;
			$header_settings['mobiles']['options'][ $field_name ] = $field_value;
		}
	}

	// Layout-defined values
	$header_templates = us_config( 'header-templates', array() );
	if ( isset( $usof_options['header_layout'] ) AND isset( $header_templates[ $usof_options['header_layout'] ] ) ) {
		$header_template = us_fix_header_template_settings( $header_templates[ $usof_options['header_layout'] ] );
		$header_settings = us_array_merge( $header_settings, $header_template );
	}

	// Filling elements' data with default values
	$header_settings = us_fix_header_settings( $header_settings );

	// Side options
	$rules = array(
		'header_transparent' => array(
			'new_name' => 'transparent',
		),
		'header_fullwidth' => array(
			'new_names' => array( 'top_fullwidth', 'middle_fullwidth', 'bottom_fullwidth' ),
		),
		'header_top_height' => array(
			'new_name' => 'top_height',
		),
		'header_top_sticky_height' => array(
			'new_name' => 'top_sticky_height',
		),
		'header_middle_height' => array(
			'new_name' => 'middle_height',
		),
		'header_middle_sticky_height' => array(
			'new_name' => 'middle_sticky_height',
		),
		'header_bottom_height' => array(
			'new_name' => 'bottom_height',
		),
		'header_bottom_sticky_height' => array(
			'new_name' => 'bottom_sticky_height',
		),
		'header_main_width' => array(
			'new_name' => 'width',
		),
	);

	foreach ( $rules as $old_name => $rule ) {
		if ( ! isset( $usof_options[ $old_name ] ) AND ( isset( $rule['new_name'] ) OR isset( $rule['new_names'] ) ) ) {
			continue;
		}
		if ( isset( $rule['transfer_if'] ) AND ! usof_execute_show_if( $rule['transfer_if'], $usof_options ) ) {
			continue;
		}
		$new_names = isset( $rule['new_names'] ) ? $rule['new_names'] : array( $rule['new_name'] );
		foreach ( $new_names as $new_name ) {
			$header_settings['default']['options'][ $new_name ] = $usof_options[ $old_name ];
		}
	}

	// header_sticky => sticky
	if ( isset( $usof_options['header_sticky'] ) ) {
		if ( is_array( $usof_options['header_sticky'] ) ) {
			foreach ( array( 'default', 'tablets', 'mobiles' ) as $layout ) {
				$header_settings[ $layout ]['options']['sticky'] = in_array( $layout, $usof_options['header_sticky'] );
			}
		} else {
			$header_settings['default']['options']['sticky'] = $usof_options['header_sticky'];
			$header_settings['tablets']['options']['sticky'] = $usof_options['header_sticky'];
			$header_settings['mobiles']['options']['sticky'] = $usof_options['header_sticky'];
		}
	}

	// Transferring elements' values
	$rules = array(
		'image:1' => array(
			'show_if' => array( 'logo_type', '=', 'img' ),
			'values' => array(
				'img' => '=logo_image',
				'link' => array( 'url' => '/' ),
				'height' => '=logo_height',
				'height_tablets' => '=logo_height_tablets',
				'height_mobiles' => '=logo_height_mobiles',
				'height_sticky' => '=logo_height_sticky',
				'height_sticky_tablets' => '=logo_height_tablets',
				'height_sticky_mobiles' => '=logo_height_mobiles',
			),
		),
		'text:1' => array(
			'show_if' => array( 'logo_type', '=', 'text' ),
			'values' => array(
				'text' => '=logo_text',
				'link' => array( 'url' => '/' ),
				'size' => '=logo_font_size',
				'size_tablets' => '=logo_font_size_tablets',
				'size_mobiles' => '=logo_font_size_mobiles',
			),
		),
		'text:2' => array(
			'show_if' => array(
				array( 'header_contacts_show', '=', 1 ),
				'and',
				array( 'header_contacts_phone', '!=', '' ),
			),
			'values' => array(
				'text' => '=header_contacts_phone',
				'icon' => 'fas|phone',
			),
		),
		'text:3' => array(
			'show_if' => array(
				array( 'header_contacts_show', '=', 1 ),
				'and',
				array( 'header_contacts_email', '!=', '' ),
			),
			'values' => array(
				'text' => '=header_contacts_email',
				'icon' => 'fas|envelope',
			),
		),
		'menu:1' => array(
			'values' => array(
				'source' => '=menu_source',
				'font_size' => '=menu_fontsize',
				'indents' => '=menu_indents',
				'vstretch' => '=menu_height',
				'dropdown_font_size' => '=menu_sub_fontsize',
				'mobile_width' => '=menu_mobile_width',
				'mobile_behavior' => '=menu_togglable_type',
				'mobile_font_size' => '=menu_fontsize_mobile',
				'mobile_dropdown_font_size' => '=menu_sub_fontsize_mobile',
			),
		),
		'search:1' => array(
			'show_if' => array( 'header_search_show', '=', 1 ),
			'values' => array(
				'layout' => '=header_search_layout',
			),
		),
		'socials:1' => array(
			'values' => array(
				'items' => array(),
			),
		),
	);

	foreach ( $rules as $elm => $rule ) {
		if ( ! isset( $header_settings['data'][ $elm ] ) ) {
			$header_settings['data'][ $elm ] = array();
			$type = strtok( $elm, ':' );
			// Setting default values for fallback
			$elm_config = us_config( 'elements/' . $type, array() );
			foreach ( $elm_config['params'] as $field_name => $field ) {
				$value = isset( $field['std'] ) ? $field['std'] : '';
				// Some default header values may be based on main theme options' values
				if ( is_string( $value ) AND substr( $value, 0, 1 ) == '=' AND isset( $usof_options[ substr( $value, 1 ) ] ) ) {
					$value = $usof_options[ substr( $value, 1 ) ];
				}
				$header_settings['data'][ $elm ][ $field_name ] = $value;
			}
		}
		// Setting values
		if ( isset( $rule['values'] ) AND is_array( $rule['values'] ) ) {
			foreach ( $rule['values'] as $key => $value ) {
				if ( is_string( $value ) AND substr( $value, 0, 1 ) == '=' ) {
					$old_key = substr( $value, 1 );
					if ( ! isset( $usof_options[ $old_key ] ) ) {
						continue;
					}
					$value = $usof_options[ $old_key ];
				}
				$header_settings['data'][ $elm ][ $key ] = $value;
			}
		}
		// Hiding the element when needed
		if ( isset( $rule['show_if'] ) AND ! usof_execute_show_if( $rule['show_if'], $usof_options ) ) {
			foreach ( array( 'default', 'tablets', 'mobiles' ) as $layout ) {
				foreach ( $header_settings[ $layout ]['layout'] as $cell => $cell_elms ) {
					if ( $cell == 'hidden' ) {
						continue;
					}
					if ( ( $elm_pos = array_search( $elm, $cell_elms ) ) !== FALSE ) {
						array_splice( $header_settings[ $layout ]['layout'][ $cell ], $elm_pos, 1 );
						$header_settings[ $layout ]['layout']['hidden'][] = $elm;
						break;
					}
				}
			}
		}
	}

	// Logos for tablets and mobiles states
	if ( isset( $header_settings['data']['image:1'] ) ) {
		foreach ( array( 'tablets' => 'image:2', 'mobiles' => 'image:3' ) as $layout => $key ) {
			if ( isset( $header_settings['data'][ $key ] ) OR ! isset( $usof_options[ 'logo_image_' . $layout ] ) OR empty( $usof_options[ 'logo_image_' . $layout ] ) ) {
				continue;
			}
			$header_settings['data'][ $key ] = array_merge(
				$header_settings['data']['image:1'], array(
					'img' => $usof_options[ 'logo_image_' . $layout ],
					'img_transparent' => '',
				)
			);
			foreach ( $header_settings[ $layout ]['layout'] as $cell => $cell_elms ) {
				if ( $cell == 'hidden' ) {
					continue;
				}
				if ( ( $elm_pos = array_search( 'image:1', $cell_elms ) ) !== FALSE ) {
					$header_settings[ $layout ]['layout'][ $cell ][ $elm_pos ] = $key;
					$header_settings[ $layout ]['layout']['hidden'][] = 'image:1';
					break;
				}
			}
			$header_settings['default']['layout']['hidden'][] = $key;
			$header_settings[ ( $layout == 'tablets' ) ? 'mobiles' : 'tablets' ]['layout']['hidden'][] = $key;
		}
	}

	// Fixing text links
	if ( isset( $header_settings['data']['text:3'] ) AND isset( $header_settings['data']['text:3']['text'] ) ) {
		$header_settings['data']['text:3']['link'] = 'mailto:' . $header_settings['data']['text:3']['text'];
	}

	// Inverting logo position
	if ( isset( $usof_options['header_invert_logo_pos'] ) AND $usof_options['header_invert_logo_pos'] ) {
		foreach ( array( 'default', 'tablets', 'mobiles' ) as $layout ) {
			if ( isset( $header_settings[ $layout ]['layout']['middle_left'] ) AND isset( $header_settings[ $layout ]['layout']['middle_left'] ) ) {
				$tmp = $header_settings[ $layout ]['layout']['middle_left'];
				$header_settings[ $layout ]['layout']['middle_left'] = $header_settings[ $layout ]['layout']['middle_right'];
				$header_settings[ $layout ]['layout']['middle_right'] = $tmp;
			}
		}
	}

	return $header_settings;
}

/**
 * Recursively output elements of a certain state / place
 *
 * @param array $settings Current layout
 * @param string $state Current state
 * @param string $place Outputted place
 * @param string $context 'header' / 'grid'
 */
function us_output_builder_elms( &$settings, $state, $place, $context = 'header' ) {

	$layout = &$settings[ $state ]['layout'];
	$data = &$settings['data'];
	if ( ! isset( $layout[ $place ] ) OR ! is_array( $layout[ $place ] ) ) {
		return;
	}

	// Set 3 states for header and 1 for other contexts, like Grid Layouts
	$_states = ( $context === 'header' ) ? array( 'default', 'tablets', 'mobiles' ) : array( 'default' );

	$visible_elms = array();
	foreach ( $_states as $_state ) {
		$visible_elms[ $_state ] = us_get_builder_shown_elements_list( us_arr_path( $settings, $_state . '.layout', array() ) );
	}

	foreach ( $layout[ $place ] as $elm ) {
		$classes = '';
		if ( $context === 'header' ) {
			if ( isset( $data[ $elm ] ) ) {
				if ( us_arr_path( $data[ $elm ], 'hide_for_sticky', FALSE ) ) {
					$classes .= ' hide-for-sticky';
				}
				if ( us_arr_path( $data[ $elm ], 'hide_for_not_sticky', FALSE ) ) {
					$classes .= ' hide-for-not-sticky';
				}
			}
		}
		foreach ( $_states as $_state ) {
			if ( ! in_array( $elm, $visible_elms[ $_state ] ) ) {
				$classes .= ' hidden_for_' . $_state;
			}
		}
		if ( $context === 'header' ) {
			$classes .= ' ush_' . str_replace( ':', '_', $elm );
		} elseif ( in_array( $context, array( 'grid', 'grid_term' ) ) ) {
			$classes .= ' usg_' . str_replace( ':', '_', $elm );
		}
		if ( substr( $elm, 1, 7 ) == 'wrapper' ) {
			// Wrapper
			$type = strtok( $elm, ':' );
			if ( isset( $data[ $elm ] ) ) {
				if ( isset( $data[ $elm ]['alignment'] ) ) {
					$classes .= ' align_' . $data[ $elm ]['alignment'];
				}
				if ( isset( $data[ $elm ]['valign'] ) ) {
					$classes .= ' valign_' . $data[ $elm ]['valign'];
				}
				if ( isset( $data[ $elm ]['wrap'] ) AND $data[ $elm ]['wrap'] ) {
					$classes .= ' wrap';
				}
				if ( isset( $data[ $elm ]['bg_gradient'] ) AND $data[ $elm ]['bg_gradient'] ) {
					$classes .= ' bg_gradient';
				}
				if ( isset( $data[ $elm ]['el_class'] ) ) {
					$classes .= ' ' . $data[ $elm ]['el_class'];
				}
			}
			echo '<div class="w-' . $type . $classes . '">';
			us_output_builder_elms( $settings, $state, $elm, $context );
			echo '</div>';
		} else {
			// Element
			$type = strtok( $elm, ':' );
			$defaults = us_get_elm_defaults( $type, $context );
			if ( ! isset( $data[ $elm ] ) ) {
				$data[ $elm ] = us_get_elm_defaults( $type, $context );
			} else {
				if ( ! empty( $data[ $elm ]['color_text'] ) ) {
					$classes .= ' with_text_color';
				}
				if ( $context === 'header' AND ! empty( $data[ $elm ]['el_class'] ) ) {
					$classes .= ' ' . $data[ $elm ]['el_class'];
				}
			}
			$values = array_merge( $defaults, array_intersect_key( $data[ $elm ], $defaults ) );
			$values['id'] = $elm;
			$values['classes'] = ( isset( $values['classes'] ) ? $values['classes'] : '' ) . $classes;
			$values['us_elm_context'] = $context;

			// Adding special classes
			us_load_template( 'templates/elements/' . $type, $values );
		}
	}
}

/**
 * Get default value for an element
 *
 * @param string $type
 * @param string $context 'header' or 'grid'
 *
 * @return mixed
 */
function us_get_elm_defaults( $type, $context = 'header' ) {
	global $us_elm_defaults, $usof_options;
	if ( ! isset( $us_elm_defaults ) ) {
		$us_elm_defaults = array();
	}
	if ( ! isset( $us_elm_defaults[ $context ] ) ) {
		$us_elm_defaults[ $context ] = array();
	}
	if ( ! isset( $us_elm_defaults[ $context ][ $type ] ) ) {
		$us_elm_defaults[ $context ][ $type ] = array();
		$elm_config = us_config( 'elements/' . $type, array() );
		foreach ( $elm_config['params'] as $field_name => $field ) {
			$value = isset( $field['std'] ) ? $field['std'] : '';
			if ( $context === 'header' ) {
				// Some default header values may be based on main theme options' values
				usof_load_options_once();
				if ( is_string( $value ) AND substr( $value, 0, 1 ) == '=' AND isset( $usof_options[ substr( $value, 1 ) ] ) ) {
					$value = $usof_options[ substr( $value, 1 ) ];
				}
			}
			$us_elm_defaults[ $context ][ $type ][ $field_name ] = $value;
		}
	}

	return us_arr_path( $us_elm_defaults, array( $context, $type ), array() );
}

// Backward compability with older HB versions
if ( ! function_exists( 'us_get_header_elm_defaults' ) ) {
	function us_get_header_elm_defaults( $type ) {
		return us_get_elm_defaults( $type, 'header' );
	}
}

/**
 * Get elements
 *
 * @param string $type
 * @param bool $key_as_class Should the keys of the resulting array be css classes instead of elms ids?
 *
 * @return array
 */
function us_get_header_elms_of_a_type( $type, $key_as_class = TRUE ) {
	global $us_header_settings;
	us_load_header_settings_once();
	$defaults = us_get_elm_defaults( $type, 'header' );
	$result = array();
	if ( ! is_array( $us_header_settings['data'] ) ) {
		return $result;
	}
	foreach ( $us_header_settings['data'] as $elm_id => $elm ) {
		if ( strtok( $elm_id, ':' ) != $type ) {
			continue;
		}
		$key = $key_as_class ? ( 'ush_' . str_replace( ':', '_', $elm_id ) ) : $elm_id;
		$result[ $key ] = array_merge( $defaults, array_intersect_key( $elm, $defaults ) );
	}

	return $result;
}

/**
 * Make the provided header settings value consistent and proper
 *
 * @param $value array
 *
 * @return array
 */
function us_fix_header_settings( $value ) {
	if ( empty( $value ) OR ! is_array( $value ) ) {
		$value = array();
	}
	if ( ! isset( $value['data'] ) OR ! is_array( $value['data'] ) ) {
		$value['data'] = array();
	}
	$options_defaults = array();
	foreach ( us_config( 'header-settings.options', array() ) as $group => $opts ) {
		foreach ( $opts as $opt_name => $opt ) {
			$options_defaults[ $opt_name ] = isset( $opt['std'] ) ? $opt['std'] : '';
		}
	}
	foreach ( array( 'default', 'tablets', 'mobiles' ) as $state ) {
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

	foreach ( $value['data'] as $elm_id => $values ) {
		$type = strtok( $elm_id, ':' );
		$defaults = us_get_elm_defaults( $type, 'header' );
		$value['data'][ $elm_id ] = array_merge( $defaults, array_intersect_key( $value['data'][ $elm_id ], $defaults ) );
	}

	return $value;
}

function us_fix_header_template_settings( $value ) {

	if ( isset( $value['title'] ) ) {
		// Don't need this in data processing
		unset( $value['title'] );
	}
	$template_structure = array(
		'default' => array( 'options' => array(), 'layout' => array() ),
		'tablets' => array( 'options' => array(), 'layout' => array() ),
		'mobiles' => array( 'options' => array(), 'layout' => array() ),
		'data' => array(),
	);
	$value = us_array_merge( $template_structure, $value );
	$layout_structure = array(
		'top_left' => array(),
		'top_center' => array(),
		'top_right' => array(),
		'middle_left' => array(),
		'middle_center' => array(),
		'middle_right' => array(),
		'bottom_left' => array(),
		'bottom_center' => array(),
		'bottom_right' => array(),
		'hidden' => array(),
	);
	foreach ( array( 'default', 'tablets', 'mobiles' ) as $state ) {
		// Options
		$value[ $state ]['options'] = array_merge( ( $state == 'default' ) ? array() : $value['default']['options'], $value[ $state ]['options'] );
		// Layout
		$value[ $state ]['layout'] = array_merge( $layout_structure, ( $state == 'default' ) ? array() : $value['default']['layout'], $value[ $state ]['layout'] );
	}
	$value = us_fix_header_settings( $value );

	return $value;
}

/**
 * Get list of user registered nav menus with theirs proper names, in a format sutable for usof select field
 *
 * @return array
 */
function us_get_nav_menus() {
	$menus = array();
	foreach ( get_terms( 'nav_menu', array( 'hide_empty' => TRUE ) ) as $menu ) {
		$menus[ $menu->slug ] = $menu->name;
	}

	// Adding us_main_menu location if it is filled with mene
	$theme_locations = get_nav_menu_locations();
	if ( isset( $theme_locations['us_main_menu'] ) ) {
		$menu_obj = get_term( $theme_locations['us_main_menu'], 'nav_menu' );
		if ( $menu_obj AND is_object( $menu_obj ) AND isset ( $menu_obj->name ) ) {
			$menus['location:us_main_menu'] = $menu_obj->name . ' (' . __( 'Custom Menu', 'us' ) . ')';
		}
	}

	return $menus;
}

/**
 * Get the list of header elements that are shown in the certain layout listing
 *
 * @param array $list Euther layout or separate list
 *
 * @return array
 */
function us_get_builder_shown_elements_list( $list ) {
	$shown = array();
	foreach ( $list as $key => $sublist ) {
		if ( $key != 'hidden' ) {
			$shown = array_merge( $shown, $sublist );
		}
	}

	return $shown;
}

// Changing ordering to avoid JavaScript errors with NextGEN Gallery plugin
add_action( 'wp_footer', 'us_pass_header_settings_to_js', - 2 );
function us_pass_header_settings_to_js() {
	global $us_header_settings;
	us_load_header_settings_once();
	$header_settings = $us_header_settings;
	if ( isset( $header_settings['data'] ) ) {
		unset( $header_settings['data'] );
	}
	echo '<script>';
	echo '$us.headerSettings = ' . json_encode( $header_settings ) . ';';
	echo '</script>';
}

/**
 * Get the header design options css for all the fields
 *
 * @return string
 */
function us_get_header_design_options_css() {
	global $us_header_settings;
	us_load_header_settings_once();
	$tablets_breakpoint = ( isset( $us_header_settings['tablets']['options']['breakpoint'] ) ) ? intval( $us_header_settings['tablets']['options']['breakpoint'] ) : 900;
	$mobiles_breakpoint = ( isset( $us_header_settings['mobiles']['options']['breakpoint'] ) ) ? intval( $us_header_settings['mobiles']['options']['breakpoint'] ) : 600;
	$sizes = array(
		'default' => '@media (min-width: ' . $tablets_breakpoint . 'px)',
		'tablets' => '@media (min-width: ' . $mobiles_breakpoint . 'px) and (max-width: ' . ( $tablets_breakpoint - 1 ) . 'px)',
		'mobiles' => '@media (max-width: ' . ( $mobiles_breakpoint - 1 ) . 'px)',
	);
	$data = array();
	foreach ( $us_header_settings['data'] as $elm_id => $elm ) {
		if ( ! isset( $elm['design_options'] ) OR empty( $elm['design_options'] ) OR ! is_array( $elm['design_options'] ) ) {
			continue;
		}
		$elm_class = 'ush_' . str_replace( ':', '_', $elm_id );
		foreach ( $elm['design_options'] as $key => $value ) {
			if ( $value === '' ) {
				continue;
			}
			$key = explode( '_', $key );
			if ( ! isset( $data[ $key[2] ] ) ) {
				$data[ $key[2] ] = array();
			}
			if ( ! isset( $data[ $key[2] ][ $elm_class ] ) ) {
				$data[ $key[2] ][ $elm_class ] = array();
			}
			if ( ! isset( $data[ $key[2] ][ $elm_class ][ $key[0] ] ) ) {
				$data[ $key[2] ][ $elm_class ][ $key[0] ] = array();
			}
			$data[ $key[2] ][ $elm_class ][ $key[0] ][ $key[1] ] = $value;
		}
	}
	$css = '';
	foreach ( $sizes as $state => $mquery ) {
		if ( ! isset( $data[ $state ] ) ) {
			continue;
		}
		$css .= $mquery . " {";
		foreach ( $data[ $state ] as $elm_class => $props ) {
			$css .= '.' . $elm_class . '{';
			foreach ( $props as $prop => $values ) {
				// Add solid border if its values not empty
				if ( $prop === 'border' AND ! empty( $values ) ) {
					$css .= 'border-style: solid; border-width: 0;';
				}
				if ( count( $values ) == 4 AND count( array_unique( $values ) ) == 1 ) {
					// All the directions have the same value, so grouping them together
					$values = array_values( $values );
					if ( $prop === 'border' ) {
						$css .= $prop . '-width:' . $values[0] . ';';
					} else {
						$css .= $prop . ':' . $values[0] . '!important;';
					}
				} else {
					foreach ( $values as $dir => $val ) {
						if ( $prop === 'border' ) {
							$css .= $prop . '-' . $dir . '-width:' . $val . ';';
						} else {
							$css .= $prop . '-' . $dir . ':' . $val . '!important;';
						}
					}
				}
			}
			$css .= "}";
		}
		$css .= "}";
	}

	return $css;
}

/**
 * Add link to Theme Options to Admin Bar
 *
 * @param $wp_admin_bar
 */
function us_admin_bar_theme_options_link( $wp_admin_bar ) {
	$wp_admin_bar->add_node(
		array(
			'id' => 'us_theme_otions',
			'title' => __( 'Theme Options', 'us' ),
			'href' => admin_url( 'admin.php?page=us-theme-options' ),
			'parent' => 'site-name',
		)
	);
}

if ( ! is_admin() AND function_exists( 'current_user_can' ) AND current_user_can( 'administrator' ) ) {
	add_action( 'admin_bar_menu', 'us_admin_bar_theme_options_link', 99 );
}

/**
 * Add "no-touch" class to html on desktops
 */
add_action( 'wp_head', 'us_output_no_touch_script' );
function us_output_no_touch_script() {
	?>
	<script>
		if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			var root = document.getElementsByTagName('html')[0]
			root.className += " no-touch";
		}
	</script>
	<?php
}
