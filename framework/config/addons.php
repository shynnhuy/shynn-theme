<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Addons configuration
 *
 * @filter us_config_addons
 */

return array(
	array(
		'name' => 'WPBakery Page Builder',
		'slug' => 'js_composer',
		'description' => __( 'Save time working on your website content.', 'us' ),
		'premium' => TRUE,
		// for NOT free plugins we need to provide "url" and "changelog url"
		'changelog_url' => 'https://kb.wpbakery.com/docs/preface/release-notes/',
		'url' => 'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431',
	),
	array(
		'name' => 'Header Builder',
		'slug' => 'us-header-builder',
		'description' => __( 'Create custom website headers layouts with any elements.', 'us' ),
		'premium' => TRUE,
		'changelog_url' => 'https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/changelog/',
		'url' => 'https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/hb/',
	),
	array(
		'name' => 'Slider Revolution',
		'slug' => 'revslider',
		'description' => __( 'Create interactive sliders and presentations.', 'us' ),
		'premium' => TRUE,
		'changelog_url' => 'http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380',
		'url' => 'https://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380',
	),
	array(
		'name' => 'WooCommerce',
		'slug' => 'woocommerce',
		'description' => __( 'Most popular eCommerce plugin that allows you to sell anything.', 'us' ),
	),
	array(
		'name' => 'Custom Post Type UI',
		'slug' => 'custom-post-type-ui',
		'description' => __( 'Create and manage custom post types and taxonomies.', 'us' ),
	),
	array(
		'name' => 'Advanced Custom Fields',
		'slug' => 'advanced-custom-fields',
		'description' => __( 'Add fields to edit screens and display their values on website pages.', 'us' ),
	),
	array(
		'name' => 'TablePress',
		'slug' => 'tablepress',
		'description' => __( 'Create and manage tables.', 'us' ),
	),
	array(
		'name' => 'Contact Form 7',
		'slug' => 'contact-form-7',
		'description' => __( 'Create customizable contact forms and edit the mail contents.', 'us' ),
	),
	array(
		'name' => 'Yoast SEO',
		'slug' => 'wordpress-seo',
		'description' => __( 'Improve your website search engine optimization.', 'us' ),
	),
	array(
		'name' => 'Smush Image Compression and Optimization',
		'slug' => 'wp-smushit',
		'description' => __( 'Resize, optimize and compress all of your images.', 'us' ),
		'icon_file' => 'jpg', // in case when PNG icon doesn't exist
	),
	array(
		'name' => 'WP Super Cache',
		'slug' => 'wp-super-cache',
		'description' => __( 'Improve your website pages loading speed.', 'us' ),
	),
	array(
		'name' => 'UpdraftPlus Backup',
		'slug' => 'updraftplus',
		'description' => __( 'Backup your website files and database.', 'us' ),
		'icon_file' => 'jpg', // in case when PNG icon doesn't exist
	),
);
