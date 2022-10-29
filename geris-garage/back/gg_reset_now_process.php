<?php 
add_action('wp_ajax_gg_reset_now_process','gg_reset_now_process');
add_action('wp_ajax_nopriv_gg_reset_now_process', 'gg_reset_now_process');

function gg_reset_now_process() {
	
	global $wpdb;
    $table_name = $wpdb->base_prefix.'gg_history';

    $ids = $_POST['product_ids'];

    foreach ($ids as $id) {
    	$wpdb->query($wpdb->prepare("UPDATE $table_name SET status='1' WHERE product_id=$id"));
    }
    echo "Reset Successfully.";

   
	wp_die();
}