<?php

function gstl_add_remove_members(){


if( current_user_can('dj-employee') || current_user_can('administrator') ) {

$args = array("post_type" => "gstl_events", "posts_per_page" => -1);

$query = new WP_Query($args);
$djs="";
$djs .= '<div id="gstl_accordion" class="gstl_post_list">';
$i=1;
if($query->have_posts()): while($query->have_posts()): $query->the_post();

$djlist = get_post_meta(get_the_ID(),'gstl_dj_event', false);



// Check if dj allowed to access an event 
//if(in_array(get_current_user_id(), $djlist) || current_user_can('administrator')){

if(in_array(get_current_user_id(), $djlist)){

$djs .= " <h3>".get_the_title()."</h3><div>";

$djs .= "<p>";
$djs .= "<form method='post' action='' id='member_insert_form' onkeydown='return event.key != ".'Enter'.";'><input type='text' id='insert_members' placeholder='Write member name here'><input type='hidden' class='post_id' value='".get_the_ID()."'><input type='button' class='btn_insert' value='Add to list'></form>";

	$response = get_post_meta(get_the_ID(), 'dj_'.get_current_user_id(), false);


if(!empty($response)){

	foreach ($response as $user) {

		$djs .= '<div class="box membox"><h4 for="gstl_location">'. esc_html( $user ).'</h4>';

		if(!in_array($user, $response)){
			$djs .= '<a href="#" class="button button-primary gstl_member" member-id="'.$user.'" post-id="'.get_the_ID().'"> Add to list </a></div>';
		}else{
			$djs .= '<a href="#" class="button button-default gstl_member_delete" member-id="'.$user.'" post-id="'.get_the_ID().'"> Remove </a></div>';
		}
	}
}else{
	$djs .=  "<p style='margin-top:20px;'> No user added yet. </p>";
}

}else{
	//$djs .= "<p style='margin-top:20px;'> Permission denied for this page. </p>";
}

$djs .= "</div>";


endwhile;
endif;

}else{
	$djs .= "<h3> This page is only accessible for  DJs</h3>";
} // Role Check end here



return $djs;
}
add_shortcode("djs", "gstl_add_remove_members");