<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * WooCommerce Theme Support
 *
 * @link http://www.woothemes.com/woocommerce/
 */

add_action( 'after_setup_theme', 'us_woocommerce_support' );
function us_woocommerce_support() {
	add_theme_support(
		'woocommerce', array(
			'gallery_thumbnail_image_width' => 150, // changed gallery thumbnail size to default WP 'thumbnail'
		)
	);
	if ( in_array( 'zoom', us_get_option( 'product_gallery', array() ) ) ) {
		add_theme_support( 'wc-product-gallery-zoom' );
	}
	if ( in_array( 'lightbox', us_get_option( 'product_gallery', array() ) ) ) {
		add_theme_support( 'wc-product-gallery-lightbox' );
	}
	if ( in_array( 'slider', us_get_option( 'product_gallery', array() ) ) ) {
		add_theme_support( 'wc-product-gallery-slider' );
	}
}

if ( ! class_exists( 'woocommerce' ) ) {
	return FALSE;
}

// Change size of "Product gallery" thumbnails, when "Slider" is OFF, for showing like 1 column gallery
if ( ! in_array( 'slider', us_get_option( 'product_gallery', array() ) ) ) {
	add_filter( 'woocommerce_gallery_thumbnail_size', 'us_woocommerce_gallery_thumbnail_size' );
	function us_woocommerce_gallery_thumbnail_size() {
		return 'woocommerce_single';
	}
}

// Disable WooCommerce front CSS
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// Disable select2 CSS on Checkout page
add_action( 'wp_enqueue_scripts', 'us_woocomerce_dequeue_checkout_styles', 100 );
function us_woocomerce_dequeue_checkout_styles() {
	wp_dequeue_style( 'select2' );
	wp_deregister_style( 'select2' );
}

// Enqueue theme CSS
if ( ! ( defined( 'US_DEV' ) AND US_DEV ) AND us_get_option( 'optimize_assets', 0 ) == 0 ) {
	add_action( 'wp_enqueue_scripts', 'us_woocommerce_enqueue_styles', 14 );
}
function us_woocommerce_enqueue_styles( $styles ) {
	global $us_template_directory_uri;
	$min_ext = ( ! ( defined( 'US_DEV' ) AND US_DEV ) ) ? '.min' : '';
	wp_enqueue_style( 'us-woocommerce', $us_template_directory_uri . '/css/plugins/woocommerce' . $min_ext . '.css', array(), US_THEMEVERSION, 'all' );
}

// Add classes to <body> of WooCommerce pages
add_action( 'body_class', 'us_wc_body_class' );
function us_wc_body_class( $classes ) {
	$classes[] = 'us-woo-cart_' . us_get_option( 'shop_cart', 'standard' );
	if ( us_get_option( 'shop_catalog', 0 ) == 1 ) {
		$classes[] = 'us-woo-catalog';
	}

	return $classes;
}

/*
*************** Adjust HTML markup for all WooCommerce pages ***************
*/
add_action( 'template_redirect', 'us_maybe_change_woocommerce_template_path' );
function us_maybe_change_woocommerce_template_path() {
	if ( ( ( is_shop() OR is_product_tag() OR is_product_category() ) AND us_get_option( 'content_shop_id', '' ) != '' )
		OR ( is_product_category() AND us_get_option( 'content_tax_product_cat_id', '__defaults__' ) != '__defaults__' )
		OR ( is_product_tag() AND us_get_option( 'content_tax_product_tag_id', '__defaults__' ) != '__defaults__' ) ) {

		add_filter( 'woocommerce_template_path', 'us_woocommerce_template_path' );
		function us_woocommerce_template_path() {
			return 'framework/plugins-support/woocommerce/';
		}
	}

}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
if ( ! function_exists( 'us_woocommerce_before_main_content' ) ) {
	add_action( 'woocommerce_before_main_content', 'us_woocommerce_before_main_content', 10 );
	function us_woocommerce_before_main_content() {

		$show_shop_section = TRUE;

		if ( is_single() ) {
			$content_area_id = us_get_page_area_id( 'content' );
			if ( $content_area_id != '' AND get_post_status( $content_area_id ) != FALSE ) {
				$show_shop_section = FALSE;
				add_filter( 'wc_get_template_part', 'us_wc_get_template_part_content_single_product', 10, 3 );
			}
		}

		echo '<main class="l-main">';

		if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {

			// Titlebar, if it is enabled in Theme Options
			us_load_template( 'templates/titlebar' );

			// START wrapper for Sidebar
			us_load_template( 'templates/sidebar', array( 'place' => 'before' ) );
		}

		// Output content of Shop page in a first separate section
		if ( is_post_type_archive( 'product' ) AND ! is_search() AND 0 === absint( get_query_var( 'paged' ) ) ) {

			$shop_page = get_post( wc_get_page_id( 'shop' ) );
			if ( $shop_page ) {
				$shop_page_content = apply_filters( 'the_content', $shop_page->post_content );
				if ( $shop_page_content ) {
					if ( strpos( $shop_page_content, ' class="l-section' ) === FALSE ) {
						$shop_page_content = '<section class="l-section for_shop_description"><div class="l-section-h i-cf">' . $shop_page_content . '</div></section>';
					}
					echo $shop_page_content;
				}
			}
		}

		if ( $show_shop_section ) {
			echo '<section id="shop" class="l-section for_shop">';
			echo '<div class="l-section-h i-cf">';
		}
	}
}

function us_wc_get_template_part_content_single_product( $template, $slug, $name = '' ) {
	if ( ! ( $slug == 'content' AND $name == 'single-product' ) ) {
		return $template;
	}

	return us_locate_file( 'templates/content.php' );
}

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
if ( ! function_exists( 'us_woocommerce_after_main_content' ) ) {
	add_action( 'woocommerce_after_main_content', 'us_woocommerce_after_main_content', 20 );
	function us_woocommerce_after_main_content() {
		$show_shop_section = TRUE;

		if ( is_single() ) {
			$content_area_id = us_get_page_area_id( 'content' );
			if ( $content_area_id != '' AND get_post_status( $content_area_id ) != FALSE ) {
				$show_shop_section = FALSE;
			}
		}

		if ( $show_shop_section ) {
			echo '</div></section>';
		}

		if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {
			// AFTER wrapper for Sidebar
			us_load_template( 'templates/sidebar', array( 'place' => 'after' ) );
		}
		echo '</main>';
	}
}

// Change columns number on Shop page (from Theme Options > Shop)
add_filter( 'loop_shop_columns', 'loop_columns' );
if ( ! function_exists( 'loop_columns' ) ) {
	function loop_columns() {
		return us_get_option( 'shop_columns', 4 );
	}
}

// Change items number on Shop page (from Theme Options > Shop)
add_filter( 'loop_shop_per_page', 'us_loop_shop_per_page' );
if ( ! function_exists( 'us_loop_shop_per_page' ) ) {
	function us_loop_shop_per_page() {
		return get_option( 'posts_per_page' );
	}
}

// Change Related Products quantity (from Theme Options > Shop)
function woo_related_products_limit() {
	global $product;

	$args['posts_per_page'] = us_get_option( 'product_related_qty', 4 );

	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'us_related_products_args' );
function us_related_products_args( $args ) {
	$args['posts_per_page'] = us_get_option( 'product_related_qty', 4 );
	$args['columns'] = us_get_option( 'product_related_qty', 4 );

	return $args;
}

// Change Cross-sells quantity (from Theme Options > Shop)
add_filter( 'woocommerce_cross_sells_total', 'us_woocommerce_cross_sells_total' );
add_filter( 'woocommerce_cross_sells_columns', 'us_woocommerce_cross_sells_total' );
function us_woocommerce_cross_sells_total( $count ) {
	return us_get_option( 'product_related_qty', 4 );
}

// Remove default woocommerce sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Move cross sells bellow the shipping
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 10 );

// Move breadcrumbs before product title on Products default template
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );

// Alter Cart - add total number
add_filter( 'woocommerce_add_to_cart_fragments', 'us_add_to_cart_fragments' );
function us_add_to_cart_fragments( $fragments ) {
	global $woocommerce;

	$fragments['a.cart-contents'] = '<a class="cart-contents" href="' . esc_url( wc_get_cart_url() ) . '">' . $woocommerce->cart->get_cart_total() . '</a>';

	return $fragments;
}

// Correct pagination
if ( ! function_exists( 'woocommerce_pagination' ) ) {
	function woocommerce_pagination() {
		global $us_woo_disable_pagination;
		if ( isset( $us_woo_disable_pagination ) AND $us_woo_disable_pagination ) {
			return;
		}

		global $wp_query;
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}
		the_posts_pagination(
			array(
				'mid_size' => 3,
				'before_page_number' => '<span>',
				'after_page_number' => '</span>',
			)
		);
	}
}

// Remove focus state on Checkout page
add_filter( 'woocommerce_checkout_fields', 'us_woocommerce_disable_autofocus_billing_firstname' );
function us_woocommerce_disable_autofocus_billing_firstname( $fields ) {
	$fields['shipping']['shipping_first_name']['autofocus'] = FALSE;

	return $fields;
}

// Wrap attributes <select> for better styling
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'us_woocommerce_dropdown_variation_attribute_options_html' );
function us_woocommerce_dropdown_variation_attribute_options_html( $html ) {
	$html = '<div class="woocommerce-select">' . $html . '</div>';

	return $html;
}

// Add amount of products in cart to show in Header element
add_action( 'woocommerce_after_mini_cart', 'us_woocommerce_after_mini_cart' );
function us_woocommerce_after_mini_cart() {
	global $woocommerce;

	echo '<span class="us_mini_cart_amount" style="display: none;">' . $woocommerce->cart->cart_contents_count . '</span>';
}

// Wrap "Add To Cart" button's text with placehoders.
add_action( 'woocommerce_before_template_part', 'us_woocommerce_before_loop_add_to_cart_template_part', 10, 4 );
function us_woocommerce_before_loop_add_to_cart_template_part( $template_name, $template_path, $located, $args ) {
	if ( $template_name == 'loop/add-to-cart.php' ) {
		add_filter( 'woocommerce_product_add_to_cart_text', 'us_add_to_cart_text', 99, 2 );
		add_filter( 'woocommerce_loop_add_to_cart_link', 'us_add_to_cart_text_replace', 99, 3 );
	}
}

add_action( 'woocommerce_after_template_part', 'us_woocommerce_after_loop_add_to_cart_template_part', 10, 4 );
function us_woocommerce_after_loop_add_to_cart_template_part( $template_name, $template_path, $located, $args ) {
	if ( $template_name == 'loop/add-to-cart.php' ) {
		remove_filter( 'woocommerce_product_add_to_cart_text', 'us_add_to_cart_text', 99 );
		remove_filter( 'woocommerce_loop_add_to_cart_link', 'us_add_to_cart_text_replace', 99 );
	}
}

// Use placeholders instead of actual HTML semantics, because after this filter the esc_html() function is applied
function us_add_to_cart_text( $text, $product ) {
	$text = '{{us_add_to_cart_start}}' . $text . '{{us_add_to_cart_end}}';

	return $text;
}

// Replace placeholders with actual HTML wrapper for "Add To Cart" buttons
function us_add_to_cart_text_replace( $html, $product, $args ) {
	$html = str_replace( '{{us_add_to_cart_start}}', '<i class="g-preloader type_1"></i><span class="w-btn-label">', $html );
	$html = str_replace( '{{us_add_to_cart_end}}', '</span>', $html );

	return $html;
}

// Remove metaboxes from Shop page
add_filter( 'us_config_meta-boxes', 'us_remove_meta_for_shop_page' );
function us_remove_meta_for_shop_page( $config ) {
	$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : NULL;
	if ( $post_id !== NULL AND $post_id == get_option( 'woocommerce_shop_page_id' ) ) {
		foreach ( $config as $metabox_key => $metabox ) {
			if ( $metabox['id'] == 'us_portfolio_settings' ) {
				unset( $config[ $metabox_key ] );
			}
			if ( $metabox['id'] == 'us_page_settings' ) {
				unset( $config[ $metabox_key ]['fields']['us_content_id'] );
				unset( $config[ $metabox_key ]['fields']['us_footer_id'] );
			}
		}
	}

	return $config;
}
