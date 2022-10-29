<?php 
add_action( 'wp_ajax_gstl_add_members_in_list', 'gstl_add_members_in_list' );
add_action( 'wp_ajax_nopriv_gstl_add_members_in_list', 'gstl_add_members_in_list' );
function gstl_add_members_in_list() {
	
	$member_name = $_POST['member_name'];
	$post_id = $_POST['post_id'];
	
	$limit = get_post_meta($post_id,'djlimit_'.get_current_user_id(),true);

	$user_added = get_post_meta($post_id, 'dj_'.get_current_user_id(), false);

	if(count($user_added) < $limit){

		$response = get_post_meta($post_id, 'gstl_join_event', false);
	    if (!in_array($member_name, $response)) {   
	    	add_post_meta($post_id, 'gstl_join_event', $member_name);
	    	add_post_meta($post_id, 'dj_'.get_current_user_id(), $member_name);
	    	echo "User added in list";
	    } 
	}else{
		echo "Your limit to add members in list exceed.";
	}
    
	wp_die();
}