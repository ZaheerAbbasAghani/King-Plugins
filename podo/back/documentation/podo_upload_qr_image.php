<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_podo_upload_qr_image', 'podo_upload_qr_image' );
add_action( 'wp_ajax_podo_upload_qr_image', 'podo_upload_qr_image' );
function podo_upload_qr_image() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_payment_methods';

$image = $_POST['image'];
$id = $_POST['id'];

//print_r($_POST);
$data = array("QRImage" => $image);
$where = array("id" => $id);
$updated = $wpdb->update( $table_name, $data, $where );
 
if ( false === $updated ) {
    $response = array("message" => "Something went wrong!", "status" => $update);
	echo wp_send_json($response);
} else {
  $response = array("message" => "Image Uploaded", "status" => 1);
	echo wp_send_json($response);
}




	wp_die();
}