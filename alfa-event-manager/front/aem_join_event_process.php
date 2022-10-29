<?php
add_action( 'wp_ajax_aem_join_event_process', 'aem_join_event_process' );
add_action( 'wp_ajax_nopriv_aem_join_event_process', 'aem_join_event_process' );
function aem_join_event_process() {
	
	$post_id = $_POST['post_id'];
	$user = wp_get_current_user();
	$user_id = $user->ID;
	$aem_select_roles = get_post_meta( $post_id, 'aem_select_roles', false );
	$roles = ( array ) $user->roles;
 	$current_role =  $roles[0];
 	$aem_max_users = get_post_meta( $post->ID, 'aem_max_users', true );
 	$limit_exceeded = get_post_meta( $post->ID, 'limit_exceeded', true );

 	if(empty($limit_exceeded)){
		$limit_exceeded = 0;
	}

 	if($aem_max_users !== $limit_exceeded){
		if(in_array($current_role, $aem_select_roles[0]) || current_user_can('um_chc') || current_user_can('administrator')){
			add_post_meta( $post_id, "aem_user_joined", $user_id);

			$total = $limit_exceeded + 1;
			update_post_meta( $post_id, "limit_exceeded", $total);
			echo "Join Event Successfully";
		}else{
			echo "You are not allowed to join this event!";
		}
	}else{
		echo "Max limit to join events by user exceeded";
	}


	wp_die();
}