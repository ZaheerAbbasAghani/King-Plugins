<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_delete_all_reservations", "sqr_delete_all_reservations");
add_action("wp_ajax_nopriv_sqr_delete_all_reservations", "sqr_delete_all_reservations");

function sqr_delete_all_reservations(){

	global $wpdb;
    $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
 
    if($_POST['delete'] == 1){

        $delete = $wpdb->query('TRUNCATE TABLE '.$table_name);
        if($delete == 1){
        	echo "All Reservation Deleted Successfully.";
        }else{
        	echo "Something went wrong!";
        }

    }
  
	wp_die();
}