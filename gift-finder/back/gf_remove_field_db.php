<?php 


add_action( 'wp_ajax_gf_remove_field_db', 'gf_remove_field_db' );
add_action( 'wp_ajax_nopriv_gf_remove_field_db', 'gf_remove_field_db' );

function gf_remove_field_db() {
	global $wpdb; // this is how you get access to the database
	$table_name = $wpdb->base_prefix.'gf_fields_table';
	$name = $_POST['name'];

	$delete = $wpdb->delete($table_name, array('name' => $name), array("%s"));

	echo $delete;




	wp_die(); // this is required to terminate immediately and return a proper response
}