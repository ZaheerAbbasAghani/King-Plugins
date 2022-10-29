<?php 

add_action('wp_ajax_gift_registry_product_purchasing','gift_registry_product_purchasing' );
add_action('wp_ajax_nopriv_gift_registry_product_purchasing','gift_registry_product_purchasing' );
function gift_registry_product_purchasing() {


	//print_r($_POST);

global $wpdb; 
$table_name = $wpdb->base_prefix.'gft_gift_registry_users';


$product_id  = $_POST['product_id'];
$product_url = $_POST['product_url'];
$pro = wc_get_product( $product_id );


if ($pro->is_type( 'simple' )) {
   
	$wpdb->query($wpdb->prepare("UPDATE $table_name SET status=1 WHERE product_id=$product_id"));

	echo $product_url;

}
elseif($pro->is_type('variable')){
   $wpdb->query($wpdb->prepare("UPDATE $table_name SET status=1 WHERE product_id=$product_id"));

	echo $product_url;
}


wp_die();

}