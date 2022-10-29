<?php 

function afi_custom_fields_generator($atts=""){

$a = $atts[0];

if(is_user_logged_in()){

$m = get_user_meta(get_current_user_id(),$a,true) == "" ? "" : get_user_meta(get_current_user_id(),$a,true);
$rand = rand(0,1000);
$field = "<form method='post' action='' class='afi_get_info'>
	<input type='text' name='".$a."' id='".$a."' value='".$m."'>
	<input type='submit' value='Submit'>
	<div class='afi_message' id='".$rand."'></div>
	</form>";
return $field;
}else{
	return "Please Login.";
}

}
add_shortcode("send_custom_field","afi_custom_fields_generator");