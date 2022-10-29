<?php
add_action("wp_ajax_sol_insert_pricing", "sol_insert_pricing");
add_action("wp_ajax_nopriv_sol_insert_pricing", "sol_insert_pricing");
function sol_insert_pricing(){

//print_r($_POST);

global $wpdb; 
$table_name = $wpdb->base_prefix.'sol_pricing';

$pricing = $_POST['pricing'];
//print_r($pricing);

foreach ($pricing as $row) {
	
	$kwp_from = $row['kwp_from'];
	$kwp_m2	= $row['kwp_m2'];
	$price = $row['pricing'];

	if(!empty($kwp_from) && !empty($kwp_from) && !empty($price)){

	 	$query = "SELECT * FROM $table_name WHERE kwp_from='$kwp_from' AND kwp_m2='$kwp_m2' AND pricing='$price' ";
		$query_results = $wpdb->get_results($query);
		if(count($query_results) == 0) {
			$rowResult=$wpdb->insert($table_name, array("kwp_from" => $kwp_from,"kwp_m2" => $kwp_m2,  "pricing" => $price),array("%s","%s","%s"));
			echo "<p> Record added in database </p> \n";	
		}else{
			echo "<p> Row already exists. </p> \n";	
		}
	}
}




wp_die();
}
