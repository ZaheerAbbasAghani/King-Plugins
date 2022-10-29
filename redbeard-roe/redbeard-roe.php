<?php

/*

Plugin Name: Redbeard Roe

Plugin URI: https://www.fiverr.com/zaheerabbasagha

Description: This plugin is used to create siri shortcuts post type

Version: 1.0

Author: Zaheer Abbas Aghani

Author URI: https://profiles.wordpress.org/zaheer01/

License: GPLv3 or later

Text Domain: redbeardroe

Domain Path: /languages

*/



defined("ABSPATH") or die("No direct access!");

class RedbeardRoe {



function __construct() {





	require_once plugin_dir_path(__FILE__) . 'back/redr_custom_post_type.php';
	add_action('wp_enqueue_scripts', array($this, 'redr_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'redr_enqueue_script_back'));
	
	add_action( 'pre_get_posts', array($this,'add_siri_shortcuts_types_to_query') );

}







function redr_start_from_here() {

	//require_once plugin_dir_path(__FILE__) . 'back/redr_custom_post_type.php';

}









// Enqueue Style and Scripts



function redr_enqueue_script_front() {

//Style & Script

wp_enqueue_style('redr-style', plugins_url('assets/css/redr.css', __FILE__),'1.0.0','all');

wp_enqueue_script('cv19-script', plugins_url('assets/js/redr.js', __FILE__),array('jquery'),'1.0.0', true);

}

function redr_enqueue_script_back() {

//Style & Script

wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');

wp_enqueue_script('redr-script', plugins_url('assets/js/redr_back.js', __FILE__),array('jquery'),'1.0.0', true);

}



function add_siri_shortcuts_types_to_query( $query ) {

    if ( is_home() && $query->is_main_query() )

        $query->set( 'post_type', array( 'post', 'siri_shortcuts' ) );

    return $query;

}





} // class ends



// CHECK WETHER CLASS EXISTS OR NOT.



if (class_exists('RedbeardRoe')) {

	$obj = new RedbeardRoe();

}