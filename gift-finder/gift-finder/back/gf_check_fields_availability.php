<?php 

add_action( 'wp_ajax_gf_check_fields_availability', 'gf_check_fields_availability' );
add_action( 'wp_ajax_nopriv_gf_check_fields_availability', 'gf_check_fields_availability' );

function gf_check_fields_availability() {
	
	global $wpdb;
    $table_name = $wpdb->base_prefix.'gf_fields_table';

	if($_POST['fields'] == 1){

		$query = "SELECT type,required,label,subtype,inline,name,className,multiple,valuess,selected,dependson_type, dependson_code FROM $table_name";
		$query_results = $wpdb->get_results($query);
		$fields = array();
		if(count($query_results) > 0) {
			
			foreach ($query_results as $results) {
				$fields[] = array("type" => $results->type, "required" => $results->required,"label" => $results->label,"subtype" => $results->subtype,"inline" => $results->inline,"name" => $results->name,"className" => $results->className,"multiple" => $results->multiple,"values" => unserialize($results->valuess),"multiple" => $results->selected, "dependson_type" => $results->dependson_type, "dependson_code" => $results->dependson_code);
			}
		}
		wp_send_json( $fields );
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}