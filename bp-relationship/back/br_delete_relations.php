<?php
add_action( 'wp_ajax_nopriv_br_delete_relations', 'br_delete_relations' );
add_action( 'wp_ajax_br_delete_relations', 'br_delete_relations' );
function br_delete_relations() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'br_relationship';

	$reltion  = $_POST['reltion'];
	$wpdb->delete( $table_name, array( 'id' => $reltion ), array( '%d' ) );
	
	echo "Deleted Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}