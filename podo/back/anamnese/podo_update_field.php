<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_podo_update_field','podo_update_field' );
add_action( 'wp_ajax_podo_update_field', 'podo_update_field' );
function podo_update_field() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_fields_maker';

$flabel 	= $_POST['flabel'];
$ftype 		= $_POST['ftype'];
$field_id 	= $_POST['field_id'];


$update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET label='$flabel', fieldtype='$ftype' WHERE id=$field_id"));

echo " Eintrag wurde erfolgreich aktuallisiert. ";



wp_die();

}