<?php

function show_cases_by_country($post){


$args = array("post_type"=>"events","posts_per_page" => -1);
$query = new WP_Query($args);

if($query->have_posts()): while($query->have_posts()): $query->the_post();



//print_r($aem_event_start_time);


$post .= "<div class='aem_event_manager col-md-3'>";
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
$post.='<article><a href="'.get_the_permalink().'"><img src="'.esc_url($featured_img_url).'"></a>
  	<header class="entry-header-event">
    	<h2 class="entry-title"><a href="'.get_the_permalink().'"> '.get_the_title().'</a></h2>
  	</header>
  	<div class="aem_event_content"><p>'.get_the_excerpt().'</p></article>';


//<div class="aem_event_content"><p>Address: '.$aem_address.'</p><p> Event Start Date: <span> '.$aem_event_start_date.'</span><br> Event End Date: <span>'.$aem_event_end_date.'</span> <span>'.$aem_event_end_time.'</span> </p></div><p>'.get_the_excerpt().'</p>


	
$post .= "</div>";

endwhile;
endif;

return $post;
}
add_shortcode("aem_event_list","show_cases_by_country");