<?php
add_action( 'wp_ajax_nopriv_sol_delete_pricing', 'sol_delete_pricing' );
add_action( 'wp_ajax_sol_delete_pricing', 'sol_delete_pricing' );
function sol_delete_pricing() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'sol_pricing';

	$sol_id  = $_POST['sol_id'];
	$wpdb->delete($table_name, array('id' =>$sol_id));
	echo "Deleted Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}