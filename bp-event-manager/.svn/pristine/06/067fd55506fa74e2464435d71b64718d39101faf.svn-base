<?php

//Update Events

add_action('wp_ajax_bpem_event_delete_response', 'bpem_event_delete_response');
add_action('wp_ajax_nopriv_bpem_event_delete_response', 'bpem_event_delete_response');

function bpem_event_delete_response() {

//$image_url     = sanitize_text_field($_POST['ev_image']);

	$ev_id = sanitize_text_field($_POST['ev_id']);
	$location = sanitize_text_field($_POST['ev_location']);

	$start_date = sanitize_text_field($_POST['ev_start_date']);
	$start_time = sanitize_text_field($_POST['ev_start_time']);
	$end_date = sanitize_text_field($_POST['ev_end_date']);
	$end_time = sanitize_text_field($_POST['ev_end_time']);
	$ev_organizer = sanitize_text_field($_POST['ev_organizer']);
	$ev_organizer_url = sanitize_text_field($_POST['ev_organizer_url']);

	delete_post_meta($post_id, 'evn_location', $location);
	delete_post_meta($post_id, 'evn_startDate', $start_date);
	delete_post_meta($post_id, 'evn_startTime', $start_time);
	delete_post_meta($post_id, 'evn_endDate', $end_date);
	delete_post_meta($post_id, 'evn_endTime', $end_time);
	delete_post_meta($post_id, 'evn_organizer', $ev_organizer);
	delete_post_meta($post_id, 'evn_organizer_url', $ev_organizer_url);

	wp_delete_post($ev_id, 1);

	wp_die();

}