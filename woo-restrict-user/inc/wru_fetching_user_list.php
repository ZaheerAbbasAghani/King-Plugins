<?php
function my_search() {
$term = strtolower( $_GET['term'] );
global $wpdb;
//SELECT * FROM skills WHERE display_name LIKE '%".$term."%' 
$wp_user_search = $wpdb->get_results("SELECT * from $wpdb->users WHERE display_name LIKE '%".$term."%' OR user_email LIKE '%".$term."%'");
$suggestions = array();
foreach ( $wp_user_search as $userid ) {
	$display_name  = stripslashes($userid->display_name);
	$suggestion = array();
	$suggestion['label'] = $display_name.' ('.$userid->user_email.')#'.$userid->ID;
	$suggestions[] = $suggestion;
}

$response = json_encode( $suggestions );
echo $response;
exit();


}

add_action( 'wp_ajax_my_search', 'my_search' );
add_action( 'wp_ajax_nopriv_my_search', 'my_search' );