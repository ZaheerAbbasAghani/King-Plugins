<?php
add_action( 'wp_ajax_jf_delete_all_records', 'jf_delete_all_records' );
add_action( 'wp_ajax_nopriv_jf_delete_all_records', 'jf_delete_all_records' );

function jf_delete_all_records() {

global $wpdb; // this is how you get access to the database
$table_faqs = $wpdb->prefix . 'faqs';
$delete = $_POST['delete'];

if($delete == 1){
	$wpdb->query("TRUNCATE TABLE $table_faqs");
	echo "All delete successfully.";
}

	wp_die(); // this is required to terminate immediately and return a proper response
}