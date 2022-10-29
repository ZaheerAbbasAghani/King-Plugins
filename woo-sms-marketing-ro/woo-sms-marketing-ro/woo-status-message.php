<?php
/*
Plugin Name: Woo Status Message
Plugin URI: https://sms-marketing.ro
Description: This plugin is used send messages to customers regarding order status with API from sms-marketing.ro
Version: 1.0
Author: Eduard Popa
Author URI: https://sms-marketing.ro
License: GPLv3 or later
Text Domain: woo-status-message
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WooStatusMessage {

function __construct() {
	add_action('init', array($this, 'wsm_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'wsm_enqueue_script_front'));
	add_action( 'woocommerce_order_status_changed', array($this,"my_awesome_publication_notification"),20, 1 );

	//add_action('admin_enqueue_scripts', array($this, 'wsm_enqueue_admin'));
	
}



function wsm_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'wsm_dashboard/wsm_admin_settings.php';
	require_once plugin_dir_path(__FILE__) . 'wsm_dashboard/wsm_shortcode.php';

}

// Enqueue Style and Scripts

function wsm_enqueue_script_front() {
//Style & Script
wp_enqueue_style('wsm-style', plugins_url('assets/css/wsm.css', __FILE__),'1.0.0','all');
wp_enqueue_script('wsm-script', plugins_url('assets/js/wsm.js', __FILE__),array('jquery'),'1.0.0', true);
}


function my_awesome_publication_notification($order_id, $checkout=null) {
   global $woocommerce;
   $order = wc_get_order( $order_id );
   $key = esc_attr(get_option('wsm_api_key'));
   $sim = esc_attr(get_option('wsm_slot_sim'));
   $device = esc_attr(get_option('wsm_device_id'));

//$key = '21c895b11597e854302dcc99ac1b1591ffef3b06';


$full_name = $order->get_billing_first_name().' '.$order->get_billing_last_name();
$address = $order->get_billing_address_1().' '.$order->get_billing_address_2();
$total = wc_format_decimal($order->get_total(), 2);
$ordernumber = $order->get_order_number();
// Make sure enable/disable
$enable_disable_pending_payment = get_option("enable_disable_pending_payment");
$enable_disable_processing = get_option("enable_disable_processing");
$enable_disable_on_hold = get_option("enable_disable_on_hold");
$enable_disable_completed = get_option("enable_disable_completed");
$enable_disable_cancel = get_option("enable_disable_cancel");
$enable_disable_refunded = get_option("enable_disable_refunded");
$enable_disable_failed = get_option("enable_disable_failed");

//Status Messages

$wsm_status_pending_payment = get_option("wsm_status_pending_payment");
$wsm_status_processing = get_option("wsm_status_processing");
$wsm_status_on_hold = get_option("wsm_status_on_hold");
$wsm_status_completed = get_option("wsm_status_completed");
$wsm_status_cancel = get_option("wsm_status_cancel");
$wsm_status_refunded = get_option("wsm_status_refunded");
$wsm_status_failed = get_option("wsm_status_failed");




if($enable_disable_pending_payment['wsm_status_pp']==1 && $order->get_status()=="pending"){
	$pending_recods = array();
	$order_pending = explode(" ", $wsm_status_pending_payment);
    foreach ($order_pending as $pending) {
        if($pending == "{name}"){
            $pending = $full_name." ";
        }elseif($pending == "{address}"){
            $pending = $address." ";
        }elseif($pending == "{amount}"){
            $pending = $total." ";
        }elseif($pending == "{ordernumber}"){
            $pending = $ordernumber;
        }else{
            $pending = $pending;
        }
        array_push($pending_recods, $pending);
    }

    $final_order = implode($pending_recods);
	$body = array(
	    'message' => $final_order."\nStatus: ".$order->get_status(),
	    'phone' => $order->get_billing_phone(),
	    'device' => $device,
	    'sim'	=> $sim
	);

	$response = wp_remote_post( 'https://sms-marketing.ro/api/send?key='."$key".'', array(
	'body' => $body
	) );
	$body = wp_remote_retrieve_body( $response );
	$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $body, true ) : null;
	
}

if($enable_disable_processing["wsm_processing"]==1 && $order->get_status()=="processing"){
	$pending_recods = array();
	$order_pending = explode(" ", $wsm_status_processing);
    foreach ($order_pending as $pending) {
        if($pending == "{name}"){
            $pending = $full_name." \n";
        }elseif($pending == "{address}"){
            $pending = $address."\n";
        }elseif($pending == "{amount}"){
            $pending = $total."\n";
        }elseif($pending == "{ordernumber}"){
            $pending = $ordernumber;
        }else{
            $pending = $pending;
        }
        array_push($pending_recods, $pending);
    }

    $final_order = implode($pending_recods);

	$body = array(
	    'message' => $final_order."\nStatus: ".$order->get_status(),
	    'phone' => $order->get_billing_phone(),
	    'device' => $device,
	    'sim'	=> $sim
	);
	$response = wp_remote_post( 'https://sms-marketing.ro/api/send?key='."$key".'', array(
	'body' => $body
	) );
	$body = wp_remote_retrieve_body( $response );
	$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $body, true ) : null;
	
}

if($enable_disable_on_hold["wsm_on_hold"] == 1 && $order->get_status()=="on-hold"){

	$pending_recods = array();
	$order_pending = explode(" ", $wsm_status_on_hold);
    foreach ($order_pending as $pending) {
        if($pending == "{name}"){
            $pending = $full_name." \n";
        }elseif($pending == "{address}"){
            $pending = $address."\n";
        }elseif($pending == "{amount}"){
            $pending = $total."\n";
        }elseif($pending == "{ordernumber}"){
            $pending = $ordernumber;
        }else{
            $pending = $pending;
        }
        array_push($pending_recods, $pending);
    }

    $final_order = implode($pending_recods);

	$body = array(
	   	'message' => $final_order."\nStatus: ".$order->get_status(),
	    'phone' => $order->get_billing_phone(),
	    'device' => $device,
	    'sim'	=> $sim
	);
	$response = wp_remote_post( 'https://sms-marketing.ro/api/send?key='."$key".'', array(
	'body' => $body
	) );
	$body = wp_remote_retrieve_body( $response );
	$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $body, true ) : null;
	
}

if($enable_disable_completed['wsm_completed']==1 && $order->get_status()=="completed"){
	$pending_recods = array();
	$order_pending = explode(" ", $wsm_status_completed);
    foreach ($order_pending as $pending) {
        if($pending == "{name}"){
            $pending = $full_name." \n";
        }elseif($pending == "{address}"){
            $pending = $address."\n";
        }elseif($pending == "{amount}"){
            $pending = $total."\n";
        }elseif($pending == "{ordernumber}"){
            $pending = $ordernumber;
        }else{
            $pending = $pending;
        }
        array_push($pending_recods, $pending);
    }

    $final_order = implode($pending_recods);

	$body = array(
	    'message' => $final_order."\nStatus: ".$order->get_status(),
	    'phone' => $order->get_billing_phone(),
	    'device' => $device,
	    'sim'	=> $sim
	);
	$response = wp_remote_post( 'https://sms-marketing.ro/api/send?key='."$key".'', array(
	'body' => $body
	) );
	$body = wp_remote_retrieve_body( $response );
	$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $body, true ) : null;


}

if($enable_disable_cancel["wsm_cancel"]=="1" && $order->get_status()=="cancelled"){
	
	$pending_recods = array();
	$order_pending = explode(" ", $wsm_status_cancel);
    foreach ($order_pending as $pending) {
        if($pending == "{name}"){
            $pending = $full_name." \n";
        }elseif($pending == "{address}"){
            $pending = $address."\n";
        }elseif($pending == "{amount}"){
            $pending = $total."\n";
        }elseif($pending == "{ordernumber}"){
            $pending = $ordernumber;
        }else{
            $pending = $pending;
        }
        array_push($pending_recods, $pending);
    }

    $final_order = implode($pending_recods);

	$body = array(
	    'message' => $final_order."\nStatus: ".$order->get_status(),
	    'phone' => $order->get_billing_phone(),
	    'device' => $device,
	    'sim'	=> $sim
	);
	$response = wp_remote_post( 'https://sms-marketing.ro/api/send?key='."$key".'', array(
	'body' => $body
	) );
	$body = wp_remote_retrieve_body( $response );
	$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $body, true ) : null;

}

if($enable_disable_refunded["wsm_refunded"]== 1 && $order->get_status()=="refunded"){
	
	$pending_recods = array();
	$order_pending = explode(" ", $wsm_status_refunded);
    foreach ($order_pending as $pending) {
        if($pending == "{name}"){
            $pending = $full_name." \n";
        }elseif($pending == "{address}"){
            $pending = $address."\n";
        }elseif($pending == "{amount}"){
            $pending = $total."\n";
        }elseif($pending == "{ordernumber}"){
            $pending = $ordernumber;
        }else{
            $pending = $pending;
        }
        array_push($pending_recods, $pending);
    }

    $final_order = implode($pending_recods);


	$body = array(
	    'message' => $final_order."\nStatus: ".$order->get_status(),
	    'phone' => $order->get_billing_phone(),
	    'device' => $device,
	    'sim'	=> $sim
	);
	$response = wp_remote_post( 'https://sms-marketing.ro/api/send?key='."$key".'', array(
	'body' => $body
	) );
	$body = wp_remote_retrieve_body( $response );
	$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $body, true ) : null;

}

if($enable_disable_failed["wsm_failed"]== 1 && $order->get_status()=="failed"){
	
	$pending_recods = array();
	$order_pending = explode(" ", $wsm_status_failed);
    foreach ($order_pending as $pending) {
        if($pending == "{name}"){
            $pending = $full_name." \n";
        }elseif($pending == "{address}"){
            $pending = $address."\n";
        }elseif($pending == "{amount}"){
            $pending = $total."\n";
        }elseif($pending == "{ordernumber}"){
            $pending = $ordernumber;
        }else{
            $pending = $pending;
        }
        array_push($pending_recods, $pending);
    }

    $final_order = implode($pending_recods);
	$body = array(
	    'message' => $final_order."\nStatus: ".$order->get_status(),
	    'phone' => $order->get_billing_phone(),
	    'device' => $device,
	    'sim'	=> $sim
	);
	$response = wp_remote_post( 'https://sms-marketing.ro/api/send?key='."$key".'', array(
	'body' => $body
	) );
	$body = wp_remote_retrieve_body( $response );
	$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $body, true ) : null;
	
}





}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WooStatusMessage')) {
	$obj = new WooStatusMessage();
}