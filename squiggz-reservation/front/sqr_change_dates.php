<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
	@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_change_dates", "sqr_change_dates");
add_action("wp_ajax_nopriv_sqr_change_dates", "sqr_change_dates");

function sqr_change_dates(){


	global $wpdb;

    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
    $results 	= $wpdb->get_results ("SELECT * FROM $table_name");
	$endDate 	= strtotime($_POST['endDate']);
	$row_id 	= $_POST['row_id'];

	$data_update = array('start_date' => $_POST['startDate'], 'end_date' => $_POST['endDate']);
	$data_where = array('id' => $row_id);
	$update = $wpdb->update($table_name, $data_update, $data_where);

	if($update == 1){
		echo "Update Successfully";
	}

	wp_die();
}