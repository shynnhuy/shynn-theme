<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output text element
 *
 * @var $text           string
 * @var $size           int Text size
 * @var $size_tablets   int Text size for tablets
 * @var $size_mobiles   int Text size for mobiles
 * @var $link           string Link
 * @var $icon           string FontAwesome or Material icon
 * @var $font           string Font Source
 * @var $color          string Custom text color
 * @var $design_options array
 * @var $classes        string
 * @var $id             string
 */

$classes = isset( $classes ) ? $classes : '';

$output = '<div class="w-text' . $classes . '">';

$link_atts = us_generate_link_atts( $link );
if ( ! empty( $link_atts ) ) {
	$output .= '<a class="w-text-h"' . $link_atts . '>';
} else {
	$output .= '<div class="w-text-h">';
}

if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
$output .= '<span class="w-text-value">' . strip_tags( $text, '<br>' ) . '</span>';

if ( ! empty( $link_atts ) ) {
	$output .= '</a>';
} else {
	$output .= '</div>';
}
$output .= '</div>';

echo $output;
