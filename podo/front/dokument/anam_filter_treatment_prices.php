<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_filter_treatment_prices', 'anam_filter_treatment_prices' );
add_action( 'wp_ajax_anam_filter_treatment_prices', 'anam_filter_treatment_prices' );
function anam_filter_treatment_prices() {
	

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_treatments_list';


$id = $_POST['id'];
$currency = get_option('podo_currency');
$query ="SELECT * FROM $table_name WHERE id='$id'";
$results = $wpdb->get_results($query);

//echo $results[0]->price.' '.$currency;

$response = array("price" => $results[0]->price.' '.$currency, "duration" => $results[0]->duration);

echo wp_send_json($response);

	wp_die();
}