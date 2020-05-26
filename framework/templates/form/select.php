<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's select field
 *
 * @var $name        string Field name
 * @var $type        string Field type
 * @var $label       string Field label
 * @var $placeholder string Field placeholder
 * @var $description string Field description
 * @var $icon        string Field icon
 * @var $field_id    string Field id
 * @var $classes     string Additional field classes
 * @var $values      string Field values
 *
 * @action Before the template: 'us_before_template:templates/form/select'
 * @action After the template: 'us_after_template:templates/form/select'
 * @filter Template variables: 'us_template_vars:templates/form/select'
 */

global $us_cform_index;

$name = isset( $name ) ? $name : '';
$type = isset( $type ) ? $type : '';
$label = isset( $label ) ? trim( $label ) : '';
$placeholder = isset( $placeholder ) ? trim( $placeholder ) : '';
$description = isset( $description ) ? trim( $description ) : '';
$icon = isset( $icon ) ? us_prepare_icon_tag( $icon ) : '';
$field_id = isset( $field_id ) ? $field_id : 1;
$field_id = 'us_form_' . $us_cform_index . '_' . $type . '_' . $field_id;
$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';

$values = isset( $values ) ? explode( "\n", trim( $values ) ) : array();

// Do not show this field if it has no values
if ( empty( $values ) ) {
	return;
}

if ( ! empty( $label ) ) {
	$aria_label = $label;
} elseif ( empty( $label ) AND ! empty( $placeholder ) ) {
	$aria_label = $placeholder;
} else {
	$aria_label = $field_id;
}

$field_atts = '';
if ( ! empty( $name ) ) {
	$field_atts .= ' name="' . $name . '"';
} else {
	$field_atts .= ' name="' . $field_id . '"';
}
$field_atts .= ' aria-label="' . strip_tags( $aria_label ) . '"';

if ( ! empty( $icon ) ) {
	$classes .= ' with_icon';
}
?>
<div class="w-form-row for_select<?php echo $classes ?>">
	<?php if ( ! empty( $label ) ) : ?>
		<div class="w-form-row-label">
			<span><?php echo strip_tags( $label, '<a><br><strong>' ) ?></span>
		</div>
	<?php endif; ?>
	<div class="w-form-row-field">
		<?php do_action( 'us_form_field_start', $vars );
		echo $icon;
		?>
		<select <?php echo $field_atts ?>>
			<?php
			foreach ( $values as $value ) {
				echo '<option value="' . esc_attr( $value ) . '">' . $value . '</option>';
			}
			?>
		</select>
		<span class="w-form-row-field-bar"></span>
		<?php do_action( 'us_form_field_end', $vars ) ?>
	</div>
	<?php if ( ! empty( $description ) ) : ?>
		<div class="w-form-row-description">
			<?php echo strip_tags( $description, '<a><br><strong>' ) ?>
		</div>
	<?php endif; ?>
	<div class="w-form-row-state"><?php _e( 'Select an option', 'us' ) ?></div>
</div>
