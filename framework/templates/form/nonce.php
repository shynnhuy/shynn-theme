<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Output a form's nonce field
 *
 * @var $name        string Nonce Name
 * @var $action      string Nonce Action
 */

$name = isset( $name ) ? $name : '';
$action = isset( $action ) ? $action : '';

if ( ! empty( $action ) AND ! empty( $name ) ) {
	wp_nonce_field( $action, $name );
}
