<?php

function dq_iam_out_of_queue(){

	//print_r($_POST);
	$user = $_POST['user_id'];
	$salon = $_POST['salon_id'];

	delete_post_meta($salon,"user_in_queue",$user);
	echo "Customer is out of queue now.";


	$user_on_top = get_post_meta( $salon, 'user_in_queue', false );

	if(!empty($user_on_top[0])){
		$user_info = get_userdata($user_on_top[0]);
		$user_email = $user_info->user_email;
		$subject = "You are top of queue.".get_the_title( $salon );
		$message = "The Salon name as: ".get_the_title( $salon )." waiting for you.";
		wp_mail( $user_email, $subject, $message);
	}

	if(!empty($user_on_top[1])){
		$user_info = get_userdata($user_on_top[1]);
		$user_email = $user_info->user_email;
		$subject = "You are second on queue.".get_the_title( $salon );
		$message = "The Salon name as: ".get_the_title( $salon )." waiting for you.";
		wp_mail( $user_email, $subject, $message);
	}


	wp_die();
}

add_action("wp_ajax_dq_iam_out_of_queue","dq_iam_out_of_queue");
add_action("wp_ajax_no_priv_dq_iam_out_of_queue","dq_iam_out_of_queue");