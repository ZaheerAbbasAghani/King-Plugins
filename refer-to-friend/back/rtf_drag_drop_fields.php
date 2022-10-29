<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_rtf_drag_drop_fields', 'rtf_drag_drop_fields' );
add_action( 'wp_ajax_rtf_drag_drop_fields', 'rtf_drag_drop_fields' );
function rtf_drag_drop_fields() {


global $wpdb;
$table_name = $wpdb->base_prefix.'rtf_fields_maker';
$position = $_POST['data'];

$i=1;
foreach($position as $k=>$v){
	$update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET 
		forder=".$i." WHERE id=".$v));
	$i++;
}

echo "Update Successfully.";

	wp_die();
}
