<?php 

// Same handler function...
add_action( 'wp_ajax_srm_save_rankings', 'srm_save_rankings' );
add_action( 'wp_ajax_nopriv_srm_save_rankings', 'srm_save_rankings' );
function srm_save_rankings() {
	global $wpdb;


$rank = $_POST['ranking_details'];
$rinfo = explode('_', $rank);

$info = [];
for ($i = 0; $i < count($rinfo); $i += 2) {
    if (array_key_exists($rinfo[$i], $info)) {
        $info[$rinfo[$i]] = $info[$rinfo[$i]] + $rinfo[$i + 1];
    } else {
        $info[$rinfo[$i]] = $rinfo[$i + 1];
    }
}


//Get the max value in the array.
$maxVal = max($info);

//Get all of the keys that contain the max value.
$maxValKeys = array_keys($info, $maxVal);

$last = end($maxValKeys);
$arr = array($last);



$html .= "<div class='ranking_results'>";
foreach ($arr as $key) {
	$term = get_term_by('term_id', $key , 'types'); 
	$args = array(
	    'post_type' => 'styles',
	    'tax_query' => array(
	        array (
	            'taxonomy' => 'types',
	            'field' => 'id',
	            'terms' => $key,
	        )
	    ),
	);
	// The Query
	$query = new WP_Query( $args );
 	
	if($query->have_posts()): while($query->have_posts()): $query->the_post();

		$thumb = get_the_post_thumbnail_url();
    	$html .= "<div style='clear:both;'><div class='leftSide'><img src='".$thumb."'></div>";
		$html .= "<div class='rightSide'><p> You have finished the test! </p>";
		$html .= "<p>Your style is:</p> <h3>".$term->name."</h3></div></div>";
	
	endwhile;
	endif;

}
$html .= "</div>";

if(is_user_logged_in()):

	global $wpdb; 
	$table_name = $wpdb->base_prefix.'srm_rankings';
	$user_id = get_current_user_id();

	$query = "SELECT * FROM $table_name WHERE user_id='$user_id' ";
	$query_results = $wpdb->get_results($query);
	if(count($query_results) == 0) {
		$rowResult=$wpdb->insert($table_name, 
				array("user_id" => $user_id,"ranking" => json_encode($info), "ranking_result" => $term->name),
				array("%d","%s","%s")
		);
	}


	$response = array("rhtml" => $html,"style" => strtolower(sanitize_title($term->name)), "log" => 1);
	wp_send_json( $response);
else:

	$response = array("log" => 0, "message" => "You must be login to see results.");
	setcookie("ranking_details", json_encode($info), time() + (86400 * 30), "/"); // 86400 = 1 day
	setcookie("ranking_style", $term->name, time() + (86400 * 30), "/"); // 86400 = 1 day

	wp_send_json( $response);
	



endif;

	wp_die();
}