<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output elements list to choose from
 *
 * @var $body string Optional predefined body
 */
global $us_template_directory_uri;

$templates = us_config( 'grid-templates', array() );

if ( ! isset( $body ) ) {
	$params_defaults = array();

	$body = '<ul class="us-bld-window-list">';
	foreach ( $templates as $name => $template ) {
		$template_title = isset( $template['title'] ) ? $template['title'] : ucfirst( $name );
		$template = us_fix_grid_settings( $template );
		$body .= '<li data-name="' . esc_attr( $name ) . '" class="us-bld-window-item type_gtemplate ' . $name . '">';
		$body .= '<div class="us-bld-window-item-h">';
		$body .= '<div class="us-bld-window-item-icon">';
		$body .= '<img src="' . $us_template_directory_uri . '/admin/img/grid-templates/' . $name . '.jpg" />';
		$body .= '</div>';
		$body .= '<div class="us-bld-window-item-title">' . $template_title . '</div>';
		$body .= '<div class="us-bld-window-item-data hidden"' . us_pass_data_to_js( $template ) . '></div>';
		$body .= '</div>';
		$body .= '</li>';
	}
	$body .= '</ul>';
}

$output = '<div class="us-bld-window for_templates"><div class="us-bld-window-h">';
$output .= '<div class="us-bld-window-header"><div class="us-bld-window-title">' . __( 'Grid Layout Templates', 'us' ) . '</div><div class="us-bld-window-closer" title="' . us_translate( 'Close' ) . '"></div></div>';
$output .= '<div class="us-bld-window-body">';
$output .= $body;
$output .= '<span class="usof-preloader"></span>';
$output .= '</div>';
$output .= '</div></div>';

echo $output;
