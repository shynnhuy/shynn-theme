<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's radio input
 *
 * @var $name        string Field name
 * @var $type        string Field type
 * @var $label       string Field label
 * @var $placeholder string Field placeholder
 * @var $description string Field description
 * @var $field_id    string Field id
 * @var $classes     string Additional field classes
 * @var $values      string Field values
 *
 * @action Before the template: 'us_before_template:templates/form/radio'
 * @action After the template: 'us_after_template:templates/form/radio'
 * @filter Template variables: 'us_template_vars:templates/form/radio'
 */

global $us_cform_index;


$name = isset( $name ) ? $name : '';
$type = isset( $type ) ? $type : '';
$label = isset( $label ) ? trim( $label ) : '';
$placeholder = isset( $placeholder ) ? trim( $placeholder ) : '';
$description = isset( $description ) ? trim( $description ) : '';
$required = isset( $required ) ? $required : FALSE;
$field_id = isset( $field_id ) ? $field_id : 1;
$field_id = 'us_form_' . $us_cform_index . '_' . $type . '_' . $field_id;
$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';

$values = isset( $values ) ? explode( "\n", $values ) : array();

// Do not show this field if it has no values
if ( empty( $values ) ) {
	return;
}

$field_atts = 'type="radio"';
$field_atts .= ' class="screen-reader-text"';
if ( ! empty( $name ) ) {
	$field_atts .= ' name="' . $name . '"';
} else {
	$field_atts .= ' name="' . $field_id . '"';
}
if ( $required ) {
	$classes .= ' required';
	$field_atts .= 'data-required="true" aria-required="true"';
	if ( ! empty( $label ) ) {
		$label .= ' <span class="required">*</span>';
	}
}
?>
<div class="w-form-row for_radio<?php echo $classes ?>">
	<?php if ( ! empty( $label ) ) : ?>
		<div class="w-form-row-label">
			<span><?php echo strip_tags( $label, '<a><br><strong>' ) ?></span>
		</div>
	<?php endif; ?>
	<div class="w-form-row-field">
		<?php do_action( 'us_form_field_start', $vars );
		foreach ( $values as $key => $value ) {
			$value = trim( $value );
			if ( empty( $value ) ) {
				continue;
			}
			?>
			<label>
				<input <?php echo $field_atts ?> value="<?php echo esc_attr( $value ) ?>"<?php checked( $key, 0 ) ?>/>
				<span class="w-form-radio"></span>
				<span><?php echo strip_tags( $value, '<a><br><strong>' ) ?></span>
			</label>
			<?php
		}
		do_action( 'us_form_field_end', $vars ) ?>
	</div>
	<?php if ( ! empty( $description ) ) : ?>
		<div class="w-form-row-description">
			<?php echo strip_tags( $description, '<a><br><strong>' ) ?>
		</div>
	<?php endif; ?>
	<div class="w-form-row-state"><?php _e( 'Select an option', 'us' ) ?></div>
</div>
