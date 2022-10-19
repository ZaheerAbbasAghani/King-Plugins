<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_make_sure_correct_slot", "sqr_make_sure_correct_slot");
add_action("wp_ajax_nopriv_sqr_make_sure_correct_slot", "sqr_make_sure_correct_slot");

function sqr_make_sure_correct_slot(){


	$floor_id = $_POST['floor_id'];
	$spots = explode(",", $_POST['spots']);

	wp_die();	
}