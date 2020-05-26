<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a single Grid listing. The universal template that is used by all the possible Grid listings.
 *
 * (!) $query_args should be filtered before passing to this template.
 *
 * @action Before the template: 'us_before_template:templates/us_grid/listing'
 * @action After the template: 'us_after_template:templates/us_grid/listing'
 * @filter Template variables: 'us_template_vars:templates/us_grid/listing'
 */

$us_grid_index = isset( $us_grid_index ) ? intval( $us_grid_index ) : 0;
$grid_elm_id = isset( $el_id ) ? $el_id : 'us_grid_' . $us_grid_index;
$post_id = isset( $post_id ) ? $post_id : NULL;
$is_widget = isset( $is_widget ) ? $is_widget : FALSE;
$classes = isset( $classes ) ? $classes : '';
$filter_taxonomy_name = isset( $filter_taxonomy_name ) ? $filter_taxonomy_name : '';
$terms = isset( $terms ) ? $terms : array();

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

if ( ! $is_widget AND $post_id != NULL AND $type != 'carousel' ) {
	$us_grid_ajax_indexes[ $post_id ] = isset( $us_grid_ajax_indexes[ $post_id ] ) ? ( $us_grid_ajax_indexes[ $post_id ] ) : 1;
} else {
	$us_grid_ajax_indexes = NULL;
}

// Determine Grid Layout
if ( ! empty( $items_layout ) ) {
	if ( $templates_config = us_config( 'grid-templates', array(), TRUE ) AND isset( $templates_config[ $items_layout ] ) ) {
		$grid_layout_settings = us_fix_grid_settings( $templates_config[ $items_layout ] );
	} elseif ( $grid_layout = get_post( (int) $items_layout ) ) {
		if ( $grid_layout instanceof WP_Post AND $grid_layout->post_type === 'us_grid_layout' ) {
			if ( ! empty( $grid_layout->post_content ) AND substr( strval( $grid_layout->post_content ), 0, 1 ) === '{' ) {
				try {
					$grid_layout_settings = json_decode( $grid_layout->post_content, TRUE );
				}
				catch ( Exception $e ) {
				}
			}
		}
	}
}

// Output "No Grid Layout"
if ( ! isset( $grid_layout_settings ) OR empty( $grid_layout_settings ) ) {
	echo '<div>Selected Grid Layout is not found. Check the element settings.</div>';

	return;
}

/*
 * Set items offset to WP Query flow
 * Needed both for regular us_grid element on page and it's AJAX pagination.
 */
if ( $exclude_items == 'offset' AND abs( intval( $items_offset ) ) > 0 ) {
	global $us_grid_items_offset;
	$us_grid_items_offset = abs( intval( $items_offset ) );
	$query_args['_id'] = 'us_grid';
	add_action( 'pre_get_posts', 'us_grid_query_offset', 1 );
	add_filter( 'found_posts', 'us_grid_adjust_offset_pagination', 1, 2 );
}

// Filter and execute database query
global $wp_query, $us_grid_skip_ids;
if ( empty ( $us_grid_index ) OR ! is_array( $us_grid_skip_ids ) ) {
	$us_grid_skip_ids = array();
}
$use_custom_query = isset( $query_args ) AND is_array( $query_args ) AND ! empty( $query_args );
if ( $use_custom_query ) {
	us_open_wp_query_context();
	$wp_query = new WP_Query( $query_args );
} else {
	$query_args = $wp_query->query;

	// Extracting query arguments from WP_Query that are not shown but relevant
	if ( ! isset( $query_args['post_type'] ) ) {
		$request_where = substr( $wp_query->request, strpos( $wp_query->request, 'WHERE' ) );
		if ( preg_match_all( '~\.post_type = \'([a-z0-9\_\-]+)\'~', $request_where, $matches ) ) {
			$query_args['post_type'] = $matches[1];
		} elseif ( preg_match( '~\.post_type IN (\((\'([a-z0-9\_\-]+)\'(, )?)+\))~', $request_where, $matches ) ) {
			$post_types_str = substr( $matches[1], 2, - 2 );
			$post_types = explode( "', '", $post_types_str );
			$query_args['post_type'] = $post_types;
		}

	}
	if ( ! isset( $query_args['post_status'] ) AND preg_match_all( '~\.post_status = \'([a-z]+)\'~', $wp_query->request, $matches ) ) {
		$query_args['post_status'] = $matches[1];
	}
	// Fetching additional params for WooCommerce Products
	if ( $query_args['post_type'] == 'product' ) {
		if ( ! isset( $query_args['posts_per_page'] ) AND ! empty( $wp_query->query_vars['posts_per_page'] ) ) {
			$query_args['posts_per_page'] = $wp_query->query_vars['posts_per_page'];
		}
		if ( ! isset( $query_args['order'] ) AND ! empty( $wp_query->query_vars['order'] ) ) {
			$query_args['order'] = $wp_query->query_vars['order'];
		}
		if ( ! isset( $query_args['orderby'] ) AND ! empty( $wp_query->query_vars['orderby'] ) ) {
			$query_args['orderby'] = $wp_query->query_vars['orderby'];
		}
	}

}

// Check if the grid have items to output, separately for posts and terms
$no_results = FALSE;
if ( in_array( $post_type, array( 'taxonomy_terms', 'current_child_terms' ) ) ) {
	if ( empty( $terms ) ) {
		$no_results = TRUE;
	}
} elseif ( ! have_posts() ) {
	$no_results = TRUE;
}

// Output No results
if ( $no_results ) {

	// Output No results message if it is not empty
	if ( ! empty( $no_items_message ) ) {
		echo '<h4 class="w-grid-none">' . strip_tags( $no_items_message, '<br><strong>' ) . '</h4>';
	}
	if ( $use_custom_query ) {
		us_close_wp_query_context();
	}

	return;
}

// Setting global variable for Image size to use in grid elements
if ( ! empty( $img_size ) AND $img_size != 'default' ) {
	global $us_grid_img_size;
	$us_grid_img_size = $img_size;
}

// Filter Bar HTML
$filter_html = $data_atts = '';
$filter_classes = $filter_style . ' align_' . $filter_align;
if ( $filter_taxonomy_name != '' AND $type != 'carousel' AND $pagination != 'regular' AND ! $is_widget ) {

	// $categories_names already contains only the used categories
	if ( count( $filter_taxonomies ) > 1 ) {
		$filter_html .= '<div class="g-filters ' . $filter_classes . '"><div class="g-filters-list">';

		$active_tab = ' active';
		// Output "All" item
		if ( $filter_show_all ) {
			$filter_html .= '<a class="g-filters-item' . $active_tab . '" href="javascript:void(0)" data-taxonomy="*">';
			$filter_html .= '<span>' . __( 'All', 'us' ) . '</span>';
			$filter_html .= '</a>';
			$active_tab = '';
		}

		// Output taxonomy Items
		foreach ( $filter_taxonomies as $filter_taxonomy ) {
			$filter_html .= '<a class="g-filters-item' . $active_tab . '" href="javascript:void(0)"';
			$filter_html .= ' data-taxonomy="' . $filter_taxonomy->slug . '"';
			$filter_html .= ' data-amount="' . $filter_taxonomy->count . '"';
			$filter_html .= '>';
			$filter_html .= '<span>' . $filter_taxonomy->name . '</span>';
			$filter_html .= '<span class="g-filters-item-amount">' . $filter_taxonomy->count . '</span>';
			$filter_html .= '</a>';
			$active_tab = '';
		}

		$filter_html .= '</div></div>';

		$data_atts .= ' data-filter_taxonomy_name="' . $filter_taxonomy_name . '"';
		if ( ! $filter_show_all ) {
			$filter_default_taxonomies = $filter_taxonomies[0]->slug;
			$data_atts .= ' data-filter_default_taxonomies="' . $filter_default_taxonomies . '"';
		} elseif ( ! empty( $filter_default_taxonomies ) ) {
			$data_atts .= ' data-filter_default_taxonomies="' . $filter_default_taxonomies . '"';
		}
	}
}

// Get all needed variables to pass into listing-start & listing-end templates
$template_vars = array(
	'grid_layout_settings' => $grid_layout_settings,
	'us_grid_index' => $us_grid_index,
	'classes' => $classes,
	'filter_html' => $filter_html,
	'is_widget' => $is_widget,
	'data_atts' => $data_atts,
	'query_args' => $query_args,
	'post_id' => $post_id,
	'us_grid_ajax_indexes' => $us_grid_ajax_indexes,
	'wp_query' => $wp_query,
);
// Add default values for unset variables from Grid config
foreach ( $default_grid_params as $param => $value ) {
	$template_vars[ $param ] = isset( $$param ) ? $$param : $value;
}
// Add default values for unset variables from Carousel config
if ( $type == 'carousel' ) {
	foreach ( $default_carousel_params as $param => $value ) {
		$template_vars[ $param ] = isset( $$param ) ? $$param : $value;
	}
}

// Load listing Start
us_load_template( 'templates/us_grid/listing-start', $template_vars );

// Load posts
global $us_grid_listing_post_atts;
$us_grid_listing_post_atts = array(
	'grid_layout_settings' => $grid_layout_settings,
	'type' => $type,
	'is_widget' => $is_widget,
	'overriding_link' => $overriding_link,
);
if ( empty( $terms ) ) {
	$template_vars[ 'items_count' ] = $wp_query->post_count;
	while ( have_posts() ) {
		the_post();
		$us_grid_skip_ids[] = get_the_ID();
		us_load_template( 'templates/us_grid/listing-post' );
	}
} else {
	global $us_grid_term;
	$template_vars[ 'items_count' ] = count( $terms );
	foreach ( $terms as $term ) {
		$us_grid_term = $term;
		$us_grid_skip_ids[] = $term->term_id;
		us_load_template( 'templates/us_grid/listing-term' );
	}
}

// Load listing End
us_load_template( 'templates/us_grid/listing-end', $template_vars );

// If we are in front end editor mode, apply JS to the current grid
if ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) {
	echo '<script>
	jQuery(function($){
		if (typeof $us !== "undefined" && typeof $us.WGrid === "function") {
			var $gridContainer = $("#' . $grid_elm_id . '");
			$gridContainer.wGrid();
		}
	});
	</script>';
}

if ( $use_custom_query ) {
	// Cleaning up
	us_close_wp_query_context();
}

// Reset image size for the next grid element
if ( isset( $us_grid_img_size ) ) {
	$us_grid_img_size = 'default';
}
