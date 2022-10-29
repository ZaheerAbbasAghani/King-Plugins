<?php
add_action( 'wp_ajax_nopriv_podo_delete_field', 'podo_delete_field' );
add_action( 'wp_ajax_podo_delete_field', 'podo_delete_field' );
function podo_delete_field() {
	global $wpdb;
	$table_name = $wpdb->base_prefix.'anam_fields_maker';
	$table_name1 = $wpdb->base_prefix.'anam_document_info';

	$id  = $_POST['id'];
	$column_name = $_POST['col'];
	$wpdb->delete( $table_name,array('id' => $id),array('%d'));
	
	//$string = str_replace(' ', '', strtolower($column_name));

	$query = "ALTER TABLE $table_name1 DROP COLUMN $column_name";
	$wpdb->query($query);

	echo "Eintrag wurde erfolgreich gelöscht.";

	wp_die(); // this is required to terminate immediately and return a proper response
}