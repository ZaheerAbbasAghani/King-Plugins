<?php 

function prox_list_of_puzzles_shortcode($puzzles){

$args = array("post_type" => "puzzles");

$query  = new WP_Query($args);

$puzzles .= "<div class='prox_wrapper'>";
if($query->have_posts()): while($query->have_posts()): $query->the_post();
$puzzle_desc = get_post_meta(get_the_ID(),"prox_numer_puzzle",true);
$puzzles .= "<div class='prox_box' id='post_".get_the_ID()."'>";
	$puzzles .= "<h4>".get_the_title()."</h4>";
	$puzzles .= "<p>".get_the_content()."</p>";
	$puzzles .= "<form method='post' action='' class='puzzle_form'>

	<input type='text' placeholder='Enter first answer' style='width:32%;' class='answer1' required>
	<input type='text'  placeholder='Enter second answer' style='width:32%;' class='answer2' required>
	<input type='text'  placeholder='Enter third answer' style='width:32%;' class='answer3' required>";
	$puzzles .= "<h4>Number Puzzle</h4>";
	$puzzles .= $puzzle_desc;

	$puzzles .="<input type='number'  placeholder='Enter puzzle number' style='width:98%;margin-bottom:20px;margin-top:10px;' id='puzzle_number' required>";

	$puzzles .="<input type='submit' value='Submit' class='submit_puzzle'>";


	$puzzles .= "</form>";
$puzzles .= "</div>";

endwhile;
endif;

$puzzles .= "</div>";

return $puzzles;
}
add_shortcode("puzzles","prox_list_of_puzzles_shortcode");