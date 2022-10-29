<?php 
add_action( 'wp_ajax_gg_buy_now_process', 'gg_buy_now_process' );
add_action( 'wp_ajax_nopriv_gg_buy_now_process', 'gg_buy_now_process' );

function gg_buy_now_process() {
	
	global $wpdb;
    $table_name = $wpdb->base_prefix.'gg_history';
    $user_id = get_current_user_id();
    $user_info = get_userdata($user_id);
    $product_id = $_POST['id'];
    $price = str_replace("€","", get_post_meta($product_id, '_product_price', true));

    $time = current_time( 'mysql' );


	$result = $wpdb->insert($table_name, array("user_id" => $user_id, "user_email" => $user_info->user_email, "user_purchases" => $price, "product_id" => $product_id,"status" => 0, "created_date" => $time),array("%d","%s","%s","%d","%s","%s"));


	if($result == 1){
		//echo "Thanks for purchasing.";

		$profile_url = um_user_profile_url( $user_id );
		$response = array("message" => "Thanks for purchasing.", "profile_url" => $profile_url.'?profiletab=gg_purchase_history');
		wp_send_json($response);

	}else{
		echo "Something went wrong";
	}

	//echo "<p> Zip Code ($zip) Added In Database </p> \n";




	wp_die();
}