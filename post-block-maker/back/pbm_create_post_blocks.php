<?php 

add_action( 'wp_ajax_pbm_create_post_blocks', 'pbm_create_post_blocks' );
add_action( 'wp_ajax_nopriv_pbm_create_post_blocks', 'pbm_create_post_blocks' );

function pbm_create_post_blocks() {
	global $wpdb; // this is how you get access to the database
	  $category = $_POST['category'];
	$post_id = $_POST['page'];
    $post_content = get_post($post_id);
    $content = $post_content->post_content;
    $pieces = explode("<!--more-->", $content);
    preg_match_all('#<h2>(.*?)</h2>#', $post_content->post_content, $matches);

	$potential_title =  array_slice($matches[0], 0, 2);

   // echo $potential_title[1];
  
    $i=0;
    foreach (array_reverse($pieces) as $value) {
        
        if(!empty($value)){
            $args = array(
                'post_type'    	=> 'post',
                'post_status'  	=> 'publish',
                'post_title'   	=> strip_tags($potential_title[$i]),
                'post_category' => array($category),
                'post_content'  => $value,
                'filter' => true,
            );
         
            $id = wp_insert_post($args);
            
            update_post_meta($id, '_whp_hide_on_frontpage', 1);
            update_post_meta($id, '_whp_hide_on_categories', 1);
            update_post_meta($id, '_whp_hide_on_search', 1);
            update_post_meta($id, '_whp_hide_on_tags', 1);
            update_post_meta($id, '_whp_hide_on_authors', 1);
            update_post_meta($id, '_whp_hide_on_date', 1);
            update_post_meta($id, '_whp_hide_in_rss_feed', 1);
            
            $i++;
        }
      
    }



	wp_die(); // this is required to terminate immediately and return a proper response
}