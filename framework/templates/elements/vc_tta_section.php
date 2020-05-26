<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: vc_tta_section
 *
 * Overloaded by UpSolution custom implementation.
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var $shortcode      string Current shortcode name
 * @var $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var $content        string Shortcode's inner content
 *
 * @var $title          string Section title
 * @var $tab_id         string Section slug
 * @var $icon           string Icon
 * @var $i_position     string Icon position: 'left' / 'right'
 * @var $active         bool Tab is opened when page loads
 * @var $indents        string Indents type: '' / 'none'
 * @var $bg_color       string Background color
 * @var $text_color     string Text color
 * @var $c_position     string Control position (inherited from wrapping vc_tta_tabs shortcode): 'left' / 'right'
 * @var $title_tag      string Title HTML tag (inherited from wrapping vc_tta_tabs shortcode): 'div' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'p'
 * @var $title_size     string Title Size
 * @var $el_class       string Extra class name
 */

global $us_tabs_atts, $us_tab_index;
// Tab indexes start from 1
$us_tab_index = isset( $us_tab_index ) ? ( $us_tab_index + 1 ) : 1;

// We could overload some of the atts at vc_tabs implementation, so apply them here as well
if ( isset( $us_tab_index ) AND isset( $us_tabs_atts[ $us_tab_index - 1 ] ) ) {
	foreach ( $us_tabs_atts[ $us_tab_index - 1 ] as $_key => $_value ) {
		${$_key} = $_value;
	}
}

$content_html = do_shortcode( $content );

$classes = $item_tag_href = '';
if ( $icon ) {
	$classes .= ' with_icon';
}
if ( $indents == 'none' ) {
	$classes .= ' no_indents';
}
if ( $active ) {
	$classes .= ' active';
}
// Hide the section with empty content
if ( $content_html == '' ) {
	$classes .= ' content-empty';
}
if ( ! empty( $el_class ) ) {
	$classes .= ' ' . $el_class;
}

$item_tag = 'div';

if ( ! empty( $tab_id ) ) {
	$item_tag = 'a';
	$item_tag_href = ' href="#' . $tab_id . '"';
	$tab_id = ' id="' . $tab_id . '"';
}

$inline_css = us_prepare_inline_css(
	array(
		'background' => $bg_color,
		'color' => $text_color,
	)
);
if ( ! empty( $inline_css ) ) {
	$classes .= ' color_custom';
}

// Replace comments amount instead of variable
$title = us_replace_comment_count_var( $title );

// Output the element
$output = '<div class="w-tabs-section' . $classes . '"' . $tab_id . $inline_css . '>';
$output .= '<' . $item_tag . $item_tag_href . ' class="w-tabs-section-header"' . us_prepare_inline_css( array( 'font-size' => $title_size ) ) . '><div class="w-tabs-section-header-h">';
if ( $c_position == 'left' ) {
	$output .= '<div class="w-tabs-section-control"></div>';
}
if ( $icon AND $i_position == 'left' ) {
	$output .= us_prepare_icon_tag( $icon );
}
$output .= '<' . $title_tag . ' class="w-tabs-section-title">' . $title . '</' . $title_tag . '>';
if ( $icon AND $i_position == 'right' ) {
	$output .= us_prepare_icon_tag( $icon );
}
if ( $c_position == 'right' ) {
	$output .= '<div class="w-tabs-section-control"></div>';
}
$output .= '</div></' . $item_tag . '>';
$output .= '<div class="w-tabs-section-content"><div class="w-tabs-section-content-h i-cf">' . $content_html . '</div></div>';
$output .= '</div>';

echo $output;
