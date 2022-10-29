<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Direct access restricted

add_action( 'wp_ajax_bmc_delete_sticky_notes', 'bmc_delete_sticky_notes' );
add_action( 'wp_ajax_nopriv_bmc_delete_sticky_notes', 'bmc_delete_sticky_notes' );
function bmc_delete_sticky_notes() {
	
$nonce = $_POST['ajax_nonce'];
if ( ! wp_verify_nonce( $nonce, 'ajax_nonce' ) )
die ( 'Busted!');

	global $wpdb;
	$table_name = $wpdb->prefix . 'bmc_canvas_table';
	$id =  sanitize_text_field($_POST['id']);
	//print_r($id);
	
	$wpdb->delete($table_name , array('id' => $id));
	echo "Record Deleted";

	wp_die();
}