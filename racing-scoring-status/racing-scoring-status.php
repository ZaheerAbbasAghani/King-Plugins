<?php
/*
Plugin Name: Racing Scoring Status
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: A custom WordPress plugin for my company to distribute to our clients. The plugin would periodically obtain data from our server via api, update some custom tables in the database and display the data. Would need an admin page as well to control the data that is displayed.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: ticket-seller
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class RacingScoringStatus {

function __construct() {
	add_action('init', array($this, 'rss_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'rss_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'rss_enqueue_admin'));
	add_action("init", array($this, "rss_store_data_temprory"));
	
}



function rss_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/rss_options_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/rss_update_now.php';
	require_once plugin_dir_path(__FILE__) . 'front/rss_frontend_tables.php';

	//delete_option( "rss_number_of_features" );

}

// Enqueue Style and Scripts

function rss_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('rss-datatable-style', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css','1.11.5','all');
	wp_enqueue_style('rss-style', plugins_url('assets/css/rss.css', __FILE__),'1.0.0','all');
	
	wp_enqueue_script('rss-datatable-script', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',array('jquery'),'1.11.5', true);
	wp_enqueue_script('rss-script', plugins_url('assets/js/rss.js', __FILE__),array('jquery'),'1.0.0', true);
	
}


// Enqueue Style and Scripts

function rss_enqueue_admin($hook) {

	if($hook != "toplevel_page_rss_racing_scoring_status")
		return false;
	
	wp_enqueue_style('rss-admin-style', plugins_url('assets/css/rss_admin.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('rss-admin-script', plugins_url('assets/js/rss_admin.js', __FILE__),array('jquery'),'1.0.0', true);
	wp_localize_script( 'rss-admin-script', 'rss_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}


function rss_store_data_temprory(){

$time_period = get_option('rss_update_how_often');
$time = $time_period == "" ? "" : $time_period['rss_time_period'];

// Get any existing copy of our transient data
if ( false === ( $special_query_results = get_transient( 'rss_divisions' ) ) && !empty($time_period)) {
    // It wasn't there, so regenerate the data and save the transient
   
	$wp_request_url =  esc_attr( get_option('rss_api_url') );
	$wp_request_headers = array(
	  'ApiKey' => get_option('rss_api_key')
	);
	$wp_response = wp_remote_request(
	  $wp_request_url,
	  array(
	      'method'    => 'GET',
	      'headers'   => $wp_request_headers
	  )
	);

	$json = $wp_response['body'];
	$recods = json_decode($json)->myDivisions;

	if($time == 72){
		set_transient('rss_divisions', $recods, $time * HOUR_IN_SECONDS );	
	}
	elseif ($time == 24) {
		set_transient('rss_divisions', $recods, $time * HOUR_IN_SECONDS );
	}
	elseif ($time == 7) {
		set_transient('rss_divisions', $recods, $time * DAY_IN_SECONDS );
	}

}
 
// Use the data like you would have normally...


}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('RacingScoringStatus')) {
	$obj = new RacingScoringStatus();
}