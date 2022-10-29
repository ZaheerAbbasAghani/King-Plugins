<?php 

function aem_list_of_events($event){

$event .= '<div class="list_of_category text-center"><div class="container"> ';
$event .= '<ul class="list-unstyled list-inline">';
	if( $terms = get_terms( array(
    'taxonomy' => 'genres', // to make it simple I use default categories
    'orderby' => 'name') ) ) : 
	// if categories exist, display the dropdown
	foreach ( $terms as $term ) :
		$event.=  '<li class="list-inline-item"><a href="#" term-id="' . $term->term_id . '"> ' . $term->name . '</a></li>'; 
	
	endforeach;
	
endif;

$event.=  '<li class="list-inline-item"><a href="#" term-id="0" class="reset"> Reset </a></li>'; 

$event.= ' </ul>';
$event .= '</div></div>';


$args = array("post_per_page" => 10, "post_type" => "events");
$query = new WP_Query($args);
$i=0;
$event .= "<div class='container eventList'>";
$event .= "<div class='boxes'>";
$event .= "<div class='row'>";
if($query->have_posts()): while($query->have_posts()): $query->the_post();
	$event .= "<div class='col-xs-12 col-sm-6 col-md-3 col-lg-3 bx clearfix' id='".get_the_ID()."'><div class='aem_box clearfix'>";
		$event .= "<a href='".get_the_permalink()."'> <h5><a href='".get_the_permalink()."' class='title'> ".get_the_title()."</a></h5>"; 
		$thecontent = wp_trim_words( get_the_content(), 10, '...' );
		$end_date = get_post_meta(get_the_ID(),'aem_event_end_date', true );
		$aem_amount_points = get_post_meta(get_the_ID(),'aem_amount_points', true );
		$event .= "<a href='".get_the_permalink()."' class='aem_cover'>";
		if(!empty($thecontent)) { 
			$event .= "<p>".$thecontent."</p>"; 
		}
		$event .= "<p style='display:none;' class='enddate'>".$end_date."</p>"; 
		$event .= "<p class='counter_".$i."' id='aem_timer'></p>"; 
		$event .= "<b class='clearfix'>Points:$aem_amount_points </b></a>"; 
		if(is_user_logged_in()){
			
			$joined = get_post_meta(get_the_ID(), 'aem_user_joined', false);
			if(!in_array(get_current_user_id(), $joined)){
				$event .= "<a href='#' class='btn btn-success join_now' post-id='".get_the_ID()."'>  Join Now </a>"; 
			}else{
				$event .= "<a href='#' class='btn btn-success leave_now' post-id='".get_the_ID()."'>  Leave Event </a>"; 
			}
		}else{
			$event .= "<a href='".get_site_url().'/login'."' class='btn btn-success'>  Join Now </a>"; 
		}
		
	$event .= "</div></div>";
$i++;
endwhile;
endif;
$event .= "</div>";
$event .= "</div>";
$event .= "</div>";

return $event;

}
add_shortcode("list-events", "aem_list_of_events");