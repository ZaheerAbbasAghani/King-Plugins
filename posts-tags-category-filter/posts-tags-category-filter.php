<?php
/*
Plugin Name: Posts Tags Category Filter
Plugin URI: https://www.fiverr.com/users/zaheerabbasagha
Description: This plugin is used to filter the posts by tags and category.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: posts-tags-category-filter
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class PostsTagsCategoryFilter {

function __construct() {
	add_action('init', array($this, 'ptcf_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'ptcf_enqueue_script_front'));
	//add_action('admin_enqueue_scripts', array($this, 'ptcf_enqueue_admin'));
	
}



function ptcf_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/ptcf_shortcode.php';
	require_once plugin_dir_path(__FILE__) . 'front/ptcf_filter_categories.php';
	require_once plugin_dir_path(__FILE__) . 'front/ptcf_filter_orders.php';
}

// Enqueue Style and Scripts

function ptcf_enqueue_script_front() {
//Style & Script
wp_enqueue_style('ptcf-style', plugins_url('assets/css/ptcf.css', __FILE__),'1.0.0','all');
wp_enqueue_script('ptcf-script', plugins_url('assets/js/ptcf.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script( 'ptcf-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}





} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('PostsTagsCategoryFilter')) {
	$obj = new PostsTagsCategoryFilter();
}