<?php
/*
Plugin Name: Vehicle Washing
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: A car washing fees plugin form. The last four of the vehicles Vin, license plate, year make and model. On the back end I need to be able to see the exact form time that they paid and car was washed or parked.I need to be able to send before and after pictures to clients along with receipt of serivces
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: vehicle-washing
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class VehicleWashing {

function __construct() {
	register_activation_hook( __FILE__,array($this, 'vw_plugin_activate') );
	add_action('init', array($this, 'vw_start_from_here'));
	add_action('admin_enqueue_scripts', array($this,'vw_enqueue_script_dashboard'));
	add_filter('enter_title_here', array($this, 'vw_title_place_holder'), 20 , 2 );
	add_action('wp_enqueue_scripts', array($this,'vw_enqueue_script_frontend'));
	add_action("template_redirect",array($this,"vw_custom_redirect"));
	add_action('wp_login', array($this, 'vw_login_redirect_based_on_roles'), 10, 2);
}

/**
 * Activate the plugin.
 */
function vw_plugin_activate() { 
	$upload_dir = wp_upload_dir();
	if ( ! is_dir( $upload_dir['basedir'].'/vehicle-before-after' ) ) {
	   	wp_mkdir_p($upload_dir['basedir'].'/vehicle-before-after' );
	}
}


function vw_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/vw_upload_handler.php';
	require_once plugin_dir_path(__FILE__) . 'back/vw_delete_file.php';
	require_once plugin_dir_path(__FILE__) . 'back/vw_move_images_to_folder.php';
	require_once plugin_dir_path(__FILE__) . 'back/vw_file_loader_before.php';
	require_once plugin_dir_path(__FILE__) . 'back/vw_file_loader_after.php';
	require_once plugin_dir_path(__FILE__) . 'back/vm_invoice_generator.php';
}

// Enqueue Style and Scripts
function vw_enqueue_script_dashboard() {
	//Style & Script

	global $pagenow;
	//print_r( get_current_screen());

	if (( $pagenow == 'post.php' ) || (get_post_type() == 'vehicle')) {

		
		wp_enqueue_style('vw-dropzone', 'https://unpkg.com/dropzone@5.9.3/dist/min/dropzone.min.css','5.9.3/','all');

		wp_enqueue_style('vw-style', plugins_url('assets/css/vw.css', __FILE__),'1.0.0','all');

		
		wp_enqueue_script('vw-dropzone', 'https://unpkg.com/dropzone@5.9.3/dist/min/dropzone.min.js',array('jquery'),'5.9.3', true);

		wp_enqueue_script('vw-script', plugins_url('assets/js/vw.js', __FILE__),array('jquery'),'1.0.0', true);

		wp_localize_script('vw-script', 'vw_object', array(
        	'upload_file' => admin_url('admin-ajax.php?action=vw_upload_handler'),
        	'move_file'=>admin_url('admin-ajax.php?action=vw_move_images_to_folder'),
        	'delete_file' => admin_url('admin-ajax.php?action=vw_delete_file'),
        	'file_loader_before' => admin_url('admin-ajax.php?action=vw_file_loader_before'),
        	'file_loader_after' => admin_url('admin-ajax.php?action=vw_file_loader_after'),
        	'invoice_generator' => admin_url('admin-ajax.php?action=vm_invoice_generator'),

    	));


	}
}

function vw_enqueue_script_frontend(){

	wp_enqueue_style('vw-front', plugins_url('assets/css/vw_front.css', __FILE__),'1.3.4','all');

	//wp_enqueue_script('jquery.fancybox',plugins_url('assets/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__) ,array('jquery'),'1.3.4', true);
	wp_enqueue_script('simple-front', plugins_url('assets/js/vw_front.js', __FILE__),array('jquery'),'1.0.0', true);

	
}


function vw_title_place_holder($title , $post){

    if( $post->post_type == 'vehicle' ){
        $my_title = "Vin number (last 4)";
        return $my_title;
    }
    return $title;
}


function vw_custom_redirect() {        
	global $post;
	if ( $post->post_type == 'vehicle' && is_singular( 'vehicle' )) {
		if(!is_user_logged_in()){

			setcookie('afterLogin',get_the_permalink(get_the_ID()),(time()+3600), "/");
			
		 	wp_redirect( wp_login_url() ); 
		  	exit();
		}
	}    
}


function vw_login_redirect_based_on_roles($user_login, $user) {

    if( in_array( 'subscriber',$user->roles ) && isset($_COOKIE['afterLogin'])){
        exit( wp_redirect($_COOKIE['afterLogin']) );
    }   
}





} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('VehicleWashing')) {
	$obj = new VehicleWashing();
	require_once plugin_dir_path(__FILE__) . 'back/vw_custom_post_type.php';
}