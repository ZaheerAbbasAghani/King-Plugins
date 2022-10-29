<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action('wp_ajax_nopriv_podo_insert_treatments','podo_insert_treatments' );
add_action('wp_ajax_podo_insert_treatments','podo_insert_treatments' );
function podo_insert_treatments() {

global $wpdb; 
$table_name = $wpdb->base_prefix.'anam_treatments_list';

$tname = $_POST['tname'];
$tprice = $_POST['tprice'];
$tduration = $_POST['tduration'];
$tdescription = $_POST['tdescription'];
$time = $time = current_time( 'mysql' );

$query = "SELECT * FROM $table_name WHERE name='$tname' ";
$query_results = $wpdb->get_results($query);
if(count($query_results) == 0) {
	
	$rowResult=$wpdb->insert($table_name, array("name" => $tname, "price" => $tprice, "duration" => $tduration,"description" => $tdescription,"created_at" => $time),array("%s","%s","%s","%s"));
	echo "Treatment Created Successfully.";	
}else{
	echo "Treatment already exists";	
}




	wp_die();
}
