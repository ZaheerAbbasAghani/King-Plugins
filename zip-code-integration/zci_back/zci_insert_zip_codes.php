<?php
add_action( 'wp_ajax_nopriv_zci_insert_zip_codes', 'zci_insert_zip_codes' );
add_action( 'wp_ajax_zci_insert_zip_codes', 'zci_insert_zip_codes' );
function zci_insert_zip_codes() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'zci_zip_codes';

	parse_str($_POST['zipcodes'], $ziparray);
	foreach ($ziparray['zci_zip_codes'] as $zip) {
	
	 	$query = "SELECT * FROM $table_name WHERE zip_code='$zip' ";
		$query_results = $wpdb->get_results($query);
		if(count($query_results) == 0) {
			$rowResult=$wpdb->insert($table_name, array("zip_code" => $zip),array("%s"));
			echo "<p> Zip Code ($zip) Added In Database </p> \n";	
		}else{
			echo "<p> Zip Code ($zip) already in database </p> \n";	
		}

	}

	wp_die(); // this is required to terminate immediately and return a proper response
}