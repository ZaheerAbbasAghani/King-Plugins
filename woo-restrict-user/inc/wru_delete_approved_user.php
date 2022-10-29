<?php
function wru_delete_approved_user() {

if(isset($_POST['user_id'])){
	
	$user_id = $_POST['user_id'];
	$post_id = $_POST['post_id'];
	$approve_date = $_POST['approve_date'];
	$var_list = $_POST['var_list'];

	delete_post_meta( $post_id, 'wru_approve_user', $user_id);
	delete_post_meta( $post_id, 'wru_till_dt', $approve_date);
	delete_post_meta( $post_id, 'wru_approve_variation',$var_list);
	delete_post_meta( $post_id, 'wru_all_approved_data');

/*	delete_post_meta( $post_id, 'wru_approve_user');
	delete_post_meta( $post_id, 'wru_till_dt');
	delete_post_meta( $post_id, 'wru_approve_variation');
	delete_post_meta( $post_id, 'wru_all_approved_data');*/

	echo "Deleted";
}
   

die();
}

add_action( 'wp_ajax_wru_delete_approved_user', 'wru_delete_approved_user' );
add_action( 'wp_ajax_nopriv_wru_delete_approved_user', 'wru_delete_approved_user' );