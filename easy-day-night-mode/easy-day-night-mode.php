<?php
/*
Plugin Name: Easy Day Night Mode
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: You can transform your website mode from day to night and night to day.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: bp-event-manager
Domain Path: /languages
 */

defined("ABSPATH") or die("No direct access!");
class EasyDayNightMode {

	function __construct() {
		add_action('init', array($this, 'bpem_start_from_here'));
		add_action('wp_enqueue_scripts', array($this, 'bpem_enqueue_script_front'));
		add_action('admin_enqueue_scripts', array($this, 'ednm_enqueue_admin'));
		//add_action('admin_init', array($this, 'bpem_enqueue_script_front'));

	/*	add_action('init', array($this, 'bpem_enqueue_script_front'));

		add_action('init', array($this, 'bpem_start_from_here'));

		add_action('init', array($this, 'bpem_register_dashboard_post_page'));

		add_action('admin_enqueue_scripts', array($this, 'bpem_admin_enqueue_scripts'));

		add_action('admin_menu', array($this, 'bpem_cpt_ui_for_admin_only'));

		add_action('add_meta_boxes', array($this, 'bpem_attendees_add_meta_boxes'));

		add_action('plugins_loaded', array($this, 'load_textdomain'));
		add_action('admin_footer', array($this, 'bpem_deactivate_scripts'));

		add_action('widgets_init', array($this, 'bpem_all_events_in_calendar'));

		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'bpem_plugin_action_links'));*/

	}

// Activate plugin

/*	function bpem_activate() {

		flush_rewrite_rules();
		add_post_type_support('bpem_event', 'buddypress-activity');
	}

	public function load_textdomain() {

		load_plugin_textdomain('bpem-plugin', false, basename(dirname(__FILE__)) . '/languages/');

	}*/

//All Plugin files




	function bpem_start_from_here() {
		require_once plugin_dir_path(__FILE__) . 'ednm_front/day_night_shortcode.php';
		require_once plugin_dir_path(__FILE__) . 'ednm_back/ednm_options_page.php';
	}

// Enqueue Style and Scripts

function bpem_enqueue_script_front() {
//Style
wp_enqueue_style('ednm-style', plugins_url('assets/css/ednm.css', __FILE__),'1.0.0','all');


//Script
wp_enqueue_script('ednm-script', plugins_url('assets/js/ednm.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script('ednm-script', 'myScript', array(
    'pluginsUrl' => plugins_url('', __FILE__),
));



//wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
}

function ednm_enqueue_admin(){
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script('ednm-script-admin', plugins_url('assets/js/ednm_admin.js', __FILE__),array('jquery','wp-color-picker'),'1.0.0', true);
	wp_enqueue_script('ednm-script-cookie',"https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js",array('jquery'),'1.4.1', true);

wp_enqueue_style('ednm-mirror', plugins_url('assets/css/codemirror.css', __FILE__),'','all');
wp_enqueue_style('ednm-mirror-theme', plugins_url('assets/theme/xq-dark.css', __FILE__),'','all');

wp_enqueue_script('ednm-script-mirror',plugins_url('assets/js/codemirror.js', __FILE__),array('jquery'),'', true);



}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('EasyDayNightMode')) {

	$obj = new EasyDayNightMode();

	//require_once plugin_dir_path(__FILE__) . 'bpem-dash/bpem_showing_all_events.php';

}

//activate plugin hook

//register_activation_hook(__FILE__, array($obj, 'bpem_activate'));
