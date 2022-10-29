<?php
add_action( 'wp_ajax_jac_subscriber_remove', 'jac_subscriber_remove' );
add_action( 'wp_ajax_nopriv_jac_subscriber_remove', 'jac_subscriber_remove' );

function jac_subscriber_remove() {
    global $wpdb;
    $table_name = $wpdb->base_prefix.'jac_subscribe';
    $id = $_POST['id'];
	$wpdb->delete( $table_name, array( 'id' => $id ) );

	echo "Subscriber Delete Success";

	wp_die(); // this is required to terminate immediately and return a proper response
}