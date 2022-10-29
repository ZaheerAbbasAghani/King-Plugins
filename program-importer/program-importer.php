<?php
/*
Plugin Name: Program Importer
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Plugin is used to import program from xls file and attach it to TV schedule plugin cpt.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: program-importer
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class ProgramImporter {

function __construct() {
	add_action('init', array($this, 'pi_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'pi_enqueue_script_front'));
	
}



function pi_start_from_here() {
	require_once plugin_dir_path(__FILE__).'back/program-importer-settings.php';

}

// Enqueue Style and Scripts

function pi_enqueue_script_front() {
//Style & Script
wp_enqueue_style('pi-style', plugins_url('assets/css/pi.css', __FILE__),'1.0.0','all');
wp_enqueue_script('pi-script', plugins_url('assets/js/pi.js', __FILE__),array('jquery'),'1.0.0', true);
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('ProgramImporter')) {
	$obj = new ProgramImporter();
}