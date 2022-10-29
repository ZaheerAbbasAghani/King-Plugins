<?php
/*
Plugin Name: Refer to friend
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: When a customer hit the "Share with your friend" button, the form should pop-up. Pop-up artwork is attached. In the attached design, The background is just to show the transparency of the pop-up (you can skip that).We must be able to customize pop-up form colors, font, title, label text, button text, placeholder text, and so on... We also would like to have an option to add a dropdown and checkbox we will need in the near future. We must be able to see the data from the backend and be able to easily export the data to an excel file, whenever we want.The data must be separate for each page on the backend so we know what page or mask is shared to that page or person...so it corresponds correctly. If the form is submitted on the gocats page we must know that or any different page created. there will be different submissions from different pages, for example, the links above, faith page, go cats page, and there will be more in the future so we must know what page is shared and we also should track how many forms are on a particular page submitted.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: refer-to-friend
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class ReferToFriend {

function __construct() {
	add_action('init', array($this, 'rtf_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'rtf_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'rtf_enqueue_script_admin'));
	add_action('init', array($this, 'rtf_create_table'));
	
}



function rtf_start_from_here() {
	
	require_once plugin_dir_path(__FILE__) . 'front/rtf_refer_to_friend_btn.php';
	require_once plugin_dir_path(__FILE__) . 'front/rtf_refer_to_friend_process.php';
	require_once plugin_dir_path(__FILE__) . 'front/rtf_insert_new_entry.php';

	require_once plugin_dir_path(__FILE__) . 'back/rtf_options_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/rtf_create_form_new_field.php';
	require_once plugin_dir_path(__FILE__) . 'back/rtf_insert_field.php';
	require_once plugin_dir_path(__FILE__) . 'back/rtf_edit_field.php';
	require_once plugin_dir_path(__FILE__) . 'back/rtf_delete_field.php';
	require_once plugin_dir_path(__FILE__) . 'back/rtf_drag_drop_fields.php';

}

// Enqueue Style and Scripts

function rtf_enqueue_script_front() {
	//Style & Script
	

	wp_enqueue_style('rtf-style-sweetalert', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.10/sweetalert2.min.css','11.3.10','all');

	wp_enqueue_style('rtf-style', plugins_url('assets/css/rtf.css', __FILE__),rand(0,1000),'all');

	wp_enqueue_script('rtf-sweetalert','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.10/sweetalert2.all.min.js',array('jquery'),'11.3.10',true);

	wp_enqueue_script('rtf-script', plugins_url('assets/js/rtf.js', __FILE__),array('jquery'),rand(0,1000), true);

	$title = esc_attr(get_option('rtf_form_title') ); 
	//$bkImage  = esc_attr(get_option('rtf_upload_logo_url'));
	$bgColor = esc_attr(get_option('rtf_background_color'));
	$txtColor = esc_attr(get_option('rtf_text_color'));
	$current_page_id = get_the_ID();
	$popup_width = esc_attr(get_option('rtf_popup_width'));
	$opacity = esc_attr(get_option('rtf_opacity'));
	

	wp_localize_script('rtf-script', 'rtf_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'title' => $title, 'bgColor' => $bgColor, 'txtColor' => $txtColor, 'page_id' => $current_page_id, 'popup_width' => $popup_width, 'opacity' => $opacity, "height" => $height, "width" => $width ));

}


// Enqueue Style and Scripts Admin

function rtf_enqueue_script_admin($hook) {
	//Style & Script

		
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script("jquery-ui-draggable");
	
	wp_enqueue_style('rtf-style-admin', plugins_url('assets/css/rtf_admin.css', __FILE__),rand(0,1000),'all');

	wp_enqueue_style('rtf-style-sweetalert', 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.10/sweetalert2.min.css','11.3.10','all');

	wp_enqueue_script('rtf-sweetalert','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.10/sweetalert2.all.min.js',array('jquery'),'11.3.10',true);

	wp_enqueue_style('rtf-style-datatable', 'https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css','1.11.4','all');

	wp_enqueue_script('rtf-datatable','https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js',array('jquery'),'1.11.4',true);

	wp_enqueue_script('rtf-button','https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js',array('jquery'),'1.11.4',true);

	wp_enqueue_script('rtf-jszip','https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',array('jquery'),'1.11.4',true);

	wp_enqueue_script('rtf-pdfmake','https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',array('jquery'),'0.1.53',true);

	wp_enqueue_script('rtf-pdfmake-2','https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',array('jquery'),'0.1.53',true);

	wp_enqueue_script('rtf-buttons-html5','https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js',array('jquery'),'2.2.2',true);

	wp_enqueue_script('rtf-buttons-print','https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js',array('jquery'),'2.2.2',true);
		

	

	
	wp_enqueue_script('rtf-admin-script', plugins_url('assets/js/rtf_admin.js', __FILE__),array('jquery','wp-color-picker'),rand(0,1000), true);

	wp_localize_script('rtf-admin-script', 'rtf_ajax_admin_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )));


}


function rtf_create_table(){
	   global $wpdb;
	$table_name3 = $wpdb->base_prefix.'rtf_fields_maker';
	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name3 ) );
	if ( ! $wpdb->get_var( $query ) == $table_name3 ) {

	    $charset_collate = $wpdb->get_charset_collate();
	    $sql = "CREATE TABLE $table_name3 (
	      id mediumint(255) NOT NULL AUTO_INCREMENT,
	      label varchar(100) NOT NULL,
	      fieldtype text NOT NULL,
	      fname varchar(100) NOT NULL,
	      fvalue text NOT NULL,
	      fplaceholder varchar(100) NOT NULL,
	      forder int(100) NOT NULL,
	      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	      PRIMARY KEY  (id)
	    ) $charset_collate;";
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );

	}


	$table_name1 = $wpdb->base_prefix.'rtf_store_info';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name1 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name1 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name1 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          page_id_field varchar(50) NOT NULL,
          created_at_field TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('ReferToFriend')) {
	$obj = new ReferToFriend();
}