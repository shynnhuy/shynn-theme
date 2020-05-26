<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_btn
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var $shortcode      string Current shortcode name
 * @var $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var $content        string Shortcode's inner content
 *
 * @var $link           string Video link
 * @var $ratio          string Ratio: '16x9' / '4x3' / '3x2' / '1x1'
 * @var $max_width      string Max width in pixels
 * @var $align          string Video alignment: 'left' / 'center' / 'right'
 * @var $css            string Extra css
 * @var $el_id          string element ID
 * @var $el_class       string Extra class name
 */

$classes = $inline_css = $embed_html = $video_title = '';
$hide_video_title = ! empty( $hide_video_title ) ? TRUE : FALSE;
$hide_controls = ! empty( $hide_controls ) ? TRUE : FALSE;

if ( ! empty( $ratio ) ) {
	$classes .= ' ratio_' . $ratio;
}

if ( ! empty( $max_width ) ) {
	$classes .= ' align_' . $align;
	$inline_css = us_prepare_inline_css(
		array(
			'max-width' => $max_width,
		)
	);
}
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

foreach ( us_config( 'embeds' ) as $provider => $embed ) {
	if ( $embed['type'] != 'video' OR ! preg_match( $embed['regex'], $link, $matches ) ) {
		continue;
	}

	if ( $hide_controls AND $provider == 'youtube' ) {
		$video_title = '?controls=0';
	} elseif ( $hide_video_title AND $provider == 'vimeo' ) {
		$video_title = '&byline=0&title=0';
	}
	$video_id = $matches[ $embed['match_index'] ];
	$embed_html = str_replace( '<id>', $video_id, $embed['html'] );
	$embed_html = str_replace( '<video-title>', $video_title, $embed_html );
	break;
}

if ( empty( $embed_html ) ) {
	// Using the default WordPress way
	global $wp_embed;
	$embed_html = $wp_embed->run_shortcode( '[embed]' . $link . '[/embed]' );
}

$output = '<div class="w-video' . $classes . '"' . $inline_css . $el_id . '><div class="w-video-h">' . $embed_html . '</div></div>';

echo $output;
