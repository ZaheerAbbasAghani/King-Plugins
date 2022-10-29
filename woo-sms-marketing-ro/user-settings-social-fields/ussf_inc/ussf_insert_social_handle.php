<?php 

add_action( 'wp_ajax_ussf_insert_social_handle', 'ussf_insert_social_handle' );
add_action( 'wp_ajax_nopriv_ussf_insert_social_handle', 'ussf_insert_social_handle' );

function ussf_insert_social_handle() {


$social_handler_url = $_POST['social_handler_url'];
$social_handler_val = $_POST['social_handler'];
$current_user = get_current_user_id();

update_usermeta($current_user, $social_handler_val, $social_handler_url);

wp_die();

}