<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
	@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_make_it_reservable", "sqr_make_it_reservable");
add_action("wp_ajax_nopriv_sqr_make_it_reservable", "sqr_make_it_reservable");

function sqr_make_it_reservable(){


	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_tables';

	$date 			= $_POST['dt'];
	$start_time 	= $_POST['start_time'];
	$end_time 		= $_POST['end_time'];
	$booked_spot 	= serialize($_POST['booked_spot']);

	$checkIfExists = $wpdb->get_var("SELECT choosen_date FROM $table_name WHERE choosen_date = '$date'");
	$now = current_time('mysql');


	if ($checkIfExists == NULL) {

		$insert_array = array(
	        'choosen_date' 	=> $date,
	        'spots' 		=> $booked_spot,
	        'created_at' 	=> $now
		);
		$success = $wpdb->insert($table_name,$insert_array);
		echo "Inserted";


	}else{


		$update = $wpdb->update( $table_name, array( 'choosen_date' => $date, 'spots' => $booked_spot, 'created_at' => $now),array('choosen_date'=>$date));

		echo "Updated";

	}


	wp_die();	
}