<?php 

add_action( 'wp_ajax_tks_update_products', 'tks_update_products' );
add_action( 'wp_ajax_nopriv_tks_update_products', 'tks_update_products' );

function tks_update_products() {
	global $wpdb;

	$times = get_transient('tks_times') + 1;
	set_transient('tks_times',$times, 8760 * HOUR_IN_SECONDS );

	//echo $times;

	// Get any existing copy of our transient data
	if ( get_transient( 'tks_times' ) < 3) {

		$wp_request_url =  esc_attr( get_option('tks_get_product_url') );
		$username =  esc_attr( get_option('tks_username') );
		$password =  esc_attr( get_option('tks_password') );
		$wp_request_headers = array(
			'Authorization' => 'Basic ' . base64_encode( "$username:$password" )
		);
		$wp_response = wp_remote_request(
		  $wp_request_url,
		  array(
		      'method'    => 'GET',
		      'headers'   => $wp_request_headers
		  )
		);

		$recods = json_decode($wp_response['body']);
		set_transient('tks_records', $recods, 24 * HOUR_IN_SECONDS );

		$results = get_transient( 'tks_records' );

		foreach ($results as $result) {
		    if ( post_exists( $result->Description ) == 0 ) {
		        $post_id = wp_insert_post( array(
		            'post_title' => $result->Description,
		            'post_content' => $result->MainDescription,
		            'post_excerpt' => $result->ShortDescription,
		            'post_status' => 'publish',
		            'post_type' => "product",
		        ) );
				wp_set_object_terms( $post_id, 'simple', 'product_type' );
				$product = wc_get_product( $post_id );
				$product->set_price( $result->Price->Price->Amount );
				$product->set_regular_price( $result->Price->Price->Amount );
				$product->set_stock_quantity("100");
				$product->save();
		    }
		}
		

		echo "Product Update Successfully.";

	}else{
		echo "You already updated the product 2 times a year";
	}




	wp_die();
}