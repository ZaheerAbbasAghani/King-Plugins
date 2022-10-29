<?php 

function afi_custom_fields_generator($atts=""){

$a = $atts[0];


if(is_user_logged_in()){

$field = "<form method='post' action='' id='afi_get_info'>
	<input type='text' name='".$a."' id='".$a."'>
	<input type='submit' value='Submit'>
	</form>";
return $field;
}else{
	return "Please Login.";
}

}
add_shortcode( "send_custom_field","afi_custom_fields_generator");