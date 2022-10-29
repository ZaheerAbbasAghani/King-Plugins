<?php
// Same handler function...
add_action( 'wp_ajax_br_remove_connection', 'br_remove_connection' );
add_action( 'wp_ajax_nopriv_br_remove_connection', 'br_remove_connection' );
function br_remove_connection() {

global $wpdb;
$id = $_POST['id'];
$table_name = $wpdb->base_prefix.'br_relationship_confirm';
$wpdb->delete( $table_name, array( 'id' => $id ) );
echo "Connection removed successfully";

wp_die();
}