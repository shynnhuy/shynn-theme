<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's agreement box
 *
 * @var $name        string Field name
 * @var $type        string Field type
 * @var $label       string Field label
 * @var $description string Field description
 * @var $value       string Field value
 * @var $field_id    string Field id
 * @var $classes     string Additional field classes
 * @var $checked     bool checked
 *
 * @action Before the template: 'us_before_template:templates/form/checkbox'
 * @action After the template: 'us_after_template:templates/form/checkbox'
 * @filter Template variables: 'us_template_vars:templates/form/checkbox'
 */

global $us_cform_index;

$name = isset( $name ) ? $name : '';
$type = isset( $type ) ? $type : '';
$label = isset( $label ) ? trim( $label ) : '';
$description = isset( $description ) ? trim( $description ) : '';
$field_id = isset( $field_id ) ? $field_id : 1;
$field_id = 'us_form_' . $us_cform_index . '_' . $type . '_' . $field_id;
$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';

$value = isset( $value ) ? trim( $value ) : '1';
$checked = ( isset( $checked ) AND $checked ) ? 'checked' : '';
$classes .= ' required';
$required_atts = 'data-required="true" aria-required="true"';
?>
<div class="w-form-row for_agreement<?php echo $classes ?>">
	<?php if ( ! empty( $label ) ) : ?>
		<div class="w-form-row-label">
			<span><?php echo strip_tags( $label, '<a><br><strong>' ) . ' <span class="required">*</span>' ?></span>
		</div>
	<?php endif; ?>
	<div class="w-form-row-field">
		<?php do_action( 'us_form_field_start', $vars ) ?>
		<label>
			<input class="screen-reader-text" type="checkbox" name="<?php echo( ! empty( $name ) ? $name : $field_id ) ?>" <?php echo esc_attr( $checked ) . 'value="' . esc_attr( $value ) . '"' ?> <?php echo $required_atts ?>/>
			<span class="w-form-checkbox"></span>
			<span><?php echo strip_tags( $value, '<a><br><strong>' ) ?></span>
		</label>
		<?php do_action( 'us_form_field_end', $vars ) ?>
	</div>
	<?php if ( ! empty( $description ) ) : ?>
		<div class="w-form-row-description">
			<?php echo strip_tags( $description, '<a><br><strong>' ) ?>
		</div>
	<?php endif; ?>
	<div class="w-form-row-state"><?php _e( 'You need to agree with the terms to proceed', 'us' ) ?></div>
</div>
