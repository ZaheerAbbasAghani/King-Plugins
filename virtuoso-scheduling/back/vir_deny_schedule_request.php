<?php
add_action( 'wp_ajax_nopriv_vir_deny_schedule_request', 'vir_deny_schedule_request' );
add_action( 'wp_ajax_vir_deny_schedule_request', 'vir_deny_schedule_request' );
function vir_deny_schedule_request() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'vir_scheules';

	$id  = $_POST['id'];
	$status = 1;
	$user_id = $_POST['user_id'];

	$user = get_user_by( "id", $user_id);
	

	$email = $user->user_email;	
	$subject = "Your schedule request denied at ". get_bloginfo('name');
	$message = "Your schedule request denined at <b> ".get_bloginfo('name')."</b> from admin name: <b>".get_user_by( "id", get_current_user_id())->display_name.'</b>';

	
	$wpdb->query($wpdb->prepare("UPDATE $table_name SET vir_status='$status' WHERE id=$id"));
	$headers = array('Content-Type: text/html; charset=UTF-8');
	wp_mail($email, $subject, $message,$headers);

	echo "Deny Request Sent.";



	wp_die(); // this is required to terminate immediately and return a proper response
}