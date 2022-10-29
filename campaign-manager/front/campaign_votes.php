<?php 
add_action( 'wp_ajax_campaign_votes', 'campaign_votes' );
add_action( 'wp_ajax_nopriv_campaign_votes', 'campaign_votes' );
function campaign_votes() {
	
	global $wpdb;
    $table_name = $wpdb->base_prefix.'campaign_votes';
	
	$post_id = $_POST['post_id'];
	$candidate = $_POST['candidate'];
	$user_id = get_current_user_id();
	$time = current_time('mysql'); 

	$query="SELECT * FROM $table_name WHERE user_id='$user_id' AND post_id='$post_id'";
	$query_results = $wpdb->get_results($query);
	if(count($query_results) == 0) {
		$rowResult=$wpdb->insert($table_name, array("post_id" => $post_id,"candidate" => $candidate,"user_id" => $user_id,"vote_count" => 1,"created_date" => $time),array("%d","%s","%d","%d"));
		echo "Candidate Voted Successfully.";	
	}else{
		echo "You can vote once in each campaign";	
	}

	wp_die();
}