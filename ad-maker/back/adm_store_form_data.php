<?php 

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1);

}


/*function renameArrKey($arr, $oldKey, $newKey){
    if(!isset($arr[$oldKey])) return $arr; // Failsafe
    $keys = array_keys($arr);
    $keys[array_search($oldKey, $keys)] = $newKey;
    $newArr = array_combine($keys, $arr);
    return $newArr;
}*/


add_action( 'wp_ajax_adm_store_form_data', 'adm_store_form_data' );
add_action( 'wp_ajax_nopriv_adm_store_form_data', 'adm_store_form_data' );

function adm_store_form_data() {

	global $wpdb; // this is how you get access to the database
	$table_name = $wpdb->base_prefix.'adm_fields_table';
	$table_name1 = $wpdb->base_prefix.'adm_submitted_data_table';
	$formData = json_decode(stripslashes($_POST['formData']));

	$fields = array();
	$i = 1;
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
		$rpl = isset($results->placeholder) == "" ? "" : $results->placeholder;

		$fields[] = array("type" => $rt, "required" => $rr,"label" => $rl,"subtype" => $rs,"inline" => $ri,"name" => $rn,"className" => $cn,"multiple" => $rm,"values" => $rv,"selected" => $rsl,"placeholder" => $rpl,  "position" => $i);

		$i++;

	}


	//print_r($fields);


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
		$placeholder = $field['placeholder'] ? $field['placeholder'] : "";
		$position = $field['position'] ? $field['position'] : "";

	

		$cname = $field['label'];
		if(!empty($cname)){
			$column_name = str_replace(' ', '_',   strtolower($cname));
			$row = $wpdb->get_results( "SELECT label FROM $table_name WHERE label='$cname'" );

			if(empty($row)){
				$query = "SELECT * FROM $table_name1";
				$data_results = $wpdb->get_results($query);
				foreach ($data_results as $results) {
					$array = unserialize($results->formData);
					$array[$column_name] = "";

					$wpdb->update( $table_name1, array( 'formData' => serialize($array)),array('id'=>$results->id));
				}

			}
		}


		$values_field=serialize($field['values']) ? serialize($field['values']) : "";

		$query = "SELECT * FROM $table_name WHERE name='$name_field'";
		$query_results = $wpdb->get_results($query);
		if(count($query_results) == 0) {
			$rowResult = $wpdb->insert($table_name, 
				array("name" => $name_field,"type" => $type_field, "required" => $required_field, "label" => $label_field, "subtype" => $subtype_field,  "inline" => $inline_field, "className" => $className_field, "multiple" => $multiple_field,"selected" => $selected_field,"placeholder" => $placeholder,   "valuess" => $values_field, "position" => $position),

				array("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%d")
			);
			echo $rowResult;

		}else{

			$rowResult = $wpdb->query($wpdb->prepare("UPDATE $table_name SET name='$name_field',type='$type_field',required='$required_field',label='$label_field', subtype='$subtype_field', inline='$inline_field', className='$className_field', multiple='$multiple_field',selected='$selected_field',valuess='$values_field',placeholder='$placeholder', position='$position' WHERE name='%s'", $name_field));
			echo $rowResult;
		}

	}

	// Replacing old column with new column
	$oldColumn = $_POST['oldValue'];
	$newColumn = $_POST['newValue'];
	$oc =  strtolower($oldColumn);
	$ocname = str_replace(' ', '_', $oc);
	$nc =  strtolower($newColumn);
	$ncname = str_replace(' ', '_', $nc);
	if(!empty($oldColumn) && !empty($newColumn)){

		$query = "SELECT * FROM $table_name1";
		$data_results = $wpdb->get_results($query);
		foreach ($data_results as $results) {
			//print_r(unserialize($results->formData));
			$array = unserialize($results->formData);

			if(array_key_exists( $ocname, $array)) {

				$keys = array_keys($array);
		    	$keys[array_search($ocname, $keys)] = $ncname;
			    $final =  serialize(array_combine($keys, $array));	

			    //print_r($final);

			    $wpdb->update( $table_name1, array( 'formData' => $final),array('id'=>$results->id));

			}
		}

	}


	wp_die(); // this is required to terminate immediately and return a proper response

}