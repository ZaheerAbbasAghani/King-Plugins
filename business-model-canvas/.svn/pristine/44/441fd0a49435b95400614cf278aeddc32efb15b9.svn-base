<?php
//if ( ! defined( 'ABSPATH' ) ) exit; // Direct access restricted
add_action( 'wp_ajax_bmc_update_field_by_unique_id', 'bmc_update_field_by_unique_id' );
add_action( 'wp_ajax_nopriv_bmc_update_field_by_unique_id', 'bmc_update_field_by_unique_id' );
function bmc_update_field_by_unique_id() {

$nonce = $_POST['ajax_nonce'];
if ( ! wp_verify_nonce( $nonce, 'ajax_nonce' ) )
die ( 'Busted!');

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';

$field_val = sanitize_text_field($_POST['field_val']);
$field_attr = sanitize_text_field($_POST['field_attr']);
$field_col = sanitize_text_field($_POST['field_col']);

$wpdb->update( 
$table_name, 
array( 
	'bmc_value' => $field_val,	
	'bmc_color' => $field_col,	
), 
array( 'bmc_field' => $field_attr ), 
array( 
	'%s',	// value1
), 
array( '%s' ) 
);

echo "Record Update Success";

wp_die();
}