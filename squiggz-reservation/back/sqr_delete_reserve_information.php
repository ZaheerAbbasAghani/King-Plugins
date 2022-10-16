<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_delete_reserve_information", "sqr_delete_reserve_information");
add_action("wp_ajax_nopriv_sqr_delete_reserve_information", "sqr_delete_reserve_information");

function sqr_delete_reserve_information(){

	global $wpdb;
    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
    $row_id = $_POST['row_id'];
    $delete = $wpdb->delete( $table_name, array( 'id' => $row_id ) );
    if($delete == 1){
    	echo "Reservation Deleted Successfully.";
    }else{
    	echo "Something went wrong!";
    }
  
	wp_die();
}