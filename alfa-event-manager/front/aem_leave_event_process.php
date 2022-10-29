<?php
add_action( 'wp_ajax_aem_leave_event_process', 'aem_leave_event_process' );
add_action( 'wp_ajax_nopriv_aem_leave_event_process', 'aem_leave_event_process' );
function aem_leave_event_process() {

	$post_id = $_POST['post_id'];
	$user = wp_get_current_user();
	$user_id = $user->ID;
	delete_post_meta( $post_id, "aem_user_joined", $user_id);
	delete_post_meta( $post_id, "aem_user_joined", $user_id);
	echo "You successfully leave the event.";	
	wp_die();
}