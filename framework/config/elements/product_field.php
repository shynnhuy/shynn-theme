<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

// Get products attributes
$product_attributes_options = array();
if ( class_exists( 'woocommerce' ) ) {
	$attribute_taxonomies = wc_get_attribute_taxonomies();
	if ( ! empty( $attribute_taxonomies ) ) {
		foreach ( $attribute_taxonomies as $tax ) {
			$attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
			$label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
			$product_attributes_options[ $attribute_taxonomy_name ] = $label . ' (' . $attribute_taxonomy_name . ')';
		}
	}
}

return array(
	'title' => us_translate( 'Product data', 'woocommerce' ),
	'category' => __( 'Post Elements', 'us' ),
	'params' => array_merge( array(

		'type' => array(
			'title' => us_translate( 'Show' ),
			'type' => 'select',
			'options' => array_merge(
				array(
					'price' => us_translate( 'Price', 'woocommerce' ),
					'rating' => us_translate( 'Rating', 'woocommerce' ),
					'sku' => us_translate( 'SKU', 'woocommerce' ),
					'sale_badge' => __( 'Sale Badge', 'us' ),
					'weight' => us_translate( 'Weight', 'woocommerce' ),
					'dimensions' => us_translate( 'Dimensions', 'woocommerce' ),
				), $product_attributes_options
			),
			'std' => 'price',
			'admin_label' => TRUE,
		),
		'sale_text' => array(
			'title' => __( 'Sale Badge Text', 'us' ),
			'type' => 'text',
			'std' => us_translate( 'Sale!', 'woocommerce' ),
			'show_if' => array( 'type', '=', 'sale_badge' ),
		),

	), $typography_options, $design_options, $hover_options ),
);
