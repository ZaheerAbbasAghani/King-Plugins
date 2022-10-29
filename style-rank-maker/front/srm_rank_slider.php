<?php 

function  srm_slider_shortcode($slider){


/*$array = array("one" => "bird", "two" => "fish", "Three" => "elephant");

$keys = array_keys($array);
$last = end($keys);
 $arr = array($last);

print_r($arr);*/

global $wpdb; 
$table_name = $wpdb->base_prefix.'srm_rankings';
$user_id = get_current_user_id();

$query = "SELECT * FROM $table_name WHERE user_id='$user_id' ";
$query_results = $wpdb->get_results($query);


if(count($query_results) == 0) {


	$slider .= "<a href='#' class='srmUndo'> Undo </a>";
	$image = get_option('upload_image');
	$description = get_option('description');
	$buttonTxt = get_option('button_text'); 


	$slider .= '<div class="srm_onboarding no-padding container-fluid"><div class="intro row"><div class="col"><img src="'.$image.'" class="image"><p class="text">'.$description.'</p><a class="sticky-button d-flex d-sm-none " href="#"><button type="button" class="btn btn-warning">'.$buttonTxt.'</button></a></div></div></div>';


	$slider .= "<div class='srm_slider'>";

		$args = array( 'post_type' => 'styles', 'posts_per_page' => -1);
		$the_query = new WP_Query( $args ); 

		$slides = array();
		
		if ( $the_query->have_posts() ) : 
		
			while ( $the_query->have_posts() ) : $the_query->the_post();

			$terms = wp_get_object_terms( get_the_ID(), array_keys( get_the_taxonomies() ) );


			// Array Push
			$thumb = get_the_post_thumbnail_url();
			array_push($slides,array($thumb, $terms[0]->term_id));
			$meta_keys=array('second_featured_image','third_featured_image');
			foreach($meta_keys as $meta_key){
			$image_meta_val=get_post_meta(get_the_ID(), $meta_key, true);
				$thumb23 = wp_get_attachment_image_src( $image_meta_val)[0];
				//array_push($slides,$thumb23,$terms[0]->term_id);
				array_push($slides,array($thumb23, $terms[0]->term_id));
			}

			endwhile;
			wp_reset_postdata(); 
			else:  
			$slider .= '<div>'._e( 'Sorry, no posts matched your criteria.'). '</div>';
		endif;

	// Randomize
	while(!empty($slides)){
	    $key = rand(0,1000);
	    if(key_exists($key, $slides)){
	        $new_order[] = $slides[$key];
	    }
	    unset($slides[$key]);
	}


	// Display
	foreach ($new_order as $key => $value) {
		
			$slider .= '<div class="slick-slide"><img src="'.$value[0].'" data-id="'.$value[1].'"></div>';
	}

	$slider .= "</div>";

}else{


	echo  "<div class='ranking_results'>";
		$term = get_term_by('name', $query_results[0]->ranking_result, 'types'); 
		$args = array(
		    'post_type' => 'styles',
		    'tax_query' => array(
		        array (
		            'taxonomy' => 'types',
		            'field' => 'name',
		            'terms' => $query_results[0]->ranking_result,
		        )
		    ),
		);
		// The Query
		$query = new WP_Query( $args );
	 	
		if($query->have_posts()): while($query->have_posts()): $query->the_post();

			$thumb = get_the_post_thumbnail_url();
	    	echo  "<div style='clear:both;'><div class='leftSide'><img src='".$thumb."'></div>";
			echo  "<div class='rightSide'><p> You have finished the test! </p>";
			echo  "<p>Your style is:</p> <h3>".$term->name."</h3>";

			
			echo "<a href='".get_site_url().'/'.strtolower(sanitize_title($term->name))."' class='btn btn-warning'><button> Explore My Style </button></a>";

			echo "</div></div>";

		
		endwhile;
		endif;

	echo "</div>";




}


return $slider;

}

add_shortcode('rank-maker', 'srm_slider_shortcode');

?>