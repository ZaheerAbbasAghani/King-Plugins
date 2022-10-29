<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_rtf_insert_field', 'rtf_insert_field' );
add_action( 'wp_ajax_rtf_insert_field', 'rtf_insert_field' );
function rtf_insert_field() {

global $wpdb; 
$table_name = $wpdb->base_prefix.'rtf_fields_maker';

$flabel = $_POST['flabel'];
$ftype = $_POST['ftype'];
$str_arr = preg_split ("/\,/", $_POST['fvalue']); 
$fvalue = $_POST['fvalue'] != "" ? serialize($str_arr) : "";
$field_placeholder = $_POST['field_placeholder'];
$fname =  preg_replace('/\s+/', '_',strtolower($flabel.'_field'));
$time = $time = current_time( 'mysql' );


//print_r($_POST);

$query = "SELECT * FROM $table_name WHERE label='$flabel' ";
$query_results = $wpdb->get_results($query, ARRAY_A);
if(count($query_results) == 0) {
	$forder  = $wpdb->get_var( 'SELECT forder FROM ' . $table_name . ' ORDER BY id DESC LIMIT 1');
	$final = $forder + 1;
	$rowResult=$wpdb->insert($table_name, array("label" => $flabel, "fieldtype" => $ftype, "fname" => $fname,"fvalue" => $fvalue, "fplaceholder" =>$field_placeholder,  "forder" => $final,"created_at" => $time),array("%s","%s","%s","%s","%s","%d"));

	$table_name1 = $wpdb->base_prefix.'rtf_store_info';
	$query = "ALTER TABLE $table_name1 ADD $fname VARCHAR(255) after id";
	$wpdb->query($query);


	


	echo "Field Created Successfully";	
}else{
	echo "Field already exists";	
}
 



	wp_die();
}
