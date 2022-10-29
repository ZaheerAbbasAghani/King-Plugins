<?php
/*
Plugin Name: Covid19 Updates
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Display list of p.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: covid-19
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class Covid19Updates {

function __construct() {
	add_action('init', array($this, 'bpem_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'bpem_enqueue_script_front'));
	//add_action('admin_enqueue_scripts', array($this, 'cv19_enqueue_admin'));
	
}



function bpem_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'cv19_front/cv19_shortcode.php';

}

// Enqueue Style and Scripts

function bpem_enqueue_script_front() {
//Style & Script
wp_enqueue_style('cv19-style', plugins_url('assets/css/cv19.css', __FILE__),'1.0.0','all');
wp_enqueue_script('cv19-script', plugins_url('assets/js/cv19.js', __FILE__),array('jquery'),'1.0.0', true);
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('Covid19Updates')) {
$obj = new Covid19Updates();
}