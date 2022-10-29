<?php
/*
Plugin Name: Post Block Maker
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: There will be 1 article given and then it will need to copy pieces of the article and create a new post.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: post-block-maker
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class PostBlockMaker {

function __construct() {
	add_action('init', array($this, 'pbm_start_from_here'));
	add_action('admin_enqueue_scripts', array($this, 'pbm_enqueue_script_dashboard'));
	//add_action('admin_enqueue_scripts', array($this, 'cv19_enqueue_admin'));
	
}



function pbm_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/pbm_settings_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/pbm_create_post_blocks.php';

}

// Enqueue Style and Scripts

function pbm_enqueue_script_dashboard($hook) {


	//Style & Script
	if($hook != 'toplevel_page_pbm_block_maker_settings')
		return 0;
		wp_enqueue_style('pbm-style', plugins_url('assets/css/pbm.css', __FILE__),'1.0.0','all');
		wp_enqueue_script('pbm-script', plugins_url('assets/js/pbm.js', __FILE__),array('jquery'),'1.0.0', true);

		wp_localize_script('pbm-script', 'pbm_ajax_object', array( 'ajax_url' => admin_url('admin-ajax.php')));
	
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('PostBlockMaker')) {
$obj = new PostBlockMaker();
}