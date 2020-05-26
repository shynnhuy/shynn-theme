<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a single form
 *
 * @var $type          string Form type: 'contact' / 'search' / 'comment' / 'protectedpost' / ...
 * @var $action        string Form action
 * @var $method        string Form method: 'post' / 'get'
 * @var $fields        array Form fields (see any of the fields template header for details)
 * @var $content       string
 * @var $json_data     array Json data to pass to JavaScript
 * @var $classes       string Additional classes to append to form
 * @var $start_html    string HTML to append to the form's start
 * @var $end_html      string HTML to append to the form's end
 *
 * @action Before the template: 'us_before_template:templates/form/form'
 * @action After the template:  'us_after_template:templates/form/form'
 * @filter Template variables:  'us_template_vars:templates/form/form'
 */

// Variables defaults and filtering
$type = isset( $type ) ? $type : '';
$action = isset( $action ) ? $action : site_url( $_SERVER['REQUEST_URI'] );
$method = isset( $method ) ? $method : 'post';
$fields = isset( $fields ) ? (array) $fields : array();

// Repeatable fields IDs start from 1
$repeatable_fields = array(
	'text' => 1,
	'email' => 1,
	'textarea' => 1,
	'select' => 1,
	'agreement' => 1,
	'checkboxes' => 1,
	'radio' => 1,
);

foreach ( $fields as $field_name => $field ) {
	if ( isset( $field['type'] ) ) {
		$fields[ $field_name ]['type'] = $field['type'];
		if ( in_array( $field['type'], array_keys( $repeatable_fields ) ) ) {
			$fields[ $field_name ]['field_id'] = $repeatable_fields[ $field['type'] ];
			$repeatable_fields[ $field['type'] ] += 1;
		}
		if ( $field['type'] == 'agreement' ) {
			$field['required'] = 1;
		}
	} else {
		$fields[ $field_name ]['type'] = 'text';
	}
}

$json_data = array(
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
);

$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';
$el_id = isset( $el_id ) ? $el_id : '';
$start_html = isset( $start_html ) ? $start_html : '';
$end_html = isset( $end_html ) ? $end_html : '';
if ( ! empty( $type ) ) {
	$classes = ' for_' . $type . $classes;
}

global $us_cform_index;

$classes .= ' us_form_' . $us_cform_index;
?>
<div class="w-form<?php echo $classes ?>"<?php echo $el_id ?>>
	<form class="w-form-h" autocomplete="off" action="<?php echo esc_attr( $action ) ?>" method="<?php echo $method ?>">
		<?php echo $start_html ?>
		<?php foreach ( $fields as $field ):
			if ( isset( $field['type'] ) ) {
				us_load_template( 'templates/form/' . $field['type'], $field );
			}
			?>
		<?php endforeach; ?>
		<div class="w-form-message"></div>
		<?php echo $end_html ?>
	</form>
	<div class="w-form-json hidden"<?php echo us_pass_data_to_js( $json_data ) ?>></div>
</div>