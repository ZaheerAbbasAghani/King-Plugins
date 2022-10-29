<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_create_new_customer', 'anam_create_new_customer' );
add_action( 'wp_ajax_anam_create_new_customer', 'anam_create_new_customer' );
function anam_create_new_customer() {
	
	$cinfo = array();
	parse_str($_POST['customer_info'], $cinfo);
	$doctor_id = get_current_user_id();
	$first_name 	= $cinfo['first_name'];
	$last_name 		= $cinfo['last_name'];
	$email_address 	= $cinfo['email_address'];
	$gender 		= $cinfo['gender'];
	$birth_date 	= $cinfo['birth_date'];
	$mobile_no 		= $cinfo['mobile_no'];
	$job 			= $cinfo['job'];
	$city 			= $cinfo['city'];
	$zipcode 		= $cinfo['zipcode'];
	$fad 			= $cinfo['fad'];
	$address 		= $cinfo['address'];
	$doctor_name 	= $cinfo['doctor_name'];
	$diagnosis 		= $cinfo['diagnosis'];
	$phone_of_doctor= $cinfo['phone_of_doctor'];
	$drugs 			= $cinfo['drugs'];
	$insurance_company = $cinfo['insurance_company'];
	$vorerkrankungen =  $cinfo['vorerkrankungen'];
	$important_notes= $cinfo['important_notes'];
	$agree = $cinfo['agree_on_terms'];

//print_r($cinfo);


	global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_customer_info';

	$query = "SELECT * FROM $table_name WHERE first_name='$first_name' AND last_name='$last_name' AND email_address='$email_address' AND doctor_id='$doctor_name' ";
	$query_results = $wpdb->get_results($query);
	if(count($query_results) == 0) {

	$wpdb->query("INSERT INTO $table_name (first_name, last_name, email_address,gender,birth_date,mobile_no,job,city,zipcode,fad,address,doctor_name,diagnosis,phone_of_doctor,drugs,insurance_company,vorerkrankungen, important_notes,agree_on_terms,doctor_id) VALUES ('$first_name', '$last_name', '$email_address','$gender', '$birth_date', '$mobile_no', '$job', '$city', '$zipcode', '$fad', '$address', '$doctor_name', '$diagnosis', '$phone_of_doctor', '$drugs', '$insurance_company','$vorerkrankungen', '$important_notes','$agree','$doctor_id')");
	$lastid = $wpdb->insert_id;
	$wp_upload_dir =  wp_upload_dir();
	$custom_upload_folder= $wp_upload_dir['basedir'].'/anam_users/'.$lastid;
	if(!is_dir($custom_upload_folder)){
		mkdir($custom_upload_folder);
	}
	$response = array("message" => "Neuer Patient wurde erfolgreich erstellt", "status" => 1);
	echo wp_send_json($response);
	}else{
		//echo "Customer already exists!";	
		$response = array("message" => "Kunde existiert bereits!", "status" => 0);
		echo wp_send_json($response);
	}


	wp_die();
}