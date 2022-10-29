<?php

function dq_iam_in_queue(){

	//print_r($_POST);
	$user = $_POST['user_id'];
	$salon = $_POST['salon_id'];

	add_post_meta($salon,"user_in_queue",$user);
	echo "Thanks! You are in queue.";

	wp_die();
}

add_action("wp_ajax_dq_iam_in_queue","dq_iam_in_queue");
add_action("wp_ajax_no_priv_dq_iam_in_queue","dq_iam_in_queue");