<?php 

add_action( 'wp_ajax_camp_delete_user', 'camp_delete_user' );
add_action( 'wp_ajax_nopriv_camp_delete_user', 'camp_delete_user' );

function camp_delete_user() {
	global $wpdb;

	$post_id = $_POST['id'];
	$camp_voter_phone = get_post_meta( $post_id, 'camp_voter_phone', true);
	$phone = $_POST['phone'];
	$string = str_replace('+', '', $phone);
	$user = get_user_by("login", $string);
	$user_id = $user->data->ID;


	$array3 = unserialize($camp_voter_phone);

	if (($key3 = array_search($phone, $array3)) !== false) {
    	unset($array3[$key3]);
    	update_post_meta($post_id, "camp_voter_phone", serialize($array3));
    	delete_user_meta($user_id, "votable", $post_id);
    	//wp_delete_user($user_id);
	}

	echo "Row Deleted";

	wp_die();
}