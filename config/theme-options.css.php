<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Generates and outputs theme options' generated styleshets
 *
 * @action Before the template: us_before_template:config/theme-options.css
 * @action After the template: us_after_template:config/theme-options.css
 */

global $us_template_directory_uri;

// Define if supported plugins are enabled
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$with_shop = class_exists( 'woocommerce' );
$with_events = function_exists( 'tribe_get_option' );
$with_forums = class_exists( 'bbPress' );
$with_gforms = class_exists( 'GFForms' );
?>

/* CSS paths which need to be absolute
   =============================================================================================================================== */
@font-face {
	font-family: 'Font Awesome 5 Brands';
	font-display: block;
	font-style: normal;
	font-weight: normal;
	src: url("<?php echo $us_template_directory_uri ?>/fonts/fa-brands-400.woff2") format("woff2"),
	url("<?php echo $us_template_directory_uri ?>/fonts/fa-brands-400.woff") format("woff");
	}
.fab {
	font-family: 'Font Awesome 5 Brands';
	}
<?php
// When Font Awesome CSS file is disabled, use theme-symbols font instead heavy Font Awesome font files
if ( us_get_option( 'optimize_assets' ) AND ! in_array( 'font-awesome', us_get_option( 'assets' ) ) ) { ?>
@font-face {
	font-family: 'fontawesome';
	font-display: block;
	font-style: normal;
	font-weight: 400;
	src: url("<?php echo $us_template_directory_uri ?>/fonts/theme-symbols.woff") format("woff");
	}
.fal,
.fas,
.far {
	font-family: 'fontawesome';
	font-weight: 400;
	}
.w-testimonial-rating:before {
	content: '\f006\f006\f006\f006\f006';
	}
.w-testimonial-rating i::before {
	font-weight: 400;
	}
<?php } else { ?>
@font-face {
	font-family: 'fontawesome';
	font-display: block;
	font-style: normal;
	font-weight: 300;
	src: url("<?php echo $us_template_directory_uri ?>/fonts/fa-light-300.woff2") format("woff2"),
	url("<?php echo $us_template_directory_uri ?>/fonts/fa-light-300.woff") format("woff");
	}
.fal {
	font-family: 'fontawesome';
	font-weight: 300;
	}
@font-face {
	font-family: 'fontawesome';
	font-display: block;
	font-style: normal;
	font-weight: 400;
	src: url("<?php echo $us_template_directory_uri ?>/fonts/fa-regular-400.woff2") format("woff2"),
	url("<?php echo $us_template_directory_uri ?>/fonts/fa-regular-400.woff") format("woff");
	}
.far {
	font-family: 'fontawesome';
	font-weight: 400;
	}
@font-face {
	font-family: 'fontawesome';
	font-display: block;
	font-style: normal;
	font-weight: 900;
	src: url("<?php echo $us_template_directory_uri ?>/fonts/fa-solid-900.woff2") format("woff2"),
	url("<?php echo $us_template_directory_uri ?>/fonts/fa-solid-900.woff") format("woff");
	}
.fa,
.fas {
	font-family: 'fontawesome';
	font-weight: 900;
	}
<?php } ?>

.style_phone6-1 > div {
	background-image: url(<?php echo $us_template_directory_uri ?>/framework/img/phone-6-black-real.png);
	}
.style_phone6-2 > div {
	background-image: url(<?php echo $us_template_directory_uri ?>/framework/img/phone-6-white-real.png);
	}
.style_phone6-3 > div {
	background-image: url(<?php echo $us_template_directory_uri ?>/framework/img/phone-6-black-flat.png);
	}
.style_phone6-4 > div {
	background-image: url(<?php echo $us_template_directory_uri ?>/framework/img/phone-6-white-flat.png);
	}
/* Default icon Leaflet URLs */
.leaflet-default-icon-path {
	background-image: url(<?php echo $us_template_directory_uri ?>/css/vendor/images/marker-icon.png);
	}



/* Lazy Load extra styles
   =============================================================================================================================== */
<?php if ( us_get_option( 'lazy_load', 1 ) ) { ?>
.lazy-hidden:not(.lazy-loaded) {
	background: rgba(0,0,0,0.1);
	}
<?php } ?>



/* Typography
   =============================================================================================================================== */
<?php

// Global Text
$css = 'html, .l-header .widget {';
$css .= us_get_font_css( 'body', TRUE );
$css .= 'font-size:' . us_get_option( 'body_fontsize' ) . ';';
$css .= 'line-height:' . us_get_option( 'body_lineheight' ) . ';';
$css .= '}';

// Uploaded Fonts
$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
	foreach ( $uploaded_fonts as $uploaded_font ) {
		$files = explode( ',', $uploaded_font['files'] );
		$urls = array();
		foreach ( $files as $file ) {
			$url = wp_get_attachment_url( $file );
			if ( $url ) {
				$urls[] = 'url(' . esc_url( $url ) . ') format("' . pathinfo( $url, PATHINFO_EXTENSION ) . '")';
			}
		}
		if ( count( $urls ) ) {
			$css .= '@font-face {';
			$css .= 'font-display: swap;';
			$css .= 'font-style: normal;';
			$css .= 'font-family:"' . strip_tags( $uploaded_font['name'] ) . '";';
			$css .= 'font-weight:' . $uploaded_font['weight'] . ';';
			$css .= 'src:' . implode( ', ', $urls ) . ';';
			$css .= '}';
		}
	}
}

// Headings h1-h6
for ( $i = 1; $i <= 6; $i ++ ) {
	if ( $i == 4 ) { // set to some elements styles as <h4>
		if ( $with_shop ) {
			$css .= '.woocommerce-Reviews-title,';
		}
		$css .= '.widgettitle, .comment-reply-title, h' . $i . '{';
	} else {
		$css .= 'h' . $i . '{';
	}
	$css .= us_get_font_css( 'h' . $i );
	$css .= 'font-weight:' . us_get_option( 'h' . $i . '_fontweight' ) . ';';
	$css .= 'font-size:' . us_get_option( 'h' . $i . '_fontsize' ) . ';';
	$css .= 'line-height:' . us_get_option( 'h' . $i . '_lineheight' ) . ';';
	$css .= 'letter-spacing:' . us_get_option( 'h' . $i . '_letterspacing' ) . ';';
	$css .= 'margin-bottom:' . us_get_option( 'h' . $i . '_bottom_indent' ) . ';';
	if ( is_array( us_get_option( 'h' . $i . '_transform' ) ) ) {
		if ( in_array( 'italic', us_get_option( 'h' . $i . '_transform' ) ) ) {
			$css .= 'font-style: italic;';
		}
		if ( in_array( 'uppercase', us_get_option( 'h' . $i . '_transform' ) ) ) {
			$css .= 'text-transform: uppercase;';
		}
	}
	$css .= '}';
}

echo strip_tags( $css );
?>
@media (max-width: 767px) {
html {
	font-size: <?php echo us_get_option( 'body_fontsize_mobile' ) ?>;
	line-height: <?php echo us_get_option( 'body_lineheight_mobile' ) ?>;
	}
h1 {
	font-size: <?php echo us_get_option( 'h1_fontsize_mobile' ) ?>;
	}
h1.vc_custom_heading {
	font-size: <?php echo us_get_option( 'h1_fontsize_mobile' ) ?> !important;
	}
h2 {
	font-size: <?php echo us_get_option( 'h2_fontsize_mobile' ) ?>;
	}
h2.vc_custom_heading {
	font-size: <?php echo us_get_option( 'h2_fontsize_mobile' ) ?> !important;
	}
h3 {
	font-size: <?php echo us_get_option( 'h3_fontsize_mobile' ) ?>;
	}
h3.vc_custom_heading {
	font-size: <?php echo us_get_option( 'h3_fontsize_mobile' ) ?> !important;
	}
h4,
<?php if ( $with_shop ) { ?>
.woocommerce-Reviews-title,
<?php } ?>
.widgettitle,
.comment-reply-title {
	font-size: <?php echo us_get_option( 'h4_fontsize_mobile' ) ?>;
	}
h4.vc_custom_heading {
	font-size: <?php echo us_get_option( 'h4_fontsize_mobile' ) ?> !important;
	}
h5 {
	font-size: <?php echo us_get_option( 'h5_fontsize_mobile' ) ?>;
	}
h5.vc_custom_heading {
	font-size: <?php echo us_get_option( 'h5_fontsize_mobile' ) ?> !important;
	}
h6 {
	font-size: <?php echo us_get_option( 'h6_fontsize_mobile' ) ?>;
	}
h6.vc_custom_heading {
	font-size: <?php echo us_get_option( 'h6_fontsize_mobile' ) ?> !important;
	}
}



/* Site Layout
   =============================================================================================================================== */
body { background:
<?php
$background_image = '';
$background_color = us_get_color( 'color_body_bg', TRUE );
// Add image properties when image is set
if ( us_get_option( 'body_bg_image' ) AND $body_bg_image = usof_get_image_src( us_get_option( 'body_bg_image' ) ) ) {
	$background_image .= 'url(' . $body_bg_image[0] . ') ';
	$background_image .= us_get_option( 'body_bg_image_position' );
	if ( us_get_option( 'body_bg_image_size' ) != 'initial' ) {
		$background_image .= '/' . us_get_option( 'body_bg_image_size' );
	}
	$background_image .= ' ';
	$background_image .= us_get_option( 'body_bg_image_repeat' );
	if ( ! us_get_option( 'body_bg_image_attachment', 0 ) ) {
		$background_image .= ' fixed';
	}
	// If the color value contains gradient, add comma for correct appearance
	if ( strpos( $background_color, 'gradient' ) !== FALSE ) {
		$background_image .= ',';
	}
}
// Output single combined background value
echo $background_image . $background_color;
?>
}
body,
.l-header.pos_fixed {
	min-width: <?php echo us_get_option( 'site_canvas_width' ) ?>;
	}
.l-canvas.type_boxed,
.l-canvas.type_boxed .l-subheader,
.l-canvas.type_boxed .l-section.type_sticky,
.l-canvas.type_boxed ~ .l-footer {
	max-width: <?php echo us_get_option( 'site_canvas_width' ) ?>;
	}
.l-subheader-h,
.l-section-h,
.l-main .aligncenter,
.w-tabs-section-content-h {
	max-width: <?php echo us_get_option( 'site_content_width' ) ?>;
	}
.post-password-form {
	max-width: calc(<?php echo us_get_option( 'site_content_width' ) ?> + 5rem);
	}

/* Limit width for centered images */
@media screen and (max-width: <?php echo ( intval( us_get_option( 'site_content_width' ) ) + intval( us_get_option( 'body_fontsize' ) ) * 5 ) ?>px) {
.l-main .aligncenter {
	max-width: calc(100vw - 5rem);
	}
}

<?php if ( floatval( us_get_option( 'text_bottom_indent' ) ) != 0 ) { ?>
/* Text Block bottom indent */
.wpb_text_column:not(:last-child) {
	margin-bottom: <?php echo us_get_option( 'text_bottom_indent' ) ?>;
	}
<?php } ?>

<?php if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) { ?>
.l-sidebar {
	width: <?php echo us_get_option( 'sidebar_width' ) ?>;
	}
.l-content {
	width: <?php echo 100 - floatval( us_get_option( 'sidebar_width' ) ) ?>%;
	}
<?php } ?>

/* Columns width regarding Responsive Layout */
<?php if ( ! us_get_option( 'responsive_layout', 1 ) ) { ?>
.vc_col-sm-1 { width: 8.3333%; }
.vc_col-sm-2 { width: 16.6666%; }
.vc_col-sm-1\/5 { width: 20%; }
.vc_col-sm-3 { width: 25%; }
.vc_col-sm-4 { width: 33.3333%; }
.vc_col-sm-2\/5 { width: 40%; }
.vc_col-sm-5 { width: 41.6666%; }
.vc_col-sm-6 { width: 50%; }
.vc_col-sm-7 { width: 58.3333%; }
.vc_col-sm-3\/5 { width: 60%; }
.vc_col-sm-8 { width: 66.6666%; }
.vc_col-sm-9 { width: 75%; }
.vc_col-sm-4\/5 { width: 80%; }
.vc_col-sm-10 { width: 83.3333%; }
.vc_col-sm-11 { width: 91.6666%; }
.vc_col-sm-12 { width: 100%; }
.vc_col-sm-offset-0 { margin-left: 0; }
.vc_col-sm-offset-1 { margin-left: 8.3333%; }
.vc_col-sm-offset-2 { margin-left: 16.6666%; }
.vc_col-sm-offset-1\/5 { margin-left: 20%; }
.vc_col-sm-offset-3 { margin-left: 25%; }
.vc_col-sm-offset-4 { margin-left: 33.3333%; }
.vc_col-sm-offset-2\/5 { margin-left: 40%; }
.vc_col-sm-offset-5 { margin-left: 41.6666%; }
.vc_col-sm-offset-6 { margin-left: 50%; }
.vc_col-sm-offset-7 { margin-left: 58.3333%; }
.vc_col-sm-offset-3\/5 { margin-left: 60%; }
.vc_col-sm-offset-8 { margin-left: 66.6666%; }
.vc_col-sm-offset-9 { margin-left: 75%; }
.vc_col-sm-offset-4\/5 { margin-left: 80%; }
.vc_col-sm-offset-10 { margin-left: 83.3333%; }
.vc_col-sm-offset-11 { margin-left: 91.6666%; }
.vc_col-sm-offset-12 { margin-left: 100%; }
<?php } else { ?>
@media (max-width: <?php echo ( intval( us_get_option( 'columns_stacking_width' ) ) - 1 ) ?>px) {
.g-cols.reversed {
	flex-direction: column-reverse;
	}
.g-cols > div:not([class*=" vc_col-"]) {
	width: 100%;
	margin: 0 0 1rem;
	}
.g-cols.type_boxes > div,
.g-cols.reversed > div:first-child,
.g-cols:not(.reversed) > div:last-child,
.g-cols > div.has-fill {
	margin-bottom: 0;
	}
.vc_wp_custommenu.layout_hor,
.align_center_xs,
.align_center_xs .w-socials {
	text-align: center;
	}
.align_center_xs .w-hwrapper > * {
	margin: 0.5rem 0;
	width: 100%;
	}
}
@media (min-width: <?php echo us_get_option( 'columns_stacking_width' ) ?>) {
.l-section.for_sidebar.at_left > div > .g-cols {
	flex-direction: row-reverse;
	}
}
<?php }



/* Buttons Styles
   =============================================================================================================================== */
$btn_styles = us_get_option( 'buttons' );
$btn_styles = ( is_array( $btn_styles ) ) ? $btn_styles : array();
$buttons_css = '';

// Set Default Style for non-editable button elements
$buttons_css .= '.tribe-events-button,';
$buttons_css .= 'button[type="submit"]:not(.w-btn),';
$buttons_css .= 'input[type="submit"] {';
if ( $btn_styles[0]['font'] != 'body' ) {
	$buttons_css .= us_get_font_css( $btn_styles[0]['font'] );
}
$buttons_css .= 'font-weight:' . $btn_styles[0]['font_weight'] . ';';
$buttons_css .= 'font-style:' . ( in_array( 'italic', $btn_styles[0]['text_style'] ) ? 'italic' : 'normal' ) . ';';
$buttons_css .= 'text-transform:' . ( in_array( 'uppercase', $btn_styles[0]['text_style'] ) ? 'uppercase' : 'none' ) . ';';
$buttons_css .= 'letter-spacing:' . $btn_styles[0]['letter_spacing'] . ';';
$buttons_css .= 'border-radius:' . $btn_styles[0]['border_radius'] . ';';
$buttons_css .= 'padding:' . $btn_styles[0]['height'] . ' ' . $btn_styles[0]['width'] . ';';
$buttons_css .= 'background:' . ( ! empty( $btn_styles[0]['color_bg'] ) ? $btn_styles[0]['color_bg'] : 'transparent' ) . ';';
$buttons_css .= 'border-color:' . ( ! empty( $btn_styles[0]['color_border'] ) ? us_get_color( $btn_styles[0]['color_border'] ) : 'transparent' ) . ';';
$buttons_css .= 'color:' . ( ! empty( $btn_styles[0]['color_text'] ) ? $btn_styles[0]['color_text'] : 'inherit' ) . '!important;';
$buttons_css .= '}';
// Border
$buttons_css .= '.tribe-events-button,';
$buttons_css .= 'button[type="submit"]:not(.w-btn):before,';
$buttons_css .= 'input[type="submit"] {';
$buttons_css .= 'border-width:' . $btn_styles[0]['border_width'] . ';';
$buttons_css .= '}';
// Hover State
$buttons_css .= '.no-touch .tribe-events-button:hover,';
$buttons_css .= '.no-touch button[type="submit"]:not(.w-btn):hover,';
$buttons_css .= '.no-touch input[type="submit"]:hover {';
$buttons_css .= 'box-shadow: 0 ' . floatval( $btn_styles[0]['shadow_hover'] ) / 2 . 'em ' . $btn_styles[0]['shadow_hover'] . ' rgba(0,0,0,0.2);';
$buttons_css .= 'background:' . ( ! empty( $btn_styles[0]['color_bg_hover'] ) ? $btn_styles[0]['color_bg_hover'] : 'transparent' ) . ';';
$buttons_css .= 'border-color:' . ( ! empty( $btn_styles[0]['color_border_hover'] ) ? us_get_color( $btn_styles[0]['color_border_hover'] ) : 'transparent' ) . ';';
$buttons_css .= 'color:' . ( ! empty( $btn_styles[0]['color_text_hover'] ) ? $btn_styles[0]['color_text_hover'] : 'inherit' ) . '!important;';
$buttons_css .= '}';
// Remove transition if the default button background has a gradient (cause gradients don't support transition)
if ( strpos( $btn_styles[0]['color_bg'], 'gradient' ) !== FALSE OR strpos( $btn_styles[0]['color_bg_hover'], 'gradient' ) !== FALSE ) {
	$buttons_css .= 'button[type="submit"], input[type="submit"] { transition: none; }';
}

// Generate Buttons Styles
foreach ( $btn_styles as $btn_style ) {

	// Default State
	if ( $with_shop AND us_get_option( 'shop_secondary_btn_style' ) == $btn_style['id'] ) {
		$buttons_css .= '.woocommerce .button,';
	}
	if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
		$buttons_css .= '.woocommerce .button.alt, .woocommerce .button.checkout, .woocommerce .button.add_to_cart_button,';
	}
	$buttons_css .= '.us-btn-style_' . $btn_style['id'] . '{';
	$buttons_css .= us_get_font_css( $btn_style['font'] );
	$buttons_css .= 'font-weight:' . $btn_style['font_weight'] . ';';
	$buttons_css .= 'font-style:' . ( in_array( 'italic', $btn_style['text_style'] ) ? 'italic' : 'normal' ) . ';';
	$buttons_css .= 'text-transform:' . ( in_array( 'uppercase', $btn_style['text_style'] ) ? 'uppercase' : 'none' ) . ';';
	$buttons_css .= 'letter-spacing:' . $btn_style['letter_spacing'] . ';';
	$buttons_css .= 'border-radius:' . $btn_style['border_radius'] . ';';
	$buttons_css .= 'padding:' . $btn_style['height'] . ' ' . $btn_style['width'] . ';';
	$buttons_css .= 'background:' . ( ! empty( $btn_style['color_bg'] ) ? $btn_style['color_bg'] : 'transparent' ) . ';';
	$buttons_css .= 'border-color:' . ( ! empty( $btn_style['color_border'] ) ? us_get_color( $btn_style['color_border'] ) : 'transparent' ) . ';';
	if ( ! empty( $btn_style['color_text'] ) ) {
		$buttons_css .= 'color:' . us_get_color( $btn_style['color_text'] ) . '!important;';
	}
	if ( ! empty( $btn_style['shadow'] ) ) {
		$buttons_css .= 'box-shadow: 0 ' . floatval( $btn_style['shadow'] ) / 2 . 'em ' . $btn_style['shadow'] . ' rgba(0,0,0,0.2);';
	} else {
		$buttons_css .= 'box-shadow: none';
	}
	$buttons_css .= '}';

	// Border imitation
	if ( $with_shop AND us_get_option( 'shop_secondary_btn_style' ) == $btn_style['id'] ) {
		$buttons_css .= '.woocommerce .button:before,';
	}
	if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
		$buttons_css .= '.woocommerce .button.alt:before, .woocommerce .button.checkout:before, .woocommerce .button.add_to_cart_button:before,';
	}
	$buttons_css .= '.us-btn-style_' . $btn_style['id'] . ':before {';
	$buttons_css .= 'border-width:' . $btn_style['border_width'] . ';';
	$buttons_css .= '}';
	
	// Hover State
	if ( $with_shop AND us_get_option( 'shop_secondary_btn_style' ) == $btn_style['id'] ) {
		$buttons_css .= '.no-touch .woocommerce .button:hover,';
	}
	if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
		$buttons_css .= '.no-touch .woocommerce .button.alt:hover, .no-touch .woocommerce .button.checkout:hover, .no-touch .woocommerce .button.add_to_cart_button:hover,';
	}
	$buttons_css .= '.no-touch .us-btn-style_' . $btn_style['id'] . ':hover {';

	$buttons_css .= 'box-shadow: 0 ' . floatval( $btn_style['shadow_hover'] ) / 2 . 'em ' . $btn_style['shadow_hover'] . ' rgba(0,0,0,0.2);';
	$buttons_css .= 'background:' . ( ! empty( $btn_style['color_bg_hover'] ) ? $btn_style['color_bg_hover'] : 'transparent' ) . ';';
	$buttons_css .= 'border-color:' . ( ! empty( $btn_style['color_border_hover'] ) ? us_get_color( $btn_style['color_border_hover'] ) : 'transparent' ) . ';';
	if ( ! empty( $btn_style['color_text_hover'] ) ) {
		$buttons_css .= 'color:' . us_get_color( $btn_style['color_text_hover'] ) . '!important;';
	}
	$buttons_css .= '}';

	// Check if the button background has a gradient
	$has_gradient = FALSE;
	if ( strpos( $btn_style['color_bg'], 'gradient' ) !== FALSE OR strpos( $btn_style['color_bg_hover'], 'gradient' ) !== FALSE ) {
		$has_gradient = TRUE;
	}

	// Extra layer for "Slide" hover type OR for gradient backgrounds (cause gradients don't support transition)
	if ( ( isset( $btn_style['hover'] ) AND $btn_style['hover'] == 'slide' ) OR $has_gradient ) {

		if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
			$buttons_css .= '.woocommerce .button.add_to_cart_button,';
		}
		$buttons_css .= '.us-btn-style_' . $btn_style['id'] . '{';
		$buttons_css .= 'overflow: hidden;';
		$buttons_css .= '}';

		if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
			$buttons_css .= '.no-touch .woocommerce .button.add_to_cart_button > *,';
		}
		$buttons_css .= '.us-btn-style_' . $btn_style['id'] . ' > * {';
		$buttons_css .= 'position: relative;';
		$buttons_css .= 'z-index: 1;';
		$buttons_css .= '}';

		if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
			$buttons_css .= '.no-touch .woocommerce .button.add_to_cart_button:hover,';
		}
		$buttons_css .= '.no-touch .us-btn-style_' . $btn_style['id'] . ':hover {';
		if ( ! empty( $btn_style['color_bg'] ) AND ! empty( $btn_style['color_bg_hover'] ) ) {
			$buttons_css .= 'background:' . $btn_style['color_bg'] . ';';
		} else {
			$buttons_css .= 'background: transparent;';
		}
		$buttons_css .= '}';

		if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
			$buttons_css .= '.no-touch .woocommerce .button.add_to_cart_button:after,';
		}
		$buttons_css .= '.no-touch .us-btn-style_' . $btn_style['id'] . ':after {';
		$buttons_css .= 'content: ""; position: absolute; top: 0; left: 0; right: 0;';
		if ( $btn_style['hover'] == 'slide' ) {
			$buttons_css .= 'height: 0; transition: height 0.3s;';
		} else {
			$buttons_css .= 'bottom: 0; opacity: 0; transition: opacity 0.3s;';
		}
		$buttons_css .= 'background:' . ( ! empty( $btn_style['color_bg_hover'] ) ? $btn_style['color_bg_hover'] : 'transparent' ) . ';';
		$buttons_css .= '}';

		if ( $with_shop AND us_get_option( 'shop_primary_btn_style' ) == $btn_style['id'] ) {
			$buttons_css .= '.no-touch .woocommerce .button.add_to_cart_button:hover:after,';
		}
		$buttons_css .= '.no-touch .us-btn-style_' . $btn_style['id'] . ':hover:after {';
		if ( $btn_style['hover'] == 'slide' ) {
			$buttons_css .= 'height: 100%;';
		} else {
			$buttons_css .= 'opacity: 1;';
		}
		$buttons_css .= '}';
	}

}
echo $buttons_css;

if ( us_get_option( 'keyboard_accessibility' ) ) { ?>
a:focus,
button:focus,
input[type="checkbox"]:focus + i,
input[type="submit"]:focus {
	outline: 2px dotted <?php echo us_get_color( 'color_content_primary' ) ?>;
	}
<?php } else { ?>
a,
button,
input[type="submit"],
.ui-slider-handle {
	outline: none !important;
	}
<?php } ?>

/* Back to top Button */
.w-header-show,
.w-toplink {
	background: <?php echo us_get_color( 'back_to_top_color', TRUE ) ?>;
	}



/* Colors
   =============================================================================================================================== */

body {
	-webkit-tap-highlight-color: <?php echo us_hex2rgba( us_get_color( 'color_content_primary' ), 0.2 ) ?>;
	}

/*************************** Header Colors ***************************/

/* Top Header Area */
.l-subheader.at_top,
.l-subheader.at_top .w-dropdown-list,
.l-subheader.at_top .type_mobile .w-nav-list.level_1 {
	background: <?php echo us_get_color( 'color_header_top_bg', TRUE ) ?>;
	}
.l-subheader.at_top,
.l-subheader.at_top .w-dropdown.opened,
.l-subheader.at_top .type_mobile .w-nav-list.level_1 {
	color: <?php echo us_get_color( 'color_header_top_text' ) ?>;
	}
.no-touch .l-subheader.at_top a:hover,
.no-touch .l-header.bg_transparent .l-subheader.at_top .w-dropdown.opened a:hover {
	color: <?php echo us_get_color( 'color_header_top_text_hover' ) ?>;
	}

/* Main Header Area */
.header_ver .l-header,
.l-subheader.at_middle,
.l-subheader.at_middle .w-dropdown-list,
.l-subheader.at_middle .type_mobile .w-nav-list.level_1 {
	background: <?php echo us_get_color( 'color_header_middle_bg', TRUE ) ?>;
	}
.l-subheader.at_middle,
.l-subheader.at_middle .w-dropdown.opened,
.l-subheader.at_middle .type_mobile .w-nav-list.level_1 {
	color: <?php echo us_get_color( 'color_header_middle_text' ) ?>;
	}
.no-touch .l-subheader.at_middle a:hover,
.no-touch .l-header.bg_transparent .l-subheader.at_middle .w-dropdown.opened a:hover {
	color: <?php echo us_get_color( 'color_header_middle_text_hover' ) ?>;
	}

/* Bottom Header Area */
.l-subheader.at_bottom,
.l-subheader.at_bottom .w-dropdown-list,
.l-subheader.at_bottom .type_mobile .w-nav-list.level_1 {
	background: <?php echo us_get_color( 'color_header_bottom_bg', TRUE ) ?>;
	}
.l-subheader.at_bottom,
.l-subheader.at_bottom .w-dropdown.opened,
.l-subheader.at_bottom .type_mobile .w-nav-list.level_1 {
	color: <?php echo us_get_color( 'color_header_bottom_text' ) ?>;
	}
.no-touch .l-subheader.at_bottom a:hover,
.no-touch .l-header.bg_transparent .l-subheader.at_bottom .w-dropdown.opened a:hover {
	color: <?php echo us_get_color( 'color_header_bottom_text_hover' ) ?>;
	}

/* Transparent Header Colors */
.l-header.bg_transparent:not(.sticky) .l-subheader {
	color: <?php echo us_get_color( 'color_header_transparent_text' ) ?>;
	}
.no-touch .l-header.bg_transparent:not(.sticky) .w-text a:hover,
.no-touch .l-header.bg_transparent:not(.sticky) .w-html a:hover,
.no-touch .l-header.bg_transparent:not(.sticky) .w-dropdown a:hover,
.no-touch .l-header.bg_transparent:not(.sticky) .type_desktop .menu-item.level_1:hover > .w-nav-anchor {
	color: <?php echo us_get_color( 'color_header_transparent_text_hover' ) ?>;
	}
.l-header.bg_transparent:not(.sticky) .w-nav-title:after {
	background: <?php echo us_get_color( 'color_header_transparent_text_hover', TRUE ) ?>;
	}

/* Search Colors */
.w-search-form {
	background: <?php echo us_get_color( 'color_header_search_bg', TRUE ) ?>;
	color: <?php echo us_get_color( 'color_header_search_text' ) ?>;
	}

/*************************** Header Menu Colors ***************************/

/* Menu Item on hover */
.menu-item.level_1 > .w-nav-anchor:focus,
.no-touch .menu-item.level_1.opened > .w-nav-anchor,
.no-touch .menu-item.level_1:hover > .w-nav-anchor {
	background: <?php echo us_get_color( 'color_menu_hover_bg', TRUE ) ?>;
	color: <?php echo us_get_color( 'color_menu_hover_text' ) ?>;
	}
.w-nav-title:after {
	background: <?php echo us_get_color( 'color_menu_hover_text', TRUE ) ?>;
	}

/* Active Menu Item */
.menu-item.level_1.current-menu-item > .w-nav-anchor,
.menu-item.level_1.current-menu-parent > .w-nav-anchor,
.menu-item.level_1.current-menu-ancestor > .w-nav-anchor {
	background: <?php echo us_get_color( 'color_menu_active_bg', TRUE ) ?>;
	color: <?php echo us_get_color( 'color_menu_active_text' ) ?>;
	}

/* Active Menu Item in transparent header */
.l-header.bg_transparent:not(.sticky) .type_desktop .menu-item.level_1.current-menu-item > .w-nav-anchor,
.l-header.bg_transparent:not(.sticky) .type_desktop .menu-item.level_1.current-menu-ancestor > .w-nav-anchor {
	background: <?php echo us_get_color( 'color_menu_transparent_active_bg', TRUE ) ?>;
	color: <?php echo us_get_color( 'color_menu_transparent_active_text' ) ?>;
	}

/* Dropdowns */
.w-nav-list:not(.level_1) {
	background: <?php echo us_get_color( 'color_drop_bg', TRUE ) ?>;
	color: <?php echo us_get_color( 'color_drop_text' ) ?>;
	}

/* Dropdown Item on hover */
.no-touch .menu-item:not(.level_1) > .w-nav-anchor:focus,
.no-touch .menu-item:not(.level_1):hover > .w-nav-anchor {
	background: <?php echo us_get_color( 'color_drop_hover_bg', TRUE ) ?>;
	color: <?php echo us_get_color( 'color_drop_hover_text' ) ?>;
	}

/* Dropdown Active Item */
.menu-item:not(.level_1).current-menu-item > .w-nav-anchor,
.menu-item:not(.level_1).current-menu-parent > .w-nav-anchor,
.menu-item:not(.level_1).current-menu-ancestor > .w-nav-anchor {
	background: <?php echo us_get_color( 'color_drop_active_bg', TRUE ) ?>;
	color: <?php echo us_get_color( 'color_drop_active_text' ) ?>;
	}

/* Menu Button */
.btn.menu-item > a {
	background: <?php echo us_get_color( 'color_menu_button_bg', TRUE ) ?> !important;
	color: <?php echo us_get_color( 'color_menu_button_text' ) ?> !important;
	}
.no-touch .btn.menu-item > a:hover {
	background: <?php echo us_get_color( 'color_menu_button_hover_bg', TRUE ) ?> !important;
	color: <?php echo us_get_color( 'color_menu_button_hover_text' ) ?> !important;
	}

/*************************** Content Colors ***************************/

/* Background Color */
body.us_iframe,
.l-preloader,
.l-canvas,
.l-footer,
.l-popup-box-content,
.g-filters.style_1 .g-filters-item.active,
.w-pricing-item-h,
.w-tabs.layout_default .w-tabs-item.active,
.w-tabs.layout_ver .w-tabs-item.active,
.no-touch .w-tabs.layout_default .w-tabs-item.active:hover,
.no-touch .w-tabs.layout_ver .w-tabs-item.active:hover,
.w-tabs.layout_timeline .w-tabs-item,
.w-tabs.layout_timeline .w-tabs-section-header-h,
.leaflet-popup-content-wrapper,
.leaflet-popup-tip,
<?php if ( $with_shop ) { ?>
.w-cart-dropdown,
.woocommerce-tabs .tabs li.active,
.no-touch .woocommerce-tabs .tabs li.active:hover,
.woocommerce .shipping-calculator-form,
.woocommerce #payment .payment_box,
<?php } ?>
<?php if ( $with_forums ) { ?>
#bbp-user-navigation li.current,
<?php } ?>
<?php if ( $with_events ) { ?>
.tribe-bar-collapse .tribe-bar-filters,
.no-touch .tribe-bar-views-option:hover,
.tribe-events-tooltip,
.recurring-info-tooltip,
<?php } ?>
<?php if ( $with_gforms ) { ?>
.chosen-search input,
.chosen-choices li.search-choice,
<?php } ?>
.wpml-ls-statics-footer,
.select2-selection__choice,
.select2-search input {
	background: <?php echo us_get_color( 'color_content_bg', TRUE ) ?>;
	}
<?php if ( $with_shop ) { ?>
.woocommerce #payment .payment_methods li > input:checked + label,
.woocommerce .blockUI.blockOverlay {
	background: <?php echo us_get_color( 'color_content_bg', TRUE ) ?> !important;
	}
<?php } ?>
.w-tabs.layout_modern .w-tabs-item:after {
	border-bottom-color: <?php echo us_get_color( 'color_content_bg' ) ?>;
	}
.w-iconbox.style_circle.color_contrast .w-iconbox-icon {
	color: <?php echo us_get_color( 'color_content_bg' ) ?>;
	}

/* Alternate Background Color */
input,
textarea,
select,
.w-actionbox.color_light,
.w-form-checkbox,
.w-form-radio,
.g-filters.style_1,
.g-filters.style_2 .g-filters-item.active,
.w-flipbox-front,
.w-grid-none,
.w-iconbox.style_circle.color_light .w-iconbox-icon,
.w-pricing-item-header,
.w-progbar-bar,
.w-progbar.style_3 .w-progbar-bar:before,
.w-progbar.style_3 .w-progbar-bar-count,
.w-socials.style_solid .w-socials-item-link,
.w-tabs.layout_default .w-tabs-list,
.w-tabs.layout_ver .w-tabs-list,
.no-touch .l-main .layout_ver .widget_nav_menu a:hover,
.no-touch .owl-carousel.navpos_outside .owl-nav div:hover,
<?php if ( $with_shop ) { ?>
.woocommerce .quantity .plus,
.woocommerce .quantity .minus,
.woocommerce-tabs .tabs,
.woocommerce .cart_totals,
.woocommerce-checkout #order_review,
.woocommerce-table--order-details,
.woocommerce ul.order_details,
<?php } ?>
<?php if ( $with_forums ) { ?>
#subscription-toggle,
#favorite-toggle,
#bbp-user-navigation,
<?php } ?>
<?php if ( $with_events ) { ?>
#tribe-bar-views-toggle,
.tribe-bar-views-list,
.tribe-events-present,
.tribe-events-single-section,
.tribe-events-calendar thead th,
.tribe-mobile .tribe-events-sub-nav li a,
<?php } ?>
<?php if ( $with_gforms ) { ?>
.ginput_container_creditcard,
.chosen-single,
.chosen-drop,
.chosen-choices,
<?php } ?>
.smile-icon-timeline-wrap .timeline-wrapper .timeline-block,
.smile-icon-timeline-wrap .timeline-feature-item.feat-item,
.wpml-ls-legacy-dropdown a,
.wpml-ls-legacy-dropdown-click a,
.tablepress .row-hover tr:hover td,
.select2-selection,
.select2-dropdown {
	background: <?php echo us_get_color( 'color_content_bg_alt', TRUE ) ?>;
	}
.timeline-wrapper .timeline-post-right .ult-timeline-arrow l,
.timeline-wrapper .timeline-post-left .ult-timeline-arrow l,
.timeline-feature-item.feat-item .ult-timeline-arrow l {
	border-color: <?php echo us_get_color( 'color_content_bg_alt' ) ?>;
	}

/* Border Color */
hr,
td,
th,
.l-section,
.vc_column_container,
.vc_column-inner,
.w-comments .children,
.w-image,
.w-pricing-item-h,
.w-profile,
.w-sharing-item,
.w-tabs-list,
.w-tabs-section,
.widget_calendar #calendar_wrap,
.l-main .widget_nav_menu .menu,
.l-main .widget_nav_menu .menu-item a,
<?php if ( $with_shop ) { ?>
.woocommerce .login,
.woocommerce .track_order,
.woocommerce .checkout_coupon,
.woocommerce .lost_reset_password,
.woocommerce .register,
.woocommerce .cart.variations_form,
.woocommerce .commentlist .comment-text,
.woocommerce .comment-respond,
.woocommerce .related,
.woocommerce .upsells,
.woocommerce .cross-sells,
.woocommerce .checkout #order_review,
.widget_price_filter .ui-slider-handle,
<?php } ?>
<?php if ( $with_forums ) { ?>
#bbpress-forums fieldset,
.bbp-login-form fieldset,
#bbpress-forums .bbp-body > ul,
#bbpress-forums li.bbp-header,
.bbp-replies .bbp-body,
div.bbp-forum-header,
div.bbp-topic-header,
div.bbp-reply-header,
.bbp-pagination-links a,
.bbp-pagination-links span.current,
span.bbp-topic-pagination a.page-numbers,
.bbp-logged-in,
<?php } ?>
<?php if ( $with_events ) { ?>
.tribe-events-day-time-slot-heading,
.tribe-events-list-separator-month,
.type-tribe_events + .type-tribe_events,
<?php } ?>
<?php if ( $with_gforms ) { ?>
.gform_wrapper .gsection,
.gform_wrapper .gf_page_steps,
.gform_wrapper li.gfield_creditcard_warning,
.form_saved_message,
<?php } ?>
.smile-icon-timeline-wrap .timeline-line {
	border-color: <?php echo us_get_color( 'color_content_border' ) ?>;
	}
.w-separator.color_border,
.w-iconbox.color_light .w-iconbox-icon {
	color: <?php echo us_get_color( 'color_content_border' ) ?>;
	}
.w-flipbox-back,
.w-iconbox.style_circle.color_light .w-iconbox-icon,
<?php if ( $with_shop ) { ?>
.no-touch .woocommerce .quantity .plus:hover,
.no-touch .woocommerce .quantity .minus:hover,
.no-touch .woocommerce #payment .payment_methods li > label:hover,
.widget_price_filter .ui-slider:before,
<?php } ?>
<?php if ( $with_events ) { ?>
#tribe-bar-collapse-toggle,
<?php } ?>
<?php if ( $with_gforms ) { ?>
.gform_wrapper .gform_page_footer .gform_previous_button,
<?php } ?>
.no-touch .wpml-ls-sub-menu a:hover {
	background: <?php echo us_get_color( 'color_content_border', TRUE ) ?>;
	}
.w-iconbox.style_outlined.color_light .w-iconbox-icon,
.w-person-links-item,
.w-socials.style_outlined .w-socials-item-link,
.pagination .page-numbers {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_content_border' ) ?> inset;
	}
.w-tabs.layout_trendy .w-tabs-list {
	box-shadow: 0 -1px 0 <?php echo us_get_color( 'color_content_border' ) ?> inset;
	}

/* Heading Color */
h1, h2, h3, h4, h5, h6,
<?php if ( $with_shop ) { ?>
.woocommerce .product .price,
<?php } ?>
.w-counter.color_heading .w-counter-value {
	color: <?php echo us_get_color( 'color_content_heading' ) ?>;
	}
.w-progbar.color_heading .w-progbar-bar-h {
	background: <?php echo us_get_color( 'color_content_heading', TRUE ) ?>;
	}
<?php if ( us_get_color( 'h1_color' ) ) { ?>
h1 { color: <?php echo us_get_color( 'h1_color' ) ?> }
<?php }
if ( us_get_color( 'h2_color' ) ) { ?>
h2 { color: <?php echo us_get_color( 'h2_color' ) ?> }
<?php }
if ( us_get_color( 'h3_color' ) ) { ?>
h3 { color: <?php echo us_get_color( 'h3_color' ) ?> }
<?php }
if ( us_get_color( 'h4_color' ) ) { ?>
h4 { color: <?php echo us_get_color( 'h4_color' ) ?> }
<?php }
if ( us_get_color( 'h5_color' ) ) { ?>
h5 { color: <?php echo us_get_color( 'h5_color' ) ?> }
<?php }
if ( us_get_color( 'h6_color' ) ) { ?>
h6 { color: <?php echo us_get_color( 'h6_color' ) ?> }
<?php } ?>


/* Text Color */
input,
textarea,
select,
.l-canvas,
.l-footer,
.l-popup-box-content,
.w-form-row-field > i,
.w-iconbox.color_light.style_circle .w-iconbox-icon,
.w-tabs.layout_timeline .w-tabs-item,
.w-tabs.layout_timeline .w-tabs-section-header-h,
.leaflet-popup-content-wrapper,
.leaflet-popup-tip,
<?php if ( $with_shop ) { ?>
.w-cart-dropdown,
<?php } ?>
.select2-dropdown {
	color: <?php echo us_get_color( 'color_content_text' ) ?>;
	}
.w-iconbox.style_circle.color_contrast .w-iconbox-icon,
.w-progbar.color_text .w-progbar-bar-h,
.w-scroller-dot span {
	background: <?php echo us_get_color( 'color_content_text', TRUE ) ?>;
	}
.w-iconbox.style_outlined.color_contrast .w-iconbox-icon {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_content_text' ) ?> inset;
	}
.w-scroller-dot span {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_content_text' ) ?>;
	}

/* Link Color */
a {
	color: <?php echo us_get_color( 'color_content_link' ) ?>;
	}

/* Link Hover Color */
.no-touch a:hover,
.no-touch .tablepress .sorting:hover {
	color: <?php echo us_get_color( 'color_content_link_hover' ) ?>;
	}
<?php if ( $with_shop ) { ?>
.no-touch .w-cart-dropdown a:not(.button):hover {
	color: <?php echo us_get_color( 'color_content_link_hover' ) ?> !important;
	}
<?php } ?>

/* Primary Color */
.g-preloader,
.l-main .w-contacts-item:before,
.w-counter.color_primary .w-counter-value,
.g-filters.style_1 .g-filters-item.active,
.g-filters.style_3 .g-filters-item.active,
.w-form-row.focused .w-form-row-field > i,
.w-iconbox.color_primary .w-iconbox-icon,
.w-separator.color_primary,
.w-sharing.type_outlined.color_primary .w-sharing-item,
.no-touch .w-sharing.type_simple.color_primary .w-sharing-item:hover .w-sharing-icon,
.w-tabs.layout_default .w-tabs-item.active,
.w-tabs.layout_trendy .w-tabs-item.active,
.w-tabs.layout_ver .w-tabs-item.active,
.w-tabs-section.active .w-tabs-section-header,
.tablepress .sorting_asc,
.tablepress .sorting_desc,
<?php if ( $with_shop ) { ?>
.star-rating span:before,
.woocommerce-tabs .tabs li.active,
.no-touch .woocommerce-tabs .tabs li.active:hover,
.woocommerce #payment .payment_methods li > input:checked + label,
<?php } ?>
<?php if ( $with_forums ) { ?>
#subscription-toggle span.is-subscribed:before,
#favorite-toggle span.is-favorite:before,
<?php } ?>
.highlight_primary {
	color: <?php echo us_get_color( 'color_content_primary' ) ?>;
	}
.l-section.color_primary,
.us-btn-style_badge,
.no-touch .post_navigation.layout_sided a:hover .post_navigation-item-arrow,
.g-placeholder,
.highlight_primary_bg,
.w-actionbox.color_primary,
.w-form-row input:checked + .w-form-checkbox,
.w-form-row input:checked + .w-form-radio,
.no-touch .g-filters.style_1 .g-filters-item:hover,
.no-touch .g-filters.style_2 .g-filters-item:hover,
.w-post-elm-placeholder,
.w-iconbox.style_circle.color_primary .w-iconbox-icon,
.no-touch .w-iconbox.style_circle .w-iconbox-icon:before,
.no-touch .w-iconbox.style_outlined .w-iconbox-icon:before,
.no-touch .w-person-links-item:before,
.w-pricing-item.type_featured .w-pricing-item-header,
.w-progbar.color_primary .w-progbar-bar-h,
.w-sharing.type_solid.color_primary .w-sharing-item,
.w-sharing.type_fixed.color_primary .w-sharing-item,
.w-sharing.type_outlined.color_primary .w-sharing-item:before,
.no-touch .w-sharing-tooltip .w-sharing-item:hover,
.w-socials-item-link-hover,
.w-tabs.layout_modern .w-tabs-list,
.w-tabs.layout_trendy .w-tabs-item:after,
.w-tabs.layout_timeline .w-tabs-item:before,
.w-tabs.layout_timeline .w-tabs-section-header-h:before,
.no-touch .w-header-show:hover,
.no-touch .w-toplink.active:hover,
.no-touch .pagination .page-numbers:before,
.pagination .page-numbers.current,
.l-main .widget_nav_menu .menu-item.current-menu-item > a,
.rsThumb.rsNavSelected,
.no-touch .tp-leftarrow.custom:before,
.no-touch .tp-rightarrow.custom:before,
.smile-icon-timeline-wrap .timeline-separator-text .sep-text,
.smile-icon-timeline-wrap .timeline-wrapper .timeline-dot,
.smile-icon-timeline-wrap .timeline-feature-item .timeline-dot,
<?php if ( $with_shop ) { ?>
p.demo_store,
.woocommerce .onsale,
.widget_price_filter .ui-slider-range,
.widget_layered_nav_filters ul li a,
<?php } ?>
<?php if ( $with_forums ) { ?>
.no-touch .bbp-pagination-links a:hover,
.bbp-pagination-links span.current,
.no-touch span.bbp-topic-pagination a.page-numbers:hover,
<?php } ?>
<?php if ( $with_events ) { ?>
.tribe-events-calendar td.mobile-active,
.datepicker td.day.active,
.datepicker td span.active,
<?php } ?>
<?php if ( $with_gforms ) { ?>
.gform_page_footer .gform_next_button,
.gf_progressbar_percentage,
.chosen-results li.highlighted,
<?php } ?>
.select2-results__option--highlighted {
	background: <?php echo us_get_color( 'color_content_primary', TRUE ) ?>;
	}
.w-tabs.layout_default .w-tabs-item.active,
.w-tabs.layout_ver .w-tabs-item.active,
<?php if ( $with_shop ) { ?>
.woocommerce-product-gallery li img,
.woocommerce-tabs .tabs li.active,
.no-touch .woocommerce-tabs .tabs li.active:hover,
<?php } ?>
<?php if ( $with_forums ) { ?>
.bbp-pagination-links span.current,
.no-touch #bbpress-forums .bbp-pagination-links a:hover,
.no-touch #bbpress-forums .bbp-topic-pagination a:hover,
#bbp-user-navigation li.current,
<?php } ?>
.owl-dot.active span,
.rsBullet.rsNavSelected span,
.tp-bullets.custom .tp-bullet {
	border-color: <?php echo us_get_color( 'color_content_primary' ) ?>;
	}
.l-main .w-contacts-item:before,
.w-iconbox.color_primary.style_outlined .w-iconbox-icon,
.w-sharing.type_outlined.color_primary .w-sharing-item,
.w-tabs.layout_timeline .w-tabs-item,
.w-tabs.layout_timeline .w-tabs-section-header-h {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_content_primary' ) ?> inset;
	}
input:focus,
textarea:focus,
select:focus,
.select2-container--focus .select2-selection {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_content_primary' ) ?>;
	}

/* Secondary Color */
.no-touch .post_navigation.layout_simple a:hover .post_navigation-item-title,
.w-counter.color_secondary .w-counter-value,
.w-iconbox.color_secondary .w-iconbox-icon,
.w-separator.color_secondary,
.w-sharing.type_outlined.color_secondary .w-sharing-item,
.no-touch .w-sharing.type_simple.color_secondary .w-sharing-item:hover .w-sharing-icon,
.highlight_secondary {
	color: <?php echo us_get_color( 'color_content_secondary' ) ?>;
	}
.l-section.color_secondary,
.w-actionbox.color_secondary,
.no-touch .us-btn-style_badge:hover,
.w-iconbox.style_circle.color_secondary .w-iconbox-icon,
.w-progbar.color_secondary .w-progbar-bar-h,
.w-sharing.type_solid.color_secondary .w-sharing-item,
.w-sharing.type_fixed.color_secondary .w-sharing-item,
.w-sharing.type_outlined.color_secondary .w-sharing-item:before,
<?php if ( $with_shop ) { ?>
.no-touch .widget_layered_nav_filters ul li a:hover,
<?php } ?>
.highlight_secondary_bg {
	background: <?php echo us_get_color( 'color_content_secondary', TRUE ) ?>;
	}
.w-separator.color_secondary {
	border-color: <?php echo us_get_color( 'color_content_secondary' ) ?>;
	}
.w-iconbox.color_secondary.style_outlined .w-iconbox-icon,
.w-sharing.type_outlined.color_secondary .w-sharing-item {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_content_secondary' ) ?> inset;
	}

/* Fade Elements Color */
blockquote:before,
.w-form-row-description,
.l-main .post-author-website,
.l-main .w-profile-link.for_logout,
.l-main .widget_tag_cloud,
<?php if ( $with_shop ) { ?>
.l-main .widget_product_tag_cloud,
<?php } ?>
<?php if ( $with_forums ) { ?>
p.bbp-topic-meta,
<?php } ?>
.highlight_faded {
	color: <?php echo us_get_color( 'color_content_faded' ) ?>;
	}
<?php if ( $with_events ) { ?>
.tribe-events-cost,
.tribe-events-list .tribe-events-event-cost {
	background: <?php echo us_get_color( 'color_content_faded', TRUE ) ?>;
	}
<?php } ?>

/*************************** Alternate Content Colors ***************************/

/* Background Color */
.l-section.color_alternate,
.color_alternate .g-filters.style_1 .g-filters-item.active,
.color_alternate .w-pricing-item-h,
.color_alternate .w-tabs.layout_default .w-tabs-item.active,
.no-touch .color_alternate .w-tabs.layout_default .w-tabs-item.active:hover,
.color_alternate .w-tabs.layout_ver .w-tabs-item.active,
.no-touch .color_alternate .w-tabs.layout_ver .w-tabs-item.active:hover,
.color_alternate .w-tabs.layout_timeline .w-tabs-item,
.color_alternate .w-tabs.layout_timeline .w-tabs-section-header-h {
	background: <?php echo us_get_color( 'color_alt_content_bg', TRUE ) ?>;
	}
.color_alternate .w-iconbox.style_circle.color_contrast .w-iconbox-icon {
	color: <?php echo us_get_color( 'color_alt_content_bg' ) ?>;
	}
.color_alternate .w-tabs.layout_modern .w-tabs-item:after {
	border-bottom-color: <?php echo us_get_color( 'color_alt_content_bg' ) ?>;
	}

/* Alternate Background Color */
.color_alternate input:not([type="submit"]),
.color_alternate textarea,
.color_alternate select,
.color_alternate .w-form-checkbox,
.color_alternate .w-form-radio,
.color_alternate .g-filters.style_1,
.color_alternate .g-filters.style_2 .g-filters-item.active,
.color_alternate .w-grid-none,
.color_alternate .w-iconbox.style_circle.color_light .w-iconbox-icon,
.color_alternate .w-pricing-item-header,
.color_alternate .w-progbar-bar,
.color_alternate .w-socials.style_solid .w-socials-item-link,
.color_alternate .w-tabs.layout_default .w-tabs-list,
.color_alternate .ginput_container_creditcard {
	background: <?php echo us_get_color( 'color_alt_content_bg_alt', TRUE ) ?>;
	}

/* Border Color */
.l-section.color_alternate,
.color_alternate td,
.color_alternate th,
.color_alternate .vc_column_container,
.color_alternate .vc_column-inner,
.color_alternate .w-comments .children,
.color_alternate .w-image,
.color_alternate .w-pricing-item-h,
.color_alternate .w-profile,
.color_alternate .w-sharing-item,
.color_alternate .w-tabs-list,
.color_alternate .w-tabs-section {
	border-color: <?php echo us_get_color( 'color_alt_content_border' ) ?>;
	}
.color_alternate .w-separator.color_border,
.color_alternate .w-iconbox.color_light .w-iconbox-icon {
	color: <?php echo us_get_color( 'color_alt_content_border' ) ?>;
	}
.color_alternate .w-iconbox.style_circle.color_light .w-iconbox-icon {
	background: <?php echo us_get_color( 'color_alt_content_border', TRUE ) ?>;
	}
.color_alternate .w-iconbox.style_outlined.color_light .w-iconbox-icon,
.color_alternate .w-person-links-item,
.color_alternate .w-socials.style_outlined .w-socials-item-link,
.color_alternate .pagination .page-numbers {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_alt_content_border' ) ?> inset;
	}
.color_alternate .w-tabs.layout_trendy .w-tabs-list {
	box-shadow: 0 -1px 0 <?php echo us_get_color( 'color_alt_content_border' ) ?> inset;
	}

/* Heading Color */
.l-section.color_alternate h1,
.l-section.color_alternate h2,
.l-section.color_alternate h3,
.l-section.color_alternate h4,
.l-section.color_alternate h5,
.l-section.color_alternate h6,
.l-section.color_alternate .w-counter-value {
	color: <?php echo us_get_color( 'color_alt_content_heading' ) ?>;
	}
.color_alternate .w-progbar.color_contrast .w-progbar-bar-h {
	background: <?php echo us_get_color( 'color_alt_content_heading', TRUE ) ?>;
	}

/* Text Color */
.l-section.color_alternate,
.color_alternate input,
.color_alternate textarea,
.color_alternate select,
.color_alternate .w-form-row-field > i,
.color_alternate .w-iconbox.color_contrast .w-iconbox-icon,
.color_alternate .w-iconbox.color_light.style_circle .w-iconbox-icon,
.color_alternate .w-tabs.layout_timeline .w-tabs-item,
.color_alternate .w-tabs.layout_timeline .w-tabs-section-header-h {
	color: <?php echo us_get_color( 'color_alt_content_text' ) ?>;
	}
.color_alternate .w-iconbox.style_circle.color_contrast .w-iconbox-icon {
	background: <?php echo us_get_color( 'color_alt_content_text', TRUE ) ?>;
	}
.color_alternate .w-iconbox.style_outlined.color_contrast .w-iconbox-icon {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_alt_content_text' ) ?> inset;
	}

/* Link Color */
.color_alternate a {
	color: <?php echo us_get_color( 'color_alt_content_link' ) ?>;
	}

/* Link Hover Color */
.no-touch .color_alternate a:hover {
	color: <?php echo us_get_color( 'color_alt_content_link_hover' ) ?>;
	}

/* Primary Color */
.color_alternate .highlight_primary,
.l-main .color_alternate .w-contacts-item:before,
.color_alternate .w-counter.color_primary .w-counter-value,
.color_alternate .g-preloader,
.color_alternate .g-filters.style_1 .g-filters-item.active,
.color_alternate .g-filters.style_3 .g-filters-item.active,
.color_alternate .w-form-row.focused .w-form-row-field > i,
.color_alternate .w-iconbox.color_primary .w-iconbox-icon,
.color_alternate .w-separator.color_primary,
.color_alternate .w-tabs.layout_default .w-tabs-item.active,
.color_alternate .w-tabs.layout_trendy .w-tabs-item.active,
.color_alternate .w-tabs.layout_ver .w-tabs-item.active,
.color_alternate .w-tabs-section.active .w-tabs-section-header {
	color: <?php echo us_get_color( 'color_alt_content_primary' ) ?>;
	}
.color_alternate .highlight_primary_bg,
.color_alternate .w-actionbox.color_primary,
.no-touch .color_alternate .g-filters.style_1 .g-filters-item:hover,
.no-touch .color_alternate .g-filters.style_2 .g-filters-item:hover,
.color_alternate .w-iconbox.style_circle.color_primary .w-iconbox-icon,
.no-touch .color_alternate .w-iconbox.style_circle .w-iconbox-icon:before,
.no-touch .color_alternate .w-iconbox.style_outlined .w-iconbox-icon:before,
.color_alternate .w-pricing-item.type_featured .w-pricing-item-header,
.color_alternate .w-progbar.color_primary .w-progbar-bar-h,
.color_alternate .w-tabs.layout_modern .w-tabs-list,
.color_alternate .w-tabs.layout_trendy .w-tabs-item:after,
.color_alternate .w-tabs.layout_timeline .w-tabs-item:before,
.color_alternate .w-tabs.layout_timeline .w-tabs-section-header-h:before,
.no-touch .color_alternate .pagination .page-numbers:before,
.color_alternate .pagination .page-numbers.current {
	background: <?php echo us_get_color( 'color_alt_content_primary', TRUE ) ?>;
	}
.color_alternate .w-tabs.layout_default .w-tabs-item.active,
.color_alternate .w-tabs.layout_ver .w-tabs-item.active,
.no-touch .color_alternate .w-tabs.layout_default .w-tabs-item.active:hover,
.no-touch .color_alternate .w-tabs.layout_ver .w-tabs-item.active:hover {
	border-color: <?php echo us_get_color( 'color_alt_content_primary' ) ?>;
	}
.l-main .color_alternate .w-contacts-item:before,
.color_alternate .w-iconbox.color_primary.style_outlined .w-iconbox-icon,
.color_alternate .w-tabs.layout_timeline .w-tabs-item,
.color_alternate .w-tabs.layout_timeline .w-tabs-section-header-h {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_alt_content_primary' ) ?> inset;
	}
.color_alternate input:focus,
.color_alternate textarea:focus,
.color_alternate select:focus {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_alt_content_primary' ) ?>;
	}

/* Secondary Color */
.color_alternate .highlight_secondary,
.color_alternate .w-counter.color_secondary .w-counter-value,
.color_alternate .w-iconbox.color_secondary .w-iconbox-icon,
.color_alternate .w-separator.color_secondary {
	color: <?php echo us_get_color( 'color_alt_content_secondary' ) ?>;
	}
.color_alternate .highlight_secondary_bg,
.color_alternate .w-actionbox.color_secondary,
.color_alternate .w-iconbox.style_circle.color_secondary .w-iconbox-icon,
.color_alternate .w-progbar.color_secondary .w-progbar-bar-h {
	background: <?php echo us_get_color( 'color_alt_content_secondary', TRUE ) ?>;
	}
.color_alternate .w-iconbox.color_secondary.style_outlined .w-iconbox-icon {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_alt_content_secondary' ) ?> inset;
	}

/* Fade Elements Color */
.color_alternate .highlight_faded,
.color_alternate .w-profile-link.for_logout {
	color: <?php echo us_get_color( 'color_alt_content_faded' ) ?>;
	}

/*************************** Top Footer Colors ***************************/

/* Background Color */
.color_footer-top {
	background: <?php echo us_get_color( 'color_subfooter_bg', TRUE ) ?>;
	}

/* Alternate Background Color */
.color_footer-top input:not([type="submit"]),
.color_footer-top textarea,
.color_footer-top select,
.color_footer-top .w-form-checkbox,
.color_footer-top .w-form-radio,
.color_footer-top .w-socials.style_solid .w-socials-item-link {
	background: <?php echo us_get_color( 'color_subfooter_bg_alt', TRUE ) ?>;
	}

/* Border Color */
.color_footer-top,
.color_footer-top td,
.color_footer-top th,
.color_footer-top .vc_column_container,
.color_footer-top .vc_column-inner,
.color_footer-top .w-image,
.color_footer-top .w-pricing-item-h,
.color_footer-top .w-profile,
.color_footer-top .w-sharing-item,
.color_footer-top .w-tabs-list,
.color_footer-top .w-tabs-section {
	border-color: <?php echo us_get_color( 'color_subfooter_border' ) ?>;
	}
.color_footer-top .w-separator.color_border {
	color: <?php echo us_get_color( 'color_subfooter_border' ) ?>;
	}
.color_footer-top .w-socials.style_outlined .w-socials-item-link {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_subfooter_border' ) ?> inset;
	}

/* Text Color */
.color_footer-top {
	color: <?php echo us_get_color( 'color_subfooter_text' ) ?>;
	}

/* Link Color */
.color_footer-top a {
	color: <?php echo us_get_color( 'color_subfooter_link' ) ?>;
	}

/* Link Hover Color */
.no-touch .color_footer-top a:hover,
.color_footer-top .w-form-row.focused .w-form-row-field > i {
	color: <?php echo us_get_color( 'color_subfooter_link_hover' ) ?>;
	}
.color_footer-top input:focus,
.color_footer-top textarea:focus,
.color_footer-top select:focus {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_subfooter_link_hover' ) ?>;
	}

/*************************** Bottom Footer Colors ***************************/

/* Background Color */
.color_footer-bottom {
	background: <?php echo us_get_color( 'color_footer_bg', TRUE ) ?>;
	}

/* Alternate Background Color */
.color_footer-bottom input:not([type="submit"]),
.color_footer-bottom textarea,
.color_footer-bottom select,
.color_footer-bottom .w-form-checkbox,
.color_footer-bottom .w-form-radio,
.color_footer-bottom .w-socials.style_solid .w-socials-item-link {
	background: <?php echo us_get_color( 'color_footer_bg_alt', TRUE ) ?>;
	}

/* Border Color */
.color_footer-bottom,
.color_footer-bottom td,
.color_footer-bottom th,
.color_footer-bottom .vc_column_container,
.color_footer-bottom .vc_column-inner,
.color_footer-bottom .w-image,
.color_footer-bottom .w-pricing-item-h,
.color_footer-bottom .w-profile,
.color_footer-bottom .w-sharing-item,
.color_footer-bottom .w-tabs-list,
.color_footer-bottom .w-tabs-section {
	border-color: <?php echo us_get_color( 'color_footer_border' ) ?>;
	}
.color_footer-bottom .w-separator.color_border {
	color: <?php echo us_get_color( 'color_footer_border' ) ?>;
	}
.color_footer-bottom .w-socials.style_outlined .w-socials-item-link {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_footer_border' ) ?> inset;
	}

/* Text Color */
.color_footer-bottom {
	color: <?php echo us_get_color( 'color_footer_text' ) ?>;
	}

/* Link Color */
.color_footer-bottom a {
	color: <?php echo us_get_color( 'color_footer_link' ) ?>;
	}

/* Link Hover Color */
.no-touch .color_footer-bottom a:hover,
.color_footer-bottom .w-form-row.focused .w-form-row-field > i {
	color: <?php echo us_get_color( 'color_footer_link_hover' ) ?>;
	}
.color_footer-bottom input:focus,
.color_footer-bottom textarea:focus,
.color_footer-bottom select:focus {
	box-shadow: 0 0 0 2px <?php echo us_get_color( 'color_footer_link_hover' ) ?>;
	}

/* Menu Dropdown Settings
   =============================================================================================================================== */
<?php
global $wpdb;
$wpdb_query = 'SELECT `id` FROM `' . $wpdb->posts . '` WHERE `post_type` = "nav_menu_item"';
$menu_items = array();
foreach ( $wpdb->get_results( $wpdb_query ) as $result ) {
	$menu_items[] = $result->id;
}
foreach ($menu_items as $menu_item_id):
	$settings = ( get_post_meta( $menu_item_id, 'us_mega_menu_settings', TRUE ) ) ? get_post_meta( $menu_item_id, 'us_mega_menu_settings', TRUE ) : array();
	if ( empty($settings) ) continue; ?>

<?php if ( $settings['columns'] != '1' AND $settings['width'] == 'full' ): ?>
.header_hor .w-nav.type_desktop .menu-item-<?php echo $menu_item_id; ?> {
	position: static;
}
.header_hor .w-nav.type_desktop .menu-item-<?php echo $menu_item_id; ?> .w-nav-list.level_2 {
	left: 0;
	right: 0;
	width: 100%;
	transform-origin: 50% 0;
}
.headerinpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_desktop .menu-item-<?php echo $menu_item_id; ?> .w-nav-list.level_2 {
	transform-origin: 50% 100%;
}
<?php endif; ?>

<?php if ( $settings['direction'] == 1 AND ( $settings['columns'] == '1' OR ( $settings['columns'] != '1' AND $settings['width'] == 'custom' ) ) ): ?>
.header_hor:not(.rtl) .w-nav.type_desktop .menu-item-<?php echo $menu_item_id; ?> .w-nav-list.level_2 {
	right: 0;
	transform-origin: 100% 0;
}
.header_hor.rtl .w-nav.type_desktop .menu-item-<?php echo $menu_item_id; ?> .w-nav-list.level_2 {
	left: 0;
	transform-origin: 0 0;
	}
<?php endif; ?>

.w-nav.type_desktop .menu-item-<?php echo $menu_item_id; ?> .w-nav-list.level_2 {
	<?php
	$background_color = $settings['color_bg'];
	$background_image = '';
	// Add image properties when image is set
	if ( $settings['bg_image'] AND $bg_image = usof_get_image_src( $settings['bg_image'] ) ) {
		$background_image .= 'url(' . $bg_image[0] . ') ';
		$background_image .= $settings['bg_image_position'];
		if ( $settings['bg_image_size'] != 'initial' ) {
			$background_image .= '/' . $settings['bg_image_size'];
		}
		$background_image .= ' ';
		$background_image .= $settings['bg_image_repeat'];
		// If the color value contains gradient, add comma for correct appearance
		if ( strpos( $background_color, 'gradient' ) !== FALSE ) {
			$background_image .= ',';
		}
	}
	// Output single combined background value
	echo 'background:' . $background_image . $background_color . ';';

	if ( $settings['color_text'] != '' ) {
		echo 'color:' . us_gradient2hex( $settings['color_text'] ) . ';';
	}
	if ( $settings['columns'] != '1' AND $settings['width'] == 'custom' ) {
		echo 'width:' . $settings['custom_width'] . ';';
	}
	echo 'padding:' . $settings['padding'] . ';';
	?>
}

<?php endforeach; ?>
