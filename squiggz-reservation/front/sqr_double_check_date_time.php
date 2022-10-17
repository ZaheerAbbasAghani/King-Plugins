<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_double_check_date_time", "sqr_double_check_date_time");
add_action("wp_ajax_nopriv_sqr_double_check_date_time", "sqr_double_check_date_time");

function sqr_double_check_date_time(){

	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_reservation';

	$startDate 	= $_POST['startDate'];
	$startTime 	= $_POST['startTime'];
	$endTime 	= $_POST['endTime'];
	$intervals 	= $_POST['intervals'];

	$results = $wpdb->get_results("SELECT start_date,start_time,end_time FROM $table_name where start_date='$startDate'");
	
	foreach ($results as $key => $result) {

		if(in_array($result->start_time, $intervals) || in_array($result->end_time, $intervals)){
			$response = array("status" => 0, "message" => "Please choose correct time.");
			wp_send_json( $response );
		}

	}

	wp_die();
}