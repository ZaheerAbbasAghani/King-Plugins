<?php
/*
Plugin Name: API CHECK
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Check if Woocommerce is installed (else disable plugin and show error message) -Setting page in WP-admin where user can fill in the API key, and disable / enable the address validation -User enters WooCommerce checkout form -User selects county -User enters postalcode / number (Woocommerce checkout fields) -Plugin uses the given postalcode and number to call the API -Plugin shows loading indicator (during call) -Api returns the complete address  -Plugin hides loading indicator -Plugin fills out the complete address (street, city) in the Woocommerce checkout form -User sees the filled information and can change it when needed
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: covid-19
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class APICheck {

function __construct() {

	add_action('init', array($this, 'ac_start_from_here'));
	if(get_option('ac_enable_disabled') == 1){

		
		add_action('wp_enqueue_scripts', array($this, 'ac_enqueue_script_front'));
		add_action( 'admin_init', array($this,'bpem_if_buddypress_not_active' ));
		add_filter("woocommerce_checkout_fields",  array($this,"custom_override_checkout_fields"), 1);
		add_filter( 'woocommerce_default_address_fields',  array($this,'custom_override_default_locale_fields') );


		add_action( 'woocommerce_checkout_process',  array($this,'ac_validate_new_checkout_field') );
		add_action( 'woocommerce_checkout_update_order_meta', array($this,'ac_save_new_checkout_field') );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array($this,'ac_show_new_checkout_field_order'), 10, 1 );
		add_action( 'woocommerce_email_after_order_table',  array($this,'ac_show_new_checkout_field_emails'), 20, 4 );

			add_action( 'woocommerce_before_order_notes', array($this,'ac_add_custom_checkout_field') );
	}
}

function bpem_if_buddypress_not_active($message) {
	if (!is_plugin_active('woocommerce/woocommerce.php')) {
	echo $message .= "<div class='notice notice-error is-dismissible'><h4> Woocommerce Plugin Activation Required for API Check Plugin.</h4>
		
	</div>";
	deactivate_plugins('/api-check/api-check.php');

	}
}



function ac_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/ac_search_address.php';
	require_once plugin_dir_path(__FILE__) . 'back/ac_options_page.php';

}

// Enqueue Style and Scripts

function ac_enqueue_script_front() {
//Style & Script
	wp_enqueue_style('ac-style', plugins_url('assets/css/ac.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('ac-script', plugins_url('assets/js/ac.js', __FILE__),array('jquery'),'1.0.0', true);
	wp_localize_script( 'ac-script', 'ac_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
}



  
function ac_add_custom_checkout_field( $checkout ) { 
   $current_user = wp_get_current_user();
   $saved_house_no = $current_user->house_no;
   woocommerce_form_field( 'house_no', array(        
      'type' => 'text',        
      'class' => array( 'form-row-wide houseNo' ),        
      'label' => 'House No',        
      'placeholder' => '1',        
      'required' => true,        
      'default' => $saved_house_no,        
   ), $checkout->get_value( 'house_no' ) ); 
}


  
function ac_validate_new_checkout_field() {    
   if ( ! $_POST['house_no'] ) {
      wc_add_notice( 'Please enter your Licence Number', 'error' );
   }
}


  
function ac_save_new_checkout_field( $order_id ) { 
    if ( $_POST['house_no'] ) update_post_meta( $order_id, '_house_no', esc_attr( $_POST['house_no'] ) );
}
  

   
function ac_show_new_checkout_field_order( $order ) {    
   $order_id = $order->get_id();
   if ( get_post_meta( $order_id, '_house_no', true ) ) echo '<p><strong>House No:</strong> ' . get_post_meta( $order_id, '_house_no', true ) . '</p>';
}
 

  
function ac_show_new_checkout_field_emails( $order, $sent_to_admin, $plain_text, $email ) {
    if ( get_post_meta( $order->get_id(), '_house_no', true ) ) echo '<p><strong>License Number:</strong> ' . get_post_meta( $order->get_id(), '_house_no', true ) . '</p>';
}



function custom_override_checkout_fields($fields) {
    $fields['billing']['billing_first_name']['priority'] = 1;
    $fields['billing']['billing_last_name']['priority'] = 2;
    $fields['billing']['billing_company']['priority'] = 3;
    $fields['billing']['billing_country']['priority'] = 4;
    $fields['billing']['billing_state']['priority'] = 5;
    $fields['billing']['billing_address_1']['priority'] = 6;
    $fields['billing']['billing_address_2']['priority'] = 7;
    $fields['billing']['billing_city']['priority'] = 8;
    $fields['billing']['billing_postcode']['priority'] = 9;
    $fields['billing']['billing_email']['priority'] = 10;
    $fields['billing']['billing_phone']['priority'] = 11;
   // $fields['billing']['house_no']['priority'] = 5;
    return $fields;
}


function custom_override_default_locale_fields( $fields ) {
    $fields['postcode']['priority'] = 4;
   // $fields['house_no']['priority'] = 5;
   
    return $fields;
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('APICheck')) {
	$obj = new APICheck();
}