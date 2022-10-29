<?php

/*

Plugin Name: Ad Maker
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: The Plugin provides capability to create a form with drag and drop options. 	
and show AD form to create ads, send email, display ad. 
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: ad-maker
Domain Path: /languages

*/



defined("ABSPATH") or die("No direct access!");

class AdMaker {


function __construct() {

	add_action('init', array($this, 'adm_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'adm_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'adm_enqueue_admin'));
	add_action('init', array($this, 'adm_create_fields_table'));    
	add_action('init', array($this, 'adm_submitted_data_table'));    
	add_filter( 'wp_mail_from_name',array($this, 'adm_sender_name') );
	register_activation_hook( __FILE__, array($this,'adm_plugin_activate') );
	register_deactivation_hook(__FILE__,array($this,'my_plugin_deactivate'));
	add_action( 'adm_remove_ads_hook', array($this,'adm_ad_delete_process'));
}


function adm_start_from_here() {

	require_once plugin_dir_path(__FILE__)	. 'back/adm_check_fields_availability.php';
	require_once plugin_dir_path(__FILE__) 	. 'back/adm_store_form_data.php';
	require_once plugin_dir_path(__FILE__) 	. 'back/adm_remove_field_db.php';
	require_once plugin_dir_path(__FILE__) 	. 'back/adm_options_page.php';
	require_once plugin_dir_path(__FILE__) 	. 'back/adm_update_column_db.php';
	require_once plugin_dir_path(__FILE__) 	. 'back/adm_update_column_db.php';
	require_once plugin_dir_path(__FILE__) 	. 'back/adm_remove_this_ad.php';

	require_once plugin_dir_path(__FILE__) . 'front/adm_ad_maker_form.php';
	require_once plugin_dir_path(__FILE__) . 'front/adm_submit_form.php';
	require_once plugin_dir_path(__FILE__) . 'front/adm_delete_ad.php';
	require_once plugin_dir_path(__FILE__) . 'front/adm_ad_display.php';


}


// Register plugin activation hook
function adm_plugin_activate() {
    if( !wp_next_scheduled( 'adm_remove_ads_hook' ) ) {  
       wp_schedule_event( time(), 'daily', array($this,'adm_remove_ads_hook') );  
    }
}

// Register plugin deactivation hook
function my_plugin_deactivate(){
    wp_clear_scheduled_hook('adm_remove_ads_hook');
}


// Function I want to run when cron event runs
function adm_ad_delete_process(){
    //Function code

	global $wpdb;
	$table_name = $wpdb->base_prefix.'adm_submitted_data_table';


	$query = "SELECT * FROM $table_name WHERE pickup_date < CURDATE() ORDER BY pickup_date DESC";
	$query_results = $wpdb->get_results($query);
	foreach ($query_results as $result) {
		  $wpdb->delete( $table_name, array( 'id' => $result->id ) );
	}

}



function adm_enqueue_admin($hook){

	if($hook != "toplevel_page_adm_ad_maker")

			return 0;

		wp_enqueue_script("jquery-ui");
		wp_enqueue_script("jquery-ui-sortable");
		wp_enqueue_script("jquery-ui-tabs");

		wp_enqueue_script('adm-form-builder', 'https://formbuilder.online/assets/js/form-builder.min.js',array('jquery'),'3.8.2', true);

		wp_enqueue_style('gft-toastr', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css','2.1.3','all');

		wp_enqueue_style('gft-datatables', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css','1.12.1','all');

		wp_enqueue_script('gft-datatables', "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js",array('jquery'),'1.12.1', true);

		wp_enqueue_script('gft-toastr', "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js",array('jquery'),'2.1.3', true);

		wp_enqueue_script('adm-form-render', 'https://formbuilder.online/assets/js/form-render.min.js',array('jquery'),'3.8.2', true);
		wp_enqueue_script('adm-script-admin', plugins_url('assets/js/adm-script-admin.js', __FILE__),array('jquery'),'1.0.0', true);
		wp_localize_script('adm-script-admin', 'adm_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

}



// Enqueue Style and Scripts

function adm_enqueue_script_front() {

	//Style & Script

	wp_enqueue_style('adm-style', plugins_url('assets/css/adm.css', __FILE__),'1.0.0','all');
	wp_enqueue_style('adm-toastr', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css','2.1.3','all');
	wp_enqueue_style('adm-datatables', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css','1.12.1','all');

	wp_enqueue_script('adm-script-validate','https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js',array('jquery'),'1.19.5', true);

	wp_enqueue_script('gft-toastr', "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js",array('jquery'),'2.1.3', true);
	wp_enqueue_script('gft-datatables', "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js",array('jquery'),'1.12.1', true);
	$apikey = get_option('adm_site_key');
	
	wp_enqueue_script('adm-recaptcha', "https://www.google.com/recaptcha/api.js");
	wp_enqueue_script('adm-script',plugins_url('assets/js/adm-script.js', __FILE__),array('jquery'),'1.0.0', true);
	wp_localize_script('adm-script','adm_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

	

}


function adm_create_fields_table(){

    global $wpdb;

    $table_name = $wpdb->base_prefix.'adm_fields_table';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (

          id mediumint(255) NOT NULL AUTO_INCREMENT,
          type text NOT NULL,
          required text NOT NULL,
          label text NOT NULL,
          subtype text NOT NULL,
          inline text NOT NULL,
          name text NOT NULL,
          className text NOT NULL,
          multiple text NOT NULL,
          valuess text NOT NULL,
          selected text NOT NULL,
          placeholder text NOT NULL,
          position int(50) NOT NULL,
          PRIMARY KEY  (id)

        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
}

function adm_submitted_data_table(){
    global $wpdb;

    $table_name = $wpdb->base_prefix.'adm_submitted_data_table';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          radom_code text NOT NULL,
          formData text NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}
 
// Function to change sender name
function adm_sender_name( $original_email_from ) {
    return get_bloginfo( 'name' );
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('AdMaker')) {
	$obj = new AdMaker();
}