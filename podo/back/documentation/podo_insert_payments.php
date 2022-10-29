<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action('wp_ajax_nopriv_podo_insert_payments','podo_insert_payments' );
add_action('wp_ajax_podo_insert_payments','podo_insert_payments' );
function podo_insert_payments() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_payment_methods';

$pinfo = array();
parse_str($_POST['payments'], $pinfo);

extract($pinfo);
$now = current_time('mysql');


//print_r($pinfo);


if($enableQR == "on"){
	$qr = 1;
	$insert = $wpdb->query("INSERT INTO $table_name (payment_method_name, payment_method_description,
	QRImage, enableQR, created_at) VALUES ('$payment_method_name', '$payment_method_description','$QRImage','$qr', '$now')");
}else{
	$qr = 0;
	$insert = $wpdb->query("INSERT INTO $table_name (payment_method_name, payment_method_description,
	QRImage, enableQR, created_at) VALUES ('$payment_method_name', '$payment_method_description','','', '$now')");
}

if($insert == 1){
	$response = array("message" => "Payment Method Added.", "status" => 1);
	echo wp_send_json($response);
}else{

	$response = array("message" => "Something went wrong!", "status" => 0);
	echo wp_send_json($response);
}
	


	wp_die();
}
