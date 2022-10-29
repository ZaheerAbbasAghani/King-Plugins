<?php
/*
Plugin Name: Business Model Canvas
Plugin URI: http://wordpresswithzaheer.blogspot.com/
Description: Plug and Play Plugin Development. An admin can create different business model canvases to define the whole idea of his business.
Version: 1.0.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv2 or later
Text Domain: business-model-canvas
Domain Path: /languages
*/

defined("ABSPATH") or die('You can\t access!');

class BusinessModelCanvas {

function __construct(){
	add_action( 'admin_enqueue_scripts',  array($this ,'bmc_enqueue_script_admin' ));
	add_action( 'wp_enqueue_scripts',  array($this ,'bmc_enqueue_script_frontend' ));
	add_action( 'init', array($this,'bmc_start_from_here'));
    add_action('plugins_loaded', array($this, 'bmc_load_textdomain'));
}

function bmc_activate(){
	flush_rewrite_rules();
	//require_once plugin_dir_path( __FILE__ ).'/bmc-dashboard/bmc-canvas-table.php';

	$this->bmc_data_store();
}

function bmc_load_textdomain() {
	load_plugin_textdomain( 'bmc-lang',false,basename(dirname( __FILE__ ) ).'/languages/' ); 
}

function bmc_start_from_here(){
	require_once plugin_dir_path( __FILE__ ).'/bmc-dashboard/bmc-choose-canvases.php';
	require_once plugin_dir_path( __FILE__ ).'/bmc-dashboard/bmc-canvas-response.php';
	require_once plugin_dir_path( __FILE__ ).'/bmc-dashboard/bmc-delete-sticky-notes.php';
	require_once plugin_dir_path( __FILE__ ).'/bmc-dashboard/bmc-update-fields.php';
	require_once plugin_dir_path( __FILE__ ).'/bmc-dashboard/bmc-shortcode.php';
}


function bmc_enqueue_script_admin($hook) {
	//echo $hook;
	if ( 'toplevel_page_bmc_business_canvas_model' != $hook ) {
        return;
    }
    // Style 
    wp_enqueue_style( 'bmc-admin-style',plugins_url('bmc-dashboard/css/bmc_dashboard_style.css', __FILE__ ),'1.0.0',  'all' );

 	wp_enqueue_style( 'bmc-admin-bootstrap',plugins_url('bmc-dashboard/css/bootstrap.min.css', __FILE__ ),'','4.0.0',  'all' );
 	
	wp_enqueue_style( 'bmc-admin-fa',plugins_url('bmc-dashboard/font-awesome-4.7.0/css/font-awesome.min.css', __FILE__ ),'4.7.0',  'all' );

	wp_enqueue_script( 'bmc-front-scroll', plugins_url('bmc-dashboard/js/jquery.nicescroll.min.js', __FILE__ ),array( 'jquery' ), '3.7.0', true);

	wp_enqueue_script( 'mc-admin-bootstrap', plugins_url('bmc-dashboard/js/bootstrap.min.js', __FILE__ ),array( 'jquery' ), '4.0.0', true);

	wp_enqueue_script( 'bmc-dashboard-scroll', plugins_url('bmc-dashboard/js/jquery.nicescroll.min.js', __FILE__ ),array( 'jquery' ), '3.7.0', true);

	wp_enqueue_style( 'wp-color-picker' ); 
	wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_script( 'mc-admin-script', plugins_url('bmc-dashboard/js/bmc_dashboard_script.js', __FILE__ ), array( 'wp-color-picker' ),'1.0.0', true );

    wp_localize_script( 'mc-admin-script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('ajax_nonce') ) );


}



function bmc_enqueue_script_frontend() {
	
	wp_enqueue_style( 'frontend-bmc',plugins_url('bmc-dashboard/css/bmc_frontend_style.css', __FILE__ ),'', '1.0.0', 'all' );

	wp_enqueue_style( 'bmc-front-fa',plugins_url('bmc-dashboard/font-awesome-4.7.0/css/font-awesome.min.css', __FILE__ ),'4.7.0',  'all' );

	wp_enqueue_script( 'bmc-front-scroll', plugins_url('bmc-dashboard/js/jquery.nicescroll.min.js', __FILE__ ),array( 'jquery' ), '3.7.0', true);

	wp_enqueue_script( 'bmc-front',plugins_url('bmc-dashboard/js/bmc_frontend_script.js', __FILE__ ),array( 'jquery' ), '1.0.0', true);
	
}


function bmc_data_store(){

	if ( ! defined( 'ABSPATH' ) ) exit; // Direct access restricted
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'bmc_canvas_table';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			bmc_field varchar(10) NOT NULL,
			bmc_value varchar(255) NOT NULL,
			bmc_color varchar(15) NOT NULL,
			bmc_container varchar(50) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	}

} // end bmc_data_store



}

// CHECK WETHER CLASS EXISTS OR NOT.
if(class_exists('BusinessModelCanvas')){
	$obj = new BusinessModelCanvas();	
}

// on plugin activation
register_activation_hook( __FILE__, array($obj , 'bmc_activate') );