<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_floor_plan_delete", "sqr_floor_plan_delete");
add_action("wp_ajax_nopriv_sqr_floor_plan_delete", "sqr_floor_plan_delete");

function sqr_floor_plan_delete(){

	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_tables';

	$startDate 		= $_POST['startDate'];
	$endDate 		= $_POST['endDate'];
	$booked_spot 	= $_POST['highlighted'];


	if(!empty($endDate) && !empty($startDate) && empty($booked_spot)){
		
		$more_more_day = date('Y-m-d', strtotime($endDate . ' +1 day'));

		$period = new DatePeriod(
		     new DateTime($startDate),
		     new DateInterval('P1D'),
		     new DateTime($more_more_day)
		);
		$now = current_time('mysql');

		foreach ($period as $key => $value) {
			$dt = $value->format('Y-m-d');
			$wpdb->delete( $table_name, array( 'choosen_date' => $dt ) );
		}
		echo "Floor Plan Updated.";


	}else{


		if (empty($endDate) && !empty($startDate)) {
		
			$update = $wpdb->update($table_name, 
				array('spots'=>serialize($booked_spot)), 
				array('choosen_date'=>$startDate)
			);

		}else{

			$period = new DatePeriod(
			     new DateTime($startDate),
			     new DateInterval('P1D'),
			     new DateTime($more_more_day)
			);
			$now = current_time('mysql');
			foreach ($period as $key => $value) {
		  
				$dt = $value->format('Y-m-d');
				$update = $wpdb->update($table_name, 
					array('spots'=>serialize($booked_spot)), 
					array('choosen_date'=>$dt)
				);
			}
		}

		echo "Floor Plan Updated.";
	}


	wp_die();
}