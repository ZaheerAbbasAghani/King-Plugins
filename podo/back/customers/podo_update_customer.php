<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_update_customer_dashboard','podo_update_customer_dashboard' );
add_action( 'wp_ajax_podo_update_customer_dashboard', 'podo_update_customer_dashboard' );
function podo_update_customer_dashboard() {

$cinfo = array();
	parse_str($_POST['customer_info'], $cinfo);

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
	$vorerkrankungen 	= $cinfo['vorerkrankungen'];
	$important_notes= $cinfo['important_notes'];
	$cid = $cinfo['cid'];

	global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_customer_info';

    $info = $wpdb->query($wpdb->prepare("UPDATE $table_name SET first_name='$first_name',last_name='$last_name',email_address='$email_address',gender='$gender',birth_date='$birth_date',mobile_no='$mobile_no',job='$job',city='$city',zipcode='$zipcode',fad='$fad',address='$address',doctor_name='$doctor_name',diagnosis='$diagnosis',phone_of_doctor='$phone_of_doctor', drugs='$drugs',insurance_company='$insurance_company',vorerkrankungen='$vorerkrankungen',important_notes='$important_notes' WHERE id=$cid"));

    if($info == true){
    	$response = array("message" => "Der Kunde wurde erfolgreich aktualisiert!", "status" => 1);
		echo wp_send_json($response);
    }else{
    	$response = array("message" => "Entschuldigung, da ist etwas schief gelaufen!", "status" => 0);
		echo wp_send_json($response);
    }




wp_die();

}