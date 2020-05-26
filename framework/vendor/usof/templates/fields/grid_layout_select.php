<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Grid Layout Select
 *
 * Drop-down selector field.
 *
 * @var   $name  string Field name
 * @var   $id    string Field ID
 * @var   $field array Field options
 *
 * @param $field ['title'] string Field title
 *
 * @var   $value string Current value
 */

// Get Grid Layouts
$templates_config = us_config( 'grid-templates', array(), TRUE );

$custom_layouts = array_flip( us_get_posts_titles_for( 'us_grid_layout' ) );

$output = '<div class="usof-grid-layout-select">';
$output .= '<select name="' . $name . '">';
$output .= '<optgroup label="' . __( 'Grid Layouts', 'us' ) . '">';
foreach ( $custom_layouts as $title => $id ) {
	$output .= '<option value="' . $id . '"';
	if ( $value == $id ) {
		$output .= ' selected="selected"';
	}
	$output .= ' data-edit-url="' . admin_url( '/post.php?post=' . $id . '&action=edit' ) . '">' . $title . '</option>';
}
$current_tmpl_group = '';
foreach ( $templates_config as $template_name => $template ) {
	if ( ! empty( $template['group'] ) AND $current_tmpl_group != $template['group'] ) {
		$current_tmpl_group = $template['group'];
		$output .= '</optgroup>';
		$output .= '<optgroup label="' . $template['group'] . '">';
	}
	$output .= '<option value="' . $template_name . '"';
	if ( $value == $template_name ) {
		$output .= ' selected="selected"';
	}
	$output .= '>' . $template['title'] . '</option>';
}
$output .= '</optgroup>';
$output .= '</select>';
$output .= '<div class="us-grid-layout-desc-edit">';
$output .= sprintf( _x( '%sEdit selected%s or %screate a new one%s.', 'Grid Layout', 'us' ), '<a href="#" class="edit-link" target="_blank">', '</a>', '<a href="' . admin_url() . 'post-new.php?post_type=us_grid_layout" target="_blank">', '</a>' );
$output .= '</div>';
$output .= '<div class="us-grid-layout-desc-add">';
$output .= '<a href="' . admin_url() . 'post-new.php?post_type=us_grid_layout" target="_blank">' . __( 'Add Grid Layout', 'us' ) . '</a>.';
$output .= '</div></div>';

echo $output;
