<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_change_dokument_status','podo_change_dokument_status' );
add_action( 'wp_ajax_podo_change_dokument_status', 'podo_change_dokument_status' );
function podo_change_dokument_status() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_dokument_info';
$id = $_POST['id'];

$update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET status='paid' WHERE id=$id"));

echo "Update Successfully.";



wp_die();

}