<?php 
function fetch_custom_field_func($atts=""){

$field = $atts[0];

if($field == "user"){
	$current_user = get_current_user_id();
	$refby = get_user_meta($current_user,"wizz_user", true);
	$user .= $refby;
}else{
	$current_user = get_current_user_id();
	$refby = get_user_meta($current_user,$field, true);
	$user .= $refby;
}

return $user;
}
add_shortcode("fetch_custom_field", "fetch_custom_field_func");