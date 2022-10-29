<?php 

add_action( 'wp_ajax_tti_delete_tourist_info', 'tti_delete_tourist_info' );
add_action( 'wp_ajax_nopriv_tti_delete_tourist_info', 'tti_delete_tourist_info' );

function tti_delete_tourist_info() {
	global $wpdb; // this is how you get access to the database

    $table_name = $wpdb->base_prefix.'tti_tourist_bookings';

    $id  = $_POST['id'];
	$wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );
	

	echo "Erfolgreich entfernt.";

	wp_die(); // this is required to terminate immediately and return a proper response
}