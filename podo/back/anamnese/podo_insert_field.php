<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_podo_insert_field', 'podo_insert_field' );
add_action( 'wp_ajax_podo_insert_field', 'podo_insert_field' );
function podo_insert_field() {

global $wpdb; 
$table_name = $wpdb->base_prefix.'anam_fields_maker';

$flabel = $_POST['flabel'];
$ftype = $_POST['ftype'];
$fname = str_replace(' ', '', strtolower($flabel));
$time = $time = current_time( 'mysql' );

$query = "SELECT * FROM $table_name WHERE label='$flabel' ";
$query_results = $wpdb->get_results($query, ARRAY_A);
if(count($query_results) == 0) {
	$forder  = $wpdb->get_var( 'SELECT forder FROM ' . $table_name . ' ORDER BY id DESC LIMIT 1');
	$final = $forder + 1;
	$rowResult=$wpdb->insert($table_name, array("label" => $flabel, "fieldtype" => $ftype, "fname" => $fname,"forder" => $final,"created_at" => $time),array("%s","%s","%s","%d"));

	$table_name1 = $wpdb->base_prefix.'anam_document_info';
	$query = "ALTER TABLE $table_name1 ADD $fname VARCHAR(255) after user_id";
	$wpdb->query($query);


	echo "Eintrag wurde erfolgreich erstellt.";	
}else{
	echo "Das Label für das Feld existiert bereits";	
}




	wp_die();
}
