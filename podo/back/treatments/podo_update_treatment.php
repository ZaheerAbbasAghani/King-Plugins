<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_update_treatment','podo_update_treatment' );
add_action( 'wp_ajax_podo_update_treatment', 'podo_update_treatment' );
function podo_update_treatment() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_treatments_list';

$name 			= $_POST['name'];
$price 			= $_POST['price'];
$duration 		= $_POST['duration'];
$description 	= $_POST['description'];
$field_id 		= $_POST['field_id'];


$update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET name='$name', price='$price', duration='$duration', description='$description' WHERE id=$field_id"));

echo "Update Successfully.";



wp_die();

}