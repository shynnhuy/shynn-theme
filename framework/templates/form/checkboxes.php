<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's checkboxes
 *
 * @var $name        string Field name
 * @var $type        string Field type
 * @var $label       string Field label
 * @var $description string Field description
 * @var $required    bool Is the field required?
 * @var $field_id    string Field id
 * @var $classes     string Additional field classes
 * @var $values      string Field values
 *
 * @action Before the template: 'us_before_template:templates/form/checkboxes'
 * @action After the template: 'us_after_template:templates/form/checkboxes'
 * @filter Template variables: 'us_template_vars:templates/form/checkboxes'
 */

global $us_cform_index;

$name = isset( $name ) ? $name : '';
$type = isset( $type ) ? $type : '';
$label = isset( $label ) ? trim( $label ) : '';
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

$field_atts = 'type="checkbox"';
$field_atts .= ' class="screen-reader-text"';
$field_atts .= ' name="' . $field_id . '[]"';
if ( $required ) {
	$classes .= ' required';
	$field_atts .= 'data-required="true" aria-required="true"';
}
?>
<div class="w-form-row for_checkboxes<?php echo $classes ?>">
	<?php if ( ! empty( $label ) ) : ?>
		<div class="w-form-row-label">
			<span><?php echo strip_tags( $label, '<a><br><strong>' ) . ( $required ? ' <span class="required">*</span>' : '' ) ?></span>
		</div>
	<?php endif; ?>
	<div class="w-form-row-field">
		<?php do_action( 'us_form_field_start', $vars );
		foreach ( $values as $key => $value ) {
			$value = trim( $value );
			if ( empty( $value ) ) {
				continue;
			}
			$checkbox_id = $field_id . '_' . ( $key + 1 );
			?>
			<label>
				<input value="<?php echo esc_attr( $value ) ?>" <?php echo $field_atts ?>/>
				<span class="w-form-checkbox"></span>
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
