<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_image_slider
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 */

if ( empty( $ids ) ) {
	return;
}

$classes = ' style_' . $style;
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Royal Slider options
$js_options = array(
	'loopRewind' => TRUE,
	'slidesSpacing' => 0,
	'imageScalePadding' => 0,
	'numImagesToPreload' => 2,
	'arrowsNav' => ( $arrows != 'hide' ),
	'arrowsNavAutoHide' => ( $arrows == 'hover' ),
	'transitionType' => ( $transition == 'crossfade' ) ? 'fade' : 'move',
	'transitionSpeed' => intval( $transition_speed ),
	'block' => array(
		'moveEffect' => 'none',
		'speed' => 300,
	),
);
if ( $nav == 'dots' ) {
	$js_options['controlNavigation'] = 'bullets';
} elseif ( $nav == 'thumbs' ) {
	$js_options['controlNavigation'] = 'thumbnails';
} else {
	$js_options['controlNavigation'] = 'none';
}
if ( $autoplay AND $autoplay_period ) {
	$js_options['autoplay'] = array(
		'enabled' => TRUE,
		'pauseOnHover' => $pause_on_hover ? TRUE : FALSE,
		'delay' => intval( $autoplay_period * 1000 ),
	);
}
if ( $fullscreen ) {
	$js_options['fullscreen'] = array( 'enabled' => TRUE );
}
if ( $img_fit == 'contain' ) {
	$js_options['imageScaleMode'] = 'fit';
} elseif ( $img_fit == 'cover' ) {
	$js_options['imageScaleMode'] = 'fill';
} else/*if ( $img_fit == 'scaledown' )*/ {
	$js_options['imageScaleMode'] = 'fit-if-smaller';
}

if ( ! in_array( $img_size, get_intermediate_image_sizes() ) ) {
	$img_size = 'full';
}

// Getting images
$query_args = array(
	'include' => $ids,
	'post_status' => 'inherit',
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'orderby' => 'post__in',
	'numberposts' => - 1,
);
if ( $orderby == 'rand' ) {
	$query_args['orderby'] = 'rand';
}
$attachments = get_posts( $query_args );
if ( ! is_array( $attachments ) OR empty( $attachments ) ) {
	return;
}

$i = 1;
$first_image = array( '0' => '' );
$data_ratio = NULL;
$images_html = '';
$images_alts = array();
foreach ( $attachments as $index => $attachment ) {
	$image = wp_get_attachment_image_src( $attachment->ID, $img_size );
	if ( ! $image ) {
		continue;
	}
	// Correct width and height for SVG files
	if ( preg_match( '~\.svg$~', $image[0] ) ) {

		$size_array = us_get_intermediate_image_size( $img_size );
		if ( $size_array['width'] ) {
			$image[1] = $image[2] = $size_array['width'];
		} elseif ( $size_array['height'] ) {
			$image[1] = $image[2] = $size_array['height'];
		} else {
			$image[1] = $image[2] = '2000'; // fallback for non-numeric values
		}
	}
	if ( ! isset( $js_options['autoScaleSlider'] ) ) {
		$js_options['autoScaleSlider'] = TRUE;
		$js_options['autoScaleSliderWidth'] = $image[1];
		$js_options['autoScaleSliderHeight'] = $image[2];
		$js_options['fitInViewport'] = FALSE;
	}
	$full_image_attr = '';
	if ( $fullscreen ) {
		$full_image = wp_get_attachment_image_url( $attachment->ID, 'full' );
		if ( ! $full_image ) {
			$full_image = $image[0];
		}
		$full_image_attr = ' data-rsBigImg="' . $full_image . '"';
	}

	// Use the Caption as a Title
	$image_title = trim( strip_tags( $attachment->post_excerpt ) );
	if ( empty( $image_title ) ) {
		// If not, Use the Alt
		$image_title = trim( strip_tags( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', TRUE ) ) );
	}
	if ( empty( $image_title ) ) {
		// If no Alt, use the Title
		$image_title = trim( strip_tags( $attachment->post_title ) );
	}

	$img_alt = us_get_image_alt( $attachment->ID );

	$images_html .= '<div class="rsContent">';
	if ( $i == 1 ) {
		$first_image = $image;
		$first_image_alt = ' alt="' . $img_alt . '"';
	}
	$images_html .= '<a class="rsImg" data-rsw="' . $image[1] . '" data-rsh="' . $image[2] . '"' . $full_image_attr . ' href="' . $image[0] . '"><span data-alt="' . $img_alt . '"></span></a>';

	if ( $nav == 'thumbs' ) {
		$images_html .= wp_get_attachment_image( $attachment->ID, 'thumbnail', FALSE, array( 'class' => 'rsTmb' ) );
	}
	if ( $meta ) {
		$images_html .= '<div class="rsABlock" data-fadeEffect="false" data-moveEffect="none">';
		if ( $image_title != '' ) {
			$images_html .= '<div class="w-slider-item-title">' . $image_title . '</div>';
		}
		if ( $attachment->post_content != '' ) {
			$images_html .= '<div class="w-slider-item-description">' . $attachment->post_content . '</div>';
		}
		$images_html .= '</div>';
	}
	$images_html .= '</div>';
	$i ++;
}

// We need Royal Slider script for this
if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
	wp_enqueue_script( 'us-royalslider' );
}

$output = '<div class="w-slider' . $classes . '"' . $el_id . '>';
$output .= '<div class="w-slider-h">';
$output .= '<div class="royalSlider">' . $images_html . '</div>';
$output .= '<img src="' . $first_image[0] . '"' . $first_image_alt . '></div>';
$output .= '<div class="w-slider-json"' . us_pass_data_to_js( $js_options ) . '></div>';
$output .= '</div>';

// If we are in front end editor mode, apply JS to logos
if ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) {
	$output .= '<script>
	jQuery(function($){
		if (typeof $.fn.wSlider === "function") {
			jQuery(".w-slider").wSlider();
		}
	});
	</script>';
}

echo $output;
