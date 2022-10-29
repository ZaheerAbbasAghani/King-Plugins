<?php
/*
Plugin Name: Woo Restrict User
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Display latest posts from instagram,twitter,facebook,linkedin,youtube
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: woorestrictuser
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WooRestrictUser {

function __construct() {
	add_action('init', array($this, 'bpem_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'wru_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'wru_enqueue_admin'));
	//add_filter( 'woocommerce_variation_is_active', array($this,'bbloomer_grey_out_variations_out_of_stock'), 10, 2 );
	

}



function bpem_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'wru-dash/social-channels.php';
	require_once plugin_dir_path(__FILE__) . 'wru-dash/wru-assign-user-products.php';
	require_once plugin_dir_path(__FILE__) . 'wru-public/wru-assign-user.php';
	require_once plugin_dir_path(__FILE__) . 'inc/wru_fetching_user_list.php';
	require_once plugin_dir_path(__FILE__) . 'inc/wru_approve_user_product.php';
	require_once plugin_dir_path(__FILE__) . 'inc/wru_delete_approved_user.php';
	require_once plugin_dir_path(__FILE__) . 'wru-public/wru-user-product-front.php';
	require_once plugin_dir_path(__FILE__) . 'inc/wru_delete_user_by_approve_date.php';
	require_once plugin_dir_path(__FILE__) . 'inc/wru_register_form_after_enquiry.php';
}

// Enqueue Style and Scripts

function wru_enqueue_admin() {
//Style & Script
wp_enqueue_style('sl-style', plugins_url('wru-public/assets/css/sl_style.css', __FILE__),'1.0.0','all');

//wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-autocomplete' );
wp_enqueue_style( 'jquery-ui-styles','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );

wp_enqueue_style( 'jquery-ui-datepicker-style' , 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
wp_enqueue_script( 'jquery-ui-datepicker' );

wp_enqueue_script('wru-script', plugins_url('wru-public/assets/js/wru_script.js', __FILE__),array( 'jquery', 'jquery-ui-autocomplete' ), '1.0.0', true);
wp_localize_script( 'wru-script', 'MyAutocomplete', array( 'url' => admin_url( 'admin-ajax.php' ) ) );

}


function wru_enqueue_script_front(){
	wp_enqueue_style('front-style', plugins_url('wru-public/assets/css/wru_style.css', __FILE__),'1.0.0','all');
	if( is_user_logged_in()) {
		wp_enqueue_script('front-js', plugins_url('wru-public/assets/js/wru_front.js', __FILE__),'1.0.0','all');
	}
}


 
/*function bbloomer_grey_out_variations_out_of_stock( $is_active, $variation ) {
    if ( ! $variation->is_in_stock() ) return false;
    return $is_active;
}

*/

/*add_action( 'wp_head', 'react2wp_is_shop_remove_add_to_cart_button');
function react2wp_is_shop_remove_add_to_cart_button() {
   if ( is_product() ) {
      add_filter( 'woocommerce_is_purchasable', '__return_false' );
   }
}*/



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WooRestrictUser')) {
$obj = new WooRestrictUser();
}