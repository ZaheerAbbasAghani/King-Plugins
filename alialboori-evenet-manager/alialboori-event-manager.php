<?php
/*
Plugin Name: Alialboori Event Manager
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: The admin will add events from Wp-Admin. The user will see the previously added events. The user will be able to set a reminder by clicking a button and add his email. The user will be able to set a reminder for a custom event by putting the date and his email and will get the an email at the date. The admin will be able to see a history of the reminders and events
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: aem
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class AlialbooriEventManager {

function __construct() {
	add_action('init', array($this, 'aem_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'aem_enqueue_script_front'));
	add_filter( 'the_content', array($this, 'aem_embed_event_content_before' ));
	add_filter('the_content', array($this, 'aem_embed_event_content_after'));
	add_action('wp_footer', array($this, 'aem_show_litbox_for_user_info'));
	add_action("admin_enqueue_scripts",array($this,"aem_enqueue_script_dashboard"));
	add_action('wp', array($this,'aem_schedule_event'));
	add_action ('init', array($this,'aem_send_notification_on_reminder_date')); 
    //add_action ('mycronjob', array($this,'aem_send_notification_on_reminder_date')); 
}



function aem_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'aem_front/aem_shortcode.php';
	require_once plugin_dir_path(__FILE__) . 'aem_front/aem_set_reminder_for_user.php';
	require_once plugin_dir_path(__FILE__) . 'aem_back/aem_event_post_type.php';
	require_once plugin_dir_path(__FILE__) . 'aem_back/aem_remove_reminders.php';
	require_once plugin_dir_path(__FILE__) . 'aem_back/aem_settings_page.php';
}

// Enqueue Style and Scripts

function aem_enqueue_script_front() {
//Style & Script
wp_enqueue_style('aem-style', plugins_url('assets/css/aem.css', __FILE__),'1.0.0','all');

wp_enqueue_script('fd-validate', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js', array('jquery'), '',true);
wp_enqueue_script('aem-script', plugins_url('assets/js/aem.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script( 'aem-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

}


function aem_enqueue_script_dashboard() {
//Style & Script

wp_enqueue_script('aem-script-admin', plugins_url('assets/js/aem_admin.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script( 'aem-script-admin', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

}




function aem_embed_event_content_before( $content ) {
    if ( is_singular( 'events' ) ) {
    	$content .= "<p> <a href='#' id='aem_join_event' post-id='".get_the_ID()."' class='button'>Join Event</a> </p>";
    }
    return $content;
}


function aem_embed_event_content_after( $content ) {
    
    if ( is_singular( 'events' ) ) {

		$aem_address = get_post_meta(get_the_ID(),'aem_address',true);
		$aem_event_start_date = get_post_meta(get_the_ID(),'aem_event_start_date',true);
		$aem_event_end_date = get_post_meta(get_the_ID(),'aem_event_end_date',true);


        $content = '<div class="aem_event_content"><p>Address: '.$aem_address.'</p><p> Event Start Date: <span  id="start_date">'.$aem_event_start_date.'</span><br> Event End Date: <span  id="end_date">'.$aem_event_end_date.'</span> </p></div>'.$content;
       
      }

    return $content;
}

function aem_show_litbox_for_user_info( $content ) {
		echo  "<div class='aem_liteBox' style='display:none;'>
		<h3>Set Event Reminder <span class='aem_remove_liteBox'>x</span></h3>
		<form method='post' action='' id='eventform'>
			<input type='email' id='aem_email' name='aem_email' placeholder='Enter your email'> 
			<input type='date' id='aem_date_calendar' name='aem_date_calendar'> 
			<input type='submit' class='btn btn-create-reminder'>
		</form>
		</div>";
}

// create a scheduled event (if it does not exist already)
function aem_schedule_event() {
    if( !wp_next_scheduled( 'mycronjob' ) ) {  
       //wp_schedule_event( time('12:00:00'), 'daily', 'mycronjob' );  
       wp_schedule_event( time(), 'daily', 'mycronjob' );  
    }
}
// and make sure it's called whenever WordPress loads



// here's the function we'd like to call with our cron job
function aem_send_notification_on_reminder_date() {
	

	$args = array(
		'fields' => 'ids',
		'post_type'   => 'events',
	);
	$my_query = new WP_Query( $args );

	if($my_query->have_posts()): while($my_query->have_posts()): $my_query->the_post();
	
	$email_address 	= get_post_meta(get_the_ID(),"aem_email_address",false);
	$remind = get_post_meta(get_the_ID(),"aem_date_calendar",false);
	$i=0;
	foreach ($email_address as $email) {
		$reminder_date = strtotime($remind[$i]);
		$today = strtotime(date("d-m-Y"));
		//echo $reminder_date.' / '.$today;
		if($reminder_date == $today){
			$aem_subject = get_option("aem_subject");
			$aem_message = get_option("aem_message");

			wp_mail( $email, $aem_subject, $aem_message );
			update_post_meta( get_the_ID(), 'aem_date_calendar', date("Y-m-d",$today)." \nEmail Sent." );

		}
		$i++;
	}

	//print_r($email);


	endwhile;
	endif;

  
}





} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('AlialbooriEventManager')) {
	$obj = new AlialbooriEventManager();
}