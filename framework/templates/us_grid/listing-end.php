<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Closing part of Grid output
 */

$us_grid_index = isset( $us_grid_index ) ? intval( $us_grid_index ) : 0;
$post_id = isset( $post_id ) ? $post_id : NULL;
$filter_html = isset( $filter_html ) ? $filter_html : '';
$is_widget = isset( $is_widget ) ? $is_widget : FALSE;

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

if ( ( ! $is_widget ) AND ( $post_id != NULL ) AND ( $type != 'carousel' ) ) {
	$us_grid_ajax_indexes[ $post_id ] = isset( $us_grid_ajax_indexes[ $post_id ] ) ? ( $us_grid_ajax_indexes[ $post_id ] ) : 1;
} else {
	$us_grid_ajax_indexes = NULL;
}

// Global preloader type
$preloader_type = us_get_option( 'preloader' );
if ( ! in_array( $preloader_type, array_merge( us_get_preloader_numeric_types(), array( 'custom' ) ) ) ) {
	$preloader_type = 1;
}
$preloader_image = us_get_option( 'preloader_image' );
$preloader_image_html = '';
$img = usof_get_image_src( $preloader_image, 'medium' );
if ( $preloader_type == 'custom' AND $img[0] != '' ) {
	$preloader_image_html .= '<img src="' . esc_url( $img[0] ) . '"';
	if ( ! empty( $img[1] ) AND ! empty( $img[2] ) ) {
		// Image sizes may be missing when logo is a direct URL
		$preloader_image_html .= ' width="' . $img[1] . '" height="' . $img[2] . '"';
	}
	$preloader_image_html .= ' alt="' . us_get_image_alt( $preloader_image ) . '"/>';
}
echo '</div>';

// Output preloader for Carousel and Filter
if ( $filter_html != '' ) {
	?>
	<div class="w-grid-preloader">
		<div class="g-preloader type_<?php echo $preloader_type; ?>">
			<div><?php echo $preloader_image_html ?></div>
		</div>
	</div>
	<?php
} elseif ( $type == 'carousel' ) {
	?>
	<div class="g-preloader type_<?php echo $preloader_type; ?>">
		<div><?php echo $preloader_image_html ?></div>
	</div>
	<?php
}

// Output pagination for not Carousel type
if ( $wp_query->max_num_pages > 1 AND $type != 'carousel' ) {

	// Next page elements may have sliders, so we preloading the needed assets now
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-royalslider' );
	}

	if ( $pagination == 'infinite' ) {
		$is_infinite = TRUE;
		$pagination = 'ajax';
	}
	if ( $pagination == 'regular' ) {
		the_posts_pagination(
			array(
				'mid_size' => 3,
				'before_page_number' => '<span>',
				'after_page_number' => '</span>',
			)
		);
	} elseif ( $pagination == 'ajax' ) {
		$pagination_btn_css = us_prepare_inline_css( array( 'font-size' => $pagination_btn_size ) );
		if ( $pagination_btn_fullwidth ) {
			$loadmore_classes = ' width_full';
		} else {
			$loadmore_classes = '';
		}
		?>
		<div class="g-loadmore<?php echo $loadmore_classes; ?>">
			<div class="g-preloader type_<?php echo $preloader_type; ?>">
				<div></div>
			</div>
			<a class="w-btn us-btn-style_<?php echo $pagination_btn_style ?>"<?php echo $pagination_btn_css ?> href="javascript:void(0)">
				<span class="w-btn-label"><?php echo $pagination_btn_text ?></span>
			</a>
		</div>
		<?php
	}
}

// Define and output all JSON data
$json_data = array(

	// Controller options
	'ajax_url' => admin_url( 'admin-ajax.php' ),
	'permalink_url' => get_permalink(),
	'action' => 'us_ajax_grid',
	'max_num_pages' => $wp_query->max_num_pages,
	'infinite_scroll' => ( isset( $is_infinite ) ? $is_infinite : 0 ),

	// Grid listing template variables that will be passed to this file in the next call
	'template_vars' => array(
		'query_args' => $query_args,
		'post_id' => $post_id,
		'us_grid_index' => $us_grid_index,
		'us_grid_ajax_index' => $us_grid_ajax_indexes[ $post_id ],
		'exclude_items' => $exclude_items,
		'items_offset' => $items_offset,
		'items_layout' => $items_layout,
		'type' => $type,
		'columns' => $columns,
		'img_size' => $img_size,
		'overriding_link' => $overriding_link,
	),
);

// Carousel settings
if ( $type == 'carousel' ) {
	$json_data = array_merge( $json_data, array(
		'carousel_settings' => array(
			'items' => $columns,
			'nav' => intval( ! ! $carousel_arrows ),
			'navNext' => '',
			'navPrev' => '',
			'dots' => intval( ! ! $carousel_dots ),
			'center' => intval( ! ! $carousel_center ),
			'autoplay' => ( $carousel_autoplay AND ( $items_count > $columns ) ) ? 1 : 0,
			'smooth_play' => intval( ! ! $carousel_autoplay_smooth ),
			'timeout' => intval( $carousel_interval * 1000 ),
			'speed' => intval( $carousel_speed ),
			'transition' => strip_tags( $carousel_transition ),
			'autoHeight' => ( $columns == 1 ) ? intval( $carousel_autoheight ) : 0,
			'slideby' => ( $carousel_slideby ? 'page' : '1' ),
		),
		'carousel_breakpoints' => array(
			intval( $breakpoint_1_width ) => array(
				'items' => intval( $columns ),
			),
			intval( $breakpoint_2_width ) => array(
				'items' => min( intval( $breakpoint_1_cols ), $columns ),
				'autoplay' => intval( ! ! $breakpoint_1_autoplay ),
				'autoplayHoverPause' => intval( ! ! $breakpoint_1_autoplay ),
			),
			intval( $breakpoint_3_width ) => array(
				'items' => min( intval( $breakpoint_2_cols ), $columns ),
				'autoplay' => intval( ! ! $breakpoint_2_autoplay ),
				'autoplayHoverPause' => intval( ! ! $breakpoint_2_autoplay ),
			),
			0 => array(
				'items' => min( intval( $breakpoint_3_cols ), $columns ),
				'autoplay' => intval( ! ! $breakpoint_3_autoplay ),
				'autoplayHoverPause' => intval( ! ! $breakpoint_3_autoplay ),
				'autoHeight' => ( $carousel_autoheight AND ! $breakpoint_3_autoplay ) ? 1 : 0,
			),
		),
	)
	);
}

// Add lang variable if WPML is active
if ( class_exists( 'SitePress' ) ) {
	global $sitepress;
	if ( $sitepress->get_default_language() != $sitepress->get_current_language() ) {
		$json_data['template_vars']['lang'] = $sitepress->get_current_language();
	}
}
?>
	<div class="w-grid-json hidden"<?php echo us_pass_data_to_js( $json_data ) ?>></div>
<?php

// Output popup semantics
if ( $overriding_link == 'popup_post' ) {
	?>
	<div class="l-popup">
		<div class="l-popup-overlay"></div>
		<div class="l-popup-wrap">
			<div class="l-popup-box">
				<div class="l-popup-box-content"<?php echo us_prepare_inline_css( array( 'max-width' => $popup_width ) ); ?>>
					<div class="g-preloader type_<?php echo $preloader_type; ?>">
						<div><?php echo $preloader_image_html ?></div>
					</div>
					<iframe class="l-popup-box-content-frame" allowfullscreen></iframe>
				</div>
			</div>
			<?php if ( $popup_arrows ) { ?>
				<div class="l-popup-arrow to_next" title="Next"></div>
				<div class="l-popup-arrow to_prev" title="Previous"></div>
			<?php } ?>
			<div class="l-popup-closer"></div>
		</div>
	</div>
	<?php
}

echo '</div>';