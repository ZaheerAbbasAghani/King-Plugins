<?php 

if( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_adm_submit_form', 'adm_submit_form' );
add_action( 'wp_ajax_nopriv_adm_submit_form', 'adm_submit_form' );

function adm_submit_form() {

	global $wpdb;

	parse_str($_POST['formData'], $data);

	$final = array();
	foreach ($data as $key => $value) {
		if(is_array($value)){
			if(!empty($value)){
				array_push($final, array($key => serialize($value)));
			}else{
				array_push($final, array($key => serialize("-")));
			}
		}else{
			if(!empty($value)){
				array_push($final, array($key => $value));
			}else{
				array_push($final, array($key => "-"));
			}
		}
	}

	$converted_array = array_reduce($final, 'array_merge', array());

	//print_r($converted_array);

	$insert_array = array_reduce($final, 'array_merge', array());
//	$apikey = get_option('adm_site_key');
	$secret = get_option('adm_secret_key');
	$ip = $_SERVER['REMOTE_ADDR'];

	$postvars = array("secret"=>$secret,"response"=>$insert_array["g-recaptcha-response"], "remoteip"=>$ip);
	$url = "https://www.google.com/recaptcha/api/siteverify";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
	$data = curl_exec($ch);
	curl_close($ch);

	$status = json_decode($data, true);

	if($status['success'] == 1){

		unset($insert_array["g-recaptcha-response"]);	

		$radomCode = substr(md5(uniqid(mt_rand(), true)) , 0, 8);

		//$insert_array['radom_code'] = $radomCode;

		$table_name1 = $wpdb->base_prefix.'adm_submitted_data_table';
		$formData = array("radom_code" => $radomCode, "formData" => serialize($insert_array));
		$insert = $wpdb->insert($table_name1, $formData);

		$converted_array['link'] = get_site_url()."?adid=".$radomCode;

		$finalKeys = array();
		foreach (array_keys($converted_array) as $formattedKeys) {
			array_push($finalKeys, "{".$formattedKeys."}");
		}

		$finalValues = array();
		foreach (array_values($converted_array) as $formattedValues) {
			if(is_array(maybe_unserialize($formattedValues))){
				array_push($finalValues, implode(",",maybe_unserialize($formattedValues)));
			}else{
				array_push($finalValues,$formattedValues);
			}
		}

		$message  = get_option( 'email_body' );
		$keys = array_values($finalKeys);
		$values  = array_values($finalValues);
		$newMessage = str_replace($keys, $values, $message);

		if($insert == 1){

			$to = $converted_array['email'];
			$subject = get_option("email_subject");
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			$sent = wp_mail( $to, $subject, $newMessage, $headers);

			//echo "Data Saved Successfully.";
			$response = array("message" => "Data Saved Successfully.", "status" => 1);
			wp_send_json( $response );
		}	
	}else{
			$response = array("message" => "Please choose captcha", "status" => 2);
			wp_send_json( $response );
	}


	wp_die();

}