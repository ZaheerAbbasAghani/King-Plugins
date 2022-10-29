<?php
/*
Plugin Name: Easy Apply
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: This plugin to allow users to apply for jobs on my website
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: easy-apply
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class EasyApply {

function __construct() {
	add_action('init', array($this, 'ea_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'ea_enqueue_script_front'));
	add_action('init', array($this, 'ea_create_table'));
	register_activation_hook(__FILE__ , array($this,'ea_plugin_activation') );
	add_action('admin_enqueue_scripts', array($this, 'ea_enqueue_script_admin'));

}



function ea_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/ea-job-board-table.php';
	require_once plugin_dir_path(__FILE__) . 'front/ea_view_jobs.php';
	require_once plugin_dir_path(__FILE__) . 'front/ea_apply_form.php';
	require_once plugin_dir_path(__FILE__) . 'front/ea_insert_application.php';
	require_once plugin_dir_path(__FILE__) . 'front/ea_show_full_list.php';
	require_once plugin_dir_path(__FILE__) . 'back/ea_delete_appliedfor.php';
}

function ea_plugin_activation() {
    $wp_upload_dir =  wp_upload_dir();
	$custom_upload_folder = $wp_upload_dir['basedir'].'/'."cv";
  	if(!is_dir($path)){
  		mkdir($custom_upload_folder);
  	}
}


// Enqueue Style and Scripts

function ea_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('ae-sweet','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.min.css','11.1.2','all');
	wp_enqueue_style('ae-sweet-animte','https://cdn.jsdelivr.net/npm/animate.css@3/animate.min.css','','all');
	
	wp_enqueue_style('ae-style', plugins_url('assets/css/ea.css', __FILE__),'1.0.0','all');

	wp_enqueue_script('ea-sweet-js','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.all.min.js','11.1.2',false);

	wp_enqueue_script('ea-script',plugins_url('assets/js/ea.js', __FILE__),array('jquery'),'1.0.0',false);

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'ea-script', 'ae_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}

function ea_enqueue_script_admin($hook){

	if($hook != 'v_job_board_page_ea_apply_information')	
		return 0;

	wp_enqueue_style('ae-datatable','https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css','1.11.3','all');

	wp_enqueue_script('ae-datatable-js','https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', array('jquery'),'1.11.3',false);

	wp_enqueue_script('ae-script-admin',plugins_url('assets/js/ea_admin.js', __FILE__), array('jquery'),'1.11.3',false);

	wp_localize_script( 'ae-script-admin', 'ae_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

}


function ea_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'ea_apply';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          your_name varchar(20) NOT NULL,
          your_phone varchar(20) NOT NULL,
          your_email varchar(30) NOT NULL,
          additional_comments varchar(10) NOT NULL,
          appliedfor varchar(50) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('EasyApply')) {
	$obj = new EasyApply();
	require_once plugin_dir_path(__FILE__) . 'back/ea-custom-post-type.php';
}