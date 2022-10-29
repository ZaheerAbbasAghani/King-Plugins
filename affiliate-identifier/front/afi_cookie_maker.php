<?php 
function afi_set_new_cookie() {
    global $wp;
    // Getting Current URL
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    $url.= $_SERVER['HTTP_HOST'];   
    $url.= $_SERVER['REQUEST_URI'];    
    $key = get_option("cookie_name");

    if (strpos($url, $key) == true) {
    	$end = end(explode('=', $url));
        //echo $end;
    	setcookie($key, $end, time()+3600,COOKIEPATH, COOKIE_DOMAIN);
	}
}
add_action( 'init', 'afi_set_new_cookie');


