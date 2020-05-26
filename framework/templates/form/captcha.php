<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's captcha field
 *
 * @var $name        string Field name
 * @var $type        string Field type
 * @var $label       string Field label
 * @var $placeholder string Field placeholder
 * @var $description string Field description
 * @var $value       string Field value
 * @var $icon        string Field icon
 * @var $field_id    string Field id
 * @var $classes     string Additional field classes
 *
 * @action Before the template: 'us_before_template:templates/form/captcha'
 * @action After the template: 'us_after_template:templates/form/captcha'
 * @filter Template variables: 'us_template_vars:templates/form/captcha'
 */

global $us_cform_index;

$name = isset( $name ) ? $name : '';
$type = isset( $type ) ? $type : '';
$label = isset( $label ) ? trim( $label ) : '';
$placeholder = isset( $placeholder ) ? trim( $placeholder ) : '';
$description = isset( $description ) ? trim( $description ) : '';
$value = isset( $value ) ? $value : '';
$icon = isset( $icon ) ? us_prepare_icon_tag( $icon ) : '';
$field_id = isset( $field_id ) ? $field_id : 1;
$field_id = 'us_form_' . $us_cform_index . '_' . $type . '_' . $field_id;
$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';

$label = strip_tags( $label, '<a><br><strong>' );

if ( ! empty( $label ) ) {
	$aria_label = $label;
} elseif ( empty( $label ) AND ! empty( $placeholder ) ) {
	$aria_label = $placeholder;
} else {
	$aria_label = $field_id;
}

$numbers = array( rand( 16, 30 ), rand( 1, 15 ) );
$sign = rand( 0, 1 );
$label .= ' <span>' . implode( $sign ? ' + ' : ' - ', $numbers );
$result_hash = md5( ( $numbers[0] + ( $sign ? 1 : - 1 ) * $numbers[1] ) . NONCE_SALT );

// Always required field
$classes .= ' required';
if ( ! empty( $label ) ) {
	$label .= ' = ?</span>';
}

$field_atts = 'type="text"';
if ( ! empty( $name ) ) {
	$field_atts .= ' name="' . $name . '"';
} else {
	$field_atts .= ' name="' . $field_id . '"';
}
$field_atts .= ' aria-label="' . strip_tags( $aria_label ) . '"';
$field_atts .= ' placeholder="' . esc_attr( $placeholder ) . '"';
$field_atts .= ' data-required="true" aria-required="true"';
if ( ! empty( $icon ) ) {
	$classes .= ' with_icon';
}
?>
<div class="w-form-row for_captcha<?php echo $classes ?>">
	<div class="w-form-row-label">
		<span><?php echo $label ?></span>
	</div>
	<div class="w-form-row-field">
		<?php do_action( 'us_form_captcha_start', $vars ) ?>
		<input type="hidden" name="<?php echo( ! empty( $name ) ? $name : $field_id ) ?>_hash" value="<?php echo $result_hash ?>" />
		<?php echo $icon; ?>
		<input <?php echo $field_atts ?>"/>
		<span class="w-form-row-field-bar"></span>
		<?php do_action( 'us_form_captcha_end', $vars ) ?>
	</div>
	<?php if ( ! empty( $description ) ) : ?>
		<div class="w-form-row-description">
			<?php echo strip_tags( $description, '<a><br><strong>' ) ?>
		</div>
	<?php endif; ?>
	<div class="w-form-row-state"><?php _e( 'Enter the equation result to proceed', 'us' ) ?></div>
</div>
