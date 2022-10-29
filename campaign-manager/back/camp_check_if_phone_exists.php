<?php 

add_action( 'wp_ajax_camp_check_if_phone_exists', 'camp_check_if_phone_exists' );
add_action( 'wp_ajax_nopriv_camp_check_if_phone_exists', 'camp_check_if_phone_exists' );

function camp_check_if_phone_exists() {
	global $wpdb;

	$val = $_POST['val'];
	$post_id = $_POST['post_id'];
	$id = $_POST['id'];

	$camp_voter_phone = get_post_meta( $post_id, 'camp_voter_phone', true);

//	print_r($_POST);

	if(in_array($val, unserialize($camp_voter_phone))){
		$response = array('status' => 1, "id" => $id);
		wp_send_json( $response );
	}else{
		$response = array('status' => 0);
		wp_send_json( $response );
	}
	

	wp_die();
}