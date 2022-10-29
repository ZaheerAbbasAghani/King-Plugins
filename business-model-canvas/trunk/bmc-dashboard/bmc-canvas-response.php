<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Direct access restricted

add_action( 'wp_ajax_bmc_create_sticky_notes_by_textarea', 'bmc_create_sticky_notes_by_textarea' );
add_action( 'wp_ajax_nopriv_bmc_create_sticky_notes_by_textarea', 'bmc_create_sticky_notes_by_textarea' );
function bmc_create_sticky_notes_by_textarea() {

$nonce = $_POST['ajax_nonce'];
if ( ! wp_verify_nonce( $nonce, 'ajax_nonce' ) )
die ( 'Busted!');

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$fields =  $_POST['fields'];

foreach ($fields as $field) {
$wpdb->insert($table_name, 	
	array( 	'bmc_field' => sanitize_text_field($field['name']), 
			'bmc_value' => sanitize_text_field($field['value']) , 
			'bmc_color' => sanitize_text_field($field['color']), 
			'bmc_container' => sanitize_text_field($field['container'])), 
    array( '%s', '%s','%s','%s') 
);

}

echo "Success";

wp_die();
}