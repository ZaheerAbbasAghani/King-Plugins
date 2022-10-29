<?php
/*
Plugin Name: Geris Garage
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Every user has a access to a list of products (created by admin with privce, name, foto) which they can buy (no direct payment required). No mails or any confimrations or invoices required. The user just sees a history of when he has bought something and sees his outstanding amount.
Admin has overview over all users and can edit/create his products. He can also reset the outstanding amount.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: geris-garage
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class GerisGarage {

function __construct() {
	add_action('init', array($this, 'gg_start_from_here'));
	add_action('wp_enqueue_scripts',array($this, 'gg_enqueue_script_front'));
	add_action('admin_enqueue_scripts',array($this, 'gg_enqueue_script_admin'));
	require_once plugin_dir_path(__FILE__) . 'back/gg_cpt.php';
	add_action('init', array($this, 'gg_create_table'));
}



function gg_start_from_here() {
	require_once plugin_dir_path(__FILE__) .'front/gg_product_list.php';
	require_once plugin_dir_path(__FILE__) .'front/gg_um_custom_tab.php';
	require_once plugin_dir_path(__FILE__) .'front/gg_buy_now_process.php';
	require_once plugin_dir_path(__FILE__) .'back/gg_reset_now_process.php';
}

// Enqueue Style and Scripts

function gg_enqueue_script_front() {
	//Style & Script

	wp_enqueue_style('gg-style-datatables', 'https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css','1.11.3','all');

	wp_enqueue_style('gg-style', plugins_url('assets/css/gg_style.css', __FILE__),rand(0,1000),'all');

	wp_enqueue_script('gg-dataTables', 'https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js',array('jquery'),'1.11.3', true);

	wp_enqueue_script('gg-dataTables-sum', 'https://cdn.datatables.net/plug-ins/1.11.3/api/sum().js',array('jquery'),'1.11.3', true);

	wp_enqueue_script('gg-jchart-script', "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js",array('jquery'),'3.5.1', false);
	

	wp_enqueue_script('gg-script', plugins_url('assets/js/gg_script.js', __FILE__),array('jquery'),'1.0.0', true);
	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script('gg-script','gg_ajax_object',
            array('ajax_url'=>admin_url('admin-ajax.php')));
}



function gg_enqueue_script_admin($hook) {

	if($hook != "products_page_gg_history")
		return 0;
	wp_enqueue_style('gg-style-datatables', 'https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css','1.11.3','all');


	wp_enqueue_style('gg-admin-style', plugins_url('assets/css/gg_style_admin.css', __FILE__),rand(0,1000),'all');

	wp_enqueue_script('gg-dataTables', 'https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js',array('jquery'),'1.11.3', true);

	wp_enqueue_script('gg-dataTables-sum', 'https://cdn.datatables.net/plug-ins/1.11.3/api/sum().js',array('jquery'),'1.11.3', true);

	

	wp_enqueue_script('gg-jchart-script', "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js",array('jquery'),'3.5.1', false);

	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('gg-admin-script', plugins_url('assets/js/gg_admin_script.js', __FILE__),array('jquery'),rand(0,1000), true);

	wp_localize_script('gg-admin-script','gg_ajax_object',
            array('ajax_url'=>admin_url('admin-ajax.php')));

}


function gg_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'gg_history';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          user_id int(100) NOT NULL,
          user_email varchar(100) NOT NULL,
          user_purchases varchar(100) NOT NULL,
          product_id int(100) NOT NULL,
          status varchar(50) NOT NULL,
          created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

   /* $sql  = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query( $sql );*/

}


//$time = current_time( 'mysql' );


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('GerisGarage')) {
	$obj = new GerisGarage();
}