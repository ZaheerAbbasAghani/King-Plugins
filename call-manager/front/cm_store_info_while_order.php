<?php 

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

function cm_generate_license($order_id){

	$order = wc_get_order( $order_id );
	$email = $order->get_billing_email();
	$area_code = get_post_meta($order_id,'_area_code', true);
	foreach( $order->get_items() as $item_id => $item ){
		$product_id   = $item->get_product_id(); //Get the product ID
		$product = $item->get_product();
		$sid = get_option('auth_id');
		$token = get_option('auth_token');
		$twilio = new Client($sid, $token);
		$local = $twilio->availablePhoneNumbers("US")
		                ->local
		                ->read(["areaCode"=>$area_code], 1);
		foreach ($local as $record) {
		    	$order->update_meta_data('phone_number',$record->friendlyName);
		   		$incoming_phone_number=$twilio->incomingPhoneNumbers
                                ->create(["phoneNumber" => $record->friendlyName]);
				$order->save();
		}

	}

}
add_action("woocommerce_checkout_update_order_meta", "cm_generate_license");


add_action( 'woocommerce_admin_order_data_after_shipping_address', 'custom_checkout_field_display_admin_order_meta', 10, 1 );
function custom_checkout_field_display_admin_order_meta( $order ){
    $phone_number = get_post_meta( $order->get_id(), 'phone_number', true );
   
    if( ! empty( $phone_number ) )
        echo '<p><strong>'.__('Phone Number', 'woocommerce').': </strong> ' . $phone_number . '</p>';
}





// Display a custom checkout field after billing form
function action_woocommerce_after_checkout_billing_form( $checkout ) {

    woocommerce_form_field( 'add_area_code', array(
        'type'          => 'text',
        'maxlength'     => 3,
        'class'         => array( 'form-row-wide' ),
        'required'      => true,
        'label'         => __( 'Area Code', 'woocommerce' ),
        'placeholder'   => __( '830', 'woocommerce' ),
    ), $checkout->get_value( 'add_area_code' ));

}
add_action( 'woocommerce_after_checkout_billing_form', 'action_woocommerce_after_checkout_billing_form', 10, 1 );


// Custom checkout field validation
function action_woocommerce_checkout_process() {
    // Isset    
    if ( isset( $_POST['add_area_code'] ) ) {
        $domain = 'woocommerce';
        $phone = $_POST['add_area_code'];
        
        // Empty
        if ( empty ( $phone ) ) {
            wc_add_notice( __( 'Please enter a valid area code', $domain ), 'error' );
        }
        
        // Validates a phone number using a regular expression.
        if ( 0 < strlen( trim( preg_replace( '/[\s\#0-9_\-\+\/\(\)\.]/', '', $phone ) ) ) ) {
            wc_add_notice( __( 'Please enter a valid area code', $domain ), 'error' );
        }
    }
}
add_action( 'woocommerce_checkout_process', 'action_woocommerce_checkout_process', 10, 0 );





add_filter( 'woocommerce_email_order_meta_fields', 'cm_add_area_code_to_emails' , 10, 3 );

function cm_add_area_code_to_emails ( $fields, $sent_to_admin, $order ) {
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }

    $area_code = get_post_meta( $order_id, '_area_code', true );

    if ( '' != $area_code ) {
	$fields[ 'Area Code' ] = array(
	    'label' => __( 'Area Code', 'call_manager' ),
	    'value' => $area_code,
	);
    }
    return $fields;
}


// Display order custom meta data in Order received (thankyou) page
add_action('woocommerce_thankyou','cm_teilnehmer_thankyou',10,2);
function cm_teilnehmer_thankyou( $order_id ) {
  
	$phone_number=get_post_meta($order_id,'phone_number',true);
    if( ! empty( $phone_number ) )
        echo '<p style="display:none;" class="cm_phone_number">'.' ' . $phone_number . '</p>';
}