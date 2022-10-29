<?php
add_action( 'wp_ajax_aem_remove_reminders', 'aem_remove_reminders' );
add_action( 'wp_ajax_nopriv_aem_remove_reminders', 'aem_remove_reminders' );

function aem_remove_reminders() {

	$aem_email = $_POST['aem_email'];
	$aem_calendar = $_POST['aem_calendar'];
	$aem_post_id = $_POST['aem_post_id'];
	
	delete_post_meta($aem_post_id,"aem_email_address",$aem_email);
	delete_post_meta($aem_post_id,"aem_date_calendar",$aem_calendar);

	echo "Deleted";

	wp_die(); // this is required to terminate immediately and return a proper response
}