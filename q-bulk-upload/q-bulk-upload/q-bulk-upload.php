<?php
/*
Plugin Name: Q Bulk Upload
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Bulk upload questions from csv.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: q-bulk-upload
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class QBulkUpload {

function __construct() {
	add_action('init', array($this, 'qbu_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'qbu_enqueue_script_front'));
	//add_action('admin_enqueue_scripts', array($this, 'qbu_enqueue_admin'));
	
}



function qbu_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/qbu_options_page.php';

}

// Enqueue Style and Scripts

function qbu_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('qbu-style', plugins_url('assets/css/qbu.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('qbu-script', plugins_url('assets/js/qbu.js', __FILE__),array('jquery'),'1.0.0', true);
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('QBulkUpload')) {
	$obj = new QBulkUpload();
}