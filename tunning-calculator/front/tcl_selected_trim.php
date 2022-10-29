<?php 
add_action( 'wp_ajax_tcl_selected_trim', 'tcl_selected_trim' );
add_action( 'wp_ajax_nopriv_tcl_selected_trim', 'tcl_selected_trim' );
function tcl_selected_trim() {
global $wpdb;
$table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
$model = $_POST['model'];
$query = "SELECT DISTINCT trrim FROM $table_name where model='$model'";
$results = $wpdb->get_results($query, ARRAY_A);
echo "<select id='tcl_trim'><option value='' disabled selected> Select a Trim </option>";
$i=0;
foreach ($results as $value) {
	echo "<option value='".$value['trrim']."'>".$value['trrim']."</option>";
	$i++;
}
echo "</select>";

	wp_die();
}