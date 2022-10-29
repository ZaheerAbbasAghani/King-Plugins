<?php 

add_action( 'wp_ajax_adm_remove_this_ad', 'adm_remove_this_ad' );
add_action( 'wp_ajax_nopriv_adm_remove_this_ad', 'adm_remove_this_ad' );

function adm_remove_this_ad() {

	global $wpdb; // this is how you get access to the database

	$table_name = $wpdb->base_prefix.'adm_submitted_data_table';
	$id = $_POST['id'];
	$delete = $wpdb->delete($table_name, array('id' => $id), array("%s"));

	echo "Ad Deleted Successfully.";
	
	wp_die(); // this is required to terminate immediately and return a proper response

}