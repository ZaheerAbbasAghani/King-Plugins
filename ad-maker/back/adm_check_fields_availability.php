<?php 

add_action( 'wp_ajax_adm_check_fields_availability', 'adm_check_fields_availability' );
add_action( 'wp_ajax_nopriv_adm_check_fields_availability', 'adm_check_fields_availability' );

function adm_check_fields_availability() {

	global $wpdb;
    $table_name = $wpdb->base_prefix.'adm_fields_table';

	if($_POST['fields'] == 1){

		$query = "SELECT type,required,label,subtype,inline,name,className,multiple,valuess,selected,multiple FROM $table_name ORDER BY position ASC";
		$query_results = $wpdb->get_results($query);
		$fields = array();
		if(count($query_results) > 0) {

			foreach ($query_results as $results) {

				$fields[] = array("type" => $results->type, "required" => $results->required,"label" => $results->label,"subtype" => $results->subtype,"inline" => $results->inline,"name" => $results->name,"className" => $results->className,"multiple" => $results->multiple,"values" => unserialize($results->valuess),"placeholder" => $results->placeholder,"selected" => $results->selected);
			}
		}
		wp_send_json( $fields );
	}

	wp_die(); // this is required to terminate immediately and return a proper response

}