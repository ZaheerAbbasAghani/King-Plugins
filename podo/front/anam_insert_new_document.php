<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_insert_new_document', 'anam_insert_new_document' );
add_action( 'wp_ajax_anam_insert_new_document', 'anam_insert_new_document' );
function anam_insert_new_document() {
	

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_document_info';


$cinfo = array();
parse_str($_POST['doc_info'], $cinfo);

extract($cinfo);
$now = current_time('mysql');

$insert = $wpdb->insert($table_name,$cinfo);

if($insert == 1){
	$response = array("message" => "Neue Anamnese wurde erfolgreich erstellt.", "status" => 1);
	echo wp_send_json($response);
}else{

	$response = array("message" => "Etwas ist schief gelaufen!", "status" => 0);
	echo wp_send_json($response);
}



	wp_die();
}