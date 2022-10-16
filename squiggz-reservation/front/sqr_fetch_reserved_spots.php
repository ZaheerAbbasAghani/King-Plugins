<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_fetch_reserved_spots", "sqr_fetch_reserved_spots");
add_action("wp_ajax_nopriv_sqr_fetch_reserved_spots", "sqr_fetch_reserved_spots");

function sqr_fetch_reserved_spots(){

	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_reservation';
	$table 		= $_POST['table_id'];
	$dateToday 	= $_POST['dateToday'] == "" ? date("Y-m-d") : $_POST['dateToday'];

	$results = $wpdb->get_results("SELECT start_date,start_time,end_time,spot_selected,color FROM $table_name where floor_id='$table' AND start_date='$dateToday'");

	$res = array();
	foreach ($results as $key => $value) {
		$dt = date( 'H:i', current_time( 'timestamp', 0 ) );
		if(strtotime($value->end_time) > strtotime($dt)){
			array_push($res, array("start_date" => $value->start_date, "start_time" =>strtotime($value->start_time), "end_time" => strtotime($value->end_time),"correct_start_time" => $value->start_time, "correct_end_time" => $value->end_time,  "spot_selected" => $value->spot_selected, "color" => $value->color));
		}
	}

	//echo date( 'H:i', current_time( 'timestamp', 0 ) );

	$response = array('results' => $res);
	wp_send_json( $response );

	wp_die();
}