<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_check_empty_seats", "sqr_check_empty_seats");
add_action("wp_ajax_nopriv_sqr_check_empty_seats", "sqr_check_empty_seats");

function sqr_check_empty_seats(){




	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_reservation';

	$start_time = strtotime($_POST['start_time']);
	//$end_time 	= strtotime($_POST['end_time']);

	$response = array('start_time' => $start_time);
	wp_send_json( $response );


	wp_die();
}