<?php 
add_action( 'wp_ajax_nopriv_lms_update_leave_status', 'lms_update_leave_status' );
add_action( 'wp_ajax_lms_update_leave_status', 'lms_update_leave_status' );
function lms_update_leave_status() {
	

	global $wpdb;
	$table_name = $wpdb->base_prefix.'lms_records';

	$row_id = $_POST['id'];
	$status = $_POST['status'];
	$user_name = $_POST['user_name'];
	$user_email = $_POST['user_email'];

	$leave = $wpdb->query($wpdb->prepare("UPDATE $table_name SET lms_status='$status' WHERE id=$row_id"));

	if($status == 1){

		$subject = "You have received response from ".get_bloginfo('name');
		$message = "Hi, <br><p> Mr. <b>".$user_name."</b>, your leave application <b>approved. </b></p> <br> Thanks";
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($user_email, $subject, $message, $headers);

		echo "Update Successfully.";	
	}else{
		$subject = "You have received response from ".get_bloginfo('name');
		$message = "Hi, <br><p> Mr. <b>".$user_name."</b>, your leave application <b> denied.</b> </p><br> Thanks";
		wp_mail($user_email, $subject, $message, $headers);
		echo "Update Successfully.";	
	}
	

	wp_die();
}