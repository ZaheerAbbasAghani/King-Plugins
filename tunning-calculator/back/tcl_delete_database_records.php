<?php 

add_action( 'wp_ajax_tcl_delete_database_records', 'tcl_delete_database_records' );
add_action( 'wp_ajax_nopriv_tcl_delete_database_records', 'tcl_delete_database_records' );

function tcl_delete_database_records() {
	global $wpdb; // this is how you get access to the database
    $table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
	$wpdb->query("TRUNCATE TABLE $table_name");

	echo "All records Deleted.";

	wp_die(); 
}