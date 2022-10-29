<?php 

add_action( 'wp_ajax_vir_get_user_email', 'vir_get_user_email' );
add_action( 'wp_ajax_nopriv_vir_get_user_email', 'vir_get_user_email' );

function vir_get_user_email() {

	$label = $_POST['label'];

	global $wpdb;
    $users = $wpdb->get_results("SELECT user_email FROM $wpdb->users WHERE display_name = '$label'");
    $required_user_email = $users[0]->user_email;
    echo $required_user_email;


	wp_die(); // this is required to terminate immediately and return a proper response
}