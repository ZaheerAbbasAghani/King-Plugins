<?php
/*
Plugin Name: Woocommerce File Uploader
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Plugin is used to create functionality for persons who want to purchase products on rent.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: woo-file-uploder
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WoocommerceFileUploader {

function __construct() {
	add_action('init', array($this, 'wfu_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'wfu_enqueue_script_front'));
}

function wfu_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/wfu_file_uploader_with_email_form.php';
	require_once plugin_dir_path(__FILE__).'front/wfu_settings.php';

}

// Enqueue Style and Scripts

function wfu_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('wfu-style', plugins_url('assets/css/wfu.css', __FILE__),'1.0.0','all');
	

}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WoocommerceFileUploader')) {
	$obj = new WoocommerceFileUploader();
}