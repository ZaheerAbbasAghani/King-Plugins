<?php
/*
Plugin Name: Woocommerce Simple Rent
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Plugin is used to create functionality for persons who want to purchase products on rent.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: woo-simple-rent
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WoocommerceSimpleRent {

function __construct() {
	add_action('init', array($this, 'wsr_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'wsr_enqueue_script_front'));
	//add_action('admin_enqueue_scripts', array($this, 'wsr_enqueue_admin'));
	
}



function wsr_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/wsr_radio_buttons.php';

}

// Enqueue Style and Scripts

function wsr_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('wsr-style', plugins_url('assets/css/wsr.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('wsr-script', plugins_url('assets/js/wsr.js', __FILE__),array('jquery'),'1.0.0', true);

	// Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script( 'jquery-ui-datepicker' );

    // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
    wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' );  

}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WoocommerceSimpleRent')) {
	$obj = new WoocommerceSimpleRent();
}