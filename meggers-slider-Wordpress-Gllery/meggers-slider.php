<?php
/*
Plugin Name: Meggers Slider
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin have ability to add to post as a blog as a block, Photo on one side, content on the other, ability to add small amount of credit text on photo
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://www.fiverr.com/zaheerabbasagha
License: GPLv3 or later
Text Domain: covid-19
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class MeggersSlider {

function __construct() {
	add_action('wp_enqueue_scripts', array($this, 'ms_enqueue_script_front'));
}


// Enqueue Style and Scripts

function ms_enqueue_script_front() {
//Style & Script
wp_enqueue_style('ms-style',plugins_url('assets/css/ms.css', __FILE__),'1.0.0','all');

wp_enqueue_style('ms-font-awesome','https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

wp_enqueue_script('ms-script', plugins_url('assets/js/ms.js', __FILE__),array('jquery'),'1.0.0', true);
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('MeggersSlider')) {
	$obj = new MeggersSlider();
}