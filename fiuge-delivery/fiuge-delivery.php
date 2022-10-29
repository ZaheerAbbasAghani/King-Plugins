<?php
/*
Plugin Name: Fiuge Delivery
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Customer chooses woocommerce product, All products in the cart when customer click checkout, It will be taken to address input page. Customer can choose pickup or Fiuge delivery, If customer chooses Fiuge, We fetch the price from /deliveryinfo endpoint. We present to customer the price and the pickup time and delivery time. Then customer will move to payment and pay.When payment is successful POST to api endpoint in delivey2 with all the details.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: fiuge-delivery
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class FiugeDelivery {

function __construct() {
	add_action('init', array($this, 'fde_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'fde_enqueue_script_front'));
	add_action( 'woocommerce_review_order_before_payment', array($this,'fde_display_extra_fields_after_billing_address') , 10, 1 );
	add_action( 'woocommerce_checkout_update_order_meta', array($this,'fde_add_delivery_option_to_order') , 10, 1);
	add_filter( 'woocommerce_email_order_meta_fields', array($this,'fde_add_delivery_option_to_emails') , 10, 3 );
	add_action( 'woocommerce_after_checkout_validation',  array($this,'fde_action_woocommerce_after_checkout_validation'), 10, 2 );
	add_action( 'woocommerce_admin_order_data_after_billing_address',  array($this,'fde_checkout_field_display_admin_order_meta'), 10, 1 );

    add_action( 'woocommerce_admin_order_data_after_shipping_address',  array($this,'fde_checkout_field_display_admin_order_meta2'), 10, 1 );

	add_action( 'woocommerce_thankyou', array($this,'fde_create_invoice_for_wc_order'),  1, 1  );
	add_action('woocommerce_admin_order_totals_after_tax', array($this,'custom_admin_order_totals_after_tax'), 10, 1 );
 
}



function fde_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/fde_options_page.php';
	require_once plugin_dir_path(__FILE__) . 'front/fde_search_for_shipping_price.php';
}

// Enqueue Style and Scripts

function fde_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('fde-style-animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css','4.1.1','all');

	wp_enqueue_style('fde-style-sweetalert2', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css','11.1.9','all');

	wp_enqueue_style('fde-style', plugins_url('assets/css/fde.css', __FILE__),rand(0,1000),'all');

	wp_enqueue_script('fde-sweetalert2','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js',array('jquery'),'11.1.9', true);

	wp_enqueue_script('fde-script', plugins_url('assets/js/fde.js', __FILE__),array('jquery'),rand(0,1000), true);	

	$symbol = get_woocommerce_currency_symbol();

	wp_localize_script( 'fde-script', 'fde_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'symbol' => $symbol ) );

}


function fde_display_extra_fields_after_billing_address () { ?>
    <h3 style="margin: 30px 0px;"> Delivery Options </h3>
    <label><input type="radio" name="delivery_option" value="Pickup" required class="delivery_option" /> Pickup </label>
    <label><input type="radio" name="delivery_option" value="Fiuge Delivery" class="delivery_option"/> Fiuge Delivery </label>
  <?php 
}


function fde_add_delivery_option_to_order ( $order_id ) {
	if ( isset( $_POST ['delivery_option'] ) &&  '' != $_POST ['delivery_option'] ) {
		add_post_meta( $order_id, '_delivery_option',  sanitize_text_field( $_POST ['delivery_option'] ) );
	}
}

/*
    Include selected option in notification emails.
*/

function fde_add_delivery_option_to_emails ( $fields, $sent_to_admin, $order ) {
	
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {            
	$order_id = $order->get_id();
    } else {
	$order_id = $order->id;
    }

    $delivery_option = get_post_meta( $order_id, '_delivery_option', true );

    if ( '' != $delivery_option ) {
	$fields[ 'Delivery Date' ] = array(
	    'label' => __( 'Delivery Option', 'delivery_option' ),
	    'value' => $delivery_option,
	);
    }
    return $fields;
}

/*
    Validation nag.
    Remove this if the fields are to be optional.
*/
function fde_action_woocommerce_after_checkout_validation( $data, $errors ) { 
    if ( empty( $_POST['delivery_option'] ) ) :
        $errors->add( 'required-field', __( 'You have not chosen a delivery option.', 'woocommerce' ) );
    endif;
}



/*
    Display field value on the order edit page.
*/

function fde_checkout_field_display_admin_order_meta($order) {
    echo '<p><strong>'.__('Delivery Option').':</strong> <br/>' . get_post_meta($order->get_id(), '_delivery_option', true ) . '</p>';

  
}


function fde_checkout_field_display_admin_order_meta2($order) {
     echo '<p><strong>'.__('Delivery Time').':</strong> <br/>' . get_post_meta($order->get_id(), 'estimatedDeliveryTime', true ) . '</p>';

     echo '<p><strong>'.__('Pickup Time').':</strong> <br/>' . get_post_meta($order->get_id(), 'updatedPickupTime', true ) . '</p>';

    
    if(esc_attr(get_option('fde_order_status')) == 1)
    {
        $fde_api_key    = esc_attr( get_option('fde_api_key') );
        $fde_api_url   = esc_attr( get_option('fde_api_url') );
        $fde_sender_id     = esc_attr( get_option('fde_sender_id') );
        $url = $fde_api_url.'/deliverystatus?senderId='.$fde_sender_id.'&senderOrderNumber='.$order->get_id();
        $args = array(
          'headers' => array(
            'x-api-key' => $fde_api_key 
          )
        );
        $response = wp_remote_request( $url, $args );
        $result = json_decode($response['body']);

        echo '<p><strong>'.__('Order Status').':</strong> <br/>' . $result->statusInformation . '</p>';

        
    }


}




function fde_create_invoice_for_wc_order($order_get_id) {

    $id = $order_get_id;
	$order = wc_get_order( $id );


    $total = $order->get_total();
    $fde_delivery_price = $_COOKIE["fdeDelivery"];

    ## -- Make your checking and calculations -- ##
    $new_total = $total + $fde_delivery_price; // <== Fake calculation

    $estimatedDeliveryTime = $_COOKIE["estimatedDeliveryTime"];
    $updatedPickupTime = $_COOKIE["updatedPickupTime"];


  

    
    $url = esc_attr( get_option('fde_api_post_url') );
    $fde_api_key = esc_attr( get_option('fde_api_key') );
    $fde_sender_id  = esc_attr( get_option('fde_sender_id') );

    $store_address      = get_option('fde_address');
    $store_city         = get_option('fde_city');
    $post_code          = get_option('fde_zip_code');
    $fde_store_name     = get_option('fde_store_name');
    $fde_country_code   = get_option('fde_country_code');
    $fde_phone          = get_option('fde_phone');


    $name  = $order->get_billing_first_name().' '.$order->get_billing_last_name();
    $phone =  str_replace($fde_country_code, '', $order->get_billing_phone());

    $address1 = $order->get_billing_address_1();
    $address2 = $order->get_billing_address_2();
    $city = $order->get_billing_city();
    $customer_post_code = $order->get_billing_postcode();
    $note = $order->get_customer_note();

    foreach ( $order->get_items() as $item_id => $item ) {
        $quantity = $item->get_quantity();
    }

    $country = $order->get_billing_country();

    $country_code = $_COOKIE["country_code"];


    $body = array (
        'sender' => 
              array (
                'id' => $fde_sender_id,
                'name' => $fde_store_name,
                'phoneCountryCode' => $fde_country_code,
                'phoneNumber' => $fde_phone,
                'streetAddress' => $store_address,
                'city' => $store_city,
                'zipcode' => $post_code,
              ),
              'receiver' => 
              array (
                'name' => $name,
                'phoneCountryCode' => $country_code,
                'phoneNumber' => $phone,
                'streetAddress' => $address1,
                'apartmentNumber' => $address2,
                'city' => $city,
                'zipcode' => $customer_post_code,
              ),
                'pickupDateTime' => $estimatedDeliveryTime,
                'additionalInformation' => $note,
                'numberOfItems' => $quantity,
                'senderOrderNumber' => "$id",
                'deliveryCountry' => $country ,
                'deliveryDateTime' => $updatedPickupTime,
    );

    $args = array(
        'body'  => json_encode($body),
        'headers' => array(
            'x-api-key' => $fde_api_key 
        ),
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
        
    );
    $request = wp_remote_post( $url, $args );
    if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
        error_log( print_r( $request, true ) );
    }

    $response = wp_remote_retrieve_body( $request );

    $result = json_decode($response);
   
    $PickupTime = $result->pickupDateTime;
    $DeliveryTime = $result->deliveryDateTime;


      //add_post_meta($order->get_id(), 'fdeDelivery', $fde_delivery_price);
    $order->update_meta_data('fdeDelivery', sanitize_text_field($fde_delivery_price));
    $order->update_meta_data('estimatedDeliveryTime',sanitize_text_field($PickupTime));
    $order->update_meta_data('updatedPickupTime',sanitize_text_field($DeliveryTime));
    // Set the new calculated total
    $order->set_total( $new_total );
    $order->save();


   // print_r($result);

}


function custom_admin_order_totals_after_tax( $order_id ) {

    // Here set your data and calculations
    $label = __( 'Fiuge Delivery', 'woocommerce' );
    $value = get_post_meta($order_id, 'fdeDelivery', true);
    $symbol = get_woocommerce_currency_symbol();
    // Output
    ?>
        <tr>
            <td class="label"><?php echo $label; ?>:</td>
            <td width="1%"></td>
            <td class="custom-total"><?php echo "<b style='font-weight:700;'>".$value.' '.$symbol."</b>"; ?></td>
        </tr>
    <?php
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('FiugeDelivery')) {
	$obj = new FiugeDelivery();
}