<?php
/*
Plugin Name: Call Manager
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Plivio integration for voice, sms, call forwarding. Sms messaging center with ability for user to grant access to other users, and for the plugin to generate a new number when ordered.
New phone number would be based on area code entered in the order. So it would be accessible from woocommerce order table
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: call-manager
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");

class CallManager {

function __construct() {
	add_action('init', array($this, 'cm_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'cm_enqueue_script_front'));
	add_action( 'woocommerce_checkout_update_order_meta',array($this, 'cm_add_order_area_code_to_order'), 10, 1);
    add_action('init', array($this,'cm_create_table'));
}



function cm_start_from_here() {


	require_once plugin_dir_path(__FILE__) . 'back/cm_settings_page.php';
	require_once plugin_dir_path(__FILE__) . 'front/cm_store_info_while_order.php';
	require_once plugin_dir_path(__FILE__) . 'front/cm_custom_woo_tab.php';
	require_once plugin_dir_path(__FILE__) . 'front/cm_send_message.php';

}

// Enqueue Style and Scripts

function cm_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('cm-style', plugins_url('assets/css/cm.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('cm-script', plugins_url('assets/js/cm.js', __FILE__),array('jquery'),'1.0.0', true);

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'cm-script', 'cm_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )));


}




function cm_add_order_area_code_to_order ( $order_id ) {

	if ( isset( $_POST ['add_area_code'] ) &&  '' != $_POST ['add_area_code'] ) {
		add_post_meta($order_id,'_area_code',  sanitize_text_field($_POST['add_area_code']));
	}
}

function cm_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'cm_messages_per_user';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name));
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          sender_number varchar(15) NOT NULL,
          receiver_number varchar(15) NOT NULL,
          message text NOT NULL,
          created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
    
    /*$sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query( $sql );*/
    

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('CallManager')) {
	$obj = new CallManager();
}