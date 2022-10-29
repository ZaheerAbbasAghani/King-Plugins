<?php



function ussf_particular_user_social_field($atts){

	$social_handle = "";

	$user = "";

   extract(shortcode_atts(array(

      'social_handle' => $social_handle,

   ), $atts));





   if(is_user_logged_in()){

//		$str = explode("_", $social_handle);

	   	$user .= "<form method='post' action='' id='ussf_form'>";
		$user .= "<input type='text' name='social_handler' id='social_handler' class='".$social_handle."' user_id='".get_current_user_id()."' value='".esc_attr( get_the_author_meta( $social_handle, get_current_user_id() ))."'> ";

		//$user .= "<p class='jpamessage'> ".esc_attr( get_the_author_meta( $social_handle, get_current_user_id() )).'</p>';

		$user .= "<input type='button' name='handle_submit' class='handle_submit' value='Submit'>";
	   	$user .= "</form>";



	}



	return $user;



}



add_shortcode("social_user" ,"ussf_particular_user_social_field");