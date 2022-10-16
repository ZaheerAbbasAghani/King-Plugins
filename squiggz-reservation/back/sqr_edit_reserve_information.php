<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_edit_reserve_information", "sqr_edit_reserve_information");
add_action("wp_ajax_nopriv_sqr_edit_reserve_information", "sqr_edit_reserve_information");

function sqr_edit_reserve_information(){


	global $wpdb;
    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
    $row_id = $_POST['row_id'];
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$row_id'");

	foreach ($results as $result ){
		//echo $result->start_date;
		$response = array("start_date" =>$result->start_date, "start_time"=>$result->start_time,"end_time"=>$result->end_time, "id"=>$result->id);
		wp_send_json( $response );
	}


	wp_die();
}