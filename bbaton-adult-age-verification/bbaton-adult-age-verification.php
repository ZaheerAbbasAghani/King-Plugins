<?php
/*
Plugin Name: BBaton Adult Age Verification
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin is used to verify users by adult age verification process.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://www.fiverr.com/zaheerabbasagha
License: GPLv3 or later
Text Domain: bbaton-adult-age-verification
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class BBatonAdultAgeVerification {

function __construct() {

if (!session_id()) {
    session_start();
}
	
	add_action('init', array($this, 'bbaa_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'bbaa_enqueue_script_front'));
	add_filter( "the_content", array($this,"bbaa_lock_page_content"),10,2);
	add_action('init', array($this, 'bbaa_analyze_child'));
	add_action('wp_head', array($this, 'bbaa_redirect_back'));
	//add_action('wp_head', array($this,'bbaton_check_confirmed_pages'));
	//add_filter( 'theme_page_templates', array($this,'redirect_page_template'));	

}


function bbaa_start_from_here() {
	require_once plugin_dir_path(__FILE__).'bbaa_back/bbaa_api_settings.php';
	require_once plugin_dir_path(__FILE__).'bbaa_front/bbaa_login_process.php';
	// /require_once plugin_dir_path(__FILE__).'bbaa_back/bbaa_redirect.php';
	
	
}

// Enqueue Style and Scripts
function bbaa_enqueue_script_front() {
//Style & Script
wp_enqueue_style('bbaa-style', plugins_url('assets/css/bbaa.css', __FILE__),'1.0.0','all');
wp_enqueue_script('bbaa-script', plugins_url('assets/js/bbaa.js', __FILE__),array('jquery'),'1.0.0', true);

// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
wp_localize_script( 'bbaa-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

wp_enqueue_script('bbaa-confirm', plugins_url('assets/js/bbaa_confirm.js', __FILE__),array('jquery'),'1.0.0', false);


}

// Lock Page
function bbaa_lock_page_content($content){
	$heading = get_option('bbaa_headings');
	$bbaa_lock_pages =  get_option('bbaa_lock_pages'); 
	$image_url = plugins_url('assets/', __FILE__);
	$url = parse_url($_SERVER['REQUEST_URI']);
	if(in_array(get_the_ID(), $bbaa_lock_pages['pages']) && !isset($url['query'])){
	 if(is_page(get_the_ID())){
	 	echo "<div class='bbaa'>
	 	<div class='num_headings'><h2>1</h2><h1>9</h1></div>
	 	<h3>본 내용은 청소년 유해매체물로서 정보통신망 이용촉진법 및 정보보호 등에 관한 법률 및 청소년 보호법 규정에 의하여 19세 미만의 청소년은 사용할 수 없습니다. <br/><br/><br/> 익명으로 성인인증 하기</h3>
	 	<a href='#'><img src='".$image_url."bbaa_button.png'/></a></div>";
	 	echo "<script>jQuery('#kboard-default-list').remove();</script>";
	 	echo "<style> html{overflow-y:hidden !important;} </style>";

	 }
	}else{
		return $content;	  	
	}

}

function bbaa_analyze_child(){

	$bbaa_lock_pages =  get_option('bbaa_lock_pages'); 
	//print_r($bbaa_lock_pages);

	$array = array();

	foreach ($bbaa_lock_pages['pages'] as $page) {
		array_push($array, $page);

		$mypages = get_pages(array( 'child_of' => $page));
		$i = 0;
		foreach ($mypages as $page) {
			array_push($array, $page->ID);
			$i++;
		}
	}
	//print_r($array);
	$bbaa_lock_pages['pages'] = $array;
	update_option( "bbaa_lock_pages", $bbaa_lock_pages);	
	//print_r($bbaa_lock_pages);


}


function bbaa_redirect_back () { 



if(isset($_GET['code'])){

//$previous = $_SERVER['HTTP_REFERER'];
echo "<div class='confirm_done' style='position:fixed;top:0px;left:0px;right:0px;bottom:0px; background:#ddd;z-index:999999;text-align:center;back'><h3>인증 확인 중...</h3> ";

$url = 'http://bauth.bbaton.com/oauth/token';
$client_id = esc_attr(get_option('bbaa_client_id'));
$client_secret = esc_attr(get_option('bbaa_client_secret'));
$redirect_uri= esc_attr( get_option('bbaa_redirect_url') );
$basicauth = 'Basic ' . base64_encode( $client_id. ':' . $client_secret);

$headers = array(
'Authorization' => $basicauth,
'Content-type' => 'application/x-www-form-urlencoded'
);

$response = wp_remote_post( $url, array(
	'method' => 'POST',
	'timeout' => 60,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => $headers,
	'body' => array(
	'grant_type' => 'authorization_code',
	'redirect_uri' => $redirect_uri,
	'code' => $_GET['code']
	),
	'cookies' => array()
	)
);

$body = wp_remote_retrieve_body( $response );
$data = json_decode($body);

//print_r($data);

//if(json_decode($body)){
if(!empty($data)) {
	$url1 = 'http://bapi.bbaton.com/v1/user/me';
	$token = $data->access_token;
	$response = wp_remote_get($url1, array(
			'headers' => array(
			'Authorization' => ' Bearer '.$token
		),
	));
	$flagJson = wp_remote_retrieve_body($response);
	$result = json_decode( $flagJson );
	

	if(str_replace('"', '',$result->adult_flag) == "N"){
		echo "<p class='adult_no_flag' style='display:none;' >".str_replace('"', '',$result->adult_flag) ."</p>";
	}

	//die();

}

echo "<script>

 setTimeout(function() {
 if(jQuery('.adult_no_flag').text()=='N'){
 		
	 	jQuery('.confirm_done').html('<h3>성인인증에 실패하였습니다</h3>');
	 	var site_url = JSON.parse(localStorage.getItem('site_url'));
	 	window.location.replace(site_url);
		if(site_url !== null){
			var last_element = site_url[site_url.length-1];
			window.localStorage.removeItem(last_element);
			localStorage.removeItem(last_element);
			 localStorage.clear();
		}

 }else{
		var site_url = JSON.parse(localStorage.getItem('site_url'));
		if(site_url !== null){
			var last_element = site_url[site_url.length-1];
			window.location.replace(last_element);
		}
	}

}, 3000);

</script>"; 

}


echo "</div>";
}



} // class ends


// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('BBatonAdultAgeVerification')) {
	$obj = new BBatonAdultAgeVerification();
}