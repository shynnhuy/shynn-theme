<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Opening part of Grid output
 */

// Variables defaults
$list_classes = $css_listing = $css_layout = '';

$us_grid_index = isset( $us_grid_index ) ? intval( $us_grid_index ) : 0;
$is_widget = isset( $is_widget ) ? $is_widget : FALSE;
$classes = isset( $classes ) ? $classes : '';
$filter_html = isset( $filter_html ) ? $filter_html : '';
$data_atts = isset( $data_atts ) ? $data_atts : '';

// Check Grid params and use default values from config, if its not set
$default_grid_params = us_shortcode_atts( array(), 'us_grid' );
foreach ( $default_grid_params as $param => $value ) {
	if ( ! isset( $$param ) ) {
		$$param = $value;
	}
}

// Check Carousel params and use default values from config, if its not set
if ( $type == 'carousel' ) {
	$default_carousel_params = us_shortcode_atts( array(), 'us_carousel' );
	foreach ( $default_carousel_params as $param => $value ) {
		if ( ! isset( $$param ) ) {
			$$param = $value;
		}
	}
}

// Set grid ID
$grid_elm_id = ( ! empty( $el_id ) ) ? $el_id : 'us_grid_' . $us_grid_index;

// Calculate or not specific CSS for grid items depending on Grid Layout Aspect Ratio
$grid_fixed_ratio = us_arr_path( $grid_layout_settings, 'default.options.fixed' );

// Additional classes for "w-grid"
$classes .= ' type_' . $type;
$classes .= ' layout_' . $items_layout;
if ( $columns != 1 AND $type != 'carousel' ) {
	$classes .= ' cols_' . $columns;
}
if ( $items_valign ) {
	$classes .= ' valign_center';
}
if ( $pagination == 'regular' ) {
	$classes .= ' with_pagination';
}
if ( $grid_fixed_ratio ) {
	$classes .= ' height_fixed';
} elseif ( us_arr_path( $grid_layout_settings, 'default.options.overflow' ) ) {
	$classes .= ' overflow_hidden';
}
if ( $overriding_link == 'popup_post' ) {
	$classes .= ' popup_page';
}
if ( $filter_html != '' ) {
	$classes .= ' with_filters';
}

// Apply isotope script for Masonry type
if ( $type == 'masonry' AND $columns > 1 ) {
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-isotope' );
	}
	$classes .= ' with_isotope';
}

// Output attributes for Carousel type
if ( $type == 'carousel' ) {
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-owl' );
	}

	$list_classes .= ' owl-carousel';
	$list_classes .= ' navstyle_' . $carousel_arrows_style;
	$list_classes .= ' navpos_' . $carousel_arrows_pos;
	if ( $carousel_dots ) {
		$list_classes .= ' with_dots';
	}
	if ( $columns == 1 AND $carousel_autoheight ) {
		$list_classes .= ' autoheight';
	}

	// Customize Carousel Arrows for current listing only
	if ( $carousel_arrows ) {
		if ( ! empty( $carousel_arrows_size ) AND $carousel_arrows_size != '1.8rem' ) {
			$css_listing .= '#' . $grid_elm_id . ' .owl-nav div { font-size: ' . strip_tags( $carousel_arrows_size ) . '}';
		}
		if ( ! empty( $carousel_arrows_offset ) ) {
			$css_listing .= '#' . $grid_elm_id . ' .owl-nav div { margin-left: ' . strip_tags( $carousel_arrows_offset ) . '; margin-right: ' . strip_tags( $carousel_arrows_offset ) . '}';
		}
	}
}

// Generate items gap via CSS
if ( ! empty( $items_gap ) ) {
	if ( $columns != 1 ) {
		$css_listing .= '#' . $grid_elm_id . ' .w-grid-item { padding: ' . $items_gap . '}';
		if ( ! empty( $filter_html ) AND $pagination == 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: ' . $items_gap . ' -' . $items_gap . ' -' . $items_gap . '}';
		}
		if ( ! empty( $filter_html ) AND $pagination != 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: ' . $items_gap . ' -' . $items_gap . '}';
		}
		if ( empty( $filter_html ) AND $pagination != 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: -' . $items_gap . ' -' . $items_gap . ' ' . $items_gap . '}';
		}
		if ( empty( $filter_html ) AND $pagination == 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: -' . $items_gap . '}';
		}
		// Force gap between neighbour "w-grid" elements
		$css_listing .= '.w-grid + #' . $grid_elm_id . ' .w-grid-list { margin-top: ' . $items_gap . '}';
		// Force left & right gaps for grid-list in fullwidth section
		$css_listing .= '.l-section.width_full > div > div > .vc_col-sm-12 > div > div > #' . $grid_elm_id . ' .w-grid-list { margin-left: ' . $items_gap . '; margin-right: ' . $items_gap . '}';
		// Force top & bottom gaps for grid-list in fullheight section
		$css_listing .= '.l-section.height_auto > div > div > .vc_col-sm-12 > div > div > #' . $grid_elm_id . ':first-child .w-grid-list { margin-top: ' . $items_gap . '}';
		$css_listing .= '.l-section.height_auto > div > div > .vc_col-sm-12 > div > div > #' . $grid_elm_id . ':last-child .w-grid-list { margin-bottom: ' . $items_gap . '}';
	} elseif ( $type != 'carousel' ) {
		$css_listing .= '#' . $grid_elm_id . ' .w-grid-item:not(:last-child) { margin-bottom: ' . $items_gap . '}';
		$css_listing .= '#' . $grid_elm_id . ' .g-loadmore { margin-top: ' . $items_gap . '}';
	}
} else {
	$classes .= ' no_gap';
}

// Generate columns responsive CSS for 3 breakpoints
if ( $type != 'carousel' AND ! $is_widget ) {
	for ( $i = 1; $i < 4; $i ++ ) {
		$responsive_cols = intval( ${ 'breakpoint_' . $i .'_cols' } );
		$responsive_width = intval( ${ 'breakpoint_' . $i . '_width' } );

		if ( $columns > $responsive_cols ) {
			$css_listing .= '@media (max-width:' . ( $responsive_width - 1 ) . 'px) {';
			if ( $responsive_cols == 1 AND ! empty( $items_gap ) ) {
				$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: 0 }';
			}
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-item { width:' . 100 / $responsive_cols . '%;';
			if ( $responsive_cols == 1 AND ! empty( $items_gap ) ) {
				$css_listing .= 'padding: 0; margin-bottom: ' . $items_gap;
			}
			$css_listing .= '}';
			if ( $responsive_cols != 1 AND $grid_fixed_ratio ) {
				$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1,';
				$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 {';
				$css_listing .= 'width:' . 200 / $responsive_cols . '% }';
			}
			$css_listing .= '}';
		}
	}
}

// Add Post Title font-size for current listing only
if ( $title_size != '' ) {
	$css_listing .= '#' . $grid_elm_id . ' .w-post-elm.post_title { font-size: ' . strip_tags( $title_size ) . '}';
}

// Calculate items aspect ratio
if ( $grid_fixed_ratio ) {
	$grid_elm_ratio_w = $grid_elm_ratio_h = 1;

	$grid_elm_ratio = us_arr_path( $grid_layout_settings, 'default.options.ratio' );
	if ( $grid_elm_ratio == '4x3' ) {
		$grid_elm_ratio_w = 4;
		$grid_elm_ratio_h = 3;
	} elseif ( $grid_elm_ratio == '3x2' ) {
		$grid_elm_ratio_w = 3;
		$grid_elm_ratio_h = 2;
	} elseif ( $grid_elm_ratio == '2x3' ) {
		$grid_elm_ratio_w = 2;
		$grid_elm_ratio_h = 3;
	} elseif ( $grid_elm_ratio == '3x4' ) {
		$grid_elm_ratio_w = 3;
		$grid_elm_ratio_h = 4;
	} elseif ( $grid_elm_ratio == '16x9' ) {
		$grid_elm_ratio_w = 16;
		$grid_elm_ratio_h = 9;
	} elseif ( $grid_elm_ratio == 'custom' ) {
		$grid_elm_ratio_w = floatval( str_replace( ',', '.', preg_replace( '/^[^\d.,]+$/', '', us_arr_path( $grid_layout_settings, 'default.options.ratio_width' ) ) ) );
		if ( $grid_elm_ratio_w <= 0 ) {
			$grid_elm_ratio_w = 1;
		}
		$grid_elm_ratio_h = floatval( str_replace( ',', '.', preg_replace( '/^[^\d.,]+$/', '', us_arr_path( $grid_layout_settings, 'default.options.ratio_height' ) ) ) );
		if ( $grid_elm_ratio_h <= 0 ) {
			$grid_elm_ratio_h = 1;
		}
	}

	// Apply grid item aspect ratio
	$css_layout .= '#' . $grid_elm_id . ' .w-grid-item-h:before {';
	$css_layout .= 'padding-bottom: ' . number_format( $grid_elm_ratio_h / $grid_elm_ratio_w * 100, 4 ) . '%}';

	// Fix aspect ratio regarding meta custom size and items gap
	if ( empty( $items_gap ) ) {
		$items_gap = '0px'; // needed for CSS calc function
	}
	if ( $type != 'carousel' AND ! $is_widget ) {
		$css_layout .= '@media (min-width:' . intval( $breakpoint_3_width ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item.size_1x2 .w-grid-item-h:before {';
		$css_layout .= 'padding-bottom: calc(' . ( $grid_elm_ratio_h * 2 ) / $grid_elm_ratio_w * 100 . '% + ' . $items_gap . ' + ' . $items_gap . ')}';
		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1 .w-grid-item-h:before {';
		$css_layout .= 'padding-bottom: calc(' . $grid_elm_ratio_h / ( $grid_elm_ratio_w * 2 ) * 100 . '% - ' . $items_gap . ' * ' . $grid_elm_ratio_h / $grid_elm_ratio_w . ')}';
		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 .w-grid-item-h:before {';
		$css_layout .= 'padding-bottom: calc(' . $grid_elm_ratio_h / $grid_elm_ratio_w * 100 . '% - ' . $items_gap . ' * ' . 2 * ( $grid_elm_ratio_h / $grid_elm_ratio_w - 1 ) . ')}';
		$css_layout .= '}';
	}
}

// Generate Grid Layout settings CSS
$item_bg_color = us_arr_path( $grid_layout_settings, 'default.options.color_bg' );
$item_text_color = us_arr_path( $grid_layout_settings, 'default.options.color_text' );
$item_border_radius = floatval( us_arr_path( $grid_layout_settings, 'default.options.border_radius' ) );
$item_box_shadow = floatval( us_arr_path( $grid_layout_settings, 'default.options.box_shadow' ) );
$item_box_shadow_hover = floatval( us_arr_path( $grid_layout_settings, 'default.options.box_shadow_hover' ) );

$css_layout .= '#' . $grid_elm_id . ' .w-grid-item-h {';
if ( ! empty( $item_bg_color ) ) {
	$css_layout .= 'background:' . $item_bg_color . ';';
}
if ( ! empty( $item_text_color ) ) {
	$css_layout .= 'color:' . $item_text_color . ';';
}
if ( ! empty( $item_border_radius ) ) {
	$css_layout .= 'border-radius:' . $item_border_radius . 'rem;';
	$css_layout .= 'z-index: 3;';
}
if ( ! empty( $item_box_shadow ) OR ! empty( $item_box_shadow_hover ) ) {
	$css_layout .= 'box-shadow:';
	$css_layout .= '0 ' . number_format( $item_box_shadow / 10, 2 ) . 'rem ' . number_format( $item_box_shadow / 5, 2 ) . 'rem rgba(0,0,0,0.1),';
	$css_layout .= '0 ' . number_format( $item_box_shadow / 3, 2 ) . 'rem ' . number_format( $item_box_shadow, 2 ) . 'rem rgba(0,0,0,0.1);';
	$css_layout .= 'transition-duration: 0.3s;';
}
$css_layout .= '}';
if ( $item_box_shadow_hover != $item_box_shadow ) {
	$css_layout .= '.no-touch #' . $grid_elm_id . ' .w-grid-item-h:hover { box-shadow:';
	$css_layout .= '0 ' . number_format( $item_box_shadow_hover / 10, 2 ) . 'rem ' . number_format( $item_box_shadow_hover / 5, 2 ) . 'rem rgba(0,0,0,0.1),';
	$css_layout .= '0 ' . number_format( $item_box_shadow_hover / 3, 2 ) . 'rem ' . number_format( $item_box_shadow_hover, 2 ) . 'rem rgba(0,0,0,0.15);';
	$css_layout .= 'z-index: 4;';
	$css_layout .= '}';
}

// Generate Grid Layout elements CSS
$css_data = array();
foreach ( $grid_layout_settings['data'] as $elm_id => $elm ) {
	$elm_class = 'usg_' . str_replace( ':', '_', $elm_id );

	// Elements settings
	$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
	$elm_tag = isset( $elm['tag'] ) ? $elm['tag'] : 'div';
	$css_layout .= us_prepare_inline_css(
		array(
			'font-family' => isset( $elm['font'] ) ? $elm['font'] : '',
			'font-weight' => isset( $elm['font_weight'] ) ? $elm['font_weight'] : '',
			'text-transform' => isset( $elm['text_transform'] ) ? $elm['text_transform'] : '',
			'font-style' => isset( $elm['font_style'] ) ? $elm['font_style'] : '',
			'font-size' => isset( $elm['font_size'] ) ? $elm['font_size'] : '',
			'line-height' => isset( $elm['line_height'] ) ? $elm['line_height'] : '',
			'border-radius' => isset( $elm['border_radius'] ) ? $elm['border_radius'] : '',
			'width' => isset( $elm['width'] ) ? $elm['width'] : '',
			'flex-shrink' => ! empty( $elm['width'] ) ? '0' : '',
			'background' => isset( $elm['color_bg'] ) ? $elm['color_bg'] : '',
			'border-color' => isset( $elm['color_border'] ) ? $elm['color_border'] : '',
			'color' => isset( $elm['color_text'] ) ? $elm['color_text'] : '',
		),
		$style_attr = FALSE,
		$elm_tag
	);
	$css_layout .= ( isset( $elm['bg_gradient'] ) AND $elm['bg_gradient'] AND ! empty( $elm['color_grad'] ) ) ? 'background: linear-gradient( transparent, ' . $elm['color_grad'] . ');' : '';
	$css_layout .= '}';
	if ( ! empty( $elm['font_size_mobiles'] ) ) {
		$css_layout .= '@media (max-width:' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_layout .= 'font-size:' . $elm['font_size_mobiles'] . ' !important;';
		$css_layout .= '}}';
	}
	if ( ! empty( $elm['line_height_mobiles'] ) ) {
		$css_layout .= '@media (max-width:' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_layout .= 'line-height:' . $elm['line_height_mobiles'] . ' !important;';
		$css_layout .= '}}';
	}
	if ( isset( $elm['hide_below'] ) AND intval( $elm['hide_below'] ) != 0 ) {
		$css_layout .= '@media (max-width:' . ( intval( $elm['hide_below'] ) - 1 ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{ display: none !important; }';
		$css_layout .= '}';
	}

	// CSS of Hover effects
	if ( isset( $elm['hover'] ) AND $elm['hover'] ) {
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_layout .= isset( $elm['transition_duration'] ) ? 'transition-duration:' . $elm['transition_duration'] . ';' : '';
		if ( isset( $elm['scale'] ) AND isset( $elm['translateX'] ) AND isset( $elm['translateY'] ) ) {
			$css_layout .= 'transform: scale(' . $elm['scale'] . ') translate(' . $elm['translateX'] . ',' . $elm['translateY'] . ');';
		}
		$css_layout .= ( isset( $elm['opacity'] ) AND intval( $elm['opacity'] ) != 1 ) ? 'opacity:' . $elm['opacity'] . ';' : '';
		$css_layout .= '}';

		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item-h:hover .' . $elm_class . '{';
		if ( isset( $elm['scale_hover'] ) AND isset( $elm['translateX_hover'] ) AND isset( $elm['translateY_hover'] ) ) {
			$css_layout .= 'transform: scale(' . $elm['scale_hover'] . ') translate(' . $elm['translateX_hover'] . ',' . $elm['translateY_hover'] . ');';
		}
		$css_layout .= isset( $elm['opacity_hover'] ) ? 'opacity:' . $elm['opacity_hover'] . ';' : '';
		$css_layout .= ( ! empty( $elm['color_bg_hover'] ) ) ? 'background:' . $elm['color_bg_hover'] . ';' : '';
		$css_layout .= ( ! empty( $elm['color_border_hover'] ) ) ? 'border-color:' . $elm['color_border_hover'] . ';' : '';
		$css_layout .= ( ! empty( $elm['color_text_hover'] ) ) ? 'color:' . $elm['color_text_hover'] . ';' : '';
		$css_layout .= '}';
	}

	// CSS Design Options
	if ( ! empty( $elm['design_options'] ) AND is_array( $elm['design_options'] ) ) {
		foreach ( $elm['design_options'] as $key => $value ) {
			if ( $value === '' ) {
				continue;
			}
			$key = explode( '_', $key );
			if ( ! isset( $css_data[ $key[2] ] ) ) {
				$css_data[ $key[2] ] = array();
			}
			if ( ! isset( $css_data[ $key[2] ][ $elm_class ] ) ) {
				$css_data[ $key[2] ][ $elm_class ] = array();
			}
			if ( ! isset( $css_data[ $key[2] ][ $elm_class ][ $key[0] ] ) ) {
				$css_data[ $key[2] ][ $elm_class ][ $key[0] ] = array();
			}
			$css_data[ $key[2] ][ $elm_class ][ $key[0] ][ $key[1] ] = $value;
		}
	}
}

foreach ( array( 'default' ) as $state ) {
	if ( ! isset( $css_data[ $state ] ) ) {
		continue;
	}
	foreach ( $css_data[ $state ] as $elm_class => $props ) {
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		foreach ( $props as $prop => $values ) {
			// Add absolute positioning if its values not empty
			if ( $prop === 'position' AND ! empty( $values ) ) {
				$css_layout .= 'position: absolute;';
			}
			// Add solid border if its values not empty
			if ( $prop === 'border' AND ! empty( $values ) ) {
				$css_layout .= 'border-style: solid; border-width: 0;';
			}
			if ( count( $values ) == 4 AND count( array_unique( $values ) ) == 1 AND $prop !== 'position' ) {
				// All the directions have the same value, so grouping them together
				$values = array_values( $values );

				if ( $prop === 'border' ) {
					$css_layout .= $prop . '-width:' . $values[0] . ';';
				} else {
					$css_layout .= $prop . ':' . $values[0] . ';';
				}
			} else {
				foreach ( $values as $dir => $val ) {
					if ( $prop === 'position' ) {
						$css_prop = $dir;
					} elseif ( $prop === 'border' ) {
						$css_prop = $prop . '-' . $dir . '-width';
					} else {
						$css_prop = $prop . '-' . $dir;
					}
					$css_layout .= $css_prop . ':' . $val . ';';
				}
			}
		}
		$css_layout .= "}";
	}
}

// Output the Grid semantics
echo '<div class="w-grid' . $classes . '" id="' . esc_attr( $grid_elm_id ) . '">';
echo '<style id="' . $grid_elm_id . '_css">' . us_minify_css( $css_listing ) . '</style>';
echo '<style>' . us_minify_css( $css_layout ) . '</style>'; // TODO make this depends on the previous Grid Layout of a page
echo $filter_html;
echo '<div class="w-grid-list' . $list_classes . '"' . $data_atts . '>';
