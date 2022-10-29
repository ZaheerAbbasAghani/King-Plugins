<?php 
add_action( 'wp_ajax_nopriv_lms_delete_leave_request', 'lms_delete_leave_request' );
add_action( 'wp_ajax_lms_delete_leave_request', 'lms_delete_leave_request' );
function lms_delete_leave_request() {
	

	global $wpdb;
	$table_name = $wpdb->base_prefix.'lms_records';

	$id  = $_POST['id'];
	$wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );
	
	echo "Deleted Successfully.";

	wp_die();
}