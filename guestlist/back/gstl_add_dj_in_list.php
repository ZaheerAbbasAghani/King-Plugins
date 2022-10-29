<?php 
add_action( 'wp_ajax_gstl_add_dj_in_list', 'gstl_add_dj_in_list' );
add_action( 'wp_ajax_nopriv_gstl_add_dj_in_list', 'gstl_add_dj_in_list' );
function gstl_add_dj_in_list() {
	

	$user_id 	= $_POST['user_id'];
	$post_id 	= $_POST['post_id'];
	$limit 		= $_POST['limit'];
	
	$response = get_post_meta($post_id, 'gstl_dj_event', false);
    if (!in_array($user_id, $response)) {   
    	add_post_meta($post_id, 'gstl_dj_event', $user_id);
    	add_post_meta($post_id,'djlimit_'.$user_id,$limit);
    	echo "Event assigned to DJ";
    } 
    
	wp_die();
}