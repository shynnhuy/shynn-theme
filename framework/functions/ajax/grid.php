<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Ajax method for grids ajax pagination.
 */
add_action( 'wp_ajax_nopriv_us_ajax_grid', 'us_ajax_grid' );
add_action( 'wp_ajax_us_ajax_grid', 'us_ajax_grid' );
function us_ajax_grid() {

	if ( class_exists( 'WPBMap' ) AND method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
		WPBMap::addAllMappedShortcodes();
	}

	// Filtering $template_vars, as is will be extracted to the template as local variables
	$template_vars = shortcode_atts(
		array(
			'query_args' => array(),
			'post_id' => FALSE,
			'us_grid_index' => FALSE,
			'us_grid_ajax_index' => FALSE,
			'exclude_items' => 'none',
			'items_offset' => 0,
			'items_layout' => 'blog_1',
			'type' => 'grid',
			'columns' => 2,
			'img_size' => 'default',
			'lang' => FALSE,
			'overriding_link' => 'none',
		), us_maybe_get_post_json( 'template_vars' )
	);

	if ( class_exists( 'SitePress' ) AND $template_vars['lang'] ) {
		global $sitepress;
		$sitepress->switch_lang( $template_vars['lang'] );
	}

	$post_id = isset( $template_vars['post_id'] ) ? intval( $template_vars['post_id'] ) : 0;
	if ( $post_id > 0 ) {
		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			wp_send_json_error();
		}

		$us_grid_ajax_index = isset( $template_vars['us_grid_ajax_index'] ) ? intval( $template_vars['us_grid_ajax_index'] ) : 1;

		// Retrieving the relevant shortcode from the page to get options
		$post_content = $post->post_content;

		preg_match_all( '~\[us_grid(.*?)\]~', $post_content, $matches );

		if ( ! isset( $matches[0][ $us_grid_ajax_index - 1 ] ) ) {
			wp_send_json_error();
		}

		// Getting the relevant shortcode options
		$shortcode = $matches[0][ $us_grid_ajax_index - 1 ];
		// For proper shortcode_parse_atts behaviour
		$shortcode = substr_replace( $shortcode, ' ]', - 1 );
		$shortcode_atts = shortcode_parse_atts( $shortcode );

		$shortcode_atts = shortcode_atts(
			array(
				'post_type' => '',
			), $shortcode_atts
		);

		if ( $shortcode_atts['post_type'] == '' ) {
			$shortcode_atts['post_type'] = 'post';
		}

		$allowed_post_types = ( $shortcode_atts['post_type'] == 'current_query' ) ? NULL : array( $shortcode_atts['post_type'] );

		if ( $shortcode_atts['post_type'] == 'current_query'
			AND $template_vars['query_args']['post_type'] == 'product'
			AND ! empty( $template_vars['query_args']['orderby'] ) ) {
			$add_wc_hooks = TRUE;
		}
	}

	// Filtering query_args
	if ( isset( $template_vars['query_args'] ) AND is_array( $template_vars['query_args'] ) ) {
		// Query Args keys, that won't be filtered
		$allowed_query_keys = array(
			// Grid listing shortcode requests
			'author_name',
			'us_portfolio_category',
			'us_portfolio_tag',
			'category_name',
			'tax_query',
			// Archive requests
			'year',
			'monthnum',
			'day',
			'cat',
			'tag',
			'product_cat',
			'product_tag',
			// Search requests
			's',
			// Pagination
			'paged',
			'order',
			'orderby',
			'posts_per_page',
			'post__not_in',
			'post__in',
			// For excluding 'out of stock' products
			'meta_query',
			// For products sorting
			'order',
			'meta_key',
			// Custom users' queries
			'post_type',
		);
		$public_cpt = array_keys( us_get_public_cpt() );
		if ( ! isset( $allowed_post_types ) OR ! is_array( $allowed_post_types ) ) {
			// TODO: make static part of this list to be detected dynamically
			$allowed_post_types = array_merge(
				array( 'us_portfolio', 'post', 'attachment', 'page', 'product' ), $public_cpt
			);
		}

		foreach ( $template_vars['query_args'] as $query_key => $query_val ) {
			if ( ! in_array( $query_key, $allowed_query_keys ) ) {
				unset( $template_vars['query_args'][ $query_key ] );
			}
		}
		if ( isset( $template_vars['query_args']['post_type'] ) ) {
			$is_allowed_post_type = TRUE;
			if ( is_array( $template_vars['query_args']['post_type'] ) ) {
				foreach ( $template_vars['query_args']['post_type'] as $post_type ) {
					if ( ! in_array( $post_type, $allowed_post_types ) ) {
						$is_allowed_post_type = FALSE;
						break;
					}
				}
			} elseif ( ! in_array( $template_vars['query_args']['post_type'], $allowed_post_types ) ) {
				$is_allowed_post_type = FALSE;
			}

			if ( ! $is_allowed_post_type ) {
				unset( $template_vars['query_args']['post_type'] );
			}
		}
		if ( ! isset( $template_vars['query_args']['s'] ) AND ! isset( $template_vars['query_args']['post_type'] ) ) {
			$template_vars['query_args']['post_type'] = 'post';
		}
		// Providing proper post statuses
		if ( ! empty( $post_type ) AND ( $template_vars['query_args']['post_type'] == 'attachment' ) OR ( is_array( $template_vars['query_args']['post_type'] ) AND in_array( 'attachment', $template_vars['query_args']['post_type'] ) AND count( $template_vars['query_args']['post_type'] ) == 1 ) ) {
			$template_vars['query_args']['post_status'] = 'inherit';
			$template_vars['query_args']['post_mime_type'] = 'image';
		} else {
			$template_vars['query_args']['post_status'] = array( 'publish' => 'publish' );
			$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'public' => TRUE ) );
			// Add private states if user is capable to view them
			if ( is_user_logged_in() AND current_user_can( 'read_private_posts' ) ) {
				$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'private' => TRUE ) );
			}
			$template_vars['query_args']['post_status'] = array_values( $template_vars['query_args']['post_status'] );
		}

		// Exclude sticky posts from rand query after 1 page
		if ( isset( $template_vars['query_args']['orderby'] ) AND ! ( is_array( $template_vars['query_args']['orderby'] ) ) ) {
			if ( ( substr( $template_vars['query_args']['orderby'], 0, 4 ) == 'rand' ) AND ( $template_vars['query_args']['paged'] > '1' ) ) {
				$sticky_posts = get_option( 'sticky_posts' );
				$template_vars['query_args']['ignore_sticky_posts'] = TRUE;
				foreach ( $sticky_posts as $post_id ) {
					$template_vars['query_args']['post__not_in'][] = $post_id;
				}
			}
		}
	}

	// Passing values that were filtered due to post protocol
	global $us_grid_loop_running;
	$us_grid_loop_running = TRUE;
	// Apply WooCommerce product ordering if set
	if ( ! empty( $add_wc_hooks ) AND class_exists( 'woocommerce' ) AND is_object( wc() ) ) {
		$_GET['orderby'] = $template_vars['query_args']['orderby'];
		add_action( 'pre_get_posts', array( wc()->query, 'product_query' ) );
	}
	us_load_template( 'templates/us_grid/listing', $template_vars );
	$us_grid_loop_running = FALSE;

	// We don't use JSON to reduce data size
	die;
}
