<?php 
add_action( 'wp_ajax_gstl_remove_members_in_list', 'gstl_remove_members_in_list' );
add_action( 'wp_ajax_nopriv_gstl_remove_members_in_list', 'gstl_remove_members_in_list' );
function gstl_remove_members_in_list() {
	

	$member_id = $_POST['member_id'];
	$post_id = $_POST['post_id'];
	
	$response = get_post_meta($post_id, 'gstl_join_event', false);

    if (in_array($member_id, $response)) {   
    	delete_post_meta($post_id, 'gstl_join_event', $member_id);
    	delete_post_meta($post_id, 'dj_'.get_current_user_id(), $member_id);
    	echo "User deleted from list";
    } 

   
	wp_die();
}