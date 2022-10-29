<?php 
add_action( 'wp_ajax_fde_search_for_shipping_price', 'fde_search_for_shipping_price' );
add_action( 'wp_ajax_nopriv_fde_search_for_shipping_price', 'fde_search_for_shipping_price' );

function fde_search_for_shipping_price() {
	global $wpdb;

	$fde_api_url 	= esc_attr( get_option('fde_api_url') );
	$fde_api_key 	= esc_attr( get_option('fde_api_key') );
	$fde_sender_id 	= esc_attr( get_option('fde_sender_id') );
	$address = $_POST['address'];
	$country = $_POST['country'];
	$zipcode = $_POST['zipcode'];
	$country_code = $_POST['country_code'];
	$pickup  = date("d.m.Y h:m");

	$url = $fde_api_url.'/deliveryinfo?destinationAddress="'.$address.'"&destinationCountry='.$country.'&pickupDateTime='.$pickup.'&destinationZipcode='.$zipcode.'&senderId='.$fde_sender_id;
	$args = array(
	  'headers' => array(
	    'x-api-key' => $fde_api_key 
	  )
	);
	$response = wp_remote_request( $url, $args );
	$result = json_decode($response['body']);


	$cost = $result->cost;
	$dtime = $result->estimatedDeliveryTime;

	setcookie("fdeDelivery", $cost, time() + (86400 * 30), "/"); 
	setcookie("fdeDeliveryPickUpTime", $pickup, time() + (86400 * 30), "/"); 
	setcookie("country_code", $country_code, time() + (86400 * 30), "/"); 


	$res = array('cost' => $cost, 'time' => $dtime, 'currency' => get_option('woocommerce_currency'));
	wp_send_json( $res );


	wp_die();
}