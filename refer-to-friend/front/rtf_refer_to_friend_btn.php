<?php 

function rtf_refer_to_friend($refer){

$refer = "";

$refer = "<div class='rtf_refer_to_friend_wrapper'><a href='#' class='rtf_refer_to_friend_btn'> Share with your friend! </a> </div>";

return $refer;
}
add_shortcode("ref_button", "rtf_refer_to_friend");