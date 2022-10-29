<?php
/*
Plugin Name: Virtuoso Scheduling
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description:  Employees to be notified and request to fill a work time slot. Would need to allow for text notification and e mail notification. Guest users would be allowed as well through invite system. This would also be where they would put that they need coverage because they're unavailable which should notify all relevant employees that can provide coverage.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: virtuoso-scheduling
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class VirtuosoScheduling {

function __construct() {
	register_activation_hook( __FILE__, array($this, 'vir_create_employee_role') );
	add_action('init', array($this, 'vir_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'vir_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'vir_enqueue_script_admin'));
	
}

function vir_create_employee_role() { 
	add_role('vir_employee', __(
	   'Employee'),
	    array(
	       'read' => true,
	       'level_0' => true,
	       'manage_options'=> false,
	    )
	);

	global $wpdb;
    $table_name = $wpdb->base_prefix.'vir_scheules';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          user_id int(10) NOT NULL,
          vir_message text NOT NULL,
          vir_date varchar(100) NOT NULL,
          phone varchar(20) NOT NULL,
          vir_status int(2) NOT NULL,
          created_at datetime NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}



function vir_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/vir_options_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/vir_create_schedule.php';
	require_once plugin_dir_path(__FILE__) . 'back/vir_get_user_email.php';
	require_once plugin_dir_path(__FILE__) . 'back/vir_delete_row.php';
	require_once plugin_dir_path(__FILE__) . 'back/vir_deny_schedule_request.php';
	require_once plugin_dir_path(__FILE__) . 'back/vir_cover_schedule_request.php';

	require_once plugin_dir_path(__FILE__).'front/vir_create_schedule_frontend.php';

	//remove_role( 'vir_employee' );

/*	global $wpdb;
    $table_name = $wpdb->base_prefix.'vir_scheules';
	$sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);*/

}

// Enqueue Style and Scripts

function vir_enqueue_script_front() {
	//Style & Script


	wp_enqueue_style('lms-daterangepicker', plugins_url('assets/css/daterangepicker.css', __FILE__),'0.16.2','all');

	wp_enqueue_style('vir-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css','4.1.3','all');

	wp_enqueue_style('vir-custom', plugins_url('assets/css/vir.css', __FILE__),'1.0.0','all');

	wp_enqueue_style('vir-full-calendar',plugins_url('assets/lib/main.css', __FILE__),'2.3.2','all');

	wp_enqueue_style('vir-jqueryui','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/cupertino/jquery-ui.css','1.8.13','all');

	wp_enqueue_script('vir-moment', plugins_url('assets/js/moment.js', __FILE__),array('jquery'),'2.29.1', true);
	

	wp_enqueue_script('lms-daterangepicker', plugins_url('assets/js/
		jquery.daterangepicker.min.js', __FILE__),array('jquery'),'0.21.1', true);

	wp_enqueue_script('vir-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js',array('jquery'),'4.1.3', true);

	
	//wp_enqueue_script('vir-dataTables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',array('jquery'),'1.11.5', true);

	wp_enqueue_script('vir-full-calendar', plugins_url('assets/lib/main.js', __FILE__),array('jquery'),'5.10.2', true);

	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script("jquery-ui-autocomplete");

	wp_enqueue_script('vir-script-admin', plugins_url('assets/js/vir_admin.js', __FILE__),array('jquery'),'1.0.0', true);

	if(current_user_can('administrator') ){
		$employees = get_users( array( 'role__in' => array( 'vir_employee' ) ) );
		
		$employee = array();
		foreach ($employees as $key => $value) {
			array_push($employee, $value->display_name);	
		}
	}else{
		$employee = array();
	}

	global $wpdb; // this is how you get access to the database
	$table_name = $wpdb->base_prefix.'vir_scheules';
	$query = "SELECT * FROM $table_name";
	$query_results = $wpdb->get_results($query);

	foreach ($query_results as $result) {

	    $user = get_user_by( "id", $result->user_id);
	    $pieces = explode("to", $result->vir_date);
	    $status = $result->vir_status;

	    $startDateTime = trim($pieces[0]);
	    $endDateTime = trim($pieces[1]);

	    $scheduled_requests[] = array(
	        'title' => "Scheduled Request of ".$user->display_name,
	        'start' => str_replace(' ', 'T', $startDateTime),
	        'end' => str_replace(' ', 'T', $endDateTime),
	        'status' => $status,
	    );
	}
	
	wp_localize_script( 'vir-script-admin', 'vir_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), "employees" =>$employee, "Scheduled" => $scheduled_requests ));


}


// Enqueue Style and Scripts

function vir_enqueue_script_admin($hook) {
	//Style & Script

	if($hook != "toplevel_page_vir_settings_page")
		return 0;

	wp_enqueue_style('lms-daterangepicker', plugins_url('assets/css/daterangepicker.css', __FILE__),'0.16.2','all');

	wp_enqueue_style('vir-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css','4.1.3','all');

	//wp_enqueue_style('vir-datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css','1.11.5','all');

	wp_enqueue_style('vir-full-calendar',plugins_url('assets/lib/main.css', __FILE__),'2.3.2','all');

	wp_enqueue_style('vir-jqueryui','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/cupertino/jquery-ui.css','1.8.13','all');

	wp_enqueue_script('vir-moment', plugins_url('assets/js/moment.js', __FILE__),array('jquery'),'2.29.1', true);
	

	wp_enqueue_script('lms-daterangepicker', plugins_url('assets/js/
		jquery.daterangepicker.min.js', __FILE__),array('jquery'),'0.21.1', true);

	wp_enqueue_script('vir-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js',array('jquery'),'4.1.3', true);

	
	//wp_enqueue_script('vir-dataTables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',array('jquery'),'1.11.5', true);

	wp_enqueue_script('vir-full-calendar', plugins_url('assets/lib/main.js', __FILE__),array('jquery'),'5.10.2', true);

	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script("jquery-ui-autocomplete");

	wp_enqueue_script('vir-script-admin', plugins_url('assets/js/vir_admin.js', __FILE__),array('jquery'),'1.0.0', true);

	if(current_user_can('administrator') ){
		$employees = get_users( array( 'role__in' => array( 'vir_employee' ) ) );
		
		$employee = array();
		foreach ($employees as $key => $value) {
			array_push($employee, $value->display_name);	
		}
	}else{
		$employee = array();
	}

	global $wpdb; // this is how you get access to the database
	$table_name = $wpdb->base_prefix.'vir_scheules';
	$query = "SELECT * FROM $table_name";
	$query_results = $wpdb->get_results($query);

	foreach ($query_results as $result) {

	    $user = get_user_by( "id", $result->user_id);
	    $pieces = explode("to", $result->vir_date);
	    $status = $result->vir_status;

	    $startDateTime = trim($pieces[0]);
	    $endDateTime = trim($pieces[1]);

	    $scheduled_requests[] = array(
	        'title' => "Scheduled Request of ".$user->display_name,
	        'start' => str_replace(' ', 'T', $startDateTime),
	        'end' => str_replace(' ', 'T', $endDateTime),
	        'status' => $status,
	    );
	}
	
	wp_localize_script( 'vir-script-admin', 'vir_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), "employees" =>$employee, "Scheduled" => $scheduled_requests ));

}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('VirtuosoScheduling')) {
$obj = new VirtuosoScheduling();
}