<?php 

function v_job_board_table($jobs){

$jobs = "";

	
$args = array("post_type" => "v_job_board", "posts_per_page" => -1, 'meta_key' => 'shortlist', 'meta_value'       => 'yes');
$query = new WP_Query($args);

$jobs .= "<div class='vjobs'> 
<table>
<tr>
	<th> Date Posted </th>
	<th> Job Title </th>
	<th> Salary Range </th>
	<th> Location </th>
</tr>";

if($query->have_posts()): while($query->have_posts()): $query->the_post();

	$date = get_the_date('d/m/Y', get_the_ID());
	$title = get_the_title();
	$salary_range = get_post_meta( get_the_ID(), 'salary_range', true);
	$location = get_post_meta( get_the_ID(), 'location', true);

	$jobs .= "<tr data-id='".get_the_ID()."' class='ae-jobs'>
		<td>".$date."</td>
		<td>".$title."</td>
		<td>".$salary_range."</td>
		<td>".$location."</td>
	</tr>";

endwhile;
endif;

$jobs .= "</table>

<a href='#' class='fullList'> Click here for full list </a>
</div>";

return $jobs;
}

add_shortcode('job-board', 'v_job_board_table');