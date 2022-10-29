<?php
/*
Plugin Name: Tunning Calculator
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Show selected make,mode,trim,engine information
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: tunning-calculator
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class TunningCalculator {

function __construct() {
	add_action('init', array($this, 'tcl_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'tcl_enqueue_script_front'));
	add_action('init', array($this, 'tcl_create_table'));

  add_action( 'admin_enqueue_scripts', array($this,  'tcl_enqueue_color_picker') );
	
}



function tcl_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/tcl_file_uploader_setting.php';
  	require_once plugin_dir_path(__FILE__) . 'front/tcl_tunning_details.php';
  	require_once plugin_dir_path(__FILE__) . 'front/tcl_selected_model.php';
  	require_once plugin_dir_path(__FILE__) . 'front/tcl_selected_trim.php';
  	require_once plugin_dir_path(__FILE__) . 'front/tcl_start_your_engine.php';
    require_once plugin_dir_path(__FILE__) . 'back/tcl_delete_database_records.php';

}

// Enqueue Style and Scripts

function tcl_enqueue_script_front() {
//Style & Script

	wp_enqueue_style('tcl-style', plugins_url('assets/css/tcl.css', __FILE__),'1.0.0','all');

  wp_enqueue_script('tcl-raphael', 'http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js',array('jquery'),'2.1.2', true);

   wp_enqueue_script('tcl-kuma-gauge',  plugins_url('assets/js/kuma-gauge.jquery.min.js', __FILE__),array('jquery'),'0.2', true);

	wp_enqueue_script('tcl-script', plugins_url('assets/js/tcl.js', __FILE__),array('jquery'),'1.0.0', true);

  wp_localize_script( 'tcl-script', 'tcl_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php'), "max_limit" => get_option("max_limit"), "tcl_button_text" => get_option("tcl_button_text"), "tcl_gauge_background" => get_option("tcl_gauge_background"),"tcl_background" => get_option("tcl_background"),"tcl_background_fill" => get_option("tcl_background_fill"),"max_limit" => get_option("max_limit"),"tcl_power_labels" => get_option("tcl_power_labels"),"tcl_torque_labels" => get_option("tcl_torque_labels")
          ));

}


function tcl_enqueue_color_picker( $hook) {
   //echo $hook;
  if("toplevel_page_tunning_calculator" != $hook)
    return;

  if(function_exists( 'wp_enqueue_media' )){
      wp_enqueue_media();
  }else{
      wp_enqueue_style('thickbox');
      wp_enqueue_script('media-upload');
      wp_enqueue_script('thickbox');
  }

  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( 'tcl-admin-handle', plugins_url('assets/js/tcl_admin.js', __FILE__), array( 'wp-color-picker' ), false, true );
  wp_localize_script( 'tcl-admin-handle', 'tcl_object',
          array( 'ajax_url' => admin_url( 'admin-ajax.php')));
    
    
}


function tcl_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          make varchar(50) NOT NULL,
          model varchar(50) NOT NULL,
          trrim varchar(50) NOT NULL,
          stock_power int(20) NOT NULL,
          stock_torque int(20) NOT NULL,
          stage_1_power int(20) NOT NULL,
          stage_1_torque int(20) NOT NULL,	
          stage_2_power int(20) NOT NULL,
          stage_2_torque int(20) NOT NULL,
          stage_1_price int(20) NOT NULL,
          stage_2_price int(20) NOT NULL,
          stage1img text NOT NULL,
          stage2img text NOT NULL,
          learnmoreUrl text NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('TunningCalculator')) {
	$obj = new TunningCalculator();
}