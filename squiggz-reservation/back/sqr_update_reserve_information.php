<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_update_reserve_information", "sqr_update_reserve_information");
add_action("wp_ajax_nopriv_sqr_update_reserve_information", "sqr_update_reserve_information");

function sqr_update_reserve_information(){

	global $wpdb;
    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';

	$id 		= $_POST['row_id']; //added stripslashes_deep which removes WP escaping.
	$startDateTime 	= $_POST['startDateTime'];
	$startTime 	= $_POST['startTime'];
	$endTime 	= $_POST['endTime'];

	$update = $wpdb->update($table_name, 
		array('start_date'=>$startDateTime, 'start_time'=>$startTime, 'end_time' => $endTime), 
		array('id'=>$id)
	);

	if($update == 1){
		echo "Reservation Updated Successfully";
	}else{
		echo "Something went wrong";
	}

	wp_die();
}