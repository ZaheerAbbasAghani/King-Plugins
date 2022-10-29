<?php
add_action( 'wp_ajax_nopriv_afi_delete_fields', 'afi_delete_fields' );
add_action( 'wp_ajax_afi_delete_fields', 'afi_delete_fields' );
function afi_delete_fields() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'fields_list';

	$field  = $_POST['field'];
	$wpdb->delete($table_name,array('id'=>$field ), array('%d'));
	$users = get_users();
	foreach ( $users as $user ) {
		//echo $user->ID;
		delete_user_meta($user->ID, $_POST['field_name']);
	}

	echo "Deleted Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}