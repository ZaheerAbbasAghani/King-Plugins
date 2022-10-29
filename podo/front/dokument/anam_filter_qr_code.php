<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action('wp_ajax_nopriv_anam_filter_qr_code', 'anam_filter_qr_code' );
add_action('wp_ajax_anam_filter_qr_code','anam_filter_qr_code');
function anam_filter_qr_code() {
	

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_payment_methods';


$method = $_POST['method'];
$query ="SELECT * FROM $table_name WHERE payment_method_name='$method'";
$results = $wpdb->get_results($query);

if($results[0]->enableQR == 1){
	echo '<label><span>'.$results[0]->payment_method_description.'</span><br><img src="'.$results[0]->QRImage.'" style="width: 130px;height: 125px;"></label>';
}


	wp_die();
}