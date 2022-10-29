<?php
/*
Plugin Name: Tourist Tax Invoice
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Create booking list with an invoice for a tourist tax
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: tourist-tax-invoice
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class TouristTaxInvoice {

function __construct() {
	add_action('init', array($this, 'tti_start_from_here'));
	add_action('admin_enqueue_scripts', array($this,'tti_enqueue_script_dashboard'));
	 register_activation_hook(__FILE__ , array($this,'tti_plugin_activation') );
	add_action('init', array($this, 'tti_create_table'));
	
}



function tti_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/tti_booking_form.php';

	require_once plugin_dir_path(__FILE__) . 'back/tti_settings_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/tti_store_tourist_info.php';
	require_once plugin_dir_path(__FILE__) . 'back/tti_delete_tourist_info.php';
	require_once plugin_dir_path(__FILE__) . 'back/tti_generate_invoice.php';
	require_once plugin_dir_path(__FILE__) . 'back/tti_download_invoice.php';


}

// Enqueue Style and Scripts

function tti_enqueue_script_dashboard($hook) {
	//Style & Script


	if($hook == 'toplevel_page_tti_tourist_tax_invoice_settings' || $hook == 'kurtaxe_page_tti_reservations')
		{

			
			
			wp_enqueue_style('tti-google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto&display=swap');

			wp_enqueue_style('tti-datatables', 'https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css','1.11.3','all');

			wp_enqueue_style('tti-style', plugins_url('assets/css/tti.css', __FILE__),'1.0.0','all');
			wp_enqueue_style('tti-script-multi-form', plugins_url('assets/css/multi-form.css', __FILE__),'','all');

			wp_enqueue_style('anam-sweet','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.min.css',rand(0,1000),'all');

			wp_enqueue_script('anam-sweet-js','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.all.min.js',rand(0,1000),false);
			
			wp_enqueue_script('tti-script-validation', "https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js",array('jquery'),'1.17.0', true);
			wp_enqueue_media();
			
			wp_enqueue_script('tti-script-multi-form', plugins_url('assets/js/multi-form.js', __FILE__),array('jquery'),'', true);

			wp_enqueue_script('tti-script-dataTables', "https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js",array('jquery'),'1.11.3', true);
			
			wp_enqueue_script('tti-script', plugins_url('assets/js/tti.js', __FILE__),array('jquery'),'1.0.0', true);


			$zeroto9yearsMainSeason = get_option('0to9yearsMainSeason');
			$tento15yearsMainSeason = get_option('10to15yearsMainSeason');
			$sixteento99yearsMainSeason = get_option('16to99yearsMainSeason');

			$zeroto9yearsLowSeason = get_option('0to9yearsLowSeason');
			$tento15yearsLowSeason = get_option('10to15yearsLowSeason');
			$sixteento99yearsLowSeason = get_option('16to99yearsLowSeason');

			$uploaded_logo = esc_attr( get_option('uploaded_logo') );

			wp_localize_script( 'tti-script', 'tti_ajax_object',
		            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'zeroto9yearsMainSeason' => $zeroto9yearsMainSeason, 'tento15yearsMainSeason' => $tento15yearsMainSeason, 'sixteento99yearsMainSeason' => $sixteento99yearsMainSeason,  'zeroto9yearsLowSeason' => $zeroto9yearsLowSeason, 'tento15yearsLowSeason' => $tento15yearsLowSeason, 'sixteento99yearsLowSeason' => $sixteento99yearsLowSeason, 'uploaded_logo' => $uploaded_logo));

		}
}

function tti_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'tti_tourist_bookings';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          fullname varchar(50) NOT NULL,
          surname varchar(50) NOT NULL,
          address text NOT NULL,
          zipcode varchar(50) NOT NULL,
          city varchar(50) NOT NULL,
          number_of_person varchar(50) NOT NULL,
          invoice_id varchar(50) NOT NULL,
          collection text NOT NULL,
          created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }


  /*   	$sql = "DROP TABLE IF EXISTS $table_name";
    	$wpdb->query($sql);*/

}

function tti_plugin_activation(){

	$wp_upload_dir =  wp_upload_dir();
	$custom_upload_folder = $wp_upload_dir['basedir'].'/'."TTIPDF";
  	if(!is_dir($path)){
  		mkdir($custom_upload_folder);
  	}

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('TouristTaxInvoice')) {
	$obj = new TouristTaxInvoice();
}