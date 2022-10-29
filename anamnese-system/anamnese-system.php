<?php
/*
Plugin Name: Anamnese System
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Doctor patient system crm.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: anamnese-system
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class AnamneseSystem {

function __construct() {
  register_activation_hook(__FILE__ , array($this,'anam_plugin_activation') );
	add_action('init', array($this, 'anam_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'anam_enqueue_script_front'));
	add_action('after_setup_theme',array($this,'anam_admin_bar'));
	add_action('init',array($this,'anam_restrict_wpadmin'));
	add_action('init', array($this, 'anam_create_table'));
  	add_filter('the_content',array($this,'my_replace_content'),100);

}



function anam_start_from_here() {

    require_once plugin_dir_path(__FILE__) . 'back/anam_role_maker.php';
    require_once plugin_dir_path(__FILE__) . 'back/anam_useful_functions.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_my_account.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_create_new_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_remove_customer.php';
  
    require_once plugin_dir_path(__FILE__) . 'front/anam_preview_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_edit_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_update_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_search_customer.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_create_new_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_insert_new_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_customer_side_info.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_edit_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_update_document.php';
    require_once plugin_dir_path(__FILE__) . 'front/anam_preview_document.php';
   //require_once plugin_dir_path(__FILE__) . 'front/anam_view_images.php';
  	
}



// Enqueue Style and Scripts

function anam_enqueue_script_front() {
//Style & Script
	wp_enqueue_style('anam-style', plugins_url('assets/css/anam.css', __FILE__),rand(0,1000),'all');
	wp_enqueue_style('anam-fontawesome','https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css','5.15.3','all');
	wp_enqueue_style('anam-sweet','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.min.css',rand(0,1000),'all');
  wp_enqueue_style('anam-magnifier-popup', plugins_url('vendor/magnific-popup.css', __FILE__),'2.8.0','all');


	wp_enqueue_script('anam-magnific-lightboxjs', plugins_url('vendor/jquery.magnific-popup.js', __FILE__),array('jquery'),'2.8.0',false);

	wp_enqueue_script('anam-sweet-js','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.all.min.js',rand(0,1000),false);



  //wp_enqueue_script('anam-simple-lightbox-legacyjs', plugins_url('vendor/simple-lightbox.jquery.min.js',__FILE__),array('jquery'),'2.8.0',false);

	wp_enqueue_script('anam-script', plugins_url('assets/js/anam.js', __FILE__),array('jquery'),rand(0,1000), false);
	wp_localize_script( 'anam-script', 'anam_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	
	
}


function anam_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
      show_admin_bar(false);
    }
}

function anam_restrict_wpadmin(){
	  if( is_admin() && !defined('DOING_AJAX') && ( current_user_can('doctor') || current_user_can('subscriber') ) ){
	    wp_redirect(home_url().'/my-account');
	    exit;
	  }
}

function anam_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_customer_info';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          first_name varchar(20) NOT NULL,
          last_name varchar(20) NOT NULL,
          email_address varchar(30) NOT NULL,
          gender varchar(10) NOT NULL,
          birth_date varchar(20) NOT NULL,
          mobile_no varchar(15) NOT NULL,
          job varchar(50) NOT NULL,
          city varchar(20) NOT NULL,
          zipcode varchar(10) NOT NULL,
          fad varchar(15) NOT NULL,
          address text NOT NULL,
          doctor_name varchar(30) NOT NULL,
          diagnosis varchar(20) NOT NULL,
          phone_of_doctor varchar(15) NOT NULL,
          drugs varchar(30) NOT NULL,
          insurance_company varchar(30) NOT NULL,
          important_notes text NOT NULL,
          doctor_id varchar(20) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }



  	$table_name1 = $wpdb->base_prefix.'anam_document_info';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name1 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name1 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name1 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          senkfuss varchar(10) NOT NULL,
          spreizfuss  varchar(10) NOT NULL,
          knickfuss_nach_innen varchar(10) NOT NULL,
          Knickfuss_nach_aussen varchar(10) NOT NULL,
          hohlfuss varchar(10) NOT NULL,
          plattfuss  varchar(10) NOT NULL,
          fusschwellung  varchar(10) NOT NULL,
          other_foot_deformation varchar(10) NOT NULL,
          oberschenkel varchar(10) NOT NULL,
          unterschenkel varchar(10) NOT NULL,
          konfektion  varchar(50) NOT NULL,
          nach_mass  varchar(50) NOT NULL,
          risks varchar(100) NOT NULL,
          infektionskrankheiten  varchar(50) NOT NULL,
          findings varchar(100) NOT NULL,
          wunden varchar(50) NOT NULL,
          huhneraugen_auf_zehen varchar(10) NOT NULL,
          hammerzehen varchar(10) NOT NULL,
          nagelpilz varchar(10) NOT NULL,
          eingewachsene_nagel varchar(10) NOT NULL,
          zustand_de_nagel varchar(50) NOT NULL,
          user_id int(10) NOT NULL,
          doctor_id varchar(10) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

/*global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS $table_name1 " );*/

/*global $wpdb;
$mytables=$wpdb->get_results("SHOW TABLES");
foreach ($mytables as $mytable)
{
    foreach ($mytable as $t) 
    {       
        echo $t . "<br>";
    }
}*/

/*$existing_columns = $wpdb->get_col("DESC {$table_name}", 0);
$sql = implode( ', ', $existing_columns );
print_r($sql);*/


}



function anam_plugin_activation() {
  
    if ( ! current_user_can( 'activate_plugins' ) ) return;
    global $wpdb;
    if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'customer-page'", 'ARRAY_A' ) ) {
       
      $current_user = wp_get_current_user();
      // create post object
      $page = array(
        'post_title'  => __('Customer Page'),
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
        'post_type'   => 'page',
      );
      
      // insert the post into the database
      wp_insert_post( $page );
    }


    $wp_upload_dir =  wp_upload_dir();
	$custom_upload_folder = $wp_upload_dir['basedir'].'/'."anam_users";
	if(!is_dir($path)){
		mkdir($custom_upload_folder);
	}

}

function my_replace_content ($content) {
    $page = get_page_by_path('customer-page', OBJECT );
    if ( is_page($page) ){
        $content = do_shortcode("[customer_info]");
    }

    return $content;
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('AnamneseSystem')) {
	$obj = new AnamneseSystem();
}