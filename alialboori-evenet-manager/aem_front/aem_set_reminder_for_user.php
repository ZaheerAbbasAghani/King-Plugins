<?php
add_action( 'wp_ajax_aem_set_reminder_for_user', 'aem_set_reminder_for_user' );
add_action( 'wp_ajax_nopriv_aem_set_reminder_for_user', 'aem_set_reminder_for_user' );

function aem_set_reminder_for_user() {
	global $wpdb; // this is how you get access to the database

	$aem_email = $_POST['aem_email'];
	$aem_date_calendar = $_POST['aem_date_calendar'];
	$aem_post_id = $_POST['aem_join_event'];

	$aem_email_exists = get_post_meta( $aem_post_id, 'aem_email_address', false );

	if(in_array($aem_email, $aem_email_exists)){
		echo "Reminder already set for email (".$aem_email.").";
	}else{
		add_post_meta($aem_post_id,"aem_email_address",$aem_email);
		add_post_meta($aem_post_id,"aem_date_calendar",$aem_date_calendar);
		//add_post_meta($aem_post_id,"aem_message_status",$aem_email.'_not_sent');
		echo "Reminder set successfully.";	
        
 
        
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}