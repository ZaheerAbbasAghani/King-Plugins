<?php
// Same handler function...
add_action( 'wp_ajax_br_confirm_yes', 'br_confirm_yes' );
add_action( 'wp_ajax_nopriv_br_confirm_yes', 'br_confirm_yes' );
function br_confirm_yes() {

global $wpdb;
$sender = $_POST['sender'];
$receiver = get_current_user_id();

$table_name = $wpdb->base_prefix.'br_relationship_confirm';
$query = "SELECT * FROM $table_name WHERE br_sender='$sender' AND br_receiver='$receiver' AND br_status=1";
$query_results = $wpdb->get_results($query);
if(count($query_results) == 0) {

	$wpdb->query($wpdb->prepare("UPDATE $table_name SET br_status=1 WHERE br_sender='$sender' AND br_receiver='$receiver' "));

	if ( bp_is_active( 'notifications' ) ) {   
		bp_notifications_add_notification( array(                        
		    'user_id'           => $sender, // User to whom notification has to be send
		    'item_id'           => '',  // Id of thing you want to show it can be item_id or post or custom post or anything
		    'component_name'    => 'custom', //  component that we registered
		    'component_action'  => 'custom_action', // Our Custom Action 
		    'date_notified'     => bp_core_current_time(), // current time
		    'is_new'            => 1, // It say that is new notification
		) );
	}
	echo "Confirm Success";

}else{
	echo "Confim request already sent. Make status read.";
}

wp_die();
}