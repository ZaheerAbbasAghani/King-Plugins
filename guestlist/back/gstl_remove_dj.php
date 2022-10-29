<?php 
add_action( 'wp_ajax_gstl_remove_dj','gstl_remove_dj' );
add_action( 'wp_ajax_nopriv_gstl_remove_dj', 'gstl_remove_dj' );
function gstl_remove_dj() {
	

	$member_id 	= $_POST['user_id'];
	$post_id 	= $_POST['post_id'];
	$limit 		= $_POST['limit'];
	
	$response = get_post_meta($post_id,'gstl_dj_event', false);

    if (in_array($member_id, $response)) {   
    	delete_post_meta($post_id, 'gstl_dj_event', $member_id);
    	delete_post_meta($post_id, 'dj_'.$member_id);
    	delete_post_meta($post_id,'djlimit_'.$member_id,$limit);

    	echo "User deleted from list";
    } 
	wp_die();
}