<?php
add_action( 'wp_ajax_nopriv_zci_edit_zip_codes', 'zci_edit_zip_codes' );
add_action( 'wp_ajax_zci_edit_zip_codes', 'zci_edit_zip_codes' );
function zci_edit_zip_codes() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'zci_zip_codes';

	$edit_zip_code  = $_POST['edit_zip_code'];
	$edit_zip_id  = $_POST['edit_zip_id'];

	$zip_code = $wpdb->query($wpdb->prepare("UPDATE $table_name SET zip_code='$edit_zip_code' WHERE id=$edit_zip_id"));

	echo $zip_code." Update Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}