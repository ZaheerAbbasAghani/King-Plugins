<?php
add_action( 'wp_ajax_nopriv_zci_delete_zip_codes', 'zci_delete_zip_codes' );
add_action( 'wp_ajax_zci_delete_zip_codes', 'zci_delete_zip_codes' );
function zci_delete_zip_codes() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'zci_zip_codes';

	$zip_id  = $_POST['zip_id'];
	$wpdb->delete( $table_name, array( 'id' => $zip_id ), array( '%d' ) );
	

	echo "Deleted Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}