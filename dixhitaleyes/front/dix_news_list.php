<?php 
function dix_new_list_process($news){

	global $wpdb; 

	$table_name = $wpdb->base_prefix.'posts';
	$search_text = "%" . $_GET['dixQuery'] . "%";

    $sql = $wpdb->prepare( 
        "SELECT * FROM $table_name WHERE post_content LIKE ('%s') AND post_type='post' AND post_status='publish' ", 
        $search_text,
    );

    $query_results = $wpdb->get_results( $sql , ARRAY_A );

    if(!empty($query_results)){
	    foreach ($query_results as $value) {
	    	$thumbnail = get_the_post_thumbnail($value['ID'], 'full'); 
	    	$news .= "<div class='newsWrapper'> $thumbnail  <h3><a href='".get_the_permalink($value['ID'])."'> ".get_the_title($value['ID'])." </a> </h3>";
			$news .= "<p>".wp_trim_words( $value['post_content'],  30, "...")."</p>";
			$news .= "<a href='".get_the_permalink($value['ID'])."'> Read More » </a>";
			$news .= "</div>";
	    }
	}else{
		$results .= "<p style='font-size: 20px;'>  Try again! No result found for <b>".$_GET['dixQuery']."</b> </p>";
	}

	return $news;
}
add_shortcode("dixNews", "dix_new_list_process");