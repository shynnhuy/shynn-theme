<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Assets configuration (JS and CSS components)
 *
 * @filter us_config_assets
 */

return array(

	// Base Components
	'lazy-load' => array(
		'title' => '',
		'js' => '/framework/js/vendor/lazyloadxt.js',
		'hidden' => TRUE, // component not visible in UI
		'order' => 'top', // component will be added to the top of generated JS file
	),
	'general' => array(
		'title' => us_translate_x( 'General', 'settings screen' ),
		'css' => '/css/base/general.css',
		'js' => '/framework/js/base/general.js',
		'hidden' => TRUE, // component not visible in UI
		'order' => 'top', // component will be added to the top of generated JS file
	),
	'animation' => array(
		'title' => __( 'Animation', 'us' ),
		'css' => '/css/base/animation.css',
		'js' => '/framework/js/base/animation.js',
		'group' => __( 'Base Components', 'us' ),
	),
	'carousel' => array(
		'title' => __( 'Carousel', 'us' ),
		'css' => '/css/base/carousel.css',
	),
	'columns' => array(
		'title' => us_translate( 'Columns' ),
		'css' => '/css/base/columns.css',
	),
	'comments' => array(
		'title' => us_translate( 'Comments' ),
		'css' => '/css/base/comments.css',
		'js' => '/framework/js/base/comments.js',
	),
	'filters' => array(
		'title' => us_translate( 'Filter' ),
		'css' => '/css/base/filters.css',
	),
	'forms' => array(
		'title' => __( 'Forms', 'us' ),
		'css' => '/css/base/forms.css',
		'js' => '/framework/js/base/forms.js',
	),
	'header' => array(
		'title' => _x( 'Header', 'site top area', 'us' ),
		'css' => '/css/base/header.css',
		'js' => '/framework/js/base/header.js',
	),
	'parallax-hor' => array(
		'title' => __( 'Horizontal Parallax', 'us' ),
		'js' => '/framework/js/base/parallax-hor.js',
	),
	'pagination' => array(
		'title' => us_translate( 'Pagination' ),
		'css' => '/css/base/pagination.css',
	),
	'preloader' => array(
		'title' => __( 'Preloader', 'us' ),
		'css' => '/css/base/preloader.css',
		'js' => '/framework/js/base/preloader.js',
	),
	'print' => array(
		'title' => __( 'Print styles', 'us' ),
		'css' => '/css/base/print.css',
	),
	'popups' => array(
		'title' => __( 'Popups', 'us' ),
		'css' => '/css/base/popups.css',
	),
	'scroll' => array(
		'title' => __( 'Scroll events', 'us' ),
		'js' => '/framework/js/base/scroll.js',
		'order' => 'top',
	),
	'parallax-ver' => array(
		'title' => __( 'Vertical Parallax', 'us' ),
		'js' => '/framework/js/base/parallax-ver.js',
	),
	'ripple' => array(
		'title' => 'Ripple Effect',
		'css' => '/css/base/ripple.css',
	),
	'font-awesome' => array(
		'title' => sprintf( __( '"%s" icons', 'us' ), 'Font Awesome' ),
		'css' => '/css/base/fontawesome.css',
	),
	'material-icons' => array(
		'title' => sprintf( __( '"%s" icons', 'us' ), 'Material' ),
		'css' => '/css/base/material-icons.css',
	),

	// Content Elements
	'actionbox' => array(
		'title' => __( 'ActionBox', 'us' ),
		'css' => '/css/elements/actionbox.css',
		'group' => __( 'Content Elements', 'us' ),
	),
	'buttons' => array(
		'title' => __( 'Buttons', 'us' ),
		'css' => '/css/elements/buttons.css',
	),
	'charts' => array(
		'title' => __( 'Charts', 'us' ),
		'css' => '/css/elements/charts.css',
	),
	'contacts' => array(
		'title' => us_translate( 'Contact Info' ),
		'css' => '/css/elements/contacts.css',
	),
	'counter' => array(
		'title' => __( 'Counter', 'us' ),
		'css' => '/css/elements/counter.css',
		'js' => '/framework/js/elements/counter.js',
	),
	'dropdown' => array(
		'title' => __( 'Dropdown', 'us' ),
		'css' => '/css/elements/dropdown.css',
		'js' => '/framework/js/elements/dropdown.js',
	),
	'flipbox' => array(
		'title' => __( 'FlipBox', 'us' ),
		'css' => '/css/elements/flipbox.css',
		'js' => '/framework/js/elements/flipbox.js',
	),
	'gmaps' => array(
		'title' => sprintf( __( '%s Maps', 'us' ), 'Google' ),
		'css' => '/css/elements/gmaps.css',
		'js' => '/framework/js/elements/gmaps.js',
	),
	'lmaps' => array(
		'title' => sprintf( __( '%s Maps', 'us' ), 'OpenStreetMap' ),
		'css' => '/css/vendor/leaflet.css',
		'js' => '/framework/js/elements/lmaps.js',
	),
	'grid' => array(
		'title' => __( 'Grid', 'us' ),
		'css' => '/css/elements/grid.css',
		'js' => '/framework/js/elements/grid.js',
	),
	'gallery' => array(
		'title' => __( 'Image Gallery', 'us' ),
		'css' => '/css/elements/gallery.css',
		'js' => '/framework/js/elements/gallery.js',
	),
	'slider' => array(
		'title' => __( 'Image Slider', 'us' ),
		'css' => '/css/elements/slider.css',
		'js' => '/framework/js/elements/slider.js',
	),
	'iconbox' => array(
		'title' => __( 'IconBox', 'us' ),
		'css' => '/css/elements/iconbox.css',
	),
	'image' => array(
		'title' => us_translate( 'Image' ),
		'css' => '/css/elements/image.css',
	),
	'itext' => array(
		'title' => __( 'Interactive Text', 'us' ),
		'css' => '/css/elements/itext.css',
		'js' => '/framework/js/elements/itext.js',
	),
	'menu' => array(
		'title' => us_translate( 'Menu' ),
		'css' => '/css/elements/menu.css',
		'js' => '/framework/js/elements/menu.js',
	),
	'message' => array(
		'title' => __( 'Message Box', 'us' ),
		'css' => '/css/elements/message.css',
		'js' => '/framework/js/elements/message.js',
	),
	'scroller' => array(
		'title' => __( 'Page Scroller', 'us' ),
		'css' => '/css/elements/page-scroller.css',
		'js' => '/framework/js/elements/page-scroller.js',
	),
	'person' => array(
		'title' => __( 'Person', 'us' ),
		'css' => '/css/elements/person.css',
	),
	'popup' => array(
		'title' => __( 'Popup', 'us' ),
		'css' => '/css/elements/popup.css',
		'js' => '/framework/js/elements/popup.js',
	),
	'pricing' => array(
		'title' => __( 'Pricing Table', 'us' ),
		'css' => '/css/elements/pricing.css',
	),
	'progbar' => array(
		'title' => __( 'Progress Bar', 'us' ),
		'css' => '/css/elements/progbar.css',
		'js' => '/framework/js/elements/progbar.js',
	),
	'search' => array(
		'title' => us_translate( 'Search' ),
		'css' => '/css/elements/search.css',
	),
	'separator' => array(
		'title' => __( 'Separator', 'us' ),
		'css' => '/css/elements/separator.css',
	),
	'sharing' => array(
		'title' => __( 'Sharing Buttons', 'us' ),
		'css' => '/css/elements/sharing.css',
		'js' => '/framework/js/elements/sharing.js',
	),
	'socials' => array(
		'title' => __( 'Social Links', 'us' ),
		'css' => '/css/elements/socials.css',
	),
	'tabs' => array(
		'title' => us_translate( 'Tabs', 'js_composer' ) . ', ' . us_translate( 'Tour', 'js_composer' ) . ', ' . us_translate( 'Accordion', 'js_composer' ),
		'css' => '/css/elements/tabs.css',
		'js' => '/framework/js/elements/tabs.js',
	),
	'video' => array(
		'title' => us_translate( 'Video Player', 'js_composer' ),
		'css' => '/css/elements/video.css',
	),

	// Plugins
	'gravityforms' => array(
		'title' => 'Gravity Forms',
		'css' => '/css/plugins/gravityforms.css',
		'separated' => TRUE, // component will be minified into a separate file via "US Minify" plugin
		'apply_if' => class_exists( 'GFForms' ),
		'group' => us_translate( 'Plugins' ),
	),
	'tribe-events' => array(
		'title' => 'The Events Calendar',
		'css' => '/css/plugins/tribe-events.css',
		'separated' => TRUE,
		'apply_if' => class_exists( 'Tribe__Events__Main' ),
	),
	'ultimate-addons' => array(
		'title' => 'Ultimate Addons',
		'css' => '/css/plugins/ultimate-addons.css',
		'js' => '/framework/js/plugins/ultimate-addons.js',
		'apply_if' => class_exists( 'Ultimate_VC_Addons' ),
	),
	'bbpress' => array(
		'title' => 'bbPress',
		'css' => '/css/plugins/bbpress.css',
		'separated' => TRUE,
		'hidden' => TRUE,
		'apply_if' => class_exists( 'bbPress' ),
	),
	'slider-revolution' => array(
		'title' => 'Slider Revolution',
		'css' => '/css/plugins/slider-revolution.css',
		'hidden' => TRUE,
		'apply_if' => class_exists( 'RevSliderFront' ),
	),
	'tablepress' => array(
		'title' => 'TablePress',
		'css' => '/css/plugins/tablepress.css',
		'hidden' => TRUE,
		'apply_if' => class_exists( 'TablePress' ),
	),
	'woocommerce' => array(
		'title' => 'WooCommerce',
		'css' => '/css/plugins/woocommerce.css',
		'js' => '/framework/js/plugins/woocommerce.js',
		'separated' => TRUE,
		'hidden' => TRUE,
		'apply_if' => class_exists( 'woocommerce' ),
	),
	'wpml' => array(
		'title' => 'WPML',
		'css' => '/css/plugins/wpml.css',
		'hidden' => TRUE,
		'apply_if' => class_exists( 'SitePress' ),
	),

	// Theme Customs
	'theme' => array(
		'title' => '',
		'js' => '/js/us.custom.js',
		'hidden' => TRUE,
	),
);
