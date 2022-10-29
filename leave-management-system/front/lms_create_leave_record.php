<?php 

add_action( 'wp_ajax_lms_create_leave_record', 'lms_create_leave_record' );
add_action( 'wp_ajax_nopriv_lms_create_leave_record', 'lms_create_leave_record' );
function lms_create_leave_record() {
	global $wpdb; // this is how you get access to the database

	$table_name = $wpdb->base_prefix.'lms_records';

	$params = array();
	parse_str($_POST['details'], $params);


	$user_id = get_current_user_id();
	$user = get_user_by( "id", $user_id);

	$message = $params['InputYourMessage'];	
	$DateTimeOff = $params['DateTimeOff'];	
	$status = $_POST['status'];	
	$current_time = current_time( 'mysql' );

	$email_address = get_option('email_address');
	$email_subject = get_option('email_subject');
	$email_heading = get_option('email_heading');
	$email_body = get_option('email_body');

	$request_url = "<a href='".admin_url('admin.php?page=lms_time_off_settings','https' )."'> Click Here </a>";

	$tags = array("{name}","{email}","{message}","{leave_request_date_time}","{leave_request_created_at}","{requests_url}");

	$values = array($user->display_name, $user->user_email,$message, $DateTimeOff, $current_time, $request_url);

	$subject = str_replace($tags, $values, $email_subject);
	$email_message = str_replace($tags, $values, $email_body);

 
 	$query = "SELECT * FROM $table_name WHERE lms_name='$name' AND lms_date='$DateTimeOff' ";
	$query_results = $wpdb->get_results($query);
	if(count($query_results) == 0) {
		$rowResult=$wpdb->insert($table_name, array("user_id" => $user_id, "lms_message" => $message, "lms_date" => $DateTimeOff, "lms_status" => $status, "created_at" => $current_time ),array("%d","%s","%s","%s","%s"));

		$email_address = get_option('email_address');
		$email_subject = get_option('email_subject');
		$email_heading = get_option('email_heading');
		$email_body = get_option('email_body');

		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($email_address, $subject, $email_message, $headers);

		echo "Your Leave Application Sent.";	
	}else{
		echo "Leave application already exists.";	
	}


  //  print_r($params);

	wp_die(); // this is required to terminate immediately and return a proper response
}