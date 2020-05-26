<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * WPBakery Page Builder support
 *
 * @link http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=UpSolution
 */

if ( ! class_exists( 'Vc_Manager' ) ) {

	/**
	 * @param $width
	 *
	 * @since 4.2
	 * @return bool|string
	 */
	function us_wpb_translateColumnWidthToSpan( $width ) {
		preg_match( '/(\d+)\/(\d+)/', $width, $matches );
		if ( ! empty( $matches ) ) {
			$part_x = (int) $matches[1];
			$part_y = (int) $matches[2];
			if ( $part_x > 0 AND $part_y > 0 ) {
				$value = ceil( $part_x / $part_y * 12 );
				if ( $value > 0 AND $value <= 12 ) {
					$width = 'vc_col-sm-' . $value;
				}
			}
		}

		return $width;
	}

	/**
	 * @param $column_offset
	 * @param $width
	 *
	 * @return mixed|string
	 */
	function us_vc_column_offset_class_merge( $column_offset, $width ) {
		if ( preg_match( '/vc_col\-sm\-\d+/', $column_offset ) ) {
			return $column_offset;
		}

		return $width . ( empty( $column_offset ) ? '' : ' ' . $column_offset );
	}

	/**
	 * @param            $subject
	 * @param            $property
	 * @param bool|false $strict
	 *
	 * @since 4.9
	 * @return bool
	 */
	function us_vc_shortcode_custom_css_has_property( $subject, $property, $strict = FALSE ) {
		$styles = array();
		$pattern = '/\{([^\}]*?)\}/i';
		preg_match( $pattern, $subject, $styles );
		if ( array_key_exists( 1, $styles ) ) {
			$styles = explode( ';', $styles[1] );
		}
		$new_styles = array();
		foreach ( $styles as $val ) {
			$val = explode( ':', $val );
			if ( is_array( $property ) ) {
				foreach ( $property as $prop ) {
					$pos = strpos( $val[0], $prop );
					$full = ( $strict ) ? ( $pos === 0 AND strlen( $val[0] ) === strlen( $prop ) ) : TRUE;
					if ( $pos !== FALSE AND $full ) {
						$new_styles[] = $val;
					}
				}
			} else {
				$pos = strpos( $val[0], $property );
				$full = ( $strict ) ? ( $pos === 0 AND strlen( $val[0] ) === strlen( $property ) ) : TRUE;
				if ( $pos !== FALSE AND $full ) {
					$new_styles[] = $val;
				}
			}
		}

		return ! empty( $new_styles );
	}

	return;
}

add_action( 'vc_before_init', 'us_vc_set_as_theme' );
function us_vc_set_as_theme() {
	vc_set_as_theme();
}

add_action( 'vc_after_init', 'us_vc_after_init' );
function us_vc_after_init() {
	$updater = vc_manager()->updater();
	$updateManager = $updater->updateManager();

	remove_filter( 'upgrader_pre_download', array( $updater, 'preUpgradeFilter' ) );
	remove_filter( 'pre_set_site_transient_update_plugins', array( $updateManager, 'check_update' ) );
	remove_filter( 'plugins_api', array( $updateManager, 'check_info' ) );
	remove_action( 'in_plugin_update_message-' . vc_plugin_name(), array( $updateManager, 'addUpgradeMessageLink' ) );
}

add_action( 'vc_after_set_mode', 'us_vc_after_set_mode' );
function us_vc_after_set_mode() {

	do_action( 'us_before_js_composer_mappings' );

	// Remove VC Font Awesome style in admin pages
	add_action( 'admin_head', 'us_remove_js_composer_admin_assets', 1 );
	function us_remove_js_composer_admin_assets() {
		wp_dequeue_style( 'font-awesome' );
		wp_deregister_style( 'font-awesome' );
		if ( us_get_option( 'disable_extra_vc', 1 ) ) {
			wp_dequeue_style( 'animate-css' );
		}
	}

	if ( ! vc_is_page_editable() ) {

		// Remove original VC styles and scripts
		add_action( 'wp_enqueue_scripts', 'us_remove_vc_base_css_js', 15 );
		function us_remove_vc_base_css_js() {
			if ( wp_style_is( 'font-awesome', 'registered' ) ) {
				wp_dequeue_style( 'font-awesome' );
				wp_deregister_style( 'font-awesome' );
			}
			if ( us_get_option( 'disable_extra_vc', 1 ) ) {
				if ( wp_style_is( 'js_composer_front', 'registered' ) ) {
					wp_dequeue_style( 'js_composer_front' );
					wp_deregister_style( 'js_composer_front' );
				}
				if ( wp_script_is( 'wpb_composer_front_js', 'registered' ) ) {
					wp_deregister_script( 'wpb_composer_front_js' );
				}
			}
		}
	}

	if ( vc_is_page_editable() ) {

		// Disable some of the shortcodes for frontend editor
		US_Shortcodes::instance()->vc_front_end_compatibility();

		// Add theme CSS for frontend editor
		add_action( 'wp_enqueue_scripts', 'us_process_css_for_frontend_js_composer', 15 );
		function us_process_css_for_frontend_js_composer() {
			global $us_template_directory_uri;
			wp_enqueue_style( 'us_js_composer_front', $us_template_directory_uri . '/framework/plugins-support/js_composer/css/us_frontend_editor.css' );
		}
	}

	if ( is_admin() AND us_get_option( 'disable_extra_vc', 1 ) ) {
		// Removing grid elements
		add_action( 'admin_menu', 'us_remove_vc_grid_elements_submenu' );
		function us_remove_vc_grid_elements_submenu() {
			remove_submenu_page( VC_PAGE_MAIN_SLUG, 'edit.php?post_type=vc_grid_item' );
		}
	}

	// Disable Frontend Editor for Page Blocks
	add_action( 'current_screen', 'us_disable_frontend_for_page_blocks' );

	// Disable Icon Picker assets
	if ( us_get_option( 'disable_extra_vc', 1 ) ) {
		remove_action( 'vc_backend_editor_enqueue_js_css', 'vc_iconpicker_editor_jscss' );
		remove_action( 'vc_frontend_editor_enqueue_js_css', 'vc_iconpicker_editor_jscss' );
	}

	do_action( 'us_after_js_composer_mappings' );
}

add_action( 'init', 'us_vc_init_shortcodes', 11 );
function us_vc_init_shortcodes() {
	if ( ! function_exists( 'vc_mode' ) OR ! function_exists( 'vc_map' ) OR ! function_exists( 'vc_remove_element' ) ) {
		return;
	}

	$shortcodes_config = us_config( 'shortcodes', array(), TRUE );

	// Mapping WPBakery Page Builder backend behaviour for used shortcodes
	if ( vc_mode() != 'page' ) {

		function us_vc_param( $param_name, $param ) {
			$related_types = array(
				'text' => 'textfield',
				'slider' => 'textfield',
				'textarea' => 'textarea',
				'select' => 'dropdown',
				'radio' => 'dropdown',
				'color' => 'colorpicker',
				'link' => 'vc_link',
				'icon' => 'us_icon',
				'switch' => 'checkbox',
				'checkboxes' => 'checkbox',
				'upload' => 'attach_image',
				'editor' => 'textarea_html',
				'html' => 'textarea_raw_html',
				'group' => 'param_group',
				'wrapper_start' => 'param_to_delete',
				'wrapper_end' => 'param_to_delete',
				'heading' => 'param_to_delete',
				'ult_param_heading' => 'ult_param_heading',
				'autocomplete' => 'autocomplete',
				'us_grid_layout' => 'us_grid_layout',
				'css_editor' => 'css_editor',
				'design_options' => 'us_design_options',
			);

			$type = ( isset( $param['type'] ) AND isset( $related_types[ $param['type'] ] ) ) ? $related_types[ $param['type'] ] : 'textfield';

			if ( $type == 'param_to_delete' ) {
				return NULL;
			}

			$attributes_with_prefixes = array(
				'title',
				'description',
				'options',
				'classes',
				'cols',
				'std',
				'show_if',
			);
			foreach ( $attributes_with_prefixes as $attribute ) {
				if ( isset( $param[ 'shortcode_' . $attribute ] ) ) {
					$param[ $attribute ] = $param[ 'shortcode_' . $attribute ];
				}
			}

			if ( $param['type'] == 'checkboxes' AND ! empty( $param['std'] ) AND is_array( $param['std'] ) ) {
				$param['std'] = implode( ',', $param['std'] );
			}

			$vc_param = array(
				'type' => $type,
				'param_name' => $param_name,
				'heading' => isset( $param['title'] ) ? $param['title'] : '',
				'description' => isset( $param['description'] ) ? $param['description'] : '',
				'std' => isset( $param['std'] ) ? $param['std'] : '',
				'holder' => isset( $param['holder'] ) ? $param['holder'] : '',
				'admin_label' => isset( $param['admin_label'] ) ? $param['admin_label'] : FALSE,
				'settings' => isset( $param['settings'] ) ? $param['settings'] : NULL,
			);

			// Add option CSS classes based on "cols" param
			if ( isset( $param['cols'] ) ) {
				if ( $param['cols'] == 2 ) {
					$vc_param['edit_field_class'] = 'vc_col-sm-6';
				} elseif ( $param['cols'] == 3 ) {
					$vc_param['edit_field_class'] = 'vc_col-sm-4';
				}
			} elseif ( isset( $param['classes'] ) AND ! empty( $param['classes'] ) ) {
				$vc_param['edit_field_class'] = $param['classes'];
			}

			if ( isset( $param['group'] ) AND ! empty( $param['group'] ) ) {
				$vc_param['group'] = $param['group'];
			}

			if ( $vc_param['type'] == 'attach_image' AND isset( $param['is_multiple'] ) AND $param['is_multiple'] ) {
				$vc_param['type'] = 'attach_images';
			}

			if ( $vc_param['type'] == 'dropdown' AND isset( $param['options'] ) ) {
				$vc_param['value'] = array();
				foreach ( $param['options'] as $option_val => $option_name ) {
					$vc_param['value'][ $option_name . ' ' ] = $option_val;
				}
			}

			if ( $vc_param['type'] == 'checkbox' ) {
				if ( isset( $param['options'] ) ) {
					$vc_param['value'] = array_flip( $param['options'] );
				} elseif ( isset( $param['switch_text'] ) ) {
					$vc_param['value'] = array( $param['switch_text'] => TRUE );
				}
				if ( is_array( $vc_param['std'] ) ) {
					$vc_param['std'] = implode( ',', $vc_param['std'] );
				} elseif ( $vc_param['std'] === TRUE ) {
					$vc_param['std'] = '1';
				} elseif ( $vc_param['std'] === FALSE ) {
					$vc_param['std'] = '';
				}
			}

			// Proper dependency rules
			if ( isset( $param['show_if'] ) AND count( $param['show_if'] ) == 3 ) {
				$vc_param['dependency'] = array(
					'element' => $param['show_if'][0],
				);
				if ( $param['show_if'][1] == '=' AND $param['show_if'][2] == '' ) {
					$vc_param['dependency']['is_empty'] = TRUE;
				} elseif ( $param['show_if'][1] == '!=' AND $param['show_if'][2] == '' ) {
					$vc_param['dependency']['not_empty'] = TRUE;
				} elseif ( $param['show_if'][1] == '!=' AND ! empty( $param['show_if'][2] ) ) {
					$vc_param['dependency']['value_not_equal_to'] = $param['show_if'][2];
				} else {
					$vc_param['dependency']['value'] = $param['show_if'][2];
				}
			}

			// Proper group rules
			if ( $vc_param['type'] == 'param_group' ) {
				if ( isset( $param['params'] ) AND is_array( $param['params'] ) ) {
					$group_params = $param['params'];
					$param['params'] = array();
					foreach ( $group_params as $group_param_name => $group_param ) {
						$group_vc_param = us_vc_param( $group_param_name, $group_param );
						if ( $group_vc_param != NULL ) {
							$vc_param['params'][] = $group_vc_param;
						}
					}
				}
			}

			return $vc_param;
		}

		// Adding theme elements maps
		foreach ( $shortcodes_config['theme_elements'] as $elm_name ) {
			$shortcode = 'us_' . $elm_name;
			$elm = us_config( 'elements/' . $elm_name );

			$vc_elm = array(
				'name' => isset( $elm['title'] ) ? $elm['title'] : $shortcode,
				'base' => $shortcode,
				'description' => isset( $elm['description'] ) ? $elm['description'] : '',
				'class' => 'elm-' . $shortcode,
				'category' => isset( $elm['category'] ) ? $elm['category'] : us_translate( 'Content', 'js_composer' ),
				'icon' => isset( $elm['icon'] ) ? $elm['icon'] : '',
				'weight' => 370, // all elements go after "Text Block" element
				'admin_enqueue_js' => isset( $elm['admin_enqueue_js'] ) ? $elm['admin_enqueue_js'] : NULL,
				'js_view' => isset( $elm['js_view'] ) ? $elm['js_view'] : NULL,
				'as_parent' => isset( $elm['as_parent'] ) ? $elm['as_parent'] : NULL,
				'show_settings_on_create' => isset( $elm['show_settings_on_create'] ) ? $elm['show_settings_on_create'] : NULL,
				'params' => array(),
			);

			if ( isset( $elm['params'] ) AND is_array( $elm['params'] ) ) {
				foreach ( $elm['params'] as $param_name => &$param ) {
					if ( isset( $param['context'] ) AND is_array( $param['context'] ) AND ! in_array( 'shortcode', $param['context'] ) ) {
						continue;
					}
					$vc_param = us_vc_param( $param_name, $param );
					if ( $vc_param != NULL ) {
						$vc_elm['params'][] = $vc_param;
					}
				}
			}

			vc_map( $vc_elm );

		}

		// Include custom map files based on shortcodes name. Only for vc_ shortcodes
		global $us_template_directory;

		foreach ( $shortcodes_config['modified'] as $shortcode => $config ) {
			if ( file_exists( $us_template_directory . '/plugins-support/js_composer/map/' . $shortcode . '.php' ) ) {
				require $us_template_directory . '/plugins-support/js_composer/map/' . $shortcode . '.php';
			} elseif ( file_exists( $us_template_directory . '/framework/plugins-support/js_composer/map/' . $shortcode . '.php' ) ) {
				require $us_template_directory . '/framework/plugins-support/js_composer/map/' . $shortcode . '.php';
			}

		}
	}

	if ( us_get_option( 'disable_extra_vc', 1 ) ) {

		// Removing the elements that are not supported at the moment by the theme
		if ( is_admin() ) {
			foreach ( $shortcodes_config['disabled'] as $shortcode ) {
				vc_remove_element( $shortcode );
			}
		} else {
			add_action( 'template_redirect', 'us_vc_disable_extra_sc', 100 );
		}

	}
}

add_action( 'current_screen', 'us_disable_post_type_specific_elements' );
function us_disable_post_type_specific_elements() {
	if ( function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();
		$shortcodes_config = us_config( 'shortcodes', array(), TRUE );

		foreach ( $shortcodes_config['theme_elements'] as $elm_name ) {
			$shortcode = 'us_' . $elm_name;
			$elm = us_config( 'elements/' . $elm_name );

			if ( isset( $elm['shortcode_post_type'] ) ) {
				if ( ! in_array( $screen->post_type, $elm['shortcode_post_type'] ) ) {
					vc_remove_element( $shortcode );
				}
			}
		}
	}
}


function us_disable_frontend_for_page_blocks() {
	if ( function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();
		if ( $screen->post_type == 'us_page_block' ) {
			vc_disable_frontend();
		}
	}
}

function us_vc_disable_extra_sc() {
	$disabled_shortcodes = us_config( 'shortcodes.disabled', array() );

	foreach ( $disabled_shortcodes as $shortcode ) {
		remove_shortcode( $shortcode );
	}
}

// Disable redirect to VC welcome page
remove_action( 'init', 'vc_page_welcome_redirect' );

add_action( 'after_setup_theme', 'us_vc_init_vendor_woocommerce', 99 );
function us_vc_init_vendor_woocommerce() {
	remove_action( 'wp_enqueue_scripts', 'vc_woocommerce_add_to_cart_script' );
}

// Add autocomplete for us_grid
add_action( 'vc_after_mapping', 'us_grid_map_shortcodes' );
function us_grid_map_shortcodes() {
	add_filter( 'vc_autocomplete_us_carousel_ids_callback', 'us_grid_ids_autocomplete_suggester', 10, 1 );
	add_filter( 'vc_autocomplete_us_grid_ids_callback', 'us_grid_ids_autocomplete_suggester', 10, 1 );
	function us_grid_ids_autocomplete_suggester( $query ) {
		global $wpdb;
		$item_id = (int) $query;

		// Fetching the available post types to choose from
		$available_posts_types = us_grid_available_post_types();
		if ( count( $available_posts_types ) > 0 ) {
			$available_posts_types = array_keys( $available_posts_types );
			$where_post_type = " a.post_type IN ('" . implode( "','", $available_posts_types ) . "') AND ";
		} else {
			$where_post_type = "";
		}

		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.ID AS id, a.post_title AS title, a.post_type AS post_type
					FROM {$wpdb->posts} AS a
					WHERE post_status = 'publish' AND {$where_post_type} ( a.ID = '%d' OR a.post_title LIKE '%%%s%%' )", $item_id > 0 ? $item_id : - 1, stripslashes( $query )
			), ARRAY_A
		);

		$results = array();
		if ( is_array( $post_meta_infos ) AND ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data = array();
				$data['value'] = $value['id'];
				$post_type = get_post_type_object( $value['post_type'] );

				if ( strlen( $value['title'] ) > 0 ) {
					$post_title = $value['title'];
				} else {
					$post_title = us_translate( '(no title)' );
				}
				$post_title .= ' <i>' . $post_type->labels->singular_name . '</i>';
				$data['label'] = $post_title;
				$results[] = $data;
			}
		}

		return $results;
	}

	add_filter( 'vc_autocomplete_us_carousel_ids_render', 'us_grid_ids_render', 10, 1 );
	add_filter( 'vc_autocomplete_us_grid_ids_render', 'us_grid_ids_render', 10, 1 );
	function us_grid_ids_render( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get post
			$post_object = get_post( (int) $query );
			if ( is_object( $post_object ) ) {
				$post_type = get_post_type_object( $post_object->post_type );

				if ( strlen( $post_object->post_title ) > 0 ) {
					$post_title = $post_object->post_title;
				} else {
					$post_title = us_translate( '(no title)' );
				}
				$post_title .= ' <i>' . $post_type->labels->singular_name . '</i>';

				$post_id = $post_object->ID;
				$data = array();
				$data['value'] = $post_id;
				$data['label'] = $post_title;

				return ! empty( $data ) ? $data : FALSE;
			}

			return FALSE;
		}

		return FALSE;
	}
}

add_filter( 'us_page_block_the_content', 'us_VC_fixPContent', 11 );
function us_VC_fixPContent( $content = NULL ) {
	if ( $content ) {
		$s = array(
			'/' . preg_quote( '</div>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
			'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<div ', '/' ) . '/i',
			'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<section ', '/' ) . '/i',
			'/' . preg_quote( '</section>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
		);
		$r = array(
			'</div>',
			'<div ',
			'<section ',
			'</section>',
		);
		$content = preg_replace( $s, $r, $content );

		return $content;
	}

	return NULL;
}

// Hide activation notice
add_action( 'admin_notices', 'us_hide_js_composer_activation_notice', 100 );
function us_hide_js_composer_activation_notice() {
	?>
	<script>
		( function( $ ) {
			var setCookie = function( c_name, value, exdays ) {
				var exdate = new Date();
				exdate.setDate( exdate.getDate() + exdays );
				var c_value = encodeURIComponent( value ) + ( ( null === exdays ) ? "" : "; expires=" + exdate.toUTCString() );
				document.cookie = c_name + "=" + c_value;
			};
			setCookie( 'vchideactivationmsg_vc11', '100', 30 );
			$( '#vc_license-activation-notice' ).remove();
		} )( window.jQuery );
	</script>
	<?php
}

// Set Backend Editor as default for post types
$list = array(
	'page',
	'us_portfolio',
	'us_page_block',
);
vc_set_default_editor_post_types( $list );

// Remove Backend Editor for Headers & Grid Layouts
add_filter( 'vc_settings_exclude_post_type', 'us_vc_settings_exclude_post_type' );
function us_vc_settings_exclude_post_type( $types ) {
	$types = array(
		'us_header',
		'us_grid_layout',
	);

	return $types;
}

add_filter( 'vc_is_valid_post_type_be', 'us_vc_is_valid_post_type_be', 10, 2 );
function us_vc_is_valid_post_type_be( $result, $type ) {
	if ( in_array( $type, array( 'us_header', 'us_grid_layout', ) ) ) {
		$result = FALSE;
	}

	return $result;
}

add_action( 'current_screen', 'us_header_vc_check_post_type_validation_fix' );
function us_header_vc_check_post_type_validation_fix( $current_screen ) {
	global $pagenow;
	if ( $pagenow == 'post.php' AND $current_screen->post_type == 'us_header' ) {
		add_filter( 'vc_check_post_type_validation', '__return_false', 12 );
	}
}

global $us_template_directory_uri;
if ( ! function_exists( 'us_design_options_settings_field' ) ) {
	vc_add_shortcode_param( 'us_design_options', 'us_design_options_settings_field', $us_template_directory_uri . '/framework/plugins-support/js_composer/js/us_design_options.js' );
	function us_design_options_settings_field( $settings, $value ) {
		$directions = array( 'top', 'right', 'bottom', 'left' );

		$with_position = TRUE; // TODO: should we add Position setting?

		$output = '<div class="usof-design">';
		$output .= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="us-design-options-value wpb_vc_param_value wpb-textinput ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field' . '" type="hidden" value="' . esc_attr( $value ) . '">';

		$output .= '<div class="usof-design-control for_default">';

		$css_values = array();
		$value_pairs = explode( ';', $value );
		if ( is_array( $value_pairs ) AND count( $value_pairs ) > 0 ) {
			foreach ( $value_pairs as $value_pair ) {
				$value_pair_arr = explode( ':', $value_pair );
				if ( is_array( $value_pair_arr ) AND count( $value_pair_arr ) == 2 ) {
					$_css_name = trim( str_replace( '-', '_', $value_pair_arr[0] ) );
					$_css_value = trim( $value_pair_arr[1] );
					if ( $_css_name == 'position' ) {
						continue;
					}
					if ( in_array( $_css_name, array( 'top', 'bottom', 'left', 'right', ) ) ) {
						$_css_name = 'position_' . $_css_name;
					}
					$css_values[ $_css_name ] = $_css_value;
				}

			}
		}

		if ( $with_position ) {
			// Checking if position is enabled
			$pos_enabled = FALSE;
			foreach ( $directions as $part ) {
				$subname = 'position_' . $part;
				if ( isset( $css_values[ $subname ] ) AND $css_values[ $subname ] !== '' ) {
					$pos_enabled = TRUE;
					break;
				}
			}

			// Positioning Switcher
			$output .= '<div class="usof-switcher">';
			$output .= '<input type="hidden" name="pos_abs" value="0" />';
			$output .= '<input type="checkbox" id="usdo_pos_abs" name="pos_abs" value="1"' . ( $pos_enabled ? ' checked="checked"' : '' ) . '>';
			$output .= '<label for="usdo_pos_abs">';
			$output .= '<span class="usof-switcher-box"><i></i></span>';
			$output .= '<span class="usof-switcher-text">' . __( 'Absolute Positioning', 'us' ) . '</span>';
			$output .= '</label></div>';

			// Position
			$output .= '<div class="usof-design-position' . ( $pos_enabled ? '' : ' pos_off' ) . '">';
			$output .= '<div class="usof-design-attr">Position</div>';
			foreach ( $directions as $part ) {
				$subname = 'position_' . $part;
				$subvalue = isset( $css_values[ $subname ] ) ? $css_values[ $subname ] : '';
				if ( preg_match( '~^\d+$~', $subvalue ) ) {
					$subvalue = $subvalue . 'px';
				}
				$output .= '<input class="us-design-options-input for-position ' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
			}
		}

		// Margin
		$output .= '<div class="usof-design-margin">';
		$output .= '<div class="usof-design-attr">Margin</div>';
		foreach ( $directions as $part ) {
			$subname = 'margin_' . $part;
			$subvalue = isset( $css_values[ $subname ] ) ? $css_values[ $subname ] : '';
			if ( preg_match( '~^\d+$~', $subvalue ) ) {
				$subvalue = $subvalue . 'px';
			}
			$output .= '<input class="us-design-options-input ' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
		}

		// Border
		$output .= '<div class="usof-design-border">';
		$output .= '<div class="usof-design-attr">Border</div>';
		foreach ( $directions as $part ) {
			$subname = 'border_' . $part;
			$subvalue = isset( $css_values[ $subname ] ) ? $css_values[ $subname ] : '';
			if ( preg_match( '~^\d+$~', $subvalue ) ) {
				$subvalue = $subvalue . 'px';
			}
			$output .= '<input class="us-design-options-input ' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
		}

		// Padding
		$output .= '<div class="usof-design-padding">';
		$output .= '<div class="usof-design-attr">Padding</div>';
		foreach ( $directions as $part ) {
			$subname = 'padding_' . $part;
			$subvalue = isset( $css_values[ $subname ] ) ? $css_values[ $subname ] : '';
			if ( preg_match( '~^\d+$~', $subvalue ) ) {
				$subvalue = $subvalue . 'px';
			}
			$output .= '<input class="us-design-options-input ' . $part . '" type="text" name="' . esc_attr( $subname ) . '" value="' . esc_attr( $subvalue ) . '">';
		}
		$output .= '</div>';

		// .usof-design-border
		$output .= '</div>';

		// .usof-design-margin
		$output .= '</div>';

		if ( $with_position ) {
			// .usof-design-position
			$output .= '</div>';
		}

		// .usof-design-control
		$output .= '</div>';

		$output .= '</div>';

		return $output;
	}
}

// Add parameter for icon selection
if ( ! function_exists( 'us_icon_settings_field' ) ) {
	vc_add_shortcode_param( 'us_icon', 'us_icon_settings_field', $us_template_directory_uri . '/framework/plugins-support/js_composer/js/us_icon.js' );
	function us_icon_settings_field( $settings, $value ) {
		$icon_sets = us_config( 'icon-sets', array() );
		reset( $icon_sets );
		$value = trim( $value );
		if ( ! preg_match( '/(fas|far|fal|fab|material)\|[a-z0-9-]/i', $value ) ) {
			$value = $settings['std'];
		}
		$select_value = $input_value = '';
		$value_arr = explode( '|', $value );
		if ( count( $value_arr ) == 2 ) {
			$select_value = $value_arr[0];
			$input_value = $value_arr[1];
		}
		if ( empty( $select_value ) ) {
			$select_value = key( $icon_sets );
		}
		ob_start();
		?>
		<div class="us-icon">
			<input name="<?php echo esc_attr( $settings['param_name'] ); ?>"
				   class="us-icon-value wpb_vc_param_value wpb-textinput <?php echo esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field'; ?>"
				   type="hidden" value="<?php echo esc_attr( $value ); ?>">
			<select name="icon_set" class="us-icon-select">
				<?php foreach ( $icon_sets as $set_slug => $set_info ) { ?>
					<option value="<?php echo $set_slug ?>"<?php if ( $select_value == $set_slug ) {
						echo ' selected="selected"';
					} ?> data-info-url="<?php echo $set_info['set_url'] ?>"><?php echo $set_info['set_name'] ?></option>
				<?php } ?>
			</select>
			<div class="us-icon-preview">
				<?php
				$icon_preview_html = preg_replace( '/fa-\dx/', '', us_prepare_icon_tag( $value ) );
				echo ( $icon_preview_html ) ? $icon_preview_html : '<i class="material-icons"></i>';
				?>
			</div>
			<div class="us-icon-input">
				<input name="icon_name" class="wpb-textinput us-icon-text" type="text"
					   value="<?php echo esc_attr( $input_value ); ?>">
			</div>
		</div>
		<div class="us-icon-desc">
			<?php echo '<a class="us-icon-set-link" href="' . $icon_sets[ $select_value ]['set_url'] . '" target="_blank">' . __( 'Enter icon name from the list', 'us' ) . '</a>. ' . __( 'Examples:', 'us' ) . ' <span class="usof-example">star</span>, <span class="usof-example">edit</span>, <span class="usof-example">code</span>'; ?>
		</div>
		<?php
		$result = ob_get_clean();

		return $result;
	}
}

// Add parameter for Grid Layout selection
if ( ! function_exists( 'us_grid_layout_field' ) ) {
	vc_add_shortcode_param( 'us_grid_layout', 'us_grid_layout_field', $us_template_directory_uri . '/framework/plugins-support/js_composer/js/us_grid_layout.js' );
	function us_grid_layout_field( $settings, $value ) {
		$templates_config = us_config( 'grid-templates', array(), TRUE );

		$custom_layouts = array_flip( us_get_posts_titles_for( 'us_grid_layout' ) );
		ob_start();
		?>
		<div class="us-grid-layout">
			<select name="<?php echo esc_attr( $settings['param_name'] ); ?>"
					class="wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ) ?> dropdown us-grid-layout-select">
				<optgroup label="<?php _e( 'Grid Layouts', 'us' ); ?>">
					<?php foreach ( $custom_layouts as $title => $id ) { ?>
						<option value="<?php echo $id ?>"<?php if ( $value == $id ) {
							echo ' selected="selected"';
						} ?>
								data-edit-url="<?php echo admin_url( '/post.php?post=' . $id . '&action=edit' ); ?>"><?php echo $title; ?></option>
					<?php }
					$current_tmpl_group = '';
					foreach ( $templates_config	as $template_name => $template ) {
					if ( ! empty( $template['group'] ) AND $current_tmpl_group != $template['group'] ) {
					$current_tmpl_group = $template['group'];
					?>
				</optgroup>
				<optgroup label="<?php echo $template['group']; ?>">
					<?php
					}
					?>
					<option value="<?php echo $template_name ?>"<?php if ( $value == $template_name ) {
						echo ' selected="selected"';
					} ?>><?php echo $template['title']; ?></option>
					<?php
					}
					?>
				</optgroup>
			</select>
			<div class="us-grid-layout-desc-edit">
				<?php echo sprintf( _x( '%sEdit selected%s or %screate a new one%s.', 'Grid Layout', 'us' ), '<a href="#" class="edit-link" target="_blank">', '</a>', '<a href="' . admin_url() . 'post-new.php?post_type=us_grid_layout" target="_blank">', '</a>' ); ?>
			</div>
			<div class="us-grid-layout-desc-add">
				<a href="<?php admin_url() ?>post-new.php?post_type=us_grid_layout"
				   target="_blank"><?php _e( 'Add Grid Layout', 'us' ) ?></a>.
			</div>
		</div>
		<?php
		$result = ob_get_clean();

		return $result;
	}
}

// Add script to fill inputs with examples from description
add_action( 'admin_enqueue_scripts', 'us_input_examples' );
function us_input_examples() {
	global $pagenow;
	$screen = get_current_screen();
	$current_post_type = $screen->post_type;
	$excluded_post_types = array(
		'us_header',
		'us_grid_layout',
	);

	if ( $pagenow != 'post.php' OR in_array( $current_post_type, $excluded_post_types ) ) {
		return;
	}

	global $us_template_directory_uri;
	wp_enqueue_script( 'us_input_examples_vc', $us_template_directory_uri . '/framework/plugins-support/js_composer/js/us_input_examples.js', array( 'jquery' ), US_THEMEVERSION );
}

// Add Theme Color Palette to Iris color pickers, mostly used by WPBakery Page Builder
add_action( 'admin_print_scripts', 'us_override_iris_colorpalette', 100 );
function us_override_iris_colorpalette() {
	$screen = get_current_screen();
	if ( wp_script_is( 'wp-color-picker', 'enqueued' ) AND $screen->id != 'toplevel_page_us-theme-options' ) {

		$palette = get_option( 'usof_color_palette_' . US_THEMENAME );

		if ( is_array( $palette ) AND ! empty( $palette ) ) {
			$json_palette = array();
			$default_color = "#ffffff";
			$palette_length = count( $palette );
			// Convert all colors to HEX since Page Builder doesn't support transparency in its palette
			foreach ( $palette as $color ) {
				$color = us_gradient2hex( $color );
				$json_palette[] = us_rgba2hex( $color );
			}
			// Fill till it comes 8 colors
			if ( $palette_length !== 8 ) {
				for ( $i = 0; $i < 8 - $palette_length; $i ++ ) {
					$json_palette[] = $default_color;
				}
			}
			$json_palette = json_encode( $json_palette );
			?>
			<script>
				// Add palette to WordPress iris color pickers
				jQuery( document ).ready( function( $ ) {
					if ( ! $.wp ) {
						return;
					}
					$.wp.wpColorPicker.prototype.options = {
						palettes: <?php echo $json_palette; ?>, width: 255, hide: true,
					};
				} );
			</script>
			<?php
		}
	}
}

// Add design options CSS for shortcodes in custom pages and page blocks
function us_add_page_shortcodes_custom_css( $id ) {
	global $wp_query, $vc_manager;
	$wp_query = new WP_Query(
		array(
			'p' => $id,
			'post_type' => 'any',
		)
	);
	if ( ! empty( $vc_manager ) AND is_object( $vc_manager ) ) {
		$vc_manager->vc()->addPageCustomCss( $id );
		$vc_manager->vc()->addShortcodesCustomCss( $id );
	}
}

// Add image preview for Image shortcode
if ( ! class_exists( 'WPBakeryShortCode_us_image' ) ) {
	class WPBakeryShortCode_us_image extends WPBakeryShortCode {
		public function singleParamHtmlHolder( $param, $value ) {
			$output = '';
			// Compatibility fixes
			$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
			$type = isset( $param['type'] ) ? $param['type'] : '';
			$class = isset( $param['class'] ) ? $param['class'] : '';
			if ( $type == 'attach_image' AND $param_name == 'image' ) {
				$output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
				$element_icon = $this->settings( 'icon' );
				$img = wpb_getImageBySize(
					array(
						'attach_id' => (int) preg_replace( '/[^\d]/', '', $value ),
						'thumb_size' => 'thumbnail',
					)
				);
				$logo_html = '';
				if ( $img ) {
					$logo_html .= $img['thumbnail'];
				} else {
					$logo_html .= '<img width="150" height="150" class="attachment-thumbnail icon-wpb-single-image vc_element-icon" data-name="' . $param_name . '" alt="' . $param_name . '" style="display: none;" />';
				}
				$logo_html .= '<span class="no_image_image vc_element-icon' . ( ! empty( $element_icon ) ? ' ' . $element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" />';
				$this->setSettings( 'logo', $logo_html );
				$output .= $this->outputTitleTrue( $this->settings['name'] );
			} elseif ( ! empty( $param['holder'] ) ) {
				if ( $param['holder'] == 'input' ) {
					$output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
				} elseif ( in_array( $param['holder'], array( 'img', 'iframe' ) ) ) {
					$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . $value . '">';
				} elseif ( $param['holder'] !== 'hidden' ) {
					$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
				}
			}
			if ( ! empty( $param['admin_label'] ) && $param['admin_label'] === TRUE ) {
				$output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . __( $param['heading'], 'js_composer' ) . '</label>: ' . $value . '</span>';
			}

			return $output;
		}

		protected function outputTitle( $title ) {
			return '';
		}

		protected function outputTitleTrue( $title ) {
			return '<h4 class="wpb_element_title">' . __( $title, 'us' ) . ' ' . $this->settings( 'logo' ) . '</h4>';
		}
	}
}

// Add image preview for Person shortcode
if ( ! class_exists( 'WPBakeryShortCode_us_person' ) ) {
	class WPBakeryShortCode_us_person extends WPBakeryShortCode {
		public function singleParamHtmlHolder( $param, $value ) {
			$output = '';
			// Compatibility fixes
			$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
			$type = isset( $param['type'] ) ? $param['type'] : '';
			$class = isset( $param['class'] ) ? $param['class'] : '';
			if ( $type == 'attach_image' AND $param_name == 'image' ) {
				$output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
				$element_icon = $this->settings( 'icon' );
				$img = wpb_getImageBySize(
					array(
						'attach_id' => (int) preg_replace( '/[^\d]/', '', $value ),
						'thumb_size' => 'thumbnail',
					)
				);
				$logo_html = '';
				if ( $img ) {
					$logo_html .= $img['thumbnail'];
				} else {
					$logo_html .= '<img width="150" height="150" class="attachment-thumbnail ' . $element_icon . ' vc_element-icon" data-name="' . $param_name . '" alt="' . $param_name . '" style="display: none;" />';
				}
				$logo_html .= '<span class="no_image_image vc_element-icon ' . $element_icon . ( $img AND ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" />';
				$this->setSettings( 'logo', $logo_html );
				$output .= $this->outputTitleTrue( $this->settings['name'] );
			} elseif ( ! empty( $param['holder'] ) ) {
				if ( $param['holder'] == 'input' ) {
					$output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
				} elseif ( in_array( $param['holder'], array( 'img', 'iframe' ) ) ) {
					$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . $value . '">';
				} elseif ( $param['holder'] !== 'hidden' ) {
					$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
				}
			}
			if ( ! empty( $param['admin_label'] ) AND $param['admin_label'] === TRUE ) {
				$output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . __( $param['heading'], 'js_composer' ) . '</label>: ' . $value . '</span>';
			}

			return $output;
		}

		protected function outputTitle( $title ) {
			return '';
		}

		protected function outputTitleTrue( $title ) {
			return '<h4 class="wpb_element_title">' . __( $title, 'us' ) . ' ' . $this->settings( 'logo' ) . '</h4>';
		}
	}
}

// Add wrapper behavior for us_hwrapper shortcode
if ( ! class_exists( 'WPBakeryShortCode_us_hwrapper' ) ) {
	class WPBakeryShortCode_us_hwrapper extends WPBakeryShortCodesContainer {
	}
}
