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
    //echo $url;
    if(strpos($url, $key) == true) {

		$mystring = ltrim(strstr($url, '?'), '?');
		$ref = strtok($mystring, '=');
		
		if($ref == get_option("cookie_name")){
				
			// creating key
			$with_dollar = strtok($ref, '&');
			$txt = str_replace("=", "", $with_dollar);

			// creating value
			$filename = basename($url);
			$value = ltrim(strstr($url, '='), '=');
			$final = strtok($value, '&');
	
			setcookie($key,$final,time()+3600,COOKIEPATH,COOKIE_DOMAIN);
			
		}
       
	} 
}
add_action( 'init', 'afi_set_new_cookie');