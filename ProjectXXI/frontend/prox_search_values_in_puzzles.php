<?php 
add_action( 'wp_ajax_search_values_in_puzzles', 'search_values_in_puzzles' );
add_action( 'wp_ajax_nopriv_search_values_in_puzzles', 'search_values_in_puzzles' );
function search_values_in_puzzles() {
	global $wpdb;


	$post_id_with_underscore = $_POST['post_id'];
	$post_id = substr($post_id_with_underscore, strpos($post_id_with_underscore, "_") + 1);

	$success = get_option("success_message");
	$fail 	= get_option("fail_message");
	$answer1 = get_post_meta($post_id,"prox_answer1", true);
	$answer2 = get_post_meta($post_id,"prox_answer2", true);
	$answer3 = get_post_meta($post_id,"prox_answer3", true);
	$prox_numers= get_post_meta($post_id,"prox_numers",true);
	$page = get_post_meta($post_id,"prox_hidden_pages",true);
	$fail_url 	= get_option("fail_redirect_link");
	$late_url 	= get_option("late_redirect_link");

	$q_details = array();

	$total_attempt=get_post_meta($post_id,"prox_user_attmept", true);

	
	if($total_attempt != 0){

		if($_POST['attempt']==1) {
			$total = $total_attempt - 1;
			update_post_meta($post_id,"prox_user_attmept",$total);
		}

		if(ucfirst(trim($answer1)) == ucfirst(trim($_POST['answer1']))){
			$q_details[] = "Right";
		}else{
			$q_details[] = "Wrong";
		}

		if(ucfirst(trim($answer2)) == ucfirst(trim($_POST['answer2']))){
			$q_details[] = "Right";
		}else{
			$q_details[] = "Wrong";
		}

		if(ucfirst(trim($answer3)) == ucfirst(trim($_POST['answer3']))){
			$q_details[] = "Right";
		}else{
			$q_details[] = "Wrong";
		}

		if(ucfirst(trim($prox_numers)) == ucfirst(trim($_POST['puzzle_number']))){
			$q_details[] = "Right";
		}else{
			
			$q_details[] = "Wrong";
		}

		if(in_array("Wrong", $q_details)){
			echo wp_send_json(array("status"=>"fail", "message" => $fail,"redirect_url" => $fail_url ));
		}else{
			echo wp_send_json(array("status"=>"success", "message" => $success,"redirect_url" => get_site_url().'/'.sanitize_title($page)));
		}

	}else{
		echo wp_send_json(array("status"=>"late","message" =>"You are too late.","redirect_url" =>$late_url));
	}

	wp_die();
}