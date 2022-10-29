<?php 

add_action( 'wp_ajax_dp_get_post_by_value','dp_get_post_by_value');
add_action( 'wp_ajax_nopriv_dp_get_post_by_value', 'dp_get_post_by_value' );
function dp_get_post_by_value() {

$val = $_POST['val'].'-'.pll_current_language();

$query_meta = array(
    'post_type' => 'post',
    'meta_key' => 'dp_day_of_year_number',
    'meta_value' => $val,
    'orderby' => 'meta_value',
	'order' => 'ASC'  
);
$my_query = new WP_Query( $query_meta );

if($my_query->found_posts == 0){
	$response = array("response" => 0, "message" => "No Post Found!");
	wp_send_json($response);
}else{
	if( $my_query->have_posts() ) :
	    while( $my_query->have_posts() ) : $my_query->the_post(); 
		$response = array("response" => 1, "redirect" => get_the_permalink(),  "message" => get_the_title());
		wp_send_json($response);

	    endwhile;
	endif;
	wp_reset_query();
}

	wp_die();
}