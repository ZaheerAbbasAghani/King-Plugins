<?php



/*



Plugin Name: User Settings Social Fields

Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html

Description: Ability to display individual fields from a logged in user profile onto a page when the registered user is logged in so that they can populate those fields without going to their main WP dashboard.

Version: 1.0

Author: Zaheer Abbas Aghani

Author URI: https://profiles.wordpress.org/zaheer01/

License: GPLv3 or later

Text Domain: covid-19

Domain Path: /languages



*/







defined("ABSPATH") or die("No direct access!");



class UserSettingsSocialFields {







function __construct() {



	add_action('init', array($this, 'ussf_start_from_here'));

	add_action('wp_enqueue_scripts', array($this, 'ussf_enqueue_script_front'));



}















function ussf_start_from_here() {

	require_once plugin_dir_path(__FILE__).'ussf_inc/ussf_shortcode.php';

	require_once plugin_dir_path(__FILE__).'ussf_inc/social_user_fields_creation.php';

	require_once plugin_dir_path(__FILE__).'ussf_inc/ussf_insert_social_handle.php';

}







// Enqueue Style and Scripts







function ussf_enqueue_script_front() {



//Style & Script



wp_enqueue_style('ussf-style', plugins_url('assets/css/ussf.css', __FILE__),'1.0.0','all');



/*wp_enqueue_script('ussf-validate', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js', array('jquery'), '',true);*/





wp_enqueue_script('ussf-script', plugins_url('assets/js/ussf.js', __FILE__),array('jquery'),'1.0.0', true);





wp_localize_script('ussf-script', 'ajax_object', array('ajax_url'=>admin_url('admin-ajax.php')));



}















} // class ends







// CHECK WETHER CLASS EXISTS OR NOT.







if (class_exists('UserSettingsSocialFields')) {



$obj = new UserSettingsSocialFields();



}