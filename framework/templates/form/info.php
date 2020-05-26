<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a form's info field
 *
 * @var $value   string Field value
 * @var $classes string Additional field classes
 *
 * @action Before the template: 'us_before_template:templates/form/info'
 * @action After the template: 'us_after_template:templates/form/info'
 * @filter Template variables: 'us_template_vars:templates/form/info'
 */

$value = isset( $value ) ? trim( $value ) : '';
$classes = ! empty( $classes ) ? ( ' ' . $classes ) : '';
?>
<div class="w-form-row for_info<?php echo $classes ?>">
	<p><?php echo strip_tags( $value, '<a><br><strong>' ) ?></p>
</div>