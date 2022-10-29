<?php 

add_action('wp_ajax_gift_registry_item_delete','gift_registry_item_delete' );
add_action('wp_ajax_nopriv_gift_registry_item_delete','gift_registry_item_delete' );
function gift_registry_item_delete() {


global $wpdb; 
$table_name = $wpdb->base_prefix.'gft_gift_registry_records';

$product_id  = $_POST['product_id'];
$wpdb->delete( $table_name, array( 'product_id' => $product_id, "user_id" => get_current_user_id() ), array( '%d','%d' ) );

echo "Deleted Successfully.";

wp_die();

}