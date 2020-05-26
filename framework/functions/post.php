<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Retrieves and returns the part of current post that can be used as the post's preview.
 *
 * (!) Should be called in WP_Query fetching loop only.
 *
 * @param string $the_content Post content, retrieved with get_the_content() (without 'the_content' filters)
 * @param bool $strip_from_the_content Should the found element be removed from post content not to be duplicated?
 *
 * @return string
 */
function us_get_post_preview( &$the_content, $strip_from_the_content = FALSE ) {
	// Retrieving post format
	$post_format = get_post_format() ? get_post_format() : 'standard';
	$preview_html = '';

	global $us_post_img_ratio;
	if ( ! empty( $us_post_img_ratio ) ) {
		$video_h_style = ' style="padding-bottom:' . $us_post_img_ratio . '%;"';
	} else {
		$video_h_style = '';
	}

	// Retrieving post preview
	if ( $post_format == 'gallery' ) {
		if ( preg_match( '~\[us_image_slider.+?\]|\[gallery.+?\]~', $the_content, $matches ) ) {

			// Replacing with a simple image slider
			$gallery = preg_replace( '~(vc_gallery|gallery)~', 'us_image_slider', $matches[0] );

			global $us_post_slider_size;
			if ( ! empty( $us_post_slider_size ) ) {
				if ( preg_match( '~img_size=\"[^"]+"~', $gallery ) ) {
					$gallery = preg_replace( '~img_size=\"[^"]+"~', 'img_size="' . $us_post_slider_size . '"', $gallery );
				} else {
					$gallery = str_replace( '[us_image_slider', '[us_image_slider img_size="' . $us_post_slider_size . '"', $gallery );
				}

			}
			$preview_html = do_shortcode( $gallery );

			if ( $strip_from_the_content ) {
				$the_content = str_replace( $matches[0], '', $the_content );
			}
		} elseif ( preg_match( '~\[us_grid.+post_type="attachment".+?\]~', $the_content, $matches ) ) {
			// Replacing with a simple image slider
			$gallery = preg_replace( '~us_grid.+post_type="attachment".+images="([^"]+)([^]]+)~', 'us_image_slider ids=$1', $matches[0] );

			global $us_post_slider_size;
			if ( ! empty( $us_post_slider_size ) ) {
				$gallery = str_replace( '[us_image_slider', '[us_image_slider img_size="' . $us_post_slider_size . '"', $gallery );
			}
			$preview_html = do_shortcode( $gallery );

			if ( $strip_from_the_content ) {
				$the_content = str_replace( $matches[0], '', $the_content );
			}
		}

	} elseif ( $post_format == 'video' ) {
		$post_content = preg_replace( '~^\s*(https?://[^\s"]+)\s*$~im', "[embed]$1[/embed]", $the_content );

		if ( preg_match( '~\[embed.+?\]|\[vc_video.+?\]~', $post_content, $matches ) ) {

			global $wp_embed;
			$video = $matches[0];
			$preview_html = do_shortcode( $wp_embed->run_shortcode( $video ) );
			if ( strpos( $preview_html, 'w-video' ) === FALSE ) {
				$preview_html = '<div class="w-video"><div class="w-video-h"' . $video_h_style . '>' . $preview_html . '</div></div>';
			}
			$post_content = str_replace( $matches[0], "", $post_content );
		}
		if ( ! empty( $preview_html ) AND $strip_from_the_content ) {
			$the_content = $post_content;
		}

	} elseif ( $post_format == 'audio' ) {
		$post_content = preg_replace( '~^\s*(https?://[^\s"]+)\s*$~im', "[embed]$1[/embed]", $the_content );

		if ( preg_match( '~\[audio.+?\]\[\/audio\]~', $post_content, $matches ) ) {
			$audio = $matches[0];
			$preview_html = do_shortcode( $audio );

			$post_content = str_replace( $matches[0], "", $post_content );
		} elseif ( preg_match( '~\[embed.+?\]~', $post_content, $matches ) ) {

			global $wp_embed;
			$video = $matches[0];
			$preview_html = do_shortcode( $wp_embed->run_shortcode( $video ) );
			if ( strpos( $preview_html, 'w-video' ) === FALSE ) {
				$preview_html = '<div class="w-video"><div class="w-video-h"' . $video_h_style . '>' . $preview_html . '</div></div>';
			}
			$post_content = str_replace( $matches[0], "", $post_content );
		}
		if ( ! empty( $preview_html ) AND $strip_from_the_content ) {
			$the_content = $post_content;
		}
	}

	$preview_html = apply_filters( 'us_get_post_preview', $preview_html, get_the_ID() );

	return $preview_html;
}

/**
 * Get URL for link post format
 *
 * @param $the_content
 * @param bool|FALSE $strip_from_the_content
 */
function us_get_post_format_link_url( $url, $post ) {

	if ( get_post_format( $post->ID ) != 'link' ) {
		return $url;
	}

	$post_content = $post->post_content;
	$link = '';

	if ( preg_match( '$(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|]$i', $post_content, $matches ) ) {
		$link = $matches[0];
	}

	if ( $link != '' ) {
		//$post->post_content = str_replace( $link, "", $post->post_content );
		return $link;
	}

	return $url;
}

add_filter( 'post_link', 'us_get_post_format_link_url', 10, 3 );

/**
 * Get information about previous and next post or page (should be used in singular element context)
 *
 * @return array
 */
function us_get_post_prevnext( $invert = FALSE, $in_same_term = FALSE, $taxonomy = 'category' ) {
	$prev = $next = array();

	// Exclude posts with "Link" format
	if ( is_singular( 'post' ) ) {
		global $us_post_prevnext_exclude_ids;
		if ( $us_post_prevnext_exclude_ids === NULL ) {
			global $wpdb;
			$wpdb_query = 'SELECT `object_id` FROM `' . $wpdb->terms . '`, `' . $wpdb->term_relationships . '` ';
			$wpdb_query .= 'WHERE (`slug`=\'post-format-link\' AND `term_id`=`term_taxonomy_id`)';
			$us_post_prevnext_exclude_ids = apply_filters( 'us_get_post_prevnext_exclude_ids', $wpdb->get_col( $wpdb_query ) );
			if ( ! empty( $us_post_prevnext_exclude_ids ) ) {
				add_filter( 'get_next_post_where', 'us_exclude_post_format_link_from_prevnext' );
				add_filter( 'get_previous_post_where', 'us_exclude_post_format_link_from_prevnext' );
			}
		}
	}

	$next_post = get_next_post( $in_same_term, '', $taxonomy );
	$prev_post = get_previous_post( $in_same_term, '', $taxonomy );

	if ( ! empty( $prev_post ) ) {
		$prev = array(
			'id' => $prev_post->ID,
			'link' => get_permalink( $prev_post->ID ),
			'title' => get_the_title( $prev_post->ID ),
			'meta' => us_translate( 'Previous Post' ),
		);
	}
	if ( ! empty( $next_post ) ) {
		$next = array(
			'id' => $next_post->ID,
			'link' => get_permalink( $next_post->ID ),
			'title' => get_the_title( $next_post->ID ),
			'meta' => us_translate( 'Next Post' ),
		);
	}

	return ( $invert ) ? array( 'next' => $next, 'prev' => $prev ) : array( 'prev' => $prev, 'next' => $next );
}

function us_exclude_post_format_link_from_prevnext( $where ) {
	global $us_post_prevnext_exclude_ids;
	if ( ! empty( $us_post_prevnext_exclude_ids ) AND is_array( $us_post_prevnext_exclude_ids ) ) {
		$where .= ' AND p.ID NOT IN (' . implode( ',', $us_post_prevnext_exclude_ids ) . ')';
	}

	return $where;
}

// Display specific page when Maintenance Mode is enabled in Theme Options
add_action( 'init', 'us_maintenance_mode' );
function us_maintenance_mode() {
	if ( is_user_logged_in() ) {
		add_action( 'admin_bar_menu', 'us_maintenance_admin_bar_menu', 1000 );

		return FALSE;
	}
	if ( us_get_option( 'maintenance_mode' ) AND us_get_option( 'maintenance_page' ) ) {
		$maintenance_page = get_post( us_get_option( 'maintenance_page' ) );
		if ( $maintenance_page ) {
			if ( function_exists( 'bp_is_active' ) ) {
				add_action( 'template_redirect', 'us_display_maintenance_page', 9 );
			} else {
				add_action( 'template_redirect', 'us_display_maintenance_page' );
			}
		}
	}
}

// Show indication in admin bar when Maintenance Mode is enabled
function us_maintenance_admin_bar_menu( $wp_admin_bar ) {
	if ( us_get_option( 'maintenance_mode' ) AND us_get_option( 'maintenance_page' ) ) {
		$maintenance_page = get_post( us_get_option( 'maintenance_page' ) );
		if ( $maintenance_page ) {

			$wp_admin_bar->add_node(
				array(
					'id' => 'us-maintenance-notice',
					'href' => admin_url() . 'admin.php?page=us-theme-options',
					'title' => __( 'Maintenance Mode', 'us' ),
					'meta' => array(
						'class' => 'us-maintenance',
						'html' => '<style>.us-maintenance a{font-weight:600!important;color:#f90!important;}</style>',
					),
				)
			);
		}
	}
}

// Show specified page when Maintenance Mode is enabled
function us_display_maintenance_page() {
	$maintenance_page = get_post( us_get_option( 'maintenance_page' ) );

	if ( $maintenance_page ) {
		if ( class_exists( 'SitePress' ) ) {
			$maintenance_page = get_post( apply_filters( 'wpml_object_id', $maintenance_page->ID, 'page', TRUE ) );
		}
		us_open_wp_query_context();
		global $wp_query;
		$wp_query = new WP_Query(
			array(
				'p' => $maintenance_page->ID,
				'post_type' => 'page',
			)
		);
		the_post();

		if ( us_get_option( 'maintenance_503', 1 ) == 1 ) {
			header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
			header( 'Status: 503 Service Temporarily Unavailable' );
			header( 'Retry-After: 86400' ); // retry in a day
		}

		$us_layout = US_Layout::instance();
		$us_layout->header_show = 'never';

		get_header();
		?>
		<main class="l-main"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
			<?php
			do_action( 'us_before_page' );

			$the_content = apply_filters( 'the_content', $maintenance_page->post_content );

			echo $the_content;

			do_action( 'us_after_page' );
			?>
		</main>
		<?php
		global $us_hide_footer;
		$us_hide_footer = TRUE;

		get_footer();
		us_close_wp_query_context();
		exit();
	}
}

/**
 * Get list of posts titles by a certain post type
 * @param string $post_type Post type to get
 * @param bool $force_no_cache Allow using cache (use FALSE to force not-cached version)
 * @return array
 */
function us_get_posts_titles_for( $post_type, $orderby = 'title', $force_no_cache = TRUE ) {
	// Caching results
	static $result = array();
	if ( ! isset( $result[ $post_type ] ) OR $force_no_cache ) {
		$result[ $post_type ] = array();
		$get_posts_args = array(
			'post_type' => $post_type,
			'posts_per_page' => - 1,
			'post_status' => 'any',
			'suppress_filters' => 0,
		);
		if ( ! empty( $orderby ) AND $orderby == 'title' ) {
			$get_posts_args['orderby'] = 'title';
			$get_posts_args['order'] = 'ASC';
		}
		$posts = get_posts( $get_posts_args );
		foreach ( $posts as $post ) {
			if ( $post->post_title != '' ) {
				$result[ $post_type ][ $post->ID ] = $post->post_title;
			} else {
				$result[ $post_type ][ $post->ID ] = us_translate( '(no title)' );
			}
		}
	}

	return $result[ $post_type ];
}