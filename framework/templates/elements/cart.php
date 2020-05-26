<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output cart element
 *
 * @var $icon           int
 * @var $dropdown_effect string Dropdown Effect
 * @var $icon_size      int
 * @var $design_options array
 * @var $classes        string
 * @var $id             string
 */

if ( ! class_exists( 'woocommerce' ) ) {
	return;
}

global $cache_enabled;

$classes = isset( $classes ) ? $classes : '';
$classes .= ' dropdown_' . $dropdown_effect;
if ( $vstretch ) {
	$classes .= ' height_full';
}

$quantity_inline_css = us_prepare_inline_css(
	array(
		'background' => $quantity_color_bg,
		'color' => $quantity_color_text,
	)
);

echo '<div class="w-cart' . $classes . ' empty">';
echo '<div class="w-cart-h">';
echo '<a class="w-cart-link" href="' . esc_attr( wc_get_cart_url() ) . '" aria-label="' . __( 'Cart', 'us' ) . '">';
echo '<span class="w-cart-icon">';

if ( ! empty( $icon ) ) {
	echo us_prepare_icon_tag( $icon );
}

echo '<span class="w-cart-quantity"' . $quantity_inline_css . '></span></span></a>';
echo '<div class="w-cart-notification"><div><span class="product-name">' . us_translate( 'Product', 'woocommerce' ) . '</span> ' . __( 'was added to your cart', 'us' ) . '</div></div>';
echo '<div class="w-cart-dropdown">';
the_widget( 'WC_Widget_Cart' ); // This widget being always filled with products via AJAX
echo '</div>';
echo '</div>';
echo '</div>';
