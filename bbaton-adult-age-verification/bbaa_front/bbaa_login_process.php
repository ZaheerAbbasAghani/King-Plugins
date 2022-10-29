<?php 

add_action( 'wp_ajax_nopriv_bbaa_login_process', 'bbaa_login_process' );
add_action( 'wp_ajax_bbaa_login_process', 'bbaa_login_process' );
function bbaa_login_process() {
if (!session_id()) {
    session_start();
}

$client_id = esc_attr(get_option('bbaa_client_id'));
$client_secret = esc_attr(get_option('bbaa_client_secret'));
$redirect_uri= esc_attr( get_option('bbaa_redirect_url') );
$authorization_code = $_POST['code'];



$url = "http://bauth.bbaton.com/oauth/authorize?client_id=$client_id&redirect_uri=$redirect_uri&response_type=code&scope=read_profile";
$_SESSION["backUrl"] = $_POST['backUrl'];

echo $url;

/*$response = wp_remote_get( $url );

$body = wp_remote_retrieve_body( $response );
$data = json_deco
de($response);
*/


// /print_r($response);




	wp_die(); // this is required to terminate immediately and return a proper response
}