<?php
add_action( 'wp_ajax_nopriv_afi_insert_fields', 'afi_insert_fields' );
add_action( 'wp_ajax_afi_insert_fields', 'afi_insert_fields' );
function afi_insert_fields() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'fields_list';

	parse_str($_POST['fields'], $fieldsarray);
	foreach ($fieldsarray['afi_field_name'] as $fields) {
	
	 	$query = "SELECT * FROM $table_name WHERE field='$fields' ";
		$query_results = $wpdb->get_results($query);
		if(count($query_results) == 0) {
			$rowResult=$wpdb->insert($table_name, array("field" => $fields),array("%s"));
			echo "<p id='field_message'> New Fields ($fields) created. </p> \n";	
		}else{
			echo "<p id='field_message'> Field name already exists</p>\n";	
		}

	}

	wp_die(); // this is required to terminate immediately and return a proper response
}