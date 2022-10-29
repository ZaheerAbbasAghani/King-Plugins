<?php
add_action( 'wp_ajax_aem_filter_post_by_category', 'aem_filter_post_by_category' );
add_action( 'wp_ajax_nopriv_aem_filter_post_by_category', 'aem_filter_post_by_category' );
function aem_filter_post_by_category() {
	
	//print_r($_POST);
$term_id = $_POST['term'];


if($term_id == 0){

$args = array("post_type"=>"events", "posts_per_page" => -1,"post_status" => "publish");

$query = new WP_Query($args);
$i=0;
echo "<div class='boxes'>";
echo "<div class='row'>";
if($query->have_posts()): while($query->have_posts()): $query->the_post();
	echo  "<div class='col-xs-12 col-sm-6 col-md-3 col-lg-3 bx clearfix' id='".get_the_ID()."'><div class='aem_box clearfix'>";
		echo  "<a href='".get_the_permalink()."'> <h5><a href='".get_the_permalink()."' class='title'> ".get_the_title()."</a></h5>"; 
		$thecontent = wp_trim_words( get_the_content(), 10, '...' );
		$end_date = get_post_meta(get_the_ID(),'aem_event_end_date', true );
		$aem_amount_points = get_post_meta(get_the_ID(),'aem_amount_points', true );
		echo  "<a href='".get_the_permalink()."' class='aem_cover'>";
		if(!empty($thecontent)) { 
			echo  "<p>".$thecontent."</p>"; 
		}
		echo  "<p style='display:none;' class='enddate'>".$end_date."</p>"; 
		echo  "<p class='counter_".$i."' id='aem_timer'></p>"; 
		echo  "<b class='clearfix'>Points:$aem_amount_points </b></a>"; 
		if(is_user_logged_in()){
			
			$joined = get_post_meta(get_the_ID(), 'aem_user_joined', false);
			if(!in_array(get_current_user_id(), $joined)){
				echo  "<a href='#' class='btn btn-success join_now' post-id='".get_the_ID()."'>  Join Now </a>"; 
			}else{
				echo  "<a href='#' class='btn btn-success leave_now' post-id='".get_the_ID()."'>  Leave Event </a>"; 
			}
		}else{
			echo  "<a href='".get_site_url().'/login'."' class='btn btn-success'>  Join Now </a>"; 
		}
		
	echo  "</div></div>";
$i++;
endwhile;
endif;
echo  "</div>";
echo  "</div>";



}else{


$args = array("post_type"=>"events", "posts_per_page" => -1, "hide_empty" => 1, 'tax_query' => array(
            array(
                'taxonomy' => 'genres',
                'field' => 'term_id',
                'terms'    => $term_id
            ),
       ));

$query = new WP_Query($args);
$i=0;
echo "<div class='boxes'>";
echo "<div class='row'>";
if($query->have_posts()): while($query->have_posts()): $query->the_post();
	echo  "<div class='col-3 col-xs-12 col-sm-6 col-md-3 col-lg-3 bx clearfix' id='".get_the_ID()."'><div class='aem_box clearfix'>";
		echo  "<a href='".get_the_permalink()."'> <h5><a href='".get_the_permalink()."' class='title'> ".get_the_title()."</a></h5>"; 
		$thecontent = wp_trim_words( get_the_content(), 10, '...' );
		$end_date = get_post_meta(get_the_ID(),'aem_event_end_date', true );
		$aem_amount_points = get_post_meta(get_the_ID(),'aem_amount_points', true );
		echo  "<a href='".get_the_permalink()."' class='aem_cover'>";
		if(!empty($thecontent)) { 
			echo  "<p>".$thecontent."</p>"; 
		}
		echo  "<p style='display:none;' class='enddate'>".$end_date."</p>"; 
		echo  "<p class='counter_".$i."' id='aem_timer'></p>"; 
		echo  "<b class='clearfix'>Points:$aem_amount_points </b></a>"; 
		if(is_user_logged_in()){
			
			$joined = get_post_meta(get_the_ID(), 'aem_user_joined', false);
			if(!in_array(get_current_user_id(), $joined)){
				echo  "<a href='#' class='btn btn-success join_now' post-id='".get_the_ID()."'>  Join Now </a>"; 
			}else{
				echo  "<a href='#' class='btn btn-success leave_now' post-id='".get_the_ID()."'>  Leave Event </a>"; 
			}
		}else{
			echo  "<a href='".get_site_url().'/login'."' class='btn btn-success'>  Join Now </a>"; 
		}
		
	echo  "</div></div>";
$i++;
endwhile;
endif;
echo  "</div>";
echo  "</div>";

}

	wp_die();
}