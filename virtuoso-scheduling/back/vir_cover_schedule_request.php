<?php
add_action( 'wp_ajax_nopriv_vir_cover_schedule_request','vir_cover_schedule_request');
add_action( 'wp_ajax_vir_cover_schedule_request','vir_cover_schedule_request');
function vir_cover_schedule_request() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'vir_scheules';

	$id  = $_POST['id'];
	$status = 2;
	$user_id = $_POST['user_id'];
	$displayName = $_POST['displayName'];

	$user = get_user_by( "id", $user_id);


	$email = $user->user_email;	
	$subject = "Your schedule request accepted by ". get_user_by( "id", get_current_user_id())->display_name;
	$message = "Your schedule request accepted at <b> ".get_bloginfo('name')."</b> from employee name: <b>".get_user_by( "id", get_current_user_id())->display_name.'</b>';

	$wpdb->query($wpdb->prepare("UPDATE $table_name SET vir_status='$status' WHERE id=$id"));
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	wp_mail($email, $subject, $message,$headers);

	$email2 = esc_attr( get_option('vir_admin_email') );
	$subject2 = "A scheduled request accepted by ". get_user_by( "id", get_current_user_id())->display_name;
	$message2 = $displayName."'s schedule request accepted at <b> ".get_bloginfo('name')."</b> from employee name: <b>".get_user_by( "id", get_current_user_id())->display_name.'</b>';

	wp_mail($email2, $subject2, $message2,$headers);

	echo "Cover Request Sent.";



	wp_die(); // this is required to terminate immediately and return a proper response
}