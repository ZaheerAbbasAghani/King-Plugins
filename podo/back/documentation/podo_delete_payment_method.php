<?php
add_action( 'wp_ajax_nopriv_podo_delete_payment_method','podo_delete_payment_method');
add_action('wp_ajax_podo_delete_payment_method','podo_delete_payment_method');
function podo_delete_payment_method() {
	global $wpdb;
	$table_name = $wpdb->base_prefix.'anam_payment_methods';

	$id  = $_POST['id'];
	$wpdb->delete( $table_name,array('id' => $id),array('%d'));
	
	echo "Deleted Successfully.";

	wp_die();
}