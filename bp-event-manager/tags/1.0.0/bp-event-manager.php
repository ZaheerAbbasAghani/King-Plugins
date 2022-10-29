<?php
/**
Plugin Name: BP Event Manager
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: Plug and Play Plugin Development. A person can create events for buddypress groups.
Version: 1.0.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: bp-event-manager
*/
defined("ABSPATH") or die('You can\t access!');


class BPEventManager {

// check if buddypress is installed
function bpem_if_buddypress_not_active($message) {
  if ( !is_plugin_active('buddypress/bp-loader.php') ) {
    echo $message .= "<div class='notice notice-error is-dismissible'><h4> Buddypress Plugin Activation Required for BP Event Manager Plugin.</h4></div>";
    deactivate_plugins( '/bp-event-manager/bp-event-manager.php' );
    wp_die();
  }
}

function __construct(){

        //define('ALLOW_UNFILTERED_UPLOADS', true);
        add_action( 'init',  array($this ,'bpem_enqueue_script_front' ));
        add_action( 'init', array($this,'bpem_start_from_here'));
        add_action( 'init',array($this, 'bpem_register_dashboard_post_page' ));
        add_action('admin_enqueue_scripts' , array($this,'bpem_admin_enqueue_scripts'));
        add_action( 'admin_menu', array($this,'bpem_cpt_ui_for_admin_only' ));
        add_action( 'admin_init', array($this,'bpem_if_buddypress_not_active' ));
        add_action( 'add_meta_boxes', array($this,'bpem_attendees_add_meta_boxes' ));
    
}
// Activate plugin
function bpem_activate(){
    flush_rewrite_rules();
}



//All Plugin files
function bpem_start_from_here(){
    require_once plugin_dir_path( __FILE__ ).'bpem-front/bpem-event-form.php';
    require_once plugin_dir_path( __FILE__ ).'bpem-front/bpem-event-form-response.php';
    require_once plugin_dir_path( __FILE__ ).'bpem-front/bpem_persons_who_attend_event.php';
    require_once plugin_dir_path( __FILE__ ).'bpem-front/bpem-list-of-attendees.php';
    require_once plugin_dir_path( __FILE__ ).'bpem-front/bpem-event-calendar.php';
    require_once plugin_dir_path( __FILE__ ).'bpem-front/bpem-event-further-details.php';    
    require_once plugin_dir_path( __FILE__ ).'bpem-front/bpem_leave_event.php';    


}

// Enqueue Style and Scripts
function bpem_enqueue_script_front() {

//Style
wp_enqueue_style( 'bpem-style',plugins_url('inc/css/bpem-style.css', __FILE__ ),'1.0.0',  'all' );
wp_enqueue_style('bpem-jquery-ui',plugins_url('inc/css/jquery-ui.css', __FILE__ ),false,"1.9.0",false);
wp_enqueue_style( 'bpem-timepicker',plugins_url('inc/css/jquery.timepicker.min.css', __FILE__ ),'1.14.11','all' );
wp_enqueue_style( 'font-awesome','https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
wp_enqueue_style('bpem-fc',plugins_url('inc/css/fullcalendar.min.css', __FILE__ ),'2.3.2','all');
wp_enqueue_style('bpem-fw',plugins_url('inc/css/font-awesome.min.css', __FILE__ ),'4.7.0','all');
wp_enqueue_style('bpem-pagination',plugins_url('inc/css/simplePagination.min.css', __FILE__ ),'1.6','all');


// JS Scripts

wp_enqueue_script('fd-validate','https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js',array('jquery'), '', true);

wp_enqueue_script( 'bpem-timepicker', plugins_url('inc/js/jquery.timepicker.min.js', __FILE__ ), array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),'1.11.14',  true);
wp_enqueue_script('bpem-script',plugins_url('inc/js/bpem_script.js', __FILE__ ),array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),'1.0.0',  true);
wp_localize_script( 'bpem-script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
wp_enqueue_style( 'jquery-ui-datepicker' );
wp_enqueue_style( 'wp-color-picker' ); 
wp_enqueue_script( 'wp-color-picker');
wp_enqueue_media();
wp_enqueue_script('moments',plugins_url('inc/js/moment.min.js', __FILE__ ),array('jquery'),'2.10.6',  true);
wp_enqueue_script( 'bpem-clndr',plugins_url('inc/js/fullcalendar.min.js', __FILE__ ),array('jquery'),'2.3.2',  true);

wp_enqueue_script( 'bpem-pagination',plugins_url('inc/js/jquery.simplePagination.js', __FILE__ ),array('jquery'),'1.6',  true);



}

//Enqueue style and script for admin
function bpem_admin_enqueue_scripts(){
    wp_enqueue_style('bpem-admin',plugins_url('inc/css/admin-style.css', __FILE__ ),'1.0.0','all');
}

// Register post type
function bpem_register_dashboard_post_page() {
    require_once plugin_dir_path( __FILE__ ).'bpem-dash/bpem-post-type.php';
    require_once plugin_dir_path( __FILE__ ).'bpem-dash/bpem-admin-settings-page.php';   
    require_once plugin_dir_path( __FILE__ ).'bpem-dash/bpem_remove_attendy.php';    
    
}
// Remove events tab if user is not admin
function bpem_cpt_ui_for_admin_only() {
    if( !current_user_can( 'administrator' ) ):
        remove_menu_page( 'edit.php?post_type=bpem_event' );
    endif;
}

//List of attendees in dashboard
function bpem_attendees_add_meta_boxes(){
    add_meta_box( 'bpem_meta_box_attendees', 'Event Attendees', array($this,'bpem_list_display_attendees'), 'bpem_event', 'side', 'low' );
} //bpem_attendees_add_meta_boxes

//List of attendees in dashboard
function bpem_list_display_attendees(){
global $post;
$user_ids   =    get_post_meta( $post->ID, 'event_attend_id'); 
$count      =    count(array_filter($user_ids));    
echo "<h4 class='attandees'> Attandees(".$count.")</h4> ";
$i=0;
echo "<div class='wrap_bx'>";
foreach ($user_ids as $user_id) {
    //$usr = get_user_by('id', $user_ids[$i]); 

$avatar =  bp_core_fetch_avatar( array( 'item_id' => $user_id, 'width' => 100,'height' => 100, 'class' => 'avatar','html' => false));
    echo "<div class='box'><a href='#' class='remove_attendy' user-id='".$user_id."' event-id='".$post->ID."'>x</a > <a href='".bp_core_get_user_domain($user_id)."' class='box_attendee' target='_blank'>";
    echo "<img src='".$avatar."' alt=".bp_core_get_username( $user_id)." title=".bp_core_get_username( $user_id).">";
    
    echo "</a></div>";
$i++;
}
echo "</div>";

} //bpem_list_display_attendees





} // class ends


// CHECK WETHER CLASS EXISTS OR NOT.
if(class_exists('BPEventManager')){
    $obj = new BPEventManager();   
    //$obj1 = new AlkaFacebook(); 
}
//activate plugin hook
register_activation_hook( __FILE__, array($obj , 'bpem_activate') );