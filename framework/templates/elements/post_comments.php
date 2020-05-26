<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Comments
 */

if ( get_post_format() == 'link' OR ( is_admin() AND ( ! defined( 'DOING_AJAX' ) OR ! DOING_AJAX ) ) ) {
	return;
}

// Exclude 'comments_template' layout for Grid context
if ( $us_elm_context != 'shortcode' ) {
	$layout = 'amount';
}

$classes = isset( $classes ) ? $classes : '';
$classes .= ' layout_' . $layout;

if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) AND $us_elm_context == 'shortcode' ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Prepare inline CSS for shortcode
$inline_css = '';
if ( $us_elm_context == 'shortcode' ) {
	$inline_css .= us_prepare_inline_css(
		array(
			'font-family' => $font,
			'font-weight' => $font_weight,
			'text-transform' => $text_transform,
			'font-style' => $font_style,
			'font-size' => $font_size,
			'line-height' => $line_height,
		)
	);
}

if ( $layout == 'amount' ) {

	// Link
	if ( $link === 'post' ) {
		if ( get_post_type() == 'product' ) {
			$link_atts = ' href="' . apply_filters( 'the_permalink', get_permalink() ) . '#reviews"';
		} else {
			$link_atts = ' href="' . get_comments_link() . '"';
		}
	} elseif ( $link === 'custom' ) {
		$link_atts = us_generate_link_atts( $custom_link );
	} else {
		$link_atts = '';
	}
	if ( $color_link ) {
		$classes .= ' color_link_inherit';
	}

	// Define no comments indication
	$comments_none = '0';
	if ( ! $number ) {
		$classes .= ' with_word';
		$comments_none = us_translate( 'No Comments' );
	}

	$comments_number = get_comments_number();

	// "Hide this element if no comments"
	if ( $hide_zero AND empty( $comments_number ) ) {
		return;
	}
}

// Output the element
$output = '<div class="w-post-elm post_comments' . $classes . '"' . $inline_css . $el_id . '>';

if ( $layout == 'comments_template' ) {
	wp_enqueue_script( 'comment-reply' );

	ob_start();
	comments_template();
	$output .= ob_get_clean();

} else {
	if ( ! empty( $icon ) ) {
		$output .= us_prepare_icon_tag( $icon );
	}
	if ( ! empty( $link_atts ) ) {
		$output .= '<a class="smooth-scroll"' . $link_atts . '>';
	}

	if ( class_exists( 'woocommerce' ) AND get_post_type() == 'product' ) {
		$output .= sprintf( us_translate_n( '%s customer review', '%s customer reviews', $comments_number, 'woocommerce' ), '<span class="count">' . esc_html( $comments_number ) . '</span>' );
	} else {
		ob_start();
		$comments_label = sprintf( us_translate_n( '%s <span class="screen-reader-text">Comment</span>', '%s <span class="screen-reader-text">Comments</span>', $comments_number ), $comments_number );
		comments_number( $comments_none, $comments_label, $comments_label );
		$output .= ob_get_clean();
	}

	if ( ! empty( $link_atts ) ) {
		$output .= '</a>';
	}
}

$output .= '</div>';

echo $output;
