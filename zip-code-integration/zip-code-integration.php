<?php
/*
Plugin Name: Zip Code Integration
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: Search for Zip Codes availability.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: zip-code-integration
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class ZipCodeIntegration {

function __construct() {
	add_action('init', array($this, 'zci_start_from_here'));
    add_action('init', array($this, 'zci_create_table'));
    add_action( "admin_enqueue_scripts", array($this,"zci_enqueue_script_dash"));
    add_action( "wp_enqueue_scripts", array($this,"zci_enqueue_script_front"));

}


function zci_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'zci_back/zci_zip_code_settings.php';
    require_once plugin_dir_path(__FILE__) . 'zci_back/zci_insert_zip_codes.php';
    require_once plugin_dir_path(__FILE__) . 'zci_back/zci_edit_zip_codes.php';
    require_once plugin_dir_path(__FILE__) . 'zci_back/zci_delete_zip_codes.php';
    require_once plugin_dir_path(__FILE__) . 'zci_front/zci_search_zip_codes_frontend.php';
    require_once plugin_dir_path(__FILE__) . 'zci_front/zci_search_zip_codes.php';
    
}

function zci_enqueue_script_front(){

//wp_enqueue_style('zci-style', plugins_url('assets/css/zci.css', __FILE__),'1.0.0','all');
wp_enqueue_script('zci-front-script',plugins_url('assets/js/zci_front.js', __FILE__),array('jquery'),'1.10.21', true);

wp_localize_script('zci-front-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

}


function zci_enqueue_script_dash() {
//Style & Script
wp_enqueue_style('zci-style', plugins_url('assets/css/zci.css', __FILE__),'1.0.0','all');

wp_enqueue_script('zci-script-dtb','https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',array('jquery'),'1.10.21', true);

wp_enqueue_style('zci-style-dtb', "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css",'1.10.21','all');

wp_enqueue_script('zci-script',plugins_url('assets/js/zci.js', __FILE__),array('jquery'),'1.0.0', true);

//wp_enqueue_script('fd-validate', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js', array('jquery'), '',true);

wp_localize_script('zci-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

}


function zci_create_table(){
    global $wpdb;
    $table_name = $wpdb->base_prefix.'zci_zip_codes';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          zip_code tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('ZipCodeIntegration')) {
	$obj = new ZipCodeIntegration();
}