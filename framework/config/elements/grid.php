<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

// Get the available post types for selection
$available_posts_types = us_grid_available_post_types( TRUE );

// Fetching the available taxonomies for selection
$taxonomies_params = $filter_taxonomies_params = $available_taxonomies = array();

$known_post_type_taxonomies = us_grid_available_taxonomies();

foreach ( $known_post_type_taxonomies as $post_type => $taxonomy_slugs ) {
	if ( isset( $available_posts_types[ $post_type ] ) ) {
		$filter_values = array();
		foreach ( $taxonomy_slugs as $taxonomy_slug ) {
			$taxonomy_class = get_taxonomy( $taxonomy_slug );
			if ( ! empty( $taxonomy_class ) AND ! empty( $taxonomy_class->labels ) AND ! empty( $taxonomy_class->labels->name ) ) {
				if ( isset ( $available_taxonomies[ $taxonomy_slug ] ) ) {
					$available_taxonomies[ $taxonomy_slug ]['post_type'][] = $post_type;
				} else {
					$available_taxonomies[ $taxonomy_slug ] = array(
						'name' => $taxonomy_class->labels->name,
						'post_type' => array( $post_type ),
					);
				}
				$filter_value_label = $taxonomy_class->labels->name;
				$filter_values[ $taxonomy_slug ] = $filter_value_label;
			}
		}
		if ( count( $filter_values ) > 0 ) {
			$filter_taxonomies_params[ 'filter_' . $post_type ] = array(
				'title' => __( 'Filter by', 'us' ),
				'type' => 'select',
				'options' => array_merge(
					array( '' => '– ' . us_translate( 'None' ) . ' –' ), $filter_values
				),
				'std' => '',
				'show_if' => array( 'post_type', '=', $post_type ),
				'group' => us_translate( 'Filter' ),
			);
		}

	}
}

foreach ( $available_taxonomies as $taxonomy_slug => $taxonomy ) {
	$taxonomy_items = array();
	$taxonomy_items_raw = get_categories(
		array(
			'taxonomy' => $taxonomy_slug,
			'hierarchical' => 0,
			'hide_empty' => FALSE,
			'number' => 200,
		)
	);
	if ( $taxonomy_items_raw ) {
		foreach ( $taxonomy_items_raw as $taxonomy_item_raw ) {
			if ( is_object( $taxonomy_item_raw ) ) {
				$taxonomy_items[ $taxonomy_item_raw->slug ] = $taxonomy_item_raw->name;
			}
		}
		if ( count( $taxonomy_items ) > 0 ) {
			// Do not output the only "Uncategorized" of Posts and Products
			if ( in_array( $taxonomy_slug, array( 'category', 'product_cat' ) ) AND count( $taxonomy_items ) == 1 ) {
				continue;
			}
			foreach ( $taxonomy['post_type'] as $taxonomy_post_type ) {
				$taxonomies_params[ 'taxonomy_' . $taxonomy_slug ] = array(
					'title' => sprintf( __( 'Show Items of selected %s', 'us' ), $taxonomy['name'] ),
					'type' => 'checkboxes',
					'options' => $taxonomy_items,
					'show_if' => array( 'post_type', 'in', $taxonomy['post_type'] ),
				);
			}

		}
	}
}

// Additional values for WooCommerce products
if ( class_exists( 'woocommerce' ) ) {
	$products_show_values = array(
		'product_upsells' => us_translate( 'Upsells', 'woocommerce' ),
	);
	$products_orderby_values = array(
		'popularity' => us_translate( 'Popularity (sales)', 'woocommerce' ),
		'price_asc' => us_translate( 'Sort by price: low to high', 'woocommerce' ),
		'price_desc' => us_translate( 'Sort by price: high to low', 'woocommerce' ),
		'rating' => us_translate( 'Sort by average rating', 'woocommerce' ),
	);
	$products_exclude_values = array(
		'out_of_stock' => us_translate( 'Out of stock', 'woocommerce' ),
	);
} else {
	$products_orderby_values = $products_exclude_values = $products_show_values = array();
}

// Get "Gallery" and "Post Object" options from ACF PRO plugin
$acf_show_values = array();
if ( function_exists( 'acf_get_field_groups' ) ) {
	$acf_groups = acf_get_field_groups();
	if ( ! empty( $acf_groups ) ) {
		foreach ( $acf_groups as $group ) {
			$fields = acf_get_fields( $group['ID'] );
			foreach ( $fields as $field ) {
				if ( $field['type'] == 'gallery' ) {
					$acf_show_values[ 'acf_gallery_' . $field['name'] ] = $group['title'] . ': ' . $field['label'];
				}
				if ( $field['type'] == 'post_object' ) {
					$acf_show_values[ 'acf_posts_' . $field['name'] ] = $group['title'] . ': ' . $field['label'];
				}
			}
		}
	}
}

$grid_config = array(
	'title' => __( 'Grid', 'us' ),
	'description' => __( 'List of images, posts, pages or any custom post types', 'us' ),
	'icon' => 'fas fa-th-large',
	'params' => array(),
);

// General
$general_params = array_merge(
	array(

		'post_type' => array(
			'title' => us_translate( 'Show' ),
			'type' => 'select',
			'options' => array_merge(
				$available_posts_types, array(
					'ids' => __( 'Specific items', 'us' ),
					'related' => __( 'Items with the same taxonomy of current post', 'us' ),
					'current_query' => __( 'Items of the current query (used for archives and search results)', 'us' ),
					'taxonomy_terms' => __( 'Terms of selected taxonomy', 'us' ),
					'current_child_terms' => __( 'Child terms of current taxonomy', 'us' ),
				), $products_show_values, $acf_show_values
			),
			'std' => 'post',
			'admin_label' => TRUE,
		),
		'related_taxonomy' => array(
			'type' => 'select',
			'options' => us_get_taxonomies(),
			'std' => 'category',
			'classes' => 'for_above',
			'show_if' => array( 'post_type', 'in', array( 'related', 'taxonomy_terms' ) ),
		),
		'ids' => array(
			'type' => 'autocomplete',
			'settings' => array(
				'multiple' => TRUE,
				'sortable' => TRUE,
				'unique_values' => TRUE,
			),
			'save_always' => TRUE,
			'classes' => 'for_above',
			'show_if' => array( 'post_type', '=', 'ids' ),
		),
		'images' => array(
			'title' => us_translate( 'Images' ),
			'type' => 'upload',
			'is_multiple' => TRUE,
			'extension' => 'png,jpg,jpeg,gif,svg',
			'show_if' => array( 'post_type', '=', 'attachment' ),
		),
		'ignore_sticky' => array(
			'type' => 'switch',
			'switch_text' => __( 'Ignore sticky posts', 'us' ),
			'std' => FALSE,
			'classes' => 'for_above',
			'show_if' => array( 'post_type', '=', 'post' ),
		),
		'products_include' => array(
			'type' => 'checkboxes',
			'options' => array(
				'sale' => us_translate( 'On-sale products', 'woocommerce' ),
				'featured' => us_translate( 'Featured products', 'woocommerce' ),
			),
			'std' => '',
			'classes' => 'for_above',
			'show_if' => array( 'post_type', '=', 'product' ),
		),
		'terms_include' => array(
			'type' => 'checkboxes',
			'options' => array(
				'children' => __( 'Show child terms', 'us' ),
				'empty' => __( 'Show empty', 'us' ),
			),
			'std' => '',
			'classes' => 'for_above',
			'show_if' => array( 'post_type', '=', array( 'taxonomy_terms', 'current_child_terms' ) ),
		),
	), $taxonomies_params, array(
		'orderby' => array(
			'title' => us_translate( 'Order' ),
			'type' => 'select',
			'options' => array_merge(
				array(
					'date' => __( 'By date of creation (newer first)', 'us' ),
					'date_asc' => __( 'By date of creation (older first)', 'us' ),
					'modified' => __( 'By date of update (newer first)', 'us' ),
					'modified_asc' => __( 'By date of update (older first)', 'us' ),
					'alpha' => __( 'Alphabetically', 'us' ),
					'rand' => us_translate( 'Random' ),
					'comment_count' => us_translate( 'Number of Comments' ),
					'menu_order' => sprintf( __( 'By "%s" values from "%s" box', 'us' ), us_translate( 'Order' ), us_translate( 'Page Attributes' ) ),
					'post__in' => __( 'Manually for images and specific items', 'us' ),
				), $products_orderby_values
			),
			'std' => 'date',
			'show_if' => array( 'post_type', '!=', array( 'current_query', 'taxonomy_terms', 'current_child_terms' ) ),
		),
		'terms_orderby' => array(
			'title' => us_translate( 'Order' ),
			'type' => 'select',
			'options' => array(
				'title' => __( 'Alphabetically', 'us' ),
				'rand' => us_translate( 'Random' ),
				'count' => __( 'Items Quantity', 'us' ),
				'name' => __( 'Manually, if available', 'us' ),
			),
			'std' => 'title',
			'cols' => 2,
			'show_if' => array( 'post_type', '=', array( 'taxonomy_terms', 'current_child_terms' ) ),
		),
		'items_quantity' => array(
			'title' => __( 'Items Quantity', 'us' ),
			'type' => 'text',
			'std' => '10',
			'cols' => 2,
			'show_if' => array( 'post_type', '!=', 'current_query' ),
		),
		'exclude_items' => array(
			'title' => __( 'Exclude Items', 'us' ),
			'type' => 'select',
			'options' => array_merge(
				array(
					'none' => us_translate( 'None' ),
					'prev' => __( 'of previous Grids on this page', 'us' ),
					'offset' => __( 'by the given quantity from the beginning of output', 'us' ),
				), $products_exclude_values
			),
			'std' => 'none',
			'cols' => 2,
			'show_if' => array( 'post_type', '!=', array( 'current_query', 'taxonomy_terms', 'current_child_terms' ) ),
		),
		'items_offset' => array(
			'title' => __( 'Items Quantity to skip', 'us' ),
			'type' => 'text',
			'std' => '1',
			'show_if' => array( 'exclude_items', '=', 'offset' ),
		),
		'no_items_message' => array(
			'title' => __( 'Message when no items', 'us' ),
			'type' => 'text',
			'std' => us_translate( 'No results found' ),
		),
		'pagination' => array(
			'title' => us_translate( 'Pagination' ),
			'type' => 'select',
			'options' => array(
				'none' => us_translate( 'None' ),
				'regular' => __( 'Numbered pagination', 'us' ),
				'ajax' => __( 'Load items on button click', 'us' ),
				'infinite' => __( 'Load items on page scroll', 'us' ),
			),
			'std' => 'none',
			'show_if' => array( 'post_type', '!=', array( 'taxonomy_terms', 'current_child_terms', 'product_upsells' ) ),
		),
		'pagination_btn_text' => array(
			'title' => __( 'Button Label', 'us' ),
			'type' => 'text',
			'std' => __( 'Load More', 'us' ),
			'cols' => 2,
			'show_if' => array( 'pagination', '=', 'ajax' ),
		),
		'pagination_btn_size' => array(
			'title' => __( 'Button Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 2,
			'show_if' => array( 'pagination', '=', 'ajax' ),
		),
		'pagination_btn_style' => array(
			'title' => __( 'Button Style', 'us' ),
			'description' => $misc['desc_btn_styles'],
			'type' => 'select',
			'options' => us_get_btn_styles(),
			'std' => '1',
			'show_if' => array( 'pagination', '=', 'ajax' ),
		),
		'pagination_btn_fullwidth' => array(
			'type' => 'switch',
			'switch_text' => __( 'Stretch to the full width', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'pagination', '=', 'ajax' ),
		),
	)
);

// Appearance
$appearance_params = array(
	'items_layout' => array(
		'title' => __( 'Grid Layout', 'us' ),
		'type' => 'us_grid_layout',
		'admin_label' => TRUE,
		'std' => 'blog_1',
		'group' => us_translate( 'Appearance' ),
	),
	'type' => array(
		'title' => __( 'Display as', 'us' ),
		'type' => 'select',
		'options' => array(
			'grid' => __( 'Regular Grid', 'us' ),
			'masonry' => __( 'Masonry', 'us' ),
		),
		'std' => 'grid',
		'admin_label' => TRUE,
		'group' => us_translate( 'Appearance' ),
	),
	'items_valign' => array(
		'switch_text' => __( 'Center items vertically', 'us' ),
		'type' => 'switch',
		'std' => FALSE,
		'classes' => 'for_above',
		'show_if' => array( 'type', '!=', 'masonry' ),
		'group' => us_translate( 'Appearance' ),
	),
	'columns' => array(
		'title' => us_translate( 'Columns' ),
		'type' => 'select',
		'options' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
		),
		'std' => '2',
		'admin_label' => TRUE,
		'cols' => 2,
		'group' => us_translate( 'Appearance' ),
	),
	'items_gap' => array(
		'title' => __( 'Gap between Items', 'us' ),
		'description' => __( 'Examples:', 'us' ) . ' <span class="usof-example">5px</span>, <span class="usof-example">1.5rem</span>, <span class="usof-example">2vw</span>',
		'type' => 'text',
		'std' => '1.5rem',
		'cols' => 2,
		'group' => us_translate( 'Appearance' ),
	),
	'img_size' => array(
		'title' => __( 'Post Image Size', 'us' ),
		'description' => $misc['desc_img_sizes'],
		'type' => 'select',
		'options' => array_merge(
			array( 'default' => __( 'As in Grid Layout', 'us' ) ), us_image_sizes_select_values()
		),
		'std' => 'default',
		'cols' => 2,
		'group' => us_translate( 'Appearance' ),
	),
	'title_size' => array(
		'title' => __( 'Post Title Size', 'us' ),
		'description' => $misc['desc_font_size'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'group' => us_translate( 'Appearance' ),
	),
	'overriding_link' => array(
		'title' => __( 'Overriding Link', 'us' ),
		'description' => __( 'Applies to every item of this Grid. All Grid Layout elements become not clickable.', 'us' ),
		'type' => 'select',
		'options' => array(
			'none' => us_translate( 'None' ),
			'post' => __( 'To a Post', 'us' ),
			'popup_post' => __( 'Opens a Post in a popup', 'us' ),
			'popup_post_image' => __( 'Opens a Post Image in a popup', 'us' ),
		),
		'std' => 'none',
		'group' => us_translate( 'Appearance' ),
	),
	'popup_width' => array(
		'title' => __( 'Popup Width', 'us' ),
		'description' => $misc['desc_width'],
		'type' => 'text',
		'std' => '',
		'show_if' => array( 'overriding_link', '=', 'popup_post' ),
		'group' => us_translate( 'Appearance' ),
	),
	'popup_arrows' => array(
		'switch_text' => __( 'Prev/Next arrows', 'us' ),
		'type' => 'switch',
		'std' => TRUE,
		'show_if' => array( 'overriding_link', '=', 'popup_post' ),
		'group' => us_translate( 'Appearance' ),
	),
);

// Filter
$filter_params = array_merge(
	$filter_taxonomies_params, array(
		'filter_style' => array(
			'title' => __( 'Filter Bar Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'style_1' => us_translate( 'Style' ) . ' 1',
				'style_2' => us_translate( 'Style' ) . ' 2',
				'style_3' => us_translate( 'Style' ) . ' 3',
			),
			'std' => 'style_1',
			'cols' => 2,
			'show_if' => array( 'post_type', 'in', array_keys( $known_post_type_taxonomies ) ),
			'group' => us_translate( 'Filter' ),
		),
		'filter_align' => array(
			'title' => __( 'Filter Bar Alignment', 'us' ),
			'type' => 'select',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'center',
			'cols' => 2,
			'show_if' => array( 'post_type', 'in', array_keys( $known_post_type_taxonomies ) ),
			'group' => us_translate( 'Filter' ),
		),
		'filter_show_all' => array(
			'switch_text' => __( 'Show "All" item in filter bar', 'us' ),
			'type' => 'switch',
			'std' => TRUE,
			'show_if' => array( 'post_type', 'in', array_keys( $known_post_type_taxonomies ) ),
			'group' => us_translate( 'Filter' ),
		),
	)
);

// Responsive Options
$responsive_params = array(
	'breakpoint_1_width' => array(
		'title' => __( 'Below screen width', 'us' ),
		'type' => 'text',
		'std' => '1200px',
		'cols' => 2,
		'group' => us_translate( 'Responsive Options', 'js_composer' ),
	),
	'breakpoint_1_cols' => array(
		'title' => __( 'show', 'us' ),
		'type' => 'select',
		'options' => $misc['column_values'],
		'std' => '3',
		'cols' => 2,
		'group' => us_translate( 'Responsive Options', 'js_composer' ),
	),
	'breakpoint_2_width' => array(
		'title' => __( 'Below screen width', 'us' ),
		'type' => 'text',
		'std' => '900px',
		'cols' => 2,
		'group' => us_translate( 'Responsive Options', 'js_composer' ),
	),
	'breakpoint_2_cols' => array(
		'title' => __( 'show', 'us' ),
		'type' => 'select',
		'options' => $misc['column_values'],
		'std' => '2',
		'cols' => 2,
		'group' => us_translate( 'Responsive Options', 'js_composer' ),
	),
	'breakpoint_3_width' => array(
		'title' => __( 'Below screen width', 'us' ),
		'type' => 'text',
		'std' => '600px',
		'cols' => 2,
		'group' => us_translate( 'Responsive Options', 'js_composer' ),
	),
	'breakpoint_3_cols' => array(
		'title' => __( 'show', 'us' ),
		'type' => 'select',
		'options' => $misc['column_values'],
		'std' => '1',
		'cols' => 2,
		'group' => us_translate( 'Responsive Options', 'js_composer' ),
	),
);

$grid_config['params'] = array_merge(
	$general_params, $appearance_params, $filter_params, $responsive_params, $design_options
);

return $grid_config;
