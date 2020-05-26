<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output links menu element
 */

if ( empty( $source ) OR ! is_nav_menu( $source ) ) {
	return;
}

$classes = isset( $classes ) ? $classes : '';

wp_nav_menu(
	array(
		'container' => 'div',
		'container_class' => 'w-menu ' . $classes,
		'menu' => $source,
		'walker' => new US_Walker_Simplenav_Menu,
		'items_wrap' => '<div class="w-menu-list">%3$s</div>',
		'depth' => 1,
	)
);
