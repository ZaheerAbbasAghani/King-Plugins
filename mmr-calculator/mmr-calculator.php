<?php
/*
Plugin Name: MMR Calculator
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Analyzes hundreds of millions of matches each month.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: mmr-calculator
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class MMRCalculator {

function __construct() {
	add_action('init', array($this, 'mmr_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'mmr_enqueue_script_front'));
	//add_action('admin_enqueue_scripts', array($this, 'mmr_enqueue_admin'));
	
}



function mmr_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/mmr_form_shortcode.php';
	require_once plugin_dir_path(__FILE__) . 'front/mmr_get_information.php';
}

// Enqueue Style and Scripts

function mmr_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('mmr-style', plugins_url('assets/css/mmr.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('mmr-script', plugins_url('assets/js/mmr.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_localize_script( 'mmr-script', 'mmr_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('MMRCalculator')) {
	$obj = new MMRCalculator();
}