<?php
add_action( 'wp_ajax_create_point_for_user', 'create_point_for_user' );
add_action( 'wp_ajax_nopriv_create_point_for_user', 'create_point_for_user' );
function create_point_for_user() {

global $wpdb; 
$table_name = $wpdb->base_prefix.'alfa_points_table';

$event_id = $_POST['event_id'];
$obt_points = $_POST['obtained_points'];
$points_today = date("Y-m-d");
$user_id = $_POST['user_id'];

$user_meta=get_userdata($user_id);
$user_role=$user_meta->roles[0];

//print_r($user_roles);

$query = "SELECT * FROM $table_name WHERE event_joined='$event_id' AND user_id='$user_id'";
$query_results = $wpdb->get_results($query);
if(count($query_results) == 0) {
	$rowResult=$wpdb->insert($table_name, array("event_joined" => $event_id, "obtained_points" => $obt_points, "points_today" => $points_today, "user_id"=>$user_id,"user_role" => $user_role));
	echo "Points added successfully.";	
}else{
	echo "Points already assign to user.";	
}


wp_die();	
}