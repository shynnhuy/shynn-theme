<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's text field
 *
 * @var $name        string Field name
 * @var $type        string Field type
 * @var $label       string Field label
 * @var $placeholder string Field placeholder
 * @var $description string Field description
 * @var $value       string Field value
 * @var $required    bool Is the field required?
 * @var $icon        string Field icon
 * @var $field_id    string Field id
 * @var $classes     string Additional field classes
 *
 * @action Before the template: 'us_before_template:templates/form/text'
 * @action After the template: 'us_after_template:templates/form/text'
 * @filter Template variables: 'us_template_vars:templates/form/text'
 */

global $us_cform_index;

$name = isset( $name ) ? $name : '';
$type = isset( $type ) ? $type : '';
$label = isset( $label ) ? trim( $label ) : '';
$placeholder = isset( $placeholder ) ? trim( $placeholder ) : '';
$description = isset( $description ) ? trim( $description ) : '';
$value = isset( $value ) ? $value : '';
$required = ( isset( $required ) AND $required );
$icon = isset( $icon ) ? us_prepare_icon_tag( $icon ) : '';
$field_id = isset( $field_id ) ? $field_id : 1;
$field_id = 'us_form_' . $us_cform_index . '_' . $type . '_' . $field_id;
$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';

if ( ! empty( $label ) ) {
	$aria_label = $label;
} elseif ( empty( $label ) AND ! empty( $placeholder ) ) {
	$aria_label = $placeholder;
} else {
	$aria_label = $field_id;
}

$field_atts = 'type="text"';
if ( ! empty( $name ) ) {
	$field_atts .= ' name="' . $name . '"';
} else {
	$field_atts .= ' name="' . $field_id . '"';
}
$field_atts .= ' aria-label="' . strip_tags( $aria_label ) . '"';
$field_atts .= ' value="' . esc_attr( $value ) . '"';
if ( $required AND ! empty( $placeholder ) AND empty( $label ) ) {
	$placeholder .= ' *';
}
$field_atts .= ' placeholder="' . strip_tags( $placeholder ) . '"';

if ( $required ) {
	$classes .= ' required';
	$field_atts .= ' data-required="true" aria-required="true"';
	if ( ! empty( $label ) ) {
		$label .= ' <span class="required">*</span>';
	}
}
if ( ! empty( $icon ) ) {
	$classes .= ' with_icon';
}
?>
<div class="w-form-row for_text<?php echo $classes ?>">
	<?php if ( ! empty( $label ) ) : ?>
		<div class="w-form-row-label">
			<span><?php echo strip_tags( $label, '<a><br><strong>' ) ?></span>
		</div>
	<?php endif; ?>
	<div class="w-form-row-field">
		<?php do_action( 'us_form_field_start', $vars );
		echo $icon;
		?>
		<input <?php echo $field_atts ?>/>
		<span class="w-form-row-field-bar"></span>
		<?php do_action( 'us_form_field_end', $vars ) ?>
	</div>
	<?php if ( ! empty( $description ) ) : ?>
		<div class="w-form-row-description">
			<?php echo strip_tags( $description, '<a><br><strong>' ) ?>
		</div>
	<?php endif; ?>
	<div class="w-form-row-state"><?php _e( 'Fill out this field', 'us' ) ?></div>
</div>
