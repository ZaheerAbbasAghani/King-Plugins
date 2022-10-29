<?php
add_action( 'wp_ajax_nopriv_afi_update_field', 'afi_update_field' );
add_action( 'wp_ajax_afi_update_field', 'afi_update_field' );
function afi_update_field() {

	$user_id = get_current_user_id();
	$key= $_POST['attr'];
	$value = $_POST['field'];

	update_user_meta($user_id,$key,$value);
	echo "Thanks for your time.";

	wp_die(); 
}