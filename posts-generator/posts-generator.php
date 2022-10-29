<?php
/*
Plugin Name: Post Generator
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin generates posts from textfiels.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: posts-generator
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class PostsGenerator {

function __construct() {
	add_action('init', array($this, 'bpem_start_from_here'));
	add_action('admin_enqueue_scripts', array($this, 'pst_enqueue_script_admin'));
	
}



function bpem_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'pst_back/posts-generator-page.php';

}

// Enqueue Style and Scripts

function pst_enqueue_script_admin() {
//Style & Script
wp_enqueue_style('pst-style', plugins_url('assets/css/pststyle.css', __FILE__),'1.0.0','all');
//wp_enqueue_script('cv19-script', plugins_url('assets/js/cv19.js', __FILE__),array('jquery'),'1.0.0', true);
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('PostsGenerator')) {
$obj = new PostsGenerator();
}