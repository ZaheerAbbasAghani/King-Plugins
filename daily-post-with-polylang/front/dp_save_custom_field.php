<?php 

add_action( 'wp_ajax_dp_save_day_of_year_number', 'dp_save_day_of_year_number' );
add_action( 'wp_ajax_nopriv_dp_save_day_of_year_number', 'dp_save_day_of_year_number' );

function dp_save_day_of_year_number() {

	$num = $_POST['num'];
	$post_id = $_POST['post_id'];
	$year_day_number = get_post_meta( $post_id, 'dp_day_of_year_number', false);

	$query_meta = array(
	    'post_type' => 'post',
	    'meta_key' => 'dp_day_of_year_number',
	    'meta_value' => $num 
	);

	$time_posts = new WP_Query( $query_meta );
	if ( $time_posts->post_count == 0 ) {
	    echo __('Number Added Successfully.');
	}else{
		echo __("Number already Exists, Please try again.");
		delete_post_meta($post_id, 'dp_day_of_year_number', $num);
	}
	wp_die();

}