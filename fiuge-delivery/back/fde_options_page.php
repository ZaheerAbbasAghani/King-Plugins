<?php
// create custom plugin settings menu
add_action('admin_menu', 'fde_plugin_create_menu');

function fde_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Fiuge Delivery', 'Fiuge Delivery Settings', 'manage_options', 'fde_fiuge_delivery_settings', 'fde_plugin_settings_page', 'dashicons-location
', 25 );

	//call register settings function
	add_action( 'admin_init', 'register_fde_plugin_settings' );
}


function register_fde_plugin_settings() {
	//register our settings
	register_setting( 'fde-plugin-settings-group', 'fde_api_url' );
	register_setting( 'fde-plugin-settings-group', 'fde_api_key' );
    register_setting( 'fde-plugin-settings-group', 'fde_api_post_url' );
	register_setting( 'fde-plugin-settings-group', 'fde_sender_id' );
    register_setting( 'fde-plugin-settings-group', 'fde_store_name' );
    register_setting( 'fde-plugin-settings-group', 'fde_country_code' );
    register_setting( 'fde-plugin-settings-group', 'fde_phone' );
    register_setting( 'fde-plugin-settings-group', 'fde_address' );
    register_setting( 'fde-plugin-settings-group', 'fde_city' );
    register_setting( 'fde-plugin-settings-group', 'fde_zip_code' );
    register_setting( 'fde-plugin-settings-group', 'fde_error_message' );
    register_setting( 'fde-plugin-settings-group', 'fde_order_status' );
}

function fde_plugin_settings_page() {
    ?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Fiuge Delivery - Settings</h1><hr>

<?php settings_errors(); ?>

<form method="post" action="options.php">
    <?php settings_fields( 'fde-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'fde-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API GET REQUEST URL</th>
        <td><input type="text" name="fde_api_url" value="<?php echo esc_attr( get_option('fde_api_url') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">API POST REQUEST URL</th>
        <td><input type="text" name="fde_api_post_url" value="<?php echo esc_attr( get_option('fde_api_post_url') ); ?>" style="width: 100%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">API KEY</th>
        <td><input type="password" name="fde_api_key" value="<?php echo esc_attr( get_option('fde_api_key') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Sender ID</th>
        <td><input type="text" name="fde_sender_id" value="<?php echo esc_attr( get_option('fde_sender_id') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Store Name</th>
        <td><input type="text" name="fde_store_name" value="<?php echo esc_attr( get_option('fde_store_name') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Country Code</th>
        <td><input type="text" name="fde_country_code" value="<?php echo esc_attr( get_option('fde_country_code') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Phone</th>
        <td><input type="text" name="fde_phone" value="<?php echo esc_attr( get_option('fde_phone') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Address</th>
        <td><input type="text" name="fde_address" value="<?php echo esc_attr( get_option('fde_address') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">City</th>
        <td><input type="text" name="fde_city" value="<?php echo esc_attr( get_option('fde_city') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Zip Code</th>
        <td><input type="text" name="fde_zip_code" value="<?php echo esc_attr( get_option('fde_zip_code') ); ?>" style="width: 100%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row">Error Message</th>
        <td><input type="text" name="fde_error_message" value="<?php echo esc_attr( get_option('fde_error_message') ); ?>" style="width: 100%;"/></td>
        </tr>


        <tr valign="top">
        <th scope="row">Order Status Enable/Disable</th>
        <td><input type="checkbox" name="fde_order_status" value="1" <?php checked(1, get_option('fde_order_status'), true); ?>/></td>
        </tr>



        
    </table>
    
    <?php submit_button(); ?>

</form>
</div>


<?php 

//$fde_delivery_price = $_COOKIE["fdeDelivery"];
    
/*    $id = 1563;
    $order = wc_get_order( $id );
    
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
    $estimatedDeliveryTime = $_COOKIE["estimatedDeliveryTime"];
    $updatedPickupTime = $_COOKIE["updatedPickupTime"];

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

    print_r($response);*/

?>


<?php } ?>