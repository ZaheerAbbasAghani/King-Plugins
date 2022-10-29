<?php
/*
Plugin Name: Woo Smokty
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin is used to sell products by height,width range
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: woosm
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WooSmokty {

function __construct() {
	add_action('init', array($this, 'woosm_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'woosm_enqueue_script_front'));
	add_action( 'woocommerce_before_calculate_totals', array($this,'woocommerce_custom_price_to_cart_item'), 99 );
	
}

function woosm_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'inc/woosm_product_custom_fields.php';
	require_once plugin_dir_path(__FILE__) . 'inc/woosm_ajax.php';
}

// Enqueue Style and Scripts

function woosm_enqueue_script_front() {
//Style & Script
wp_enqueue_style('woosm-style',plugins_url('assets/css/woosm.css', __FILE__),'1.0.0','all');
wp_enqueue_script('woosm-script',plugins_url('assets/js/woosm.js',__FILE__),array('jquery'),'1.0.0', true);
}

function woocommerce_custom_price_to_cart_item( $cart_object ) {  
    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if( isset( $value["custom_price"] ) ) {
                //for woocommerce version lower than 3
                //$value['data']->price = $value["custom_price"];
                //for woocommerce version +3
                $value['data']->set_price($value["custom_price"]);
            }
        }  
    }  
}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WooSmokty')) {
	$obj = new WooSmokty();
}