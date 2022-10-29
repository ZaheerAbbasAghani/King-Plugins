<?php
/*
Plugin Name: Woocommerce G2A Import
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Import products, Get the key from g2a after succesfull purchase to woocommerce
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: woo-g2-import
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WoocommerceG2AImport {

function __construct() {
	add_action('init', array($this, 'wgi_start_from_here'));
	add_filter('cron_schedules',array($this,'wgi_custom_cron_schedule'));
	//add_action("init",array($this,'wgi_schedule_event'));
	add_action('my_x_minutes_event',array($this,'wgi_cron_function' ));
	add_action('woocommerce_thankyou', array($this,'wgi_send_key_by_email'), 10, 1);

	if ( ! wp_next_scheduled( 'my_x_minutes_event' ) ) {
	    wp_schedule_event(time(), 'every_x_minutes', 'my_x_minutes_event' );
	}


}



function wgi_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/wgi_settings.php';
}


function wgi_custom_cron_schedule( $schedules ) {
	$options1 = get_option( 'auto_import_frequency' ); 
	$hours = floor($options1['page_id1'] / 60);
	$minutes = $options1['page_id1'] % 60;

    $schedules['every_x_minutes'] = array(
        'interval' => 	$minutes * 60, 
        'display'  => __( 'Every X Minutes' ),
    );
    return $schedules;
}


//create your function, that runs on cron
function wgi_cron_function() {


$options = get_option( 'mode_of_operation' );
if($options['page_id'] == "Test Mode"){
	$envDomain = 'https://sandboxapi.g2a.com/v1/products';
    $g2aEmail = "sandboxapitest@g2a.com";
    $clientId = 'qdaiciDiyMaTjxMt'; // API Client ID
    $clientSecret = 'b0d293f6-e1d2-4629-8264-fd63b5af3207b0d293f6-e1d2-4629-8264-fd63b5af3207'; // customer Client secret
}else{
	$envDomain = 'https://api.g2a.com/v1/products';
    $g2aEmail = esc_attr( get_option('seller_email') ); // customer email
    $clientId = esc_attr( get_option('g2a_hash_key') ); // API Client ID
    $clientSecret = esc_attr( get_option('g2a_secret_key') ); // customer Client secret
}

$apiKey = hash('sha256', $clientId . $g2aEmail . $clientSecret);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $envDomain,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: $clientId, $apiKey",
        "Content-Type: application/json",
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$info = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

$json = json_decode($response, true);

$i=0;
	foreach ($json['docs'] as $result) {

	$content = "Processor: ".$result['requirements']['minimal']['reqprocessor']."\n"."Memory: ".$result['requirements']['minimal']['reqmemory']." \n "."Disk Space:".$result['requirements']['minimal']['reqdiskspace']." \n ".$result['requirements']['minimal']['reqsystem']." \n "." Other ".$result['requirements']['minimal']['reqother'];
	$percentage = esc_attr( get_option('price_markup_value') );
	$price = $result['retail_min_price'] + ($percentage / 100 ) * $result['retail_min_price'];
	$options2 = get_option( 'product_state' ); 

	if($options2['page_id2'] =="Published"){
		$status = "publish";
	}else{
		$status = $options2['page_id2'];
	}

	$post = array(
	    'post_author' => get_current_user_id(),
	    'post_content' => 	$content,
	    'post_status' =>	$status,
	    'post_title' => 	$result['name'],
	    'post_parent' => '',
	    'post_type' => "product",
	    //'post_category' => array($comma_sep)
	);

	$product = post_exists($result['name']);
		if($product == true){
		    echo "";
		}else{
		//Create post
			$post_id = wp_insert_post( $post);
			if($post_id){

			    // Create and set Categories
			    foreach ($result['categories'] as $category) {

			        $taxonomy = 'product_cat';
			        if (term_exists($category['name'], $taxonomy)) {
			            $terms = array($category['name']);
			            wp_set_object_terms($post_id, $terms, $taxonomy, true);
			        } else {
			            $term = $category['name'];
			            $inserted_term = wp_insert_term( $term, $taxonomy);
			            if(!is_wp_error($inserted_term)) {
			                wp_set_object_terms( $post_id, $term, $taxonomy, true);
			            }
			        }

			    }


			    // Product Thumbnail
			    require_once(ABSPATH . 'wp-admin/includes/media.php');
			    require_once(ABSPATH . 'wp-admin/includes/file.php');
			    require_once(ABSPATH . 'wp-admin/includes/image.php');

			    $image_url = $result['smallImage'];
			    $image_tmp = download_url($image_url);
			     
			    $image_size = filesize($image_tmp);
			    $image_name = basename($image_url) . ".jpg"; // .jpg optional
			    //Download complete now upload in your project
			    $file = array(
			       'name' => $image_name, // ex: wp-header-logo.png
			       'type' => 'image/jpg',
			       'tmp_name' => $image_tmp,
			       'error' => 0,
			       'size' => $image_size
			    );

			    //This image/file will show on media page...
			    $thumb_id = media_handle_sideload( $file, $post_id);
			    set_post_thumbnail($post_id, $thumb_id); //optional
			    //update_post_meta($post_id, '_product_image_gallery', $thumb_id);

			    update_post_meta( $post_id, '_stock', $result['qty'] );
			    update_post_meta( $post_id, '_price', $price );
			    update_post_meta( $post_id, '_regular_price', $price );
			    add_post_meta($post_id,'_product_api_id',$result['id']);
			}

		} //else end

	} //endforeach


} //endElse



}

// Send key to user by email

function wgi_send_key_by_email( $order_id ) {
    if ( ! $order_id )
        return;
if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {
//https://sandboxapi.g2a.com/v1/products
	
$options = get_option( 'mode_of_operation' );
if($options['page_id'] == "Test Mode"){
	$envDomain = 'https://sandboxapi.g2a.com//v1/order';
    $g2aEmail = "sandboxapitest@g2a.com";
    $clientId = 'qdaiciDiyMaTjxMt'; // API Client ID
    $clientSecret = 'b0d293f6-e1d2-4629-8264-fd63b5af3207b0d293f6-e1d2-4629-8264-fd63b5af3207'; // customer Client secret
}else{
	$envDomain = 'https://api.g2a.com//v1/order';
    $g2aEmail = esc_attr( get_option('seller_email') ); // customer email
    $clientId = esc_attr( get_option('g2a_hash_key') ); // API Client ID
    $clientSecret = esc_attr( get_option('g2a_secret_key') ); // customer Client secret
}
	$apiKey = hash('sha256',$clientId . $g2aEmail . $clientSecret);

	//echo $apiKey;
	// Get an instance of the WC_Order object
	$order = wc_get_order( $order_id );

   // Loop through order items
    foreach ( $order->get_items() as $item_id => $item ) {
    	$product = $item->get_product();
        $product_id = $product->get_id();
        $product_api_id = get_post_meta($product_id ,'_product_api_id',true);
        $sendpro = json_encode(array("product_id"=>$product_api_id));

		//Creating Order Here.
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $envDomain,
		CURLOPT_RETURNTRANSFER => true,
	 	CURLOPT_ENCODING => "",
		CURLOPT_TIMEOUT => 30,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_HTTPHEADER => array(
			"Authorization: $clientId, $apiKey",
			"Content-Type: application/json",
		),
		CURLOPT_POSTFIELDS => $sendpro
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			//print_r($response);
		//Creating Key Here.
		$json1 = json_decode($response, true);
		
		//print_r($json1);

		//Creating payment
		$order_id = json_encode(array("id"=>$json1['order_id']));
		$ord = $json1['order_id'];
		
		//$envDomain1="https://sandboxapi.g2a.com/v1/order/pay/".$ord; 
		$options = get_option( 'mode_of_operation' );
		if($options['page_id'] == "Test Mode"){
			$envDomain1 = "https://sandboxapi.g2a.com/v1/order/pay/".$ord; 
		}else{
			$envDomain1 = "https://api.g2a.com/v1/order/pay/".$ord; 
		}

		$curl1 = curl_init();
			curl_setopt_array($curl1, array(
		    CURLOPT_URL => $envDomain1,
		   	CURLOPT_RETURNTRANSFER => true,
		 	CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 30,
			CURLOPT_CUSTOMREQUEST => "PUT",
			CURLOPT_HTTPHEADER => array(
				"Authorization: $clientId, $apiKey",
				"Content-Type: application/json",
				"Content-Length: 0",
			),
			CURLOPT_POSTFIELDS => $order_id
		));

		$response1 = curl_exec($curl1);
		$err1 = curl_error($curl1);
		$info1 = curl_getinfo($curl1, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($curl1);

		if ($err1) {
		  echo "cURL Error #:" . $err;
		} else {
			// after payment Done.

		// Getting Key
		//$envDomain2="https://sandboxapi.g2a.com/v1/order/key/".$json1['order_id']; 

		$options = get_option( 'mode_of_operation' );
		if($options['page_id'] == "Test Mode"){
			$envDomain2 = "https://sandboxapi.g2a.com/v1/order/key/".$json1['order_id']; 
		}else{
			$envDomain2 = "https://api.g2a.com/v1/order/key/".$json1['order_id']; 
		}

		$curl2 = curl_init();
		curl_setopt_array($curl2, array(
		    CURLOPT_URL => $envDomain2,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_ENCODING => "",
		    CURLOPT_TIMEOUT => 30,
		    CURLOPT_CUSTOMREQUEST => "GET",
		    CURLOPT_HTTPHEADER => array(
		        "Authorization: $clientId, $apiKey",
		        "Content-Type: application/json",
		    ),
		));

		$response2 = curl_exec($curl2);
		$err2 = curl_error($curl2);
		$info2 = curl_getinfo($curl2, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($curl2);

		if ($err2) {
		  echo "cURL Error #:" . $err2;
		} else {
			$json2 = json_decode($response2, true);
			//print_r($json2);
			$order_data = $order->get_data();
			$order_billing_email = $order_data['billing']['email'];
			
			//Send Email
			$message = "This is the game key <b> ".$json2['key'].'</b> from site '.get_site_url().' ';
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail($order_billing_email,"Game Key: ".get_bloginfo('name'), $message, $headers);
		}
			//echo $response1;
		}





		}


    }

} //endif


    // Allow code execution only once 
/*    if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

        // Get an instance of the WC_Order object
        $order = wc_get_order( $order_id );

        // Get the order key
        $order_key = $order->get_order_key();

        // Get the order number
        $order_key = $order->get_order_number();

        if($order->is_paid())
            $paid = __('yes');
        else
            $paid = __('no');

        // Loop through order items
        foreach ( $order->get_items() as $item_id => $item ) {

            // Get the product object
            $product = $item->get_product();

            // Get the product Id
            $product_id = $product->get_id();

            // Get the product name
            $product_id = $item->get_name();
        }

        // Output some data
        echo '<p>Order ID: '. $order_id . ' — Order Status: ' . $order->get_status() . ' — Order is paid: ' . $paid . '</p>';

        // Flag the action as done (to avoid repetitions on reload for example)
        $order->update_meta_data( '_thankyou_action_done', true );
        $order->save();
    }*/
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WoocommerceG2AImport')) {
	$obj = new WoocommerceG2AImport();
}