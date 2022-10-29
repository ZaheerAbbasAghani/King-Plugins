<?php 

function wru_process_approved_users(){

$args = array(
'post_type'      => 'product',
'posts_per_page' => -1
);

$loop = new WP_Query( $args );

while ( $loop->have_posts() ) : $loop->the_post();

global $product;
$all_approved_data = get_post_meta( $product->get_id(),'wru_all_approved_data', false );

if(!empty($all_approved_data)){
	$i=0;
	foreach ($all_approved_data as $approved_data) {
		foreach ($approved_data as $app_data) {

			$today =  strtotime(date("M d, Y"));
			if($today >= $app_data['access_date'] && $app_data['user_id']==get_current_user_id() &&  $app_data['product_id']==get_the_id()){

				delete_post_meta( $app_data['product_id'],'wru_approve_user', get_current_user_id());
				delete_post_meta( $app_data['product_id'],'wru_till_dt',$app_data['access_date']);
				delete_post_meta( $app_data['product_id'],'wru_all_approved_data');
			}
			
			
		}

	}
}


endwhile;

wp_reset_query();

}
add_action( "wp_head","wru_process_approved_users");

?>