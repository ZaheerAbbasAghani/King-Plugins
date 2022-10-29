<?php
add_action( 'wp_ajax_nopriv_vir_delete_row', 'vir_delete_row' );
add_action( 'wp_ajax_vir_delete_row', 'vir_delete_row' );
function vir_delete_row() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'vir_scheules';

	$id  = $_POST['id'];
	$wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );
	
	echo "Deleted Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}