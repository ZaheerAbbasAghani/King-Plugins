<?php
add_action( 'wp_ajax_nopriv_br_insert_relationship', 'br_insert_relationship' );
add_action( 'wp_ajax_br_insert_relationship', 'br_insert_relationship' );
function br_insert_relationship() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'br_relationship';
	//print_r($_POST['relation']);
	//$relationarray = array();
	//parse_str($_POST, $relationarray);

	//print_r($_POST['relation']);

	foreach ($_POST['relation'] as $relation) {
		$rel =  $relation['value'];
	
	 	$query = "SELECT * FROM $table_name WHERE br_relation='$rel' ";
		$query_results = $wpdb->get_results($query);
		if(count($query_results) == 0) {
			$rowResult=$wpdb->insert($table_name, array("br_relation" => $rel),array("%s"));
			echo "Relation ($rel) added successfully. \n";	
		}else{
			echo "Relation ($rel) already exists. \n";	
		}
		
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}