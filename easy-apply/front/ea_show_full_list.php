<?php 

add_action( 'wp_ajax_ea_show_full_list', 'ea_show_full_list' );
add_action( 'wp_ajax_nopriv_ea_show_full_list', 'ea_show_full_list' );

function ea_show_full_list() {
global $wpdb; // this is how you get access to the database

$list = $_POST['list'];
	
if($list == 1){
	$args = array(
	  'post_type' => 'v_job_board',
	  'posts_per_page' => -1,
	  'meta_query'  => array(
        array(
            'key' => 'shortlist',
            'value' => 'yes',
            'compare' => 'NOT EXISTS'
        )
       )
	);
	$query = new WP_Query($args);
	
	
	if($query->have_posts()): while($query->have_posts()): $query->the_post();
	
	$date = get_the_date('d/m/Y', get_the_ID());
	$title = get_the_title();
	$salary_range = get_post_meta( get_the_ID(), 'salary_range', true);
	$location = get_post_meta( get_the_ID(), 'location', true);
	
	//echo get_the_title();
	echo '<tr data-id="'.get_the_ID().'" class="ae-jobs notshortlist">
		   <td>'.$date.'</td>
		   <td>'.$title.'</td>
		   <td>'.$salary_range.'</td>
		   <td>'.$location.'</td>
	      </tr>';
	
	endwhile;
	endif;
	
	
	
}


	wp_die(); // this is required to terminate immediately and return a proper response
}