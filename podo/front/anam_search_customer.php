<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_search_customer', 'anam_search_customer' );
add_action( 'wp_ajax_anam_search_customer', 'anam_search_customer' );
function anam_search_customer() {
	
global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$id = get_current_user_id();

$keyword = $_POST['keyword'];
$query = "SELECT * FROM $table_name WHERE last_name like '".$keyword."%' OR first_name like '".$keyword."%' ORDER BY last_name ASC LIMIT 0,6";
$results = $wpdb->get_results($query);

echo '<ul id="country-list" class="customer_list">';
foreach ($results as $result) {
 echo "<li data-id='".$result->id."'><a href='#'>".$result->first_name.' '.$result->last_name."</a></li>";
}
echo '</ul>';
//print_r($results);

	wp_die();
}