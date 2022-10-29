<?php
/*
Plugin Name: Explorial License Plugin
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: This plugin is used to send license key via email when woocommerce order generates.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: explorial-license-plugin
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class ExplorialLicensePlugin {

function __construct() {
	add_action('init', array($this, 'elp_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'elp_enqueue_script_front'));
	//add_action('admin_enqueue_scripts', array($this, 'elp_enqueue_admin'));
	
}



function elp_start_from_here() {
	require_once plugin_dir_path(__FILE__) .'front/elp_license_generator.php';

}

// Enqueue Style and Scripts

function elp_enqueue_script_front() {
//Style & Script
wp_enqueue_style('elp-style', plugins_url('assets/css/elp.css', __FILE__),'1.0.0','all');
wp_enqueue_script('elp-script', plugins_url('assets/js/elp.js', __FILE__),array('jquery'),'1.0.0', true);
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('ExplorialLicensePlugin')) {
$obj = new ExplorialLicensePlugin();
}