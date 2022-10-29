<?php 

add_action( 'wp_ajax_dix_fetch_product_prices', 'dix_fetch_product_prices' );
add_action( 'wp_ajax_nopriv_dix_fetch_product_prices', 'dix_fetch_product_prices' );

function dix_fetch_product_prices() {
	global $wpdb; // this is how you get access to the database


	$language = $_POST['selected_language'];


	if($language == "USDC"){

		$ids = $_POST['ids'];

		$url = 'https://www.indigopreciousmetals.com/feeds';
		$username = esc_attr(get_option('dix_username'));
		$password = esc_attr(get_option('dix_password'));

		global $wpdb; 
		$table_name = $wpdb->base_prefix.'dix_imported_data';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );


		$info = array();

		foreach ($data->productIds as $key => $value) {

			if(in_array($key, $ids)){
				array_push($info, array("id" => $key, "price" => number_format($value,2) ));
			}
		}

		$response = array('info' => $info, 'symbol' => "$", "url" => "https://www.indigopreciousmetals.com");
		wp_send_json( $response );
	}
	elseif ($language == "EUROC") {
		
		$ids = $_POST['ids'];

		$url = 'https://eur.indigopreciousmetals.com/feeds';
		$username = esc_attr(get_option('dix_username'));
		$password = esc_attr(get_option('dix_password'));

		global $wpdb; 
		$table_name = $wpdb->base_prefix.'dix_imported_data';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );


		$info = array();

		foreach ($data->productIds as $key => $value) {

			if(in_array($key, $ids)){
				array_push($info, array("id" => $key, "price" => number_format($value,2)));
			}
		}

		$response = array('info' => $info, 'symbol' => "€", "url" => "https://eur.indigopreciousmetals.com");
		wp_send_json( $response );

	}

	elseif ($language == "SGDC") {
		
		$ids = $_POST['ids'];

		$url = 'https://sgd.indigopreciousmetals.com/feeds';
		$username = esc_attr(get_option('dix_username'));
		$password = esc_attr(get_option('dix_password'));

		global $wpdb; 
		$table_name = $wpdb->base_prefix.'dix_imported_data';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );


		$info = array();

		foreach ($data->productIds as $key => $value) {

			if(in_array($key, $ids)){
				array_push($info, array("id" => $key, "price" => number_format($value,2)));
			}
		}

		$response = array('info' => $info, 'symbol' => "S$", "url" => "https://sgd.indigopreciousmetals.com");
		wp_send_json( $response );
		
	}

	elseif ($language == "MYRC") {
		
		$ids = $_POST['ids'];

		$url = 'https://myr.indigopreciousmetals.com/feeds';
		$username = esc_attr(get_option('dix_username'));
		$password = esc_attr(get_option('dix_password'));

		global $wpdb; 
		$table_name = $wpdb->base_prefix.'dix_imported_data';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );


		$info = array();

		foreach ($data->productIds as $key => $value) {

			if(in_array($key, $ids)){
				array_push($info, array("id" => $key, "price" => number_format($value,2)));
			}
		}

		$response = array('info' => $info, 'symbol' => "MYR", "url" => "https://myr.indigopreciousmetals.com");
		wp_send_json( $response );
		
	}
	elseif ($language == "GBPC") {
		
		$ids = $_POST['ids'];

		$url = 'https://gbp.indigopreciousmetals.com/feeds';
		$username = esc_attr(get_option('dix_username'));
		$password = esc_attr(get_option('dix_password'));

		global $wpdb; 
		$table_name = $wpdb->base_prefix.'dix_imported_data';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );


		$info = array();

		foreach ($data->productIds as $key => $value) {

			if(in_array($key, $ids)){
				array_push($info, array("id" => $key, "price" => number_format($value,2)));
			}
		}

		$response = array('info' => $info, 'symbol' => "£", "url" => "https://gbp.indigopreciousmetals.com");
		wp_send_json( $response );
		
	}else{

		$ids = $_POST['ids'];

		$url = 'https://www.indigopreciousmetals.com/feeds';
		$username = esc_attr(get_option('dix_username'));
		$password = esc_attr(get_option('dix_password'));

		global $wpdb; 
		$table_name = $wpdb->base_prefix.'dix_imported_data';
		$args = array(
		  'headers' => array(
		    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password )
		  )
		);
		$request=wp_remote_request( $url, $args );

		if( is_wp_error( $request ) ) {
		    return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );


		$info = array();

		foreach ($data->productIds as $key => $value) {

			if(in_array($key, $ids)){
				array_push($info, array("id" => $key, "price" => number_format($value,1)));
			}
		}

		$response = array('info' => $info, 'symbol' => "$", "url" => "https://www.indigopreciousmetals.com");
		wp_send_json( $response );

	}



	wp_die(); // this is required to terminate immediately and return a proper response
}