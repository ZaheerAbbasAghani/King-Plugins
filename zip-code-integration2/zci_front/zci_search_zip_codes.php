<?php

add_action( 'wp_ajax_nopriv_zci_search_zip_codes', 'zci_search_zip_codes' );

add_action( 'wp_ajax_zci_search_zip_codes', 'zci_search_zip_codes' );

function zci_search_zip_codes() {

	global $wpdb; 

	$table_name = $wpdb->base_prefix.'zci_zip_codes';

	$zip  = $_POST['zip_code'];



	$query = "SELECT * FROM $table_name WHERE zip_code= '$zip' ";

	$results = $wpdb->get_results($query);



	$success_message = esc_attr(get_option('success_message'));

	$fail_message = esc_attr(get_option('fail_message'));



	if(count($results) == 0) {
		$response=array("alert"=>"fail","message"=>$fail_message);
		wp_send_json( $response); 

	}else{
		$response=array("alert"=>"success","message"=>$success_message);
		wp_send_json( $response); 
	}



	wp_die(); // this is required to terminate immediately and return a proper response

}