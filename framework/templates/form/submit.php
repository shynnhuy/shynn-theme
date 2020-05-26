<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's hidden field
 *
 * @var $name                string Field name
 * @var $classes             string Additional field classes
 * @var $title               string Submit button title
 * @var $btn_classes         string Additional button classes
 * @var $btn_inner_css       string Button inner css
 * @var $btn_size_mobiles    string Button Size on Mobiles
 *
 * @action Before the template: 'us_before_template:templates/form/submit'
 * @action After the template: 'us_after_template:templates/form/submit'
 * @filter Template variables: 'us_template_vars:templates/form/submit'
 */

$name = isset( $name ) ? $name : '';
$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';
$title = ! empty( $title ) ? $title : us_translate( 'Submit' );
$btn_classes = ! empty( $btn_classes ) ? ( ' ' . $btn_classes ) : '';
$btn_inner_css = ! empty( $btn_inner_css ) ? ( '  style="' . $btn_inner_css . '"' ) : '';
$icon = isset( $icon ) ? us_prepare_icon_tag( $icon ) : '';
$icon_pos = isset( $icon_pos ) ? $icon_pos : 'left';

// Add Size on mobiles as inline style tag
$inline_style = '';
if ( ! empty( $btn_size_mobiles ) ) {
	global $us_cform_index;
	$inline_style .= '<style>@media(max-width:600px){.us_form_' . $us_cform_index . ' .w-btn{font-size:' . $btn_size_mobiles . '!important}}</style>';
}

// Swap icon position for RTL
if ( is_rtl() ) {
	$icon_pos = ( $icon_pos == 'left' ) ? 'right' : 'left';
}

?>
<div class="w-form-row for_submit<?php echo $classes ?>">
	<div class="w-form-row-field">
		<?php echo $inline_style ?>
		<button class="w-btn<?php echo $btn_classes ?>"<?php echo $btn_inner_css ?> type="submit" aria-label="<?php echo esc_attr( $title ) ?>">
			<span class="g-preloader type_1"></span>
			<?php echo ( $icon_pos == 'left' ) ? $icon : ''; ?>
			<span class="w-btn-label"><?php echo strip_tags( $title, '<br>' ) ?></span>
			<?php echo ( $icon_pos == 'right' ) ? $icon : ''; ?>
		</button>
	</div>
</div>
