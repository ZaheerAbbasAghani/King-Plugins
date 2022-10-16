<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_make_sure_correct_slot", "sqr_make_sure_correct_slot");
add_action("wp_ajax_nopriv_sqr_make_sure_correct_slot", "sqr_make_sure_correct_slot");

function sqr_make_sure_correct_slot(){


	$floor_id = $_POST['floor_id'];
	$spots = explode(",", $_POST['spots']);


	//print_r($spots);

/*	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_reservation';
	$results = $wpdb->get_results("SELECT spot_selected FROM $table_name where floor_id='$floor_id'", ARRAY_A);

	//print_r($results);

	foreach ($results as $key => $value) {
		echo $value['spot_selected']."\n";
	}
*/

	/*$allSpots = array_filter(array_merge(array(0), $spots));

	print_r($allSpots);


	if($reserve_limit == 1){
		$oneSpot = $allSpots[$reserve_limit];		
	}

	if($reserve_limit == 2){
		for ($i=0; $i < ; $i++) { 
			$oneSpot = $allSpots[$reserve_limit];		
		}
		
	}

	if($reserve_limit == 3){
		$oneSpot = $allSpots[$reserve_limit];		
	}

	if($reserve_limit == 4){
		$oneSpot = $allSpots[$reserve_limit];		
	}

	echo $allSpots[$reserve_limit];*/

	//print_r($array);
	//echo $reserve_limit;

	/*foreach ($spots as $key => $value) {
		echo $value."\n";
	}
*/
/*	$total = array();
	for ($i=1; $i < count($reserve_limit); $i++) { 
		array_push($total, $spots[$i]);
		
	}

	print_r($total);*/

/*	$startDateTime 	= $_POST['startDateTime'];
	$endDateTime 	= $_POST['endDateTime'];
	$game 			= $_POST['game'];
	$floor_id 		= $_POST['floor_id'];
	$spot 			= $_POST['choosen_spot'];
	$seats_to_fill 	= $_POST['reserve_game'];
	$color 			= $_POST['color'];

	global $wpdb;
	$table_name = $wpdb->prefix.'sqr_squizz_reservation';

	$now = current_time('mysql');
	$insert_array = array(
        'start_date' 	=> $startDateTime,
        'end_date' 		=> $endDateTime,
        'choose_game' 	=> $game,
        'spot_reserve' 	=> $seats_to_fill,
        'spot_selected' => $spot,
        'color' 		=> $color,
        'status' 		=> get_option( 'auto_approve_permission' ) == 1 ? 0 : 1,
        'floor_id' 		=> $floor_id,
        'user_id'		=> get_current_user_id(),
        'created_at' 	=> $now
	);
	$success = $wpdb->insert($table_name,$insert_array);

	if($success == 1){
		if(get_option( 'auto_approve_permission' ) == 1){
			echo get_option( 'reservation_pending_message' );
		}else{
			echo get_option( 'reservation_publish_message' );
		}
	}
*/
	wp_die();	
}