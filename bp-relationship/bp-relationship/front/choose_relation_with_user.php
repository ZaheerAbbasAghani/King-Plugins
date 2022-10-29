<?php
// Same handler function...
add_action( 'wp_ajax_choose_relation_with_user', 'choose_relation_with_user' );
add_action( 'wp_ajax_nopriv_choose_relation_with_user', 'choose_relation_with_user' );
function choose_relation_with_user() {
	

global $wpdb; 
$user_name = $_POST['user_name'];
/* Getting Username */   
$users=$wpdb->get_results("SELECT ID FROM $wpdb->users WHERE display_name='$user_name'");

$sender = get_current_user_id();
$user_id = $users[0]->ID;
$relation = $_POST['relation'];


//Check if records already exists
$table_name = $wpdb->base_prefix.'br_relationship_confirm';
$query = "SELECT * FROM $table_name WHERE br_sender='$sender' AND br_receiver='$user_id'";
$query_results = $wpdb->get_results($query);
if(count($query_results) == 0) {
	$rowResult=$wpdb->insert($table_name, array("br_sender" => $sender, "br_receiver" => $user_id, "br_relation" => $relation,"br_status"=>0),array("%s","%s","%s","%d"));
	// Sending Notification
	if ( bp_is_active( 'notifications' ) ) {   
		bp_notifications_add_notification( array(                        
		    'user_id'           => $user_id, // User to whom notification has to be send
		    'item_id'           => '',  // Id of thing you want to show it can be item_id or post or custom post or anything
		    'component_name'    => 'custom', //  component that we registered
		    'component_action'  => 'custom_action', // Our Custom Action 
		    'date_notified'     => bp_core_current_time(), // current time
		    'is_new'            => 1, // It say that is new notification
		) );
	}

	echo "Relationship status will update after user confirmation. Thanks";	
}else{
	echo "You are already in relationship with person. \n";	
}

wp_die();
}