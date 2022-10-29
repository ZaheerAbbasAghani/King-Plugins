<?php
/*
Plugin Name: Aviation Weather 
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: The plugin should show an ICAO (airport code) lookup for checking the weather for pilots.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: aviation-weather 
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class AviationWeather  {

function __construct() {
	add_action('init', array($this, 'avi_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'avi_enqueue_script_front'));
/*	register_activation_hook(__FILE__ , array($this,'avi_plugin_activation') );

	register_activation_hook( __FILE__, array($this,'my_activation'));
	add_action( 'avi_daily_event', array($this,'avi_do_this_daily'));
	register_deactivation_hook( __FILE__, array($this,'avi_deactivation' ));*/
	
}



function avi_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/avi_settings_page.php';
	require_once plugin_dir_path(__FILE__) . 'front/avi_search_form.php';
	require_once plugin_dir_path(__FILE__) . 'front/avi_aviation_weather_search.php';

}

// Enqueue Style and Scripts

function avi_enqueue_script_front() {
//Style & Script
	wp_enqueue_style('avi-style', plugins_url('assets/css/avi.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('avi-script', plugins_url('assets/js/avi.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_localize_script('avi-script', 'avi_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('AviationWeather')) {
	$obj = new AviationWeather();
}