<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Generates and outputs header generated stylesheets
 *
 * @action Before the template: us_before_template:config/header.css
 * @action After the template: us_after_template:config/header.css
 */
global $us_template_directory;

/* Set breakpoint values */
$tablets_breakpoint = us_get_header_option( 'breakpoint', 'tablets' ) ? us_get_header_option( 'breakpoint', 'tablets' ) : '901';
$mobiles_breakpoint = us_get_header_option( 'breakpoint', 'mobiles' ) ? us_get_header_option( 'breakpoint', 'mobiles' ) : '601';
$desktop_query = '(min-width:' . intval( $tablets_breakpoint ) . 'px)';
$tablets_query = '(min-width:' . intval( $mobiles_breakpoint ) . 'px) and (max-width:' . ( intval( $tablets_breakpoint ) - 1 ) . 'px)';
$mobiles_query = '(max-width:' . ( intval( $mobiles_breakpoint ) - 1 ) . 'px)';

/* Header styles as variables */
$header_hor_styles = file_get_contents( $us_template_directory . '/css/base/header-hor.css' );
$header_ver_styles = file_get_contents( $us_template_directory . '/css/base/header-ver.css' );
?>

/* =============================================== */
/* ================ Default state ================ */
/* =============================================== */

@media <?php echo $desktop_query ?> {

	.hidden_for_default { display: none !important; }

<?php if ( ! us_get_header_option( 'top_show' ) ) { ?>
	.l-subheader.at_top { display: none; }
<?php }
if ( ! us_get_header_option( 'bottom_show' ) ) { ?>
	.l-subheader.at_bottom { display: none; }
<?php }
if ( us_get_header_option( 'bg_img' ) AND $bg_image = usof_get_image_src( us_get_header_option( 'bg_img' ) ) ) { ?>
	.l-subheader.at_middle {
		background-image: url(<?php echo $bg_image[0] ?>);
		background-attachment: <?php echo ( us_get_header_option( 'bg_img_attachment' ) ) ? 'scroll' : 'fixed'; ?>;
		background-position: <?php echo us_get_header_option( 'bg_img_position' ) ?>;
		background-repeat: <?php echo us_get_header_option( 'bg_img_repeat' ) ?>;
		background-size: <?php echo us_get_header_option( 'bg_img_size' ) ?>;
	}
<?php }

// Horizontal header
if ( us_get_header_option( 'orientation' ) == 'hor' ) {
	echo $header_hor_styles;
?>
	.l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_height' ) ?>;
		height: <?php echo us_get_header_option( 'top_height' ) ?>;
	}
	.l-header.sticky .l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_sticky_height' ) ?>;
		height: <?php echo us_get_header_option( 'top_sticky_height' ) ?>;
	<?php if ( us_get_header_option( 'top_sticky_height' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_height' ) ?>;
		height: <?php echo us_get_header_option( 'middle_height' ) ?>;
	}
	.l-header.sticky .l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_sticky_height' ) ?>;
		height: <?php echo us_get_header_option( 'middle_sticky_height' ) ?>;
	<?php if ( us_get_header_option( 'middle_sticky_height' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_height' ) ?>;
		height: <?php echo us_get_header_option( 'bottom_height' ) ?>;
	}
	.l-header.sticky .l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_sticky_height' ) ?>;
		height: <?php echo us_get_header_option( 'bottom_sticky_height' ) ?>;
	<?php if ( us_get_header_option( 'bottom_sticky_height' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	/* Center the middle cell */
	.l-subheader.with_centering .l-subheader-cell.at_left,
	.l-subheader.with_centering .l-subheader-cell.at_right {
		flex-basis: 100px;
		}

	/* Calculate top padding for content overlapped by sticky header */
	.l-header.pos_fixed ~ .l-main > .l-section:first-of-type,
	.l-header.pos_fixed ~ .l-main > .l-section-gap:nth-child(2),
	.headerinpos_below .l-header.pos_fixed ~ .l-main > .l-section:nth-of-type(2),
	.l-header.pos_static.bg_transparent ~ .l-main > .l-section:first-of-type {
	<?php
	$header_height = us_get_header_option( 'top_show' ) ? intval( us_get_header_option( 'top_height' ) ) : 0;
	$header_height += intval( us_get_header_option( 'middle_height' ) );
	$header_height += us_get_header_option( 'bottom_show' ) ? intval( us_get_header_option( 'bottom_height' ) ) : 0;
	?>
		padding-top: <?php echo $header_height ?>px;
	}
	.headerinpos_bottom .l-header.pos_fixed ~ .l-main > .l-section:first-of-type {
		padding-bottom: <?php echo $header_height ?>px;
	}

	/* Fix vertical centering of first section when header is transparent */
	.l-header.bg_transparent ~ .l-main .l-section.valign_center:first-of-type > .l-section-h {
		top: -<?php echo $header_height/2 ?>px;
	}
	.headerinpos_bottom .l-header.pos_fixed.bg_transparent ~ .l-main .l-section.valign_center:first-of-type > .l-section-h {
		top: <?php echo $header_height/2 ?>px;
	}

	/* Calculate height of "Full Screen" rows */
	.l-header.pos_fixed ~ .l-main .l-section.height_full:not(:first-of-type) {
	<?php
	$header_sticky_height = us_get_header_option( 'top_show' ) ? intval( us_get_header_option( 'top_sticky_height' ) ) : 0;
	$header_sticky_height += intval( us_get_header_option( 'middle_sticky_height' ) );
	$header_sticky_height += us_get_header_option( 'bottom_show' ) ? intval( us_get_header_option( 'bottom_sticky_height' ) ) : 0;
	?>
		min-height: calc(100vh - <?php echo $header_sticky_height ?>px);
	}
	.admin-bar .l-header.pos_fixed ~ .l-main .l-section.height_full:not(:first-of-type) {
		min-height: calc(100vh - <?php echo $header_sticky_height + 32 ?>px);
	}
	.l-header.pos_static.bg_solid ~ .l-main .l-section.height_full:first-of-type {
		min-height: calc(100vh - <?php echo $header_height ?>px);
	}

	/* Calculate position of "Sticky" rows */
	.l-header.pos_fixed ~ .l-main .l-section.sticky {
		top: <?php echo $header_sticky_height ?>px;
	}
	.admin-bar .l-header.pos_fixed ~ .l-main .l-section.sticky {
		top: <?php echo $header_sticky_height + 32 ?>px;
	}
	.l-header.pos_fixed.sticky ~ .l-main .l-section.type_sticky:first-of-type {
		padding-top: <?php echo $header_sticky_height ?>px;
	}
	
	/* Initial header position BOTTOM & BELOW */
	.headerinpos_below .l-header.pos_fixed:not(.sticky) {
		position: absolute;
		top: 100%;
	}
	.headerinpos_bottom .l-header.pos_fixed:not(.sticky) {
		position: absolute;
		bottom: 0;
	}
	.headerinpos_below .l-header.pos_fixed ~ .l-main > .l-section:first-of-type,
	.headerinpos_bottom .l-header.pos_fixed ~ .l-main > .l-section:first-of-type {
		padding-top: 0 !important;
	}
	.headerinpos_below .l-header.pos_fixed ~ .l-main .l-section.height_full:nth-of-type(2) {
		min-height: 100vh;
	}
	.admin-bar.headerinpos_below .l-header.pos_fixed ~ .l-main .l-section.height_full:nth-of-type(2) {
		min-height: calc(100vh - 32px); /* WP admin bar height */
	}
	.headerinpos_bottom .l-header.pos_fixed:not(.sticky) .w-cart-dropdown,
	.headerinpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_desktop .w-nav-list.level_2 {
		bottom: 100%;
		transform-origin: 0 100%;
	}
	.headerinpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_mobile.m_layout_dropdown .w-nav-list.level_1 {
		top: auto;
		bottom: 100%;
		box-shadow: 0 -3px 3px rgba(0,0,0,0.1);
	}	
	.headerinpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_desktop .w-nav-list.level_3,
	.headerinpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_desktop .w-nav-list.level_4 {
		top: auto;
		bottom: 0;
		transform-origin: 0 100%;
	}
<?php } else {
// Vertical header
	echo $header_ver_styles;
?>
	html:not(.no-touch) .l-header.scrollable {
		position: absolute;
		height: 100%;
	}
	.l-body {
		padding-left: <?php echo us_get_header_option( 'width' ) ?>;
		position: relative;
	}
	.l-body.rtl {
		padding-left: 0;
		padding-right: <?php echo us_get_header_option( 'width' ) ?>;
	}
	.l-header,
	.l-header .w-cart-notification,
	.w-nav.type_mobile.m_layout_panel .w-nav-list.level_1 {
		width: <?php echo us_get_header_option( 'width' ) ?>;
	}
	.l-body.rtl .l-header {
		left: auto;
		right: 0;
	}
	.l-body:not(.rtl) .post_navigation.layout_sided .order_first {
		left: calc(<?php echo us_get_header_option( 'width' ) ?> - 14rem);
	}
	.l-body:not(.rtl) .w-toplink.pos_left,
	.l-body:not(.rtl) .l-section.sticky,
	.no-touch .l-body:not(.rtl) .post_navigation.layout_sided .order_first:hover {
		left: <?php echo us_get_header_option( 'width' ) ?>;
	}
	.l-body.rtl .post_navigation.layout_sided .order_second {
		right: calc(<?php echo us_get_header_option( 'width' ) ?> - 14rem);
	}
	.l-body.rtl .w-toplink.pos_right,
	.l-body.rtl .l-section.sticky,
	.no-touch .l-body.rtl .post_navigation.layout_sided .order_second:hover {
		right: <?php echo us_get_header_option( 'width' ) ?>;
	}
	.w-nav.type_desktop [class*="columns"] .w-nav-list.level_2 {
		width: calc(100vw - <?php echo us_get_header_option( 'width' ) ?>);
		max-width: 980px;
	}
	.rtl .w-nav.type_desktop .w-nav-list.level_2 {
		left: auto;
		right: 100%;
		}
<?php
if ( us_get_header_option( 'elm_align' ) == 'left' ) { ?>
	.l-subheader-cell {
		text-align: left;
		align-items: flex-start;
	}
<?php }
if ( us_get_header_option( 'elm_align' ) == 'right' ) { ?>
	.l-subheader-cell {
		text-align: right;
		align-items: flex-end;
	}
<?php }
if ( us_get_header_option( 'elm_valign' ) == 'middle' ) { ?>
	.l-subheader.at_middle {
		display: flex;
		align-items: center;
	}
<?php }
if ( us_get_header_option( 'elm_valign' ) == 'bottom' ) { ?>
	.l-subheader.at_middle {
		display: flex;
		align-items: flex-end;
	}
<?php }
}
?>
}



/* =============================================== */
/* ================ Tablets state ================ */
/* =============================================== */

@media <?php echo $tablets_query ?> {

	.hidden_for_tablets { display: none !important; }

<?php if ( ! us_get_header_option( 'top_show', 'tablets' ) ) { ?>
	.l-subheader.at_top { display: none; }
<?php }
if ( ! us_get_header_option( 'bottom_show', 'tablets' ) ) { ?>
	.l-subheader.at_bottom { display: none; }
<?php }
if ( us_get_header_option( 'bg_img', 'tablets' ) AND $bg_image = usof_get_image_src( us_get_header_option( 'bg_img', 'tablets' ) ) ) { ?>
	.l-subheader.at_middle {
		background-image: url(<?php echo $bg_image[0] ?>);
		background-attachment: <?php echo ( us_get_header_option( 'bg_img_attachment', 'tablets' ) ) ? 'scroll' : 'fixed'; ?>;
		background-position: <?php echo us_get_header_option( 'bg_img_position', 'tablets' ) ?>;
		background-repeat: <?php echo us_get_header_option( 'bg_img_repeat', 'tablets' ) ?>;
		background-size: <?php echo us_get_header_option( 'bg_img_size', 'tablets' ) ?>;
	}
<?php }

// Horizontal header on Tablets
if ( us_get_header_option( 'orientation', 'tablets' ) == 'hor' ) {
	echo $header_hor_styles;
?>
	.l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_height', 'tablets' ) ?>;
		height: <?php echo us_get_header_option( 'top_height', 'tablets' ) ?>;
	}
	.l-header.sticky .l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_sticky_height', 'tablets' ) ?>;
		height: <?php echo us_get_header_option( 'top_sticky_height', 'tablets' ) ?>;
	<?php if ( us_get_header_option( 'top_sticky_height', 'tablets' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_height', 'tablets' ) ?>;
		height: <?php echo us_get_header_option( 'middle_height', 'tablets' ) ?>;
	}
	.l-header.sticky .l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_sticky_height', 'tablets' ) ?>;
		height: <?php echo us_get_header_option( 'middle_sticky_height', 'tablets' ) ?>;
	<?php if ( us_get_header_option( 'middle_sticky_height', 'tablets' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_height', 'tablets' ) ?>;
		height: <?php echo us_get_header_option( 'bottom_height', 'tablets' ) ?>;
	}
	.l-header.sticky .l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_sticky_height', 'tablets' ) ?>;
		height: <?php echo us_get_header_option( 'bottom_sticky_height', 'tablets' ) ?>;
	<?php if ( us_get_header_option( 'bottom_sticky_height', 'tablets' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	/* Calculate top padding for content overlapped by sticky header */
	.l-header.pos_fixed ~ .l-main > .l-section:first-of-type,
	.l-header.pos_fixed ~ .l-main > .l-section-gap:nth-child(2),
	.headerinpos_below .l-header.pos_fixed ~ .l-main > .l-section:nth-of-type(2),
	.l-header.pos_static.bg_transparent ~ .l-main > .l-section:first-of-type {
	<?php
	$header_height = us_get_header_option( 'top_show', 'tablets' ) ? intval( us_get_header_option( 'top_height', 'tablets' ) ) : 0;
	$header_height += intval( us_get_header_option( 'middle_height', 'tablets' ) );
	$header_height += us_get_header_option( 'bottom_show', 'tablets' ) ? intval( us_get_header_option( 'bottom_height', 'tablets' ) ) : 0;
	?>
		padding-top: <?php echo $header_height ?>px;
	}

	/* Calculate position of "Sticky" rows */
	.l-header.pos_fixed ~ .l-main .l-section.sticky {
	<?php
	$header_sticky_height = us_get_header_option( 'top_show', 'tablets' ) ? intval( us_get_header_option( 'top_sticky_height', 'tablets' ) ) : 0;
	$header_sticky_height += intval( us_get_header_option( 'middle_sticky_height', 'tablets' ) );
	$header_sticky_height += us_get_header_option( 'bottom_show', 'tablets' ) ? intval( us_get_header_option( 'bottom_sticky_height', 'tablets' ) ) : 0;
	?>
		top: <?php echo $header_sticky_height ?>px;
	}
	.l-header.pos_fixed.sticky ~ .l-main .l-section.type_sticky:first-of-type {
		padding-top: <?php echo $header_sticky_height ?>px;
	}
<?php } else {
// Vertical header on Mobiles
	echo $header_ver_styles;
?>
	.l-header,
	.l-header .w-cart-notification,
	.w-nav.type_mobile.m_layout_panel .w-nav-list.level_1 {
		width: <?php echo us_get_header_option( 'width', 'tablets' ) ?>;
	}
	.w-search.layout_simple,
	.w-search.layout_modern.active {
		width: calc(<?php echo us_get_header_option( 'width', 'tablets' ) ?> - 40px);
	}

	/* Slided vertical header */
	.w-header-show,
	.w-header-overlay {
		display: block;
	}
	.l-header {
		bottom: 0;
		overflow-y: auto;
		-webkit-overflow-scrolling: touch;
		box-shadow: none;
		transition: transform 0.3s;
		transform: translate3d(-100%,0,0);
	}
	.header-show .l-header {
		transform: translate3d(0,0,0);
	}
<?php if ( us_get_header_option( 'elm_align', 'tablets' ) == 'left' ) { ?>
	.l-subheader-cell {
		text-align: left;
		align-items: flex-start;
	}
<?php }
if ( us_get_header_option( 'elm_align', 'tablets' ) == 'right' ) { ?>
	.l-subheader-cell {
		text-align: right;
		align-items: flex-end;
	}
<?php }
}
?>
}



/* =============================================== */
/* ================ Mobiles state ================ */
/* =============================================== */

@media <?php echo $mobiles_query ?> {

	.hidden_for_mobiles { display: none !important; }

<?php if ( ! us_get_header_option( 'top_show', 'mobiles' ) ) { ?>
	.l-subheader.at_top { display: none; }
<?php }
if ( ! us_get_header_option( 'bottom_show', 'mobiles' ) ) { ?>
	.l-subheader.at_bottom { display: none; }
<?php }
if ( us_get_header_option( 'bg_img', 'mobiles' ) AND $bg_image = usof_get_image_src( us_get_header_option( 'bg_img', 'mobiles' ) ) ) { ?>
	.l-subheader.at_middle {
		background-image: url(<?php echo $bg_image[0] ?>);
		background-attachment: <?php echo ( us_get_header_option( 'bg_img_attachment', 'mobiles' ) ) ? 'scroll' : 'fixed'; ?>;
		background-position: <?php echo us_get_header_option( 'bg_img_position', 'mobiles' ) ?>;
		background-repeat: <?php echo us_get_header_option( 'bg_img_repeat', 'mobiles' ) ?>;
		background-size: <?php echo us_get_header_option( 'bg_img_size', 'mobiles' ) ?>;
	}
<?php }

// Horizontal header on Mobiles
if ( us_get_header_option( 'orientation', 'mobiles' ) == 'hor' ) {
	echo $header_hor_styles;
?>
	.l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_height', 'mobiles' ) ?>;
		height: <?php echo us_get_header_option( 'top_height', 'mobiles' ) ?>;
	}
	.l-header.sticky .l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_sticky_height', 'mobiles' ) ?>;
		height: <?php echo us_get_header_option( 'top_sticky_height', 'mobiles' ) ?>;
	<?php if ( us_get_header_option( 'top_sticky_height', 'mobiles' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_height', 'mobiles' ) ?>;
		height: <?php echo us_get_header_option( 'middle_height', 'mobiles' ) ?>;
	}
	.l-header.sticky .l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_sticky_height', 'mobiles' ) ?>;
		height: <?php echo us_get_header_option( 'middle_sticky_height', 'mobiles' ) ?>;
	<?php if ( us_get_header_option( 'middle_sticky_height', 'mobiles' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_height', 'mobiles' ) ?>;
		height: <?php echo us_get_header_option( 'bottom_height', 'mobiles' ) ?>;
	}
	.l-header.sticky .l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_sticky_height', 'mobiles' ) ?>;
		height: <?php echo us_get_header_option( 'bottom_sticky_height', 'mobiles' ) ?>;
	<?php if ( us_get_header_option( 'bottom_sticky_height', 'mobiles' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	/* Calculate top padding for content overlapped by sticky header */
	.l-header.pos_fixed ~ .l-main > .l-section:first-of-type,
	.l-header.pos_fixed ~ .l-main > .l-section-gap:nth-child(2),
	.headerinpos_below .l-header.pos_fixed ~ .l-main > .l-section:nth-of-type(2),
	.l-header.pos_static.bg_transparent ~ .l-main > .l-section:first-of-type {
	<?php
	$header_height = us_get_header_option( 'top_show', 'mobiles' ) ? intval( us_get_header_option( 'top_height', 'mobiles' ) ) : 0;
	$header_height += intval( us_get_header_option( 'middle_height', 'mobiles' ) );
	$header_height += us_get_header_option( 'bottom_show', 'mobiles' ) ? intval( us_get_header_option( 'bottom_height', 'mobiles' ) ) : 0;
	?>
		padding-top: <?php echo $header_height ?>px;
	}

	/* Calculate position of "Sticky" rows */
	.l-header.pos_fixed ~ .l-main .l-section.sticky {
	<?php
	$header_sticky_height = us_get_header_option( 'top_show', 'mobiles' ) ? intval( us_get_header_option( 'top_sticky_height', 'mobiles' ) ) : 0;
	$header_sticky_height += intval( us_get_header_option( 'middle_sticky_height', 'mobiles' ) );
	$header_sticky_height += us_get_header_option( 'bottom_show', 'mobiles' ) ? intval( us_get_header_option( 'bottom_sticky_height', 'mobiles' ) ) : 0;
	?>
		top: <?php echo $header_sticky_height ?>px;
	}
	.l-header.pos_fixed.sticky ~ .l-main .l-section.type_sticky:first-of-type {
		padding-top: <?php echo $header_sticky_height ?>px;
	}
<?php } else {
// Vertical header on Mobiles
	echo $header_ver_styles;
?>
	.l-header,
	.l-header .w-cart-notification,
	.w-nav.type_mobile.m_layout_panel .w-nav-list.level_1 {
		width: <?php echo us_get_header_option( 'width', 'mobiles' ) ?>;
	}
	.w-search.layout_simple,
	.w-search.layout_modern.active {
		width: calc(<?php echo us_get_header_option( 'width', 'mobiles' ) ?> - 40px);
	}

	/* Slided vertical header */
	.w-header-show,
	.w-header-overlay {
		display: block;
	}
	.l-header {
		bottom: 0;
		overflow-y: auto;
		-webkit-overflow-scrolling: touch;
		box-shadow: none;
		transition: transform 0.3s;
		transform: translate3d(-100%,0,0);
	}
	.header-show .l-header {
		transform: translate3d(0,0,0);
	}
<?php if ( us_get_header_option( 'elm_align', 'mobiles' ) == 'left' ) { ?>
	.l-subheader-cell {
		text-align: left;
		align-items: flex-start;
	}
<?php }
if ( us_get_header_option( 'elm_align', 'mobiles' ) == 'right' ) { ?>
	.l-subheader-cell {
		text-align: right;
		align-items: flex-end;
	}
<?php }
}
?>
}



/* Image */

<?php foreach ( us_get_header_elms_of_a_type( 'image' ) as $class => $param ): ?>
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?> { height: <?php echo $param['height'] ?>; }
	.l-header.sticky .<?php echo $class ?> { height: <?php echo $param['height_sticky'] ?>; }
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?> { height: <?php echo $param['height_tablets'] ?>; }
	.l-header.sticky .<?php echo $class ?> { height: <?php echo $param['height_sticky_tablets'] ?>; }
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> { height: <?php echo $param['height_mobiles'] ?>; }
	.l-header.sticky .<?php echo $class ?> { height: <?php echo $param['height_sticky_mobiles'] ?>; }
}
<?php endforeach; ?>



/* Text */

<?php
foreach ( us_get_header_elms_of_a_type( 'text' ) as $class => $param ) {

	$css = '.' . $class . '{';
	$css .= us_prepare_inline_css(
		array(
			'font-family' => isset( $param['font'] ) ? $param['font'] : '',
			'font-weight' => isset( $param['font_weight'] ) ? $param['font_weight'] : '',
			'text-transform' => isset( $param['text_transform'] ) ? $param['text_transform'] : '',
			'font-style' => isset( $param['font_style'] ) ? $param['font_style'] : '',
			'font-size' => isset( $param['font_size'] ) ? $param['font_size'] : '',
			'line-height' => isset( $param['line_height'] ) ? $param['line_height'] : '',
			'white-space' => ( ! $param['wrap'] ) ? 'nowrap' : '',
		),
		$style_attr = FALSE
	);
	$css .= '}';
	if ( ! empty( $param['color'] ) ) {
		$css .= '.' . $class . ' .w-text-h { color:' . $param['color'] . '; }';
	}
	if ( ! empty( $param['font_size_tablets'] ) OR ! empty( $param['line_height_tablets'] ) ) {
		$css .= '@media ' . $tablets_query . ' {.' . $class . '{';
		$css .= ( ! empty( $param['font_size_tablets'] ) ) ? ( 'font-size:' . $param['font_size_tablets'] . ';' ) : '';
		$css .= ( ! empty( $param['line_height_tablets'] ) ) ? ( 'line-height:' . $param['line_height_tablets'] . ';' ) : '';
		$css .= '}}';
	}
	if ( ! empty( $param['font_size_mobiles'] ) OR ! empty( $param['line_height_mobiles'] ) ) {
		$css .= '@media ' . $mobiles_query . ' {.' . $class . '{';
		$css .= ( ! empty( $param['font_size_mobiles'] ) ) ? ( 'font-size:' . $param['font_size_mobiles'] . ';' ) : '';
		$css .= ( ! empty( $param['line_height_mobiles'] ) ) ? ( 'line-height:' . $param['line_height_mobiles'] . ';' ) : '';
		$css .= '}}';
	}

	echo $css;
}
?>



/* Button */

<?php foreach ( us_get_header_elms_of_a_type( 'btn' ) as $class => $param ): ?>
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?> { font-size: <?php echo $param['font_size'] ?>; }
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?> { font-size: <?php echo $param['font_size_tablets'] ?>; }
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> { font-size: <?php echo $param['font_size_mobiles'] ?>; }
}
<?php endforeach; ?>



/* Menu */

<?php foreach ( us_get_header_elms_of_a_type( 'menu' ) as $class => $param ): ?>
.header_hor .<?php echo $class ?>.type_desktop .w-nav-list.level_1 > .menu-item > a {
	padding-left: <?php echo $param['indents'] ?>;
	padding-right: <?php echo $param['indents'] ?>;
}
.header_ver .<?php echo $class ?>.type_desktop .w-nav-list.level_1 > .menu-item > a {
	padding-top: <?php echo $param['indents'] ?>;
	padding-bottom: <?php echo $param['indents'] ?>;
}
<?php
$css = '.' . $class . '{';
$css .= us_prepare_inline_css(
	array(
		'font-family' => $param['font'],
		'font-weight' => $param['font_weight'],
		'text-transform' => $param['text_transform'],
		'font-style' => $param['font_style'],
	),
	$style_attr = FALSE
);
$css .= '}';
echo $css;

if ( $param['dropdown_arrow'] ): ?>
.<?php echo $class ?>.type_desktop .menu-item-has-children .w-nav-anchor.level_1 > .w-nav-arrow {
	display: inline-block;
}
<?php endif; ?>
.<?php echo $class ?>.type_desktop .w-nav-list > .menu-item.level_1 {
	font-size: <?php echo $param['font_size'] ?>;
}
.<?php echo $class ?>.type_desktop .w-nav-list > .menu-item:not(.level_1) {
	font-size: <?php echo $param['dropdown_font_size'] ?>;
}
<?php if ( $param['dropdown_width'] ): ?>
.<?php echo $class ?>.type_desktop {
	position: relative;
}
<?php endif; ?>
.<?php echo $class ?>.type_mobile .w-nav-anchor.level_1 {
	font-size: <?php echo $param['mobile_font_size'] ?>;
}
.<?php echo $class ?>.type_mobile .w-nav-anchor:not(.level_1) {
	font-size: <?php echo $param['mobile_dropdown_font_size'] ?>;
}
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?> .w-nav-icon {
		font-size: <?php echo $param['mobile_icon_size'] ?>;
	}
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?> .w-nav-icon {
		font-size: <?php echo $param['mobile_icon_size_tablets'] ?>;
	}
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> .w-nav-icon {
		font-size: <?php echo $param['mobile_icon_size_mobiles'] ?>;
	}
}
/* Show mobile menu instead of desktop */
@media screen and (max-width: <?php echo ( intval( $param['mobile_width'] ) - 1 ) ?>px) {
	.w-nav.<?php echo $class ?> > .w-nav-list.level_1 {
		display: none;
	}
	.<?php echo $class ?> .w-nav-control {
		display: block;
	}
}
<?php endforeach; ?>



/* Links Menu */

<?php foreach ( us_get_header_elms_of_a_type( 'additional_menu' ) as $class => $param ): ?>
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size'] ?>;
	}
	.header_hor .<?php echo $class ?> .w-menu-list {
		margin: 0 -<?php echo $param['indents'] ?>;
	}
	.header_hor .<?php echo $class ?> .w-menu-item {
		padding: 0 <?php echo $param['indents'] ?>;
	}
	.header_ver .<?php echo $class ?> .w-menu-list {
		line-height: <?php echo $param['indents'] ?>;
	}
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_tablets'] ?>;
	}
	.header_hor .<?php echo $class ?> .w-menu-list {
		margin: 0 -<?php echo $param['indents_tablets'] ?>;
	}
	.header_hor .<?php echo $class ?> .w-menu-item {
		padding: 0 <?php echo $param['indents_tablets'] ?>;
	}
	.header_ver .<?php echo $class ?> .w-menu-list {
		line-height: <?php echo $param['indents_tablets'] ?>;
	}
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_mobiles'] ?>;
	}
	.header_hor .<?php echo $class ?> .w-menu-list {
		margin: 0 -<?php echo $param['indents_mobiles'] ?>;
	}
	.header_hor .<?php echo $class ?> .w-menu-item {
		padding: 0 <?php echo $param['indents_mobiles'] ?>;
	}
	.header_ver .<?php echo $class ?> .w-menu-list {
		line-height: <?php echo $param['indents_mobiles'] ?>;
	}
}
<?php endforeach; ?>



/* Search */

<?php foreach ( us_get_header_elms_of_a_type( 'search' ) as $class => $param ): ?>
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?>.layout_simple {
		max-width: <?php echo $param['field_width'] ?>;
	}
	.<?php echo $class ?>.layout_modern.active {
		width: <?php echo $param['field_width'] ?>;
	}
	.<?php echo $class ?> {
		font-size: <?php echo $param['icon_size'] ?>;
	}
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?>.layout_simple {
		max-width: <?php echo $param['field_width_tablets'] ?>;
	}
	.<?php echo $class ?>.layout_modern.active {
		width: <?php echo $param['field_width_tablets'] ?>;
	}
	.<?php echo $class ?> {
		font-size: <?php echo $param['icon_size_tablets'] ?>;
	}
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> {
		font-size: <?php echo $param['icon_size_mobiles'] ?>;
	}
}
<?php endforeach; ?>



/* Socials */

<?php foreach ( us_get_header_elms_of_a_type( 'socials' ) as $class => $param ): ?>
.<?php echo $class ?> .w-socials-list {
	margin: -<?php echo $param['gap'] ?>;
	}
.<?php echo $class ?> .w-socials-item {
	padding: <?php echo $param['gap'] ?>;
	}
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size'] ?>;
	}
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_tablets'] ?>;
	}
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_mobiles'] ?>;
	}
}
<?php endforeach; ?>



/* Dropdown */

<?php foreach ( us_get_header_elms_of_a_type( 'dropdown' ) as $class => $param ): ?>
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?> .w-dropdown-h {
		font-size: <?php echo $param['size'] ?>;
	}
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?> .w-dropdown-h {
		font-size: <?php echo $param['size_tablets'] ?>;
	}
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> .w-dropdown-h {
		font-size: <?php echo $param['size_mobiles'] ?>;
	}
}
<?php endforeach; ?>



/* Cart */

<?php foreach ( us_get_header_elms_of_a_type( 'cart' ) as $class => $param ): ?>
@media <?php echo $desktop_query ?> {
	.<?php echo $class ?> .w-cart-link {
		font-size: <?php echo $param['size'] ?>;
	}
}
@media <?php echo $tablets_query ?> {
	.<?php echo $class ?> .w-cart-link {
		font-size: <?php echo $param['size_tablets'] ?>;
	}
}
@media <?php echo $mobiles_query ?> {
	.<?php echo $class ?> .w-cart-link {
		font-size: <?php echo $param['size_mobiles'] ?>;
	}
}
<?php endforeach; ?>



/* Design Options */

<?php echo us_get_header_design_options_css() ?>
