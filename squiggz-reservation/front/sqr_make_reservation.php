<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_make_reservation", "sqr_make_reservation");
add_action("wp_ajax_nopriv_sqr_make_reservation", "sqr_make_reservation");

function sqr_make_reservation(){

	$startDate 		= $_POST['startDate'];
	$startTime 		= $_POST['startTime'];
	$endTime 		= $_POST['endTime'];
	$game 			= $_POST['game'];
	$floor_id 		= $_POST['floor_id'];
	$spot 			= $_POST['choosen_spot'];
	$seats_to_fill 	= $_POST['reserve_game'];
	$color 			= $_POST['color'];
	$intervals 		= $_POST['intervals'];


	global $wpdb;
	$table_name = $wpdb->prefix.'sqr_squizz_reservation';

		$results =$wpdb->get_results("SELECT spot_selected,start_time,end_time FROM $table_name where floor_id='$floor_id' AND start_date='$startDate'", ARRAY_A);
		$found = false;
		foreach ($results as $key => $value) {
			foreach($spot as $spo){
				if(in_array($spo, explode(",", $value['spot_selected'])) && in_array($value['start_time'], $intervals)){
					$found = true;
				}

				if(in_array($spo, explode(",", $value['spot_selected'])) && in_array($value['end_time'], $intervals)){
					$found = true;
				}
			}
		}

		//echo $found;

		if($found == 1){
			$response = array("status" => "0");
		 	wp_send_json( $response );
		}
		else{
			$now = current_time('mysql');
			$insert_array = array(
		        'start_date' 	=> $startDate,
		        'start_time' 	=> $startTime,
		        'end_time' 		=> $endTime,
		        'choose_game' 	=> $game,
		        'spot_reserve' 	=> $seats_to_fill,
		        'spot_selected' => implode(",", $spot),
		        'color' 		=> $color,
		        'status' 		=> get_option( 'auto_approve_permission' ) == 1 ? 0 : 1,
		        'floor_id' 		=> $floor_id,
		        'user_id'		=> get_current_user_id(),
		        'created_at' 	=> $now
			);
			$success = $wpdb->insert($table_name,$insert_array);

			if($success == 1){
				if(get_option( 'auto_approve_permission' ) == 1){

					$response = array("status" => "2", "message" => get_option( 'reservation_pending_message' ));
		 			wp_send_json( $response );

				}else{

					$response = array("status" => "1", "message" => get_option( 'reservation_publish_message' ));
		 			wp_send_json( $response );
				}
			}
		}


	
	wp_die();	
}