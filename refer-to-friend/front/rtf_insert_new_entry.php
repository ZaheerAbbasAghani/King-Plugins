<?php 
add_action( 'wp_ajax_nopriv_rtf_insert_new_entry', 'rtf_insert_new_entry' );
add_action( 'wp_ajax_rtf_insert_new_entry', 'rtf_insert_new_entry' );
function rtf_insert_new_entry() {
	

global $wpdb;
$table_name = $wpdb->base_prefix.'rtf_store_info';


$minfo = array();
parse_str($_POST['message_info'], $minfo);

extract($minfo);
$now = current_time('mysql');
$purl = $minfo['page_url'];
array_pop($minfo);
$insert = $wpdb->insert($table_name,$minfo);
if($insert == 1){

$lastid = $wpdb->insert_id;

//echo $lastid;

$query = "SELECT * FROM $table_name WHERE id='$lastid'";
$results = $wpdb->get_results($query, ARRAY_A);
	
	$message = "";

	array_shift($results[0]);
	foreach ($results[0] as $key => $value) {

		$k = str_replace('_field', '', $key);
		$txt = urldecode($results[0][$k."_field"]);		
		$message .= ucfirst($k)." :  $txt <br> ";
	}

	$message .= "Page URL: <a href='".$purl."'>".$purl."</a>";

	$headers = array('Content-Type: text/html; charset=UTF-8');
	wp_mail($minfo['to_field'], "An enquiry had made from ".get_bloginfo("name"), $message,$headers);

	$response = array("message" => "Created Successfully", "status" => 1);
	echo wp_send_json($response);
}else{

	$response = array("message" => "Something went wrong!", "status" => 0);
	echo wp_send_json($response);
}



	wp_die();
}