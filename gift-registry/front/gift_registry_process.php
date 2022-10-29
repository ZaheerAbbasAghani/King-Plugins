<?php 

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 0 );
}

add_action( 'wp_ajax_gift_registry_process', 'gift_registry_process' );
add_action( 'wp_ajax_nopriv_gift_registry_process', 'gift_registry_process' );
function gift_registry_process() {
	global $wpdb;
	
	if(is_user_logged_in()):

		global $wpdb; 

		$product_id = $_POST['product_id'];
		$user_id = get_current_user_id();

		$table_name = $wpdb->base_prefix.'gft_gift_registry_records';

		$query = "SELECT * FROM $table_name WHERE product_id='$product_id' AND user_id='$user_id'";
		$query_results = $wpdb->get_results($query);

		if(count($query_results) == 0) {

			$rowResult = $wpdb->insert($table_name, array("product_id" => $product_id, "user_id" => get_current_user_id(),  "created_at" => gmdate('Y-m-d H:i:s')), array("%s","%d","%s"));

			$response=array("status"=>1,"message"=>"Product Added In Gift Registry");
			wp_send_json( $response );

		}else{
			
			$response=array("status"=>2,"message"=>"Product Already Exists In Gift Registry");
			wp_send_json( $response );			
		}

		
	
	else:
		
		$response = array("status"=>0,"redirect_url"=>get_site_url().'/my-account');
		wp_send_json( $response );

	endif;

	wp_die();
}