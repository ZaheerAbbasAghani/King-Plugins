<?php
add_action( 'wp_ajax_nopriv_rtf_delete_field', 'rtf_delete_field' );
add_action( 'wp_ajax_rtf_delete_field', 'rtf_delete_field' );
function rtf_delete_field() {
	global $wpdb;
	$table_name = $wpdb->base_prefix.'rtf_fields_maker';
	$table_name1 = $wpdb->base_prefix.'rtf_store_info';

	$column_name = $_POST['col'];

//	echo $column_name;

	$id  = $_POST['id'];
	$wpdb->delete( $table_name,array('id' => $id),array('%d'));

	$query = "ALTER TABLE $table_name1 DROP COLUMN $column_name";
	$wpdb->query($query);

	echo "Deleted Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}