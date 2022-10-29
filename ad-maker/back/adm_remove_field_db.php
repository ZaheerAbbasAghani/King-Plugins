<?php 

add_action( 'wp_ajax_adm_remove_field_db', 'adm_remove_field_db' );
add_action( 'wp_ajax_nopriv_adm_remove_field_db', 'adm_remove_field_db' );

function adm_remove_field_db() {

	global $wpdb; // this is how you get access to the database

	
	$table_name = $wpdb->base_prefix.'adm_fields_table';
	$table_name1 = $wpdb->base_prefix.'adm_submitted_data_table';

	$name = $_POST['name'];
	$delete = $wpdb->delete($table_name, array('name' => $name), array("%s"));
	//echo $delete;
	
	$column_name = str_replace(' ', '_',  strtolower(preg_replace('/\(|\)/','',$_POST['label'])));
	$row = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
	WHERE table_name = '$table_name1' AND column_name = '$column_name' " );

	if(!empty($row)){
	   $wpdb->query("ALTER TABLE $table_name1 DROP COLUMN $column_name");
	}


	wp_die(); // this is required to terminate immediately and return a proper response

}