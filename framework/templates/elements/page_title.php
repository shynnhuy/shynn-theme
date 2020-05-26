<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_page_title
 */

$classes = $schema_heading = $output = '';

$classes .= ' align_' . $align;
if ( $inline AND $align != 'center' ) {
	$classes .= ' type_inline';
}
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Generate inline styles
$inline_css = us_prepare_inline_css(
	array(
		'font-family' => $font,
		'font-weight' => $font_weight,
		'text-transform' => $text_transform,
		'font-style' => $font_style,
		'font-size' => $font_size,
		'line-height' => $line_height,
		'color' => $color,
	), TRUE, $tag
);

// Add microdata depending on the relevant Theme Option
if ( us_get_option( 'schema_markup' ) ) {
	$schema_heading = ' itemprop="headline"';
}

// Get title based on page type
if ( is_home() ) {
	$title = us_translate( 'All Posts' );
} elseif ( is_search() ) {
	$title = sprintf( us_translate( 'Search results for &#8220;%s&#8221;' ), esc_attr( get_search_query() ) );
} elseif ( is_author() ) {
	$title = sprintf( us_translate( 'Posts by %s' ), get_the_author() );
} elseif ( is_tag() ) {
	$title = single_tag_title( '', FALSE );
} elseif ( is_category() ) {
	$title = single_cat_title( '', FALSE );
} elseif ( is_tax() ) {
	$title = single_term_title( '', FALSE );
} elseif ( function_exists( 'is_shop' ) AND is_shop() ) {
	$title = woocommerce_page_title( '', FALSE );
} elseif ( is_archive() ) {
	$post_type = get_post_type_object( get_post_type() );
	if ( isset( $post_type->labels->name ) ) {
		$title = $post_type->labels->name;
	}
} elseif ( is_404() ) {
	$title = us_translate( 'Page not found' );
} else {
	$title = get_the_title();
}
// Output the element
if ( $title != '' ) {
	$output .= '<' . $tag . ' class="w-page-title' . $classes . '"';
	$output .= $el_id . $schema_heading . $inline_css;
	$output .= '>';
	$output .= $title;
	$output .= '</' . $tag . '>';
}
if ( $description AND term_description() ) {
	$output .= '<div class="w-term-description">' . term_description() . '</div>';
}

echo $output;
