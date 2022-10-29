<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_remove_dokument', 'anam_remove_dokument' );
add_action( 'wp_ajax_anam_remove_dokument', 'anam_remove_dokument' );
function anam_remove_dokument() {

	global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_dokument_info';
    $id = $_POST['id'];
    $info = $wpdb->delete($table_name,array('id'=>$id),array('%d'));
    
    if($info == true){
    	$response = array("message" => "Die Dokumentation wurde erfolgreich gelöscht!", "status" => 1);
		echo wp_send_json($response);
    }else{
    	$response = array("message" => "Beim löschen der Dokumentation ist ein Fehler aufgetreten!".$gangrän, "status" => 0);
		echo wp_send_json($response);
    }


	wp_die();
}