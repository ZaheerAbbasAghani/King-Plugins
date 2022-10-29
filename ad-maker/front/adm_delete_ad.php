<?php 

if( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_adm_delete_ad', 'adm_delete_ad' );
add_action( 'wp_ajax_nopriv_adm_delete_ad', 'adm_delete_ad' );

function adm_delete_ad() {

global $wpdb;
$table_name = $wpdb->base_prefix.'adm_submitted_data_table';

$id = $_POST['id'];

$query = "SELECT * FROM $table_name WHERE radom_code='$id'";
$query_results = $wpdb->get_results($query);

if(count($query_results) == 1){
    $table_name = $wpdb->base_prefix.'adm_submitted_data_table';
    $wpdb->delete( $table_name, array( 'radom_code' => $id ) );
    echo "Your AD Deleted.";
}else{
    echo "Your AD not found on website.";
}

die();

}