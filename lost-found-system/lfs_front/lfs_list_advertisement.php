<?php 

function lfs_list_of_advertisement($advr){

$advr .= "<div class='lfs_search_form'> ".get_search_form()."</div>";

$args = array(
  'post_type' => 'advertisement',
);
$query = new WP_Query( $args );
if($query->have_posts()):
  $advr .= '<div id="lfs_list" class="lfs_list row">';
	while ( $query->have_posts() ) : $query->the_post(); 
		$date = get_post_meta(get_the_ID(), 'lfs_lost_or_found_date', true );
		$place = get_post_meta(get_the_ID(), 'lfs_lost_or_found_place', true );
    	$thumbnails = get_the_post_thumbnail_url(get_the_ID(),'full');
    	$advr .= '<div class="col-md-4">
    	<div class="img_wrapper"><a href="'.get_the_permalink().'"> 
    		<img src="'.$thumbnails.'" class="bannerImg"/></a>
    	</div>
    	<h4><a href="'.get_the_permalink().'">'.$date.' / '.$place.'</a></h4>';
    	
    	$advr .= '</div>';
          
	 endwhile; 

    $advr .= '</div>';
wp_reset_query(); 
endif; 

return $advr;

}


add_shortcode( "lfs_ad_list", "lfs_list_of_advertisement" );