<?php 
function tcl_tunning_details($tunning){

global $wpdb;
$table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
$query = "SELECT DISTINCT make FROM $table_name";
//$query = "SELECT * FROM $table_name";
$results = $wpdb->get_results($query, ARRAY_A);

//print_r($results);

$tunning .= "<div class='tcl_dropdown_list'>

<div class='dropdown_inner'>";
	$tunning .= "<select id='tcl_make'><option value='' disabled selected> Select a make </option>";
	$i=0;
	foreach ($results as $value) {
		$tunning .= "<option value='".$value['make']."'>".$value['make']."</option>";
		$i++;
	}
	$tunning .= "</select>";


	$tunning .= "<select id='tcl_model'><option value='' disabled selected> Select a Model </option>";
	$tunning .= "</select>";

	$tunning .= "<select id='tcl_trim'><option value='' disabled selected> Select a Trim </option>";
	$tunning .= "</select>";


$tunning .= "</div></div>";


return $tunning;

}
add_shortcode("tunning_details", "tcl_tunning_details");