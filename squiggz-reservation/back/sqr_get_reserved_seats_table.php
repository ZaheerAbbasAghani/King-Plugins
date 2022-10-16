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

/*	$date 			= $_POST['dt'];
	$start_time 	= $_POST['start_time'];
	$end_time 		= $_POST['end_time'];
	$booked_spot 	= serialize($_POST['booked_spot']);

	$checkIfExists = $wpdb->get_var("SELECT choosen_date FROM $table_name WHERE choosen_date = '$date'");
	$now = current_time('mysql');


	if ($checkIfExists == NULL) {

		$insert_array = array(
	        'choosen_date' 	=> $date,
	        'start_time' 	=> $start_time,
	        'end_time' 		=> $end_time,
	        'spots' 		=> $booked_spot,
	        'created_at' 	=> $now
		);
		$success = $wpdb->insert($table_name,$insert_array);
		echo "Inserted";


	}else{

		$update = $wpdb->update( $table_name, array( 'choosen_date' => $date, 'start_time' => $start_time, 'end_time' => $end_time, 'spots' => $booked_spot, 'created_at' => $now),array('choosen_date'=>$date));

		echo "Updated";

	}
*/

	wp_die();	
}