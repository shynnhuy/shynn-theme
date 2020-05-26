<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_grid
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the element config.
 *
 */

// If we are running US Grid loop already, return nothing
global $us_grid_loop_running;
if ( isset( $us_grid_loop_running ) AND $us_grid_loop_running ) {
	return;
}
$us_grid_loop_running = TRUE;

$classes = ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}

// Grid indexes for CSS, start from 1
global $us_grid_index;
$us_grid_index = isset( $us_grid_index ) ? ( $us_grid_index + 1 ) : 1;

// Get the page we are on for AJAX calls
global $us_page_block_ids;
if ( isset( $us_page_block_ids ) AND ! empty( $us_page_block_ids ) ) {
	$post_id = $us_page_block_ids[0];
} else {
	$post_id = get_the_ID();
}
$current_post_id = get_the_ID();

// Grid indexes for ajax, start from 1
if ( $shortcode_base != 'us_carousel'  ) {
	global $us_grid_ajax_indexes;
	$us_grid_ajax_indexes[ $post_id ] = isset( $us_grid_ajax_indexes[ $post_id ] ) ? ( $us_grid_ajax_indexes[ $post_id ] + 1 ) : 1;
} else {
	$us_grid_ajax_indexes = NULL;
}

// Preparing the query
$query_args = $terms = $filter_taxonomies = array();
$filter_taxonomy_name = $filter_default_taxonomies = '';

/*
 * THINGS TO OUTPUT
 */

// Singulars
if ( in_array( $post_type, array_keys( us_grid_available_post_types( TRUE ) ) ) ) {

	$query_args['post_type'] = explode( ',', $post_type );

	// Posts from selected taxonomies
	$known_post_type_taxonomies = us_grid_available_taxonomies();
	if ( ! empty( $known_post_type_taxonomies[ $post_type ] ) ) {
		foreach ( $known_post_type_taxonomies[ $post_type ] as $taxonomy ) {
			if ( ! empty( ${'taxonomy_' . $taxonomy} ) ) {
				if ( ! isset( $query_args['tax_query'] ) ) {
					$query_args['tax_query'] = array();
				}
				$query_args['tax_query'][] = array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => explode( ',', ${'taxonomy_' . $taxonomy} ),
				);
			}
		}
	}

	// Media attachments should have some differ arguments
	if ( $post_type == 'attachment' ) {
		if ( ! empty( $images ) ) {
			$ids = explode( ',', $images );
			$query_args['post__in'] = $ids;
		} else {
			$attached_images = get_attached_media( 'image', $current_post_id );
			if ( ! empty( $attached_images ) ) {
				foreach ( $attached_images as $attached_image ) {
					$query_args['post__in'][] = $attached_image->ID;
				}
			}
		}
		$query_args['post_status'] = 'inherit';
		$query_args['post_mime_type'] = 'image';
	} else {
		// Proper post statuses
		$query_args['post_status'] = array( 'publish' => 'publish' );
		$query_args['post_status'] += (array) get_post_stati( array( 'public' => TRUE ) );
		// Add private states if user is capable to view them
		if ( is_user_logged_in() AND current_user_can( 'read_private_posts' ) ) {
			$query_args['post_status'] += (array) get_post_stati( array( 'private' => TRUE ) );
		}
		$query_args['post_status'] = array_values( $query_args['post_status'] );
	}

	// Data for filter
	if ( ! empty( ${'filter_' . $post_type} ) ) {
		$filter_taxonomy_name = ${'filter_' . $post_type};
		$terms_args = array(
			'hierarchical' => FALSE,
			'taxonomy' => $filter_taxonomy_name,
			'number' => 100,
		);
		if ( ! empty( ${'taxonomy_' . $filter_taxonomy_name} ) ) {
			$terms_args['slug'] = explode( ',', ${'taxonomy_' . $filter_taxonomy_name} );
			if ( is_user_logged_in() ) {
				$terms_args['hide_empty'] = FALSE;
			}
			$filter_default_taxonomies = ${'taxonomy_' . $filter_taxonomy_name};
		}
		$filter_taxonomies = get_terms( $terms_args );
		if ( isset( $filter_show_all ) AND ! $filter_show_all AND ! empty( $filter_taxonomies[0] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $filter_taxonomy_name,
					'field' => 'slug',
					'terms' => $filter_taxonomies[0],
				),
			);
		}
	}

	// Specific items by IDs
} elseif ( $post_type == 'ids' AND ! empty( $ids ) ) {
	$ids = explode( ',', $ids );
	$query_args['ignore_sticky_posts'] = 1;
	$query_args['post_type'] = 'any';
	$query_args['post__in'] = $ids;

	// Items with the same taxonomy of current post
} elseif ( $post_type == 'related' AND is_singular() AND ! empty( $related_taxonomy ) ) {
	$query_args['ignore_sticky_posts'] = 1;
	$query_args['post_type'] = get_post_type();
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => $related_taxonomy,
			'terms' => wp_get_object_terms( $current_post_id, $related_taxonomy, array( 'fields' => 'ids' ) ),
		),
	);

	// Product upsells (WooCommerce only)
} elseif ( $post_type == 'product_upsells' AND is_singular() ) {
	$upsells_ids = get_post_meta( $current_post_id, '_upsell_ids', TRUE );
	$query_args['post_type'] = 'product';
	$query_args['post__in'] = (array) $upsells_ids;

	// Terms of selected (or current) taxonomy
} elseif ( in_array( $post_type, array( 'taxonomy_terms', 'current_child_terms' ) ) ) {
	$current_term_id = $parent = 0;
	$hide_empty = TRUE;

	if ( strpos( $terms_include, 'children' ) !== FALSE ) {
		$parent = '';
	}
	if ( strpos( $terms_include, 'empty' ) !== FALSE ) {
		$hide_empty = FALSE;
	}

	// If the current page is archive, we will output its children terms only
	if ( $post_type == 'current_child_terms' AND is_archive() ) {
		$current_term = get_queried_object();
		$related_taxonomy = $current_term->taxonomy;
		if ( strpos( $terms_include, 'children' ) !== FALSE ) {
			$current_term_id = $current_term->term_id;
		} else {
			$parent = $current_term->term_id;
		}
	}

	$terms = get_terms(
		array(
			'taxonomy' => $related_taxonomy,
			'orderby' => $terms_orderby,
			'order' => ( $terms_orderby == 'count' ) ? 'DESC' : 'ASC',
			'number' => $items_quantity,
			'hide_empty' => $hide_empty,
			'child_of' => $current_term_id,
			'parent' => $parent,
		)
	);

	// When taxonomy doesn't exist, it returns WP_Error object, so we need to use empty array for further work
	if ( is_wp_error( $terms ) ) {
		$terms = array();
	}

	// Imitate random ordering for terms
	if ( $terms_orderby == 'rand' ) {
		shuffle( $terms );
	}

	// Generate query for "Gallery" and "Post Object" types from ACF PRO plugin
} elseif ( strpos( $post_type, 'acf_' ) !== FALSE AND is_singular() ) {

	// ACF Galleries
	if ( strpos( $post_type, 'acf_gallery_' ) !== FALSE ) {
		$key = str_replace( 'acf_gallery_', '', $post_type );

		$query_args['post_type'] = 'attachment';
		$query_args['post_status'] = 'inherit';
		$query_args['post__in'] = get_post_meta( $current_post_id, $key, TRUE );
	}

	// ACF Post objects
	if ( strpos( $post_type, 'acf_posts_' ) !== FALSE ) {
		$key = str_replace( 'acf_posts_', '', $post_type );
		$ids = get_post_meta( $current_post_id, $key, TRUE );

		$query_args['post_type'] = 'any';
		$query_args['ignore_sticky_posts'] = 1;
		$query_args['post__in'] = is_array( $ids ) ? $ids : array( $ids );
	}

}

// Always exclude the current post from the query
if ( is_singular() ) {
	$query_args['post__not_in'] = array( $current_post_id );
}

// Exclude sticky posts
if ( ! empty( $ignore_sticky ) ) {
	$query_args['ignore_sticky_posts'] = 1;
}

// Set ordering arguments
switch ( $orderby ) {
	case 'date':
		$query_args['orderby'] = array( 'date' => 'DESC' );
		break;
	case 'date_asc':
		$query_args['orderby'] = array( 'date' => 'ASC' );
		break;
	case 'modified':
		$query_args['orderby'] = array( 'modified' => 'DESC' );
		break;
	case 'modified_asc':
		$query_args['orderby'] = array( 'modified' => 'ASC' );
		break;
	case 'alpha':
		$query_args['orderby'] = array( 'title' => 'ASC' );
		break;
	case 'menu_order':
		$query_args['orderby'] = array( 'menu_order' => 'ASC' );
		break;
	case 'rand':
		$query_args['orderby'] = 'RAND(' . rand() . ')';
		break;
	case 'price_desc':
		$query_args['orderby'] = 'meta_value_num';
		$query_args['meta_key'] = '_price';
		$query_args['order'] = 'DESC';
		break;
	case 'price_asc':
		$query_args['orderby'] = 'meta_value_num';
		$query_args['meta_key'] = '_price';
		$query_args['order'] = 'ASC';
		break;
	case 'popularity':
		$query_args['orderby'] = 'meta_value_num';
		$query_args['meta_key'] = 'total_sales';
		$query_args['order'] = 'DESC';
		break;
	case 'rating':
		$query_args['orderby'] = 'meta_value_num';
		$query_args['meta_key'] = '_wc_average_rating';
		$query_args['order'] = 'DESC';
		break;
	default:
		$query_args['orderby'] = $orderby;
}

// Pagination
if ( $pagination == 'regular' ) {
	$request_paged = is_front_page() ? 'page' : 'paged';
	if ( get_query_var( $request_paged ) ) {
		$query_args['paged'] = get_query_var( $request_paged );
	}
}

// Extra arguments for WooCommerce products
if ( class_exists( 'woocommerce' ) AND in_array( $post_type, array( 'product', 'product_upsells' ) ) ) {
	$query_args['meta_query'] = array();

	// Exclude out of stock products
	if ( $exclude_items == 'out_of_stock' ) {
		$query_args['meta_query'][] = array(
			'key' => '_stock_status',
			'value' => 'outofstock',
			'compare' => '!=',
		);
	}

	// Show Sale products
	if ( strpos( $products_include, 'sale' ) !== FALSE ) {
		$query_args['meta_query'][] = array(
			'key' => '_sale_price',
			'value' => '',
			'compare' => '!=',
		);
	}

	// Show Featured products
	if ( strpos( $products_include, 'featured' ) !== FALSE ) {
		$tax_query_featured = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
			'operator' => 'IN',
		);
		$query_args['tax_query'][] = $tax_query_featured;
	}
}

// Exclude posts of previous grids on the same page
if ( $exclude_items == 'prev' ) {
	global $us_grid_skip_ids;
	if ( ! empty( $us_grid_skip_ids ) AND is_array( $us_grid_skip_ids ) ) {
		if ( empty( $query_args['post__not_in'] ) OR ! is_array( $query_args['post__not_in'] ) ) {
			$query_args['post__not_in'] = array();
		}
		$query_args['post__not_in'] = array_merge( $query_args['post__not_in'], $us_grid_skip_ids );
	}
}

// Items per page
if ( $items_quantity < 1 ) {
	$items_quantity = 999;
}
$query_args['posts_per_page'] = $items_quantity;

// Reset query for using on archives
if ( $post_type == 'current_query' ) {
	if ( is_tax( 'tribe_events_cat' ) OR is_post_type_archive( 'tribe_events' ) ) {
		$the_content = apply_filters( 'the_content', get_the_content() );

		// The page may be paginated itself via <!--nextpage--> tags
		$the_pagination = wp_link_pages(
			array(
				'before' => '<nav class="post-pagination"><span class="title">' . us_translate( 'Pages:' ) . '</span>',
				'after' => '</nav>',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'echo' => 0,
			)
		);

		echo $the_content . $the_pagination;
		return;
	} elseif ( is_archive() OR is_search() OR is_home() ) {
		$query_args = NULL;
	} else {
		return;
	}
}

// Load Grid Listing template with given params
$template_vars = array(
	'query_args' => $query_args,
	'terms' => $terms,
	'us_grid_index' => $us_grid_index,
	'us_grid_ajax_indexes' => $us_grid_ajax_indexes,
	'classes' => $classes,
	'post_id' => $post_id,
	'filter_taxonomy_name' => $filter_taxonomy_name,
	'filter_default_taxonomies' => $filter_default_taxonomies,
	'filter_taxonomies' => $filter_taxonomies,
);

// Add default values for unset variables from Grid config
$default_grid_params = us_shortcode_atts( array(), 'us_grid' );
foreach ( $default_grid_params as $param => $value ) {
	$template_vars[ $param ] = isset( $$param ) ? $$param : $value;
}

// Add default values for unset variables from Carousel config
if ( $shortcode_base == 'us_carousel' ) {
	$default_carousel_params = us_shortcode_atts( array(), 'us_carousel' );
	foreach ( $default_carousel_params as $param => $value ) {
		$template_vars[ $param ] = isset( $$param ) ? $$param : $value;
	}
	$template_vars['type'] = 'carousel'; // force 'carousel' type for us_carousel shortcode
}

us_load_template( 'templates/us_grid/listing', $template_vars );

$us_grid_loop_running = FALSE;
