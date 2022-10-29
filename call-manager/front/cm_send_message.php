<?php 

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

add_action( 'wp_ajax_cm_send_messages', 'cm_send_messages' );
add_action( 'wp_ajax_nopriv_cm_send_messages', 'cm_send_messages' );
function cm_send_messages() {


$receiver_number = $_POST['receiver_number'];
$message_txt = $_POST['message'];

if(!empty($receiver_number)){


$sid = get_option('auth_id');
$token = get_option('auth_token');

$twilio_number = "+13867424629";
$client = new Client($sid, $token);
$message = $client->messages->create(
    // Where to send a text message (your cell phone?)
    '+'.$receiver_number,
    array(
        'from' => $twilio_number,
        'body' => $message_txt
    )
);

global $wpdb; 
$table_name = $wpdb->base_prefix.'cm_messages_per_user';

$time = current_time( 'mysql' );
$rowResult=$wpdb->insert($table_name, array("sender_number" => $twilio_number,"receiver_number" => "+"+$receiver_number, "message" => $message_txt, "created_date" => $time),array("%s","%s","%s"));

echo "Message Sent.";
}else{
	echo "Phone Number Missing";
}

	
	//print_r($_POST);

	wp_die();
}