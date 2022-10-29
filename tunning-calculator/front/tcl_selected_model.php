<?php 
add_action( 'wp_ajax_tcl_selected_make', 'tcl_selected_make' );
add_action( 'wp_ajax_nopriv_tcl_selected_make', 'tcl_selected_make' );
function tcl_selected_make() {
	global $wpdb;

global $wpdb;
$table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
$make = $_POST['make'];
$query = "SELECT DISTINCT model FROM $table_name where make='$make'";
$results = $wpdb->get_results($query, ARRAY_A);
	echo "<select id='tcl_model'><option value='' disabled selected> Select a Model </option>";
	$i=0;
	foreach ($results as $value) {
		echo "<option value='".$value['model']."'>".$value['model']."</option>";
		$i++;
	}
	echo "</select>";

	wp_die();
}