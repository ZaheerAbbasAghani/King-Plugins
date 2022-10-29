<?php 

require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_vir_create_schedule', 'vir_create_schedule' );
add_action( 'wp_ajax_nopriv_vir_create_schedule', 'vir_create_schedule' );

function vir_create_schedule() {
	global $wpdb; // this is how you get access to the database


	$table_name = $wpdb->base_prefix.'vir_scheules';

   
	$params = array();
	parse_str($_POST['details'], $params);

	$name = $params['InputYourName'];
	$phone = $params['InputYourPhone'];

	$users = $wpdb->get_results("SELECT id FROM $wpdb->users WHERE display_name = '$name'");
    $user_id = $users[0]->id;

	$phone_number = get_user_meta($user_id, 'vir_phone_number', false);

    if(!in_array($phone, $phone_number)){
    	add_user_meta($user_id,'vir_phone_number', $phone);
    }

	$user_id = current_user_can('administrator') ? $user_id : get_current_user_id();
	$user = get_user_by( "id", $user_id);

	$message = $params['InputYourMessage'];	
	$DateTimeOff = $params['ScheduleDateTime'];	
	$status = $_POST['status'];	
	$current_time = current_time( 'mysql' );

	$email_address = get_option('vir_admin_email') == "" ? get_bloginfo('admin_email') : get_option('vir_admin_email');
	$email_subject = get_option('vir_email_subject');
	$email_heading = get_option('vir_email_heading');
	$email_body = get_option('vir_email_body');

	$request_url = admin_url('admin.php?page=vir_settings_page','https' );

	$tags = array("{name}","{email}","{message}","{schedule_request_date_time}","{schedule_request_created_at}","{requests_url}");

	$values = array($user->display_name, $user->user_email,$message, $DateTimeOff, $current_time, $request_url);

	$subject = str_replace($tags, $values, $email_subject);
	$email_message = str_replace($tags, $values, $email_body);

 	$query = "SELECT * FROM $table_name WHERE user_id='$user_id' AND vir_date='$DateTimeOff' ";
	$query_results = $wpdb->get_results($query);
	if(count($query_results) == 0) {
		$rowResult=$wpdb->insert($table_name, array("user_id" => $user_id, "vir_message" => $message, "vir_date" => $DateTimeOff,"phone" => $phone, "vir_status" => $status, "created_at" => $current_time ),array("%d","%s","%s","%s","%d","%s"));

		$headers = array('Content-Type: text/html; charset=UTF-8');

		// Sending email to admin and other employees except current user
		$employees = get_users( array( 'role__in' => array( 'vir_employee' ) ) );
		$employee = array();
		foreach ($employees as $key => $value) {
			array_push($employee, $value->user_email);	
		}

		array_push($employee, $email_address);
		foreach ($employee as $key => $value) {
			wp_mail($value, $subject, $email_message, $headers);
		}

		
		$employee_phone = array();
		foreach ($employees as $key => $value) {
			if(!empty(get_user_meta($value->ID, 'vir_phone_number',true))){
				array_push($employee_phone,get_user_meta($value->ID,'vir_phone_number',true));
			}
		}

		$account_sid = get_option('vir_twilio_account_sid');
		$auth_token  = get_option('vir_twilio_auth_token');
		$twilio_number = get_option('vir_twilio_phone_number');
			
		foreach ($employee_phone as $ph) {

			if(!empty($ph)){
				$client = new Client($account_sid, $auth_token);
				$client->messages->create(
				    // Where to send a text message (your cell phone?)
				    $ph,
				    array(
				        'from' => $twilio_number,
				        'body' => strip_tags($email_message)
				    )
				);
			}
			//echo "Your Schedule Request Sent. ".$phone;
		}

		echo "Your Schedule Request Sent.";	
	}else{
		echo "Another Employee Using Same Schedule";	
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}