<?php
/*
Plugin Name: Leave Management System
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: A section where users can submit time off and it must be approved by admin. Admin would also get notified via e mail. Then when approved it shows that user has time off those days or hours on calendar.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: leave-management-system
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class LeaveManagementSystem {

function __construct() {
	add_action('init', array($this, 'lms_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'lms_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'lms_enqueue_admin'));
	add_action('init', array($this, 'lms_create_table'));
	add_filter('wp_mail_content_type',array($this,  'lms_set_content_type') );
	
}



function lms_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/lms_off_form.php';
	require_once plugin_dir_path(__FILE__) . 'front/lms_create_leave_record.php';
	
	require_once plugin_dir_path(__FILE__) . 'back/lms_options_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/lms_update_leave_status.php';
	require_once plugin_dir_path(__FILE__) . 'back/lms_delete_leave_request.php';
	
}

// Enqueue Style and Scripts

function lms_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('lms-daterangepicker', plugins_url('assets/css/daterangepicker.css', __FILE__),'0.16.2','all');

	wp_enqueue_style('lms-full-calendar',plugins_url('assets/lib/main.css', __FILE__),'2.3.2','all');

	wp_enqueue_style('lms-style', plugins_url('assets/css/lms.css', __FILE__),'1.0.0','all');
	

	wp_enqueue_script('lms-moment', plugins_url('assets/js/moment.js', __FILE__),array('jquery'),'2.29.1', true);
	wp_enqueue_script('lms-daterangepicker', plugins_url('assets/js/
		jquery.daterangepicker.min.js', __FILE__),array('jquery'),'0.21.1', true);

	
	wp_enqueue_script('lms-full-calendar', plugins_url('assets/lib/main.js', __FILE__),array('jquery'),'5.10.2', true);
	
	wp_enqueue_script('lms-script', plugins_url('assets/js/lms.js', __FILE__),array('jquery'),'1.0.0', true);

	global $wpdb; // this is how you get access to the database
	$user_id = get_current_user_id();
    $table_name = $wpdb->base_prefix.'lms_records';
    $query = "SELECT * FROM $table_name WHERE user_id='$user_id'";
    $query_results = $wpdb->get_results($query);

    foreach ($query_results as $result) {

        $user = get_user_by( "id", $result->user_id);
        $pieces = explode("~", $result->lms_date);

        $startDateTime = $pieces[0];
        $endDateTime = $pieces[1];

        $pieces2 = explode(" ", $startDateTime);
        $pieces3 = explode(" ", $endDateTime);

        $leave_data[] = array(
            'title' => "Leave Request of ".$user->display_name,
            'start' => $pieces2[0],
            'end' => $pieces3[1],
        );
    }


	wp_localize_script( 'lms-script', 'lms_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), "leave_data" => $leave_data));
}

function lms_enqueue_admin($hook){

	if($hook != "toplevel_page_lms_time_off_settings")
		return 0;

	wp_enqueue_script('lms-moment', plugins_url('assets/js/moment.js', __FILE__),array('jquery'),'2.29.1', true);
	
	wp_enqueue_style('lms-datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css','1.11.5','all');

	wp_enqueue_style('lms-full-calendar',plugins_url('assets/lib/main.css', __FILE__),'2.3.2','all');
	
	wp_enqueue_script('lms-dataTables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',array('jquery'),'1.11.5', true);

	wp_enqueue_script("jquery-ui-tabs");

	wp_enqueue_script('lms-full-calendar', plugins_url('assets/lib/main.js', __FILE__),array('jquery'),'5.10.2', true);

	wp_enqueue_script('lms-script-admin', plugins_url('assets/js/lms_admin.js', __FILE__),array('jquery'),'1.0.0', true);


	wp_localize_script( 'lms-script-admin', 'lms_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));

}


function lms_set_content_type(){
    return "text/html";
}



function lms_create_table(){
	//current_time( 'mysql' )
    global $wpdb;
    $table_name = $wpdb->base_prefix.'lms_records';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          user_id int(10) NOT NULL,
          lms_message text NOT NULL,
          lms_date varchar(100) NOT NULL,
          lms_status int(2) NOT NULL,
          created_at datetime NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

/*
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);*/

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('LeaveManagementSystem')) {
	$obj = new LeaveManagementSystem();
}