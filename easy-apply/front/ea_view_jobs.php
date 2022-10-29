<?php 

add_action( 'wp_ajax_ea_view_jobs', 'ea_view_jobs' );
add_action( 'wp_ajax_nopriv_ea_view_jobs', 'ea_view_jobs' );

function ea_view_jobs() {
global $wpdb; // this is how you get access to the database

$id = $_POST['id'];
$args = array(
  'p'         => $id, // ID of a page, post, or custom type
  'post_type' => 'v_job_board'
);
$query = new WP_Query($args);
$jobs = "";
$jobs .= "<div class='jobList'>";
if($query->have_posts()): while($query->have_posts()): $query->the_post();

$date = get_the_date('d/m/Y', get_the_ID());
$title = get_the_title();
$salary_range = get_post_meta( get_the_ID(), 'salary_range', true);
$total_jobs_link = get_post_meta( get_the_ID(), 'total_jobs_link', true);
$cv_library_link = get_post_meta(get_the_ID(), 'cv_library_link', true);
$location = get_post_meta( get_the_ID(), 'location', true);
$indeed = get_post_meta(get_the_ID(), 'indeed_link', true);
$description = get_the_content();

$jobs .= "<ul class='inner_blocks'>
	<li class='title'> Job Title </li>
	<li class='display'>".$title."</li>
	<li class='link'><a href='".$total_jobs_link."' target='_blank'> View on Total Jobs </a></li>
	<li style='height: 150px;float:right;width: 30%;background-image: linear-gradient(#0f46c7, #00237d);color: #fff;line-height: 24px;padding: 60px 30px;text-align: center;text-decoration: underline;cursor: pointer;' class='applyDirect directApply'>CLICK HERE TO APPLY DIRECTLY</li>

	<li class='title'>Salary Range</li>
	<li class='display'>".$salary_range."</li>
	<li class='link'><a href='".$cv_library_link."' target='_blank'> View on CV Library </a></li>

	<li class='title'> Location</li>
	<li class='display' style='border-bottom: 2px solid #fff;'>".$location."</li>
	<li class='link'><a href='".$indeed."' target='_blank'> View on Indeed </a></li>
</ul>

<ul class='inner_blocks_2'>
	<li class='title'>Description</li>
	<li class='display descriptionLabel'>".$description."</li>
	<li style='height: 150px;float:right;width: 30%;background-image: linear-gradient(#0f46c7, #00237d);color: #fff;line-height: 24px;padding: 60px 30px;text-align: center;text-decoration: underline;cursor: pointer;display:none;' class='applyDirectMobile directApply'>CLICK HERE TO APPLY DIRECTLY</li>
</ul>

";

endwhile;
endif;


$jobs .= "</div>";


echo $jobs;


	wp_die(); // this is required to terminate immediately and return a proper response
}