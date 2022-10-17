<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
	@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_get_reserved_seats_table", "sqr_get_reserved_seats_table");
add_action("wp_ajax_nopriv_sqr_get_reserved_seats_table", "sqr_get_reserved_seats_table");

function sqr_get_reserved_seats_table(){


	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_tables';
	$selected_date = $_POST['selected_date'];

   	$results = $wpdb->get_results("SELECT spots,start_time,end_time FROM $table_name where choosen_date='$selected_date'");

	if(count(unserialize($results[0]->spots)) > 1){
		$result = array("status" => 1,"start_time"=>$results[0]->start_time,"end_time" =>$results[0]->end_time, "result" => unserialize($results[0]->spots));
		wp_send_json($result);
	}else{
		$result = array("status" => 0);
		wp_send_json($result);
	}


	wp_die();	
}