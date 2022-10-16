<?php 

add_action( 'wp_ajax_nopriv_sqr_permission_needed', 'sqr_permission_needed' );
add_action( 'wp_ajax_sqr_permission_needed', 'sqr_permission_needed' );
function sqr_permission_needed() {
	global $wpdb;

    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
    $results = $wpdb->get_results ("SELECT * FROM $table_name");

	$val = $_POST['val'];
	$row_id = $_POST['row_id'];

	$data_update = array('status' => $val);
	$data_where = array('id' => $row_id);
	$update = $wpdb->update($table_name, $data_update, $data_where);

	if($update == 1){
		echo "Reservation Update Successfully";
	}


	wp_die();
}