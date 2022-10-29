<?php

//Create Events

add_action('wp_ajax_bpem_remove_attendy', 'bpem_remove_attendy');

add_action('wp_ajax_nopriv_bpem_remove_attendy', 'bpem_remove_attendy');

function bpem_remove_attendy() {

	$user = $_POST['user_id'];

	$event = $_POST['event_id'];

	delete_post_meta($event, 'event_attend_id', $user);

	wp_die();

}