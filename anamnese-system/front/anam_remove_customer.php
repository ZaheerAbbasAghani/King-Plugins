<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_remove_customer', 'anam_remove_customer' );
add_action( 'wp_ajax_anam_remove_customer', 'anam_remove_customer' );
function anam_remove_customer() {
	
	global $wpdb;
    $table_name1 = $wpdb->base_prefix.'anam_customer_info';
	$table_name2 = $wpdb->base_prefix.'anam_document_info';

//	print_r($_POST);

	$id  = $_POST['id'];
	$wpdb->delete($table_name1,array('id'=>$id),array('%d'));
	$wpdb->delete($table_name2,array('user_id'=>$id),array('%d'));

	echo __("Customer Deleted Successfully.");


	wp_die();
}