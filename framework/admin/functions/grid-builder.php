<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

add_filter( 'usof_container_classes', 'usgb_usof_container_classes' );
function usgb_usof_container_classes( $classes ) {
	return $classes . ' with_gb';
}

add_action( 'init', 'usgb_create_post_types', 7 );
function usgb_create_post_types() {

	// Grid Layout post type
	register_post_type(
		'us_grid_layout', array(
			'labels' => array(
				'name' => __( 'Grid Layouts', 'us' ),
				'singular_name' => __( 'Grid Layout', 'us' ),
				'add_new' => __( 'Add Grid Layout', 'us' ),
				'add_new_item' => __( 'Add Grid Layout', 'us' ),
			),
			'public' => TRUE,
			'show_in_menu' => 'us-theme-options',
			'exclude_from_search' => TRUE,
			'show_in_admin_bar' => FALSE,
			'publicly_queryable' => FALSE,
			'show_in_nav_menus' => FALSE,
			'capability_type' => array( 'us_page_block', 'us_page_blocks' ),
			'map_meta_cap' => TRUE,
			'supports' => FALSE,
			'rewrite' => FALSE,
			'register_meta_box_cb' => 'usgb_us_grid_type_pages',
		)
	);

	// Add "Used in" column into Grid Layouts admin page
	add_filter( 'manage_us_grid_layout_posts_columns', 'ushb_us_grid_layout_columns_head' );
	add_action( 'manage_us_grid_layout_posts_custom_column', 'ushb_us_grid_layout_columns_content', 10, 2 );
	function ushb_us_grid_layout_columns_head( $defaults ) {
		$result = array();
		foreach ( $defaults as $key => $title ) {
			if ( $key == 'date' ) {
				$result['used_in'] = __( 'Used in', 'us' );
			}
			$result[$key] = $title;
		}
		return $result;
	}
	function ushb_us_grid_layout_columns_content( $column_name, $post_ID ) {
		if ( $column_name == 'used_in' ) {
			echo us_get_used_in_locations( $post_ID );
		}
	}

}

function usgb_us_grid_type_pages() {
	global $post;
	// Dev note: This check is not necessary, but still wanted to make sure this function won't be bound somewhere else
	if ( ! ( $post instanceof WP_Post ) OR $post->post_type !== 'us_grid_layout' ) {
		return;
	}
	if ( $post->post_status === 'auto-draft' ) {
		// Page for creating new grid: creating it instantly and proceeding to editing
		$post_data = array( 'ID' => $post->ID );
		// Retrieving occupied names to generate new post title properly
		$existing_grids = usgb_get_existing_grids();
		if ( isset( $_GET['duplicate_from'] ) AND ( $original_post = get_post( (int) $_GET['duplicate_from'] ) ) !== NULL ) {
			// Handling post duplication
			$post_data['post_content'] = $original_post->post_content;
			$title_pattern = $original_post->post_title . ' (%d)';
			$cur_index = 2;
		} else {
			// Handling creation from scratch
			$title_pattern = __( 'Layout', 'us' ) . ' %d';
			$cur_index = count( $existing_grids ) + 1;
		}
		// Generating new post title
		while ( in_array( $post_data['post_title'] = sprintf( $title_pattern, $cur_index ), $existing_grids ) ) {
			$cur_index ++;
		}
		wp_update_post( $post_data );
		wp_publish_post( $post->ID );
		// Redirect
		if ( isset( $_GET['duplicate_from'] ) ) {
			// When duplicating post, showing posts list next
			wp_redirect( admin_url( 'edit.php?post_type=us_grid_layout' ) );
		} else {
			// When creating from scratch proceeding to post editing next
			wp_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
		}
	} else {
		// Page for editing a grid
		add_action( 'admin_enqueue_scripts', 'usgb_enqueue_scripts' );
		add_action( 'edit_form_top', 'usgb_edit_form_top' );
	}
}

/**
 * Get available grids
 * @return array
 */
function usgb_get_existing_grids() {
	$result = array();
	$grids = get_posts(
		array(
			'post_type' => 'us_grid_layout',
			'posts_per_page' => - 1,
			'post_status' => 'any',
			'suppress_filters' => 0,
		)
	);
	foreach ( $grids as $grid ) {
		$result[$grid->ID] = $grid->post_title;
	}

	return $result;
}

function usgb_enqueue_scripts() {
	// Appending dependencies
	usof_print_styles();
	usof_print_scripts();

	// Appending required assets
	global $us_template_directory_uri;
	wp_enqueue_script( 'us-grid-builder', $us_template_directory_uri . '/framework/admin/js/grid-builder.js', array( 'usof-scripts' ), TRUE );

	// Disabling WP auto-save
	wp_dequeue_script( 'autosave' );
}

function usgb_edit_form_top( $post ) {
	echo '<div class="usof-container type_builder" data-ajaxurl="' . esc_attr( admin_url( 'admin-ajax.php' ) ) . '" data-id="' . esc_attr( $post->ID ) . '">';
	echo '<form class="usof-form" method="post" action="#" autocomplete="off">';
	// Output _nonce and _wp_http_referer hidden fields for ajax secuirity checks
	wp_nonce_field( 'usgb-update' );
	echo '<div class="usof-header">';
	echo '<div class="usof-header-title">' . __( 'Grid Layout', 'us' ) . '</div>';

	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => 'post_title',
			'id' => 'usof_header_title',
			'field' => array(
				'type' => 'text',
				'placeholder' => __( 'Grid Layout Name', 'us' ),
				'classes' => 'desc_0', // Reset desc position of global GB field
			),
			'values' => array(
				'post_title' => $post->post_title,
			),
		)
	);

	echo '<div class="usof-control for_help"><a href="https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/grid/" target="_blank" title="' . us_translate( 'Help' ) . '"></a></div>';
	echo '<div class="usof-control for_import"><a href="#" title="' . __( 'Export / Import', 'us' ) . '"></a></div>';
	echo '<div class="usof-control for_templates">';
	echo '<a href="#" title="' . __( 'Grid Layout Templates', 'us' ) . '"></a>';
	echo '<div class="usof-control-desc"><span>' . __( 'Choose Grid Layout Template to start with', 'us' ) . '</span></div>';
	echo '</div>';
	echo '<div class="usof-control for_save status_clear">';
	echo '<button class="usof-button type_save" type="button"><span>' . us_translate( 'Save Changes' ) . '</span>';
	echo '<span class="usof-preloader"></span></button>';
	echo '<div class="usof-control-message"></div></div></div>';

	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => 'post_content',
			'id' => 'usof_header',
			'field' => array(
				'type' => 'grid_builder',
				'classes' => 'desc_0', // Reset desc position of global GB field
			),
			'values' => array(
				'post_content' => $post->post_content,
			),
		)
	);

	echo '</form>';
	echo '</div>';
}

// Add "Duplicate" link for Grid Layouts admin page
add_filter( 'post_row_actions', 'usgb_post_row_actions', 10, 2 );
function usgb_post_row_actions( $actions, $post ) {
	if ( $post->post_type === 'us_grid_layout' ) {
		// Removing duplicate post plugin affection
		unset( $actions['duplicate'], $actions['edit_as_new_draft'] );
		$actions = us_array_merge_insert(
			$actions, array(
			'duplicate' => '<a href="' . admin_url( 'post-new.php?post_type=us_grid_layout&duplicate_from=' . $post->ID ) . '" aria-label="' . esc_attr__( 'Duplicate', 'us' ) . '">' . esc_html__( 'Duplicate', 'us' ) . '</a>',
		), 'before', isset( $actions['trash'] ) ? 'trash' : 'untrash'
		);
	}

	return $actions;
}
