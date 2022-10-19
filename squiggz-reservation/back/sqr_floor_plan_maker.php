<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_floor_plan_maker", "sqr_floor_plan_maker");
add_action("wp_ajax_nopriv_sqr_floor_plan_maker", "sqr_floor_plan_maker");

function sqr_floor_plan_maker(){


	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_tables';

	$startDate 		= $_POST['startDate'];
	$endDate 		= $_POST['endDate'];
	$booked_spot 	= serialize($_POST['highlighted']);

	$more_more_day = date('Y-m-d', strtotime($endDate . ' +1 day'));

	if(!empty($endDate) && !empty($startDate)){

		$period = new DatePeriod(
		     new DateTime($startDate),
		     new DateInterval('P1D'),
		     new DateTime($more_more_day)
		);

		$now = current_time('mysql');
		foreach ($period as $key => $value) {
	  
			$dt = $value->format('Y-m-d');

			$checkIfExists = $wpdb->get_var( $wpdb->prepare( "SELECT choosen_date FROM $table_name WHERE choosen_date = '%s'", $dt ) );

			if ($checkIfExists == null) {

				$insert_array = array(
			        'choosen_date' 	=> $dt,
			        'spots' 		=> $booked_spot,
			        'created_at' 	=> $now
				);
				$success = $wpdb->insert($table_name,$insert_array);

				
			}else{

				$update = $wpdb->update($table_name, 
					array('spots'=>$booked_spot), 
					array('choosen_date'=>$dt)
				);
			}
		}
		echo "Floor Plan Updated.";
	}else{

		$checkIfExists = $wpdb->get_var( $wpdb->prepare( "SELECT choosen_date FROM $table_name WHERE choosen_date = '%s'", $startDate ) );

		if ($checkIfExists == null) {

			$insert_array = array(
		        'choosen_date' 	=> $startDate,
		        'spots' 		=> $booked_spot,
		        'created_at' 	=> $now
			);
			$success = $wpdb->insert($table_name,$insert_array);
			
		}else{

			$update = $wpdb->update($table_name, 
				array('spots'=>$booked_spot), 
				array('choosen_date'=>$startDate)
			);
		}
		echo "Floor Plan Updated.";
	}

	wp_die();
}