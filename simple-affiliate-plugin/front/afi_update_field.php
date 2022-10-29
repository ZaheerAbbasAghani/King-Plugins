<?php
add_action( 'wp_ajax_nopriv_afi_update_field', 'afi_update_field' );
add_action( 'wp_ajax_afi_update_field', 'afi_update_field' );
function afi_update_field() {

	$user_id = get_current_user_id();
	$key= $_POST['attr'];
	$value = $_POST['field'];
	$msg_id = $_POST['msg_id'];

	$blogusers = get_users();
	$user_list = array();
	foreach ( $blogusers as $user ) {
		if(!empty(get_user_meta($user->ID, 'wizz_user', true))){
    		array_push($user_list, get_user_meta($user->ID, 'wizz_user', true));
    	}
	}

	if(!in_array(strtolower($value), $user_list)){
		$usr = str_replace(' ', '', strtolower(sanitize_text_field($value)));
		if($key == "user"){
			
	 		update_user_meta($user_id,'wizz_user',strtolower($usr));
		//	echo "Thanks for your time.";	
			$response = array("message" => "Thanks for your time.", "id" => $msg_id);
			echo wp_send_json($response);
			
		}else{
			update_user_meta($user_id,$key,strtolower($usr));
			//echo "Thanks for your time.";	
			$response = array("message" => "Thanks for your time.", "id" => $msg_id);
			echo wp_send_json($response);
		}
	}else{
		//echo "Record already exists";
		$response = array("message" => "Record already exists", "id" => $msg_id);
		echo wp_send_json($response);
	}

	wp_die(); 
}