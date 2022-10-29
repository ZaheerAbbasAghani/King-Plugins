<?php 
// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
function gstl_list_of_events($atts, $content = null) {
	
	global $post;
	
	extract(shortcode_atts(array(
		'post_type'     => 'gstl_events',
		'num'     => '5',
		'order'   => 'DESC',
		'orderby' => 'post_date',
	), $atts));
	
	$args = array(
		'post_type'     => $post_type,
		'posts_per_page' => $num,
		'order'          => $order,
		'orderby'        => $orderby,
	);
	
	$output = '';
	
	$posts = get_posts($args);
	
	foreach($posts as $post) {
		
		setup_postdata($post);
		$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
		$text = wp_trim_words(get_the_content(), 15, '...' );
		$output .= '<li><a href="'. get_the_permalink() .'"><img src="'.esc_url($featured_img_url).'"><h3>'. get_the_title() .'</h3></a><p>'.$text.'</p><a href="'. get_the_permalink() .'" class="gstl_read_more"> Read More </a></li>';
		
	}
	
	wp_reset_postdata();
	
	return '<div class="gstl_thumb"> <ul>'. $output .'</ul></div>';
	
}
add_shortcode('list-events', 'gstl_list_of_events');