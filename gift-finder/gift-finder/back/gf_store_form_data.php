<?php 

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1);
}

add_action( 'wp_ajax_gf_store_form_data', 'gf_store_form_data' );
add_action( 'wp_ajax_nopriv_gf_store_form_data', 'gf_store_form_data' );

function gf_store_form_data() {
	global $wpdb; // this is how you get access to the database
	$table_name = $wpdb->base_prefix.'gf_fields_table';

	$formData = json_decode(stripslashes($_POST['formData']));

/*	echo "<pre>";
	print_r($formData);
	echo "</pre>";*/

	$fields = array();
	foreach ($formData as $results) {

		$rt = isset($results->type) == "" ? "" : $results->type;
		$rr = isset($results->required) == "" ? "" : $results->required;
		$rl = isset($results->label) == "" ? "" : $results->label;
		$rs = isset($results->subtype) == "" ? "" : $results->subtype;
		$ri = isset($results->inline) == "" ? "" : $results->inline;
		$rn = isset($results->name) == "" ? "" : $results->name;
		$cn = isset($results->className) == "" ? "" : $results->className;
		$rm = isset($results->multiple) == "" ? "" : $results->multiple;
		$rv = isset($results->values) == "" ? "" : $results->values;
		$rsl = isset($results->selected) == "" ? "" : $results->selected;

		$dtype = isset($results->dependson_type) == "" ? "" : $results->dependson_type;

		$dcode = isset($results->dependson_code) == "" ? "" : $results->dependson_code;

		$fields[] = array("type" => $rt, "required" => $rr,"label" => $rl,"subtype" => $rs,"inline" => $ri,"name" => $rn,"className" => $cn,"multiple" => $rm,"values" => $rv,"selected" => $rsl,"dependson_type" => $dtype,"dependson_code" => $dcode);
	}

	foreach ($fields as $field) {

		$name_field = $field['name'] ? $field['name'] : $field['type'];
		$type_field = $field['type'] ? $field['type'] : "";
		$required_field = $field['required'] ? $field['required'] : "";
		$label_field = str_replace("'", "\'", $field['label']) ? str_replace("'", "\'", $field['label']) : "";
		$subtype_field = $field['subtype'] ? $field['subtype'] : "";
		$inline_field = $field['inline'] ? $field['inline'] : "";
		$className_field = $field['className'] ? $field['className'] : "";
		$multiple_field = $field['multiple'] ? $field['multiple'] : "";
		$selected_field = $field['selected'] ? $field['selected'] : "";

		$values_field=serialize($field['values']) ? serialize($field['values']) : "";
		
		$dependson_type_field = $field['dependson_type'] ? $field['dependson_type'] : "";

		$dependson_code_field = $field['dependson_code'] ? $field['dependson_code'] : "";

		

		$query = "SELECT * FROM $table_name WHERE name='$name_field'";
		$query_results = $wpdb->get_results($query);
		if(count($query_results) == 0) {
				
			$rowResult = $wpdb->insert($table_name, 
				array("name" => $name_field,"type" => $type_field, "required" => $required_field, "label" => $label_field, "subtype" => $subtype_field,  "inline" => $inline_field, "className" => $className_field, "multiple" => $multiple_field,"selected" => $selected_field,  "valuess" => $values_field, "dependson_type" => $dependson_type_field,"dependson_code" => $dependson_code_field),
				array("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")
			);

			echo $rowResult;

		}else{

			$rowResult = $wpdb->query($wpdb->prepare("UPDATE $table_name SET name='$name_field',type='$type_field',required='$required_field',label='$label_field', subtype='$subtype_field', inline='$inline_field', className='$className_field', multiple='$multiple_field',selected='$selected_field',valuess='$values_field',dependson_type='$dependson_type_field',dependson_code='$dependson_code_field' WHERE name='%s'", $name_field));

			echo $rowResult;
		}
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}