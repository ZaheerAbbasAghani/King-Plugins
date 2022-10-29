<?php
add_action( 'wp_ajax_nopriv_podo_delete_treatment','podo_delete_treatment');
add_action('wp_ajax_podo_delete_treatment','podo_delete_treatment');
function podo_delete_treatment() {
	global $wpdb;
	$table_name = $wpdb->base_prefix.'anam_treatments_list';

	$id  = $_POST['id'];
	$wpdb->delete( $table_name,array('id' => $id),array('%d'));
	
	echo "Deleted Successfully.";

	wp_die();
}