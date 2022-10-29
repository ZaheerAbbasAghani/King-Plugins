<?php 

function elp_generate_license($order_id){

	$order = wc_get_order( $order_id );
	$email = $order->get_billing_email();
	 // Loop though order "line items"
	foreach( $order->get_items() as $item_id => $item ){
		$product_id   = $item->get_product_id(); //Get the product ID
		$product = $item->get_product();
		//$email = "aghanizaheer@gmail.com";
		$usagesAmount = array();

		// Only for product variation
		if( $product->is_type('variation') ){
		     // Get the variation attributes
		    $variation_attributes = $product->get_variation_attributes();

		    array_push($usagesAmount, $variation_attributes['attribute_pa_anzahl-spieler']);
		}

		$sku = get_post_meta( $product_id, '_sku', true);
		$usagesAmount = explode('-', $usagesAmount[0]);

		$endpoint = 'https://staging.api.explorial.ch/internal/license';
		$body = [
		    'article'  => $sku,
		    'usagesAmount' => $usagesAmount[0],
		    'woocommerceId' => $order_id,
		];
		$body = wp_json_encode($body);
		$options = [
		    'body'        => $body,
		    'headers'     => [
		    'Authorization' => 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxZDRmZjgxOC0xZjFiLTRhNTQtYTZmMy02NmM0Njg4ZjQ5Y2EiLCJpYXQiOjE2MzA1MDkwODR9.tdc46n9KyqctMACKy-pH67eggCgvOgNihqC-Tmy399jvKYlRI3u1CkIwTQJ5oxmk9-PMyXB2UVUMpAONNPkReg',
		        'Content-Type' => 'application/json',
		    ],
		    'timeout'     => 60,
		    'redirection' => 5,
		    'blocking'    => true,
		    'httpversion' => '1.0',
		    'sslverify'   => false,
		    'data_format' => 'body',
		];
		 
		$response = wp_remote_post( $endpoint, $options );
		if ( is_wp_error( $response ) ) {
		    $error_message = $response->get_error_message();
		    echo "Something went wrong: $error_message";
		} else {
		    $result = json_decode($response['body']);
		    $order->update_meta_data('license_code',$result->value);
		    $order->save();

			$to = $email;
			$subject = 'Your license code: '.get_bloginfo('name');
			$body = 'This is your license code: <b>'.$result->value.'</b> from website <a href="'.get_site_url().'">'.get_bloginfo('name').'</a>';
			$headers = array('Content-Type: text/html; charset=UTF-8');

			wp_mail( $to, $subject, $body, $headers );

		}
	}

}
add_action("woocommerce_checkout_update_order_meta", "elp_generate_license");


add_action( 'woocommerce_admin_order_data_after_shipping_address', 'custom_checkout_field_display_admin_order_meta', 10, 1 );
function custom_checkout_field_display_admin_order_meta( $order ){
    $license_code = get_post_meta( $order->get_id(), 'license_code', true );
    if( ! empty( $license_code ) )
        echo '<p><strong>'.__('License Code', 'woocommerce').': </strong> ' . $license_code . '</p>';
}