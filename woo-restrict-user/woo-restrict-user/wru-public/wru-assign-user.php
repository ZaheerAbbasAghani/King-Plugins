<?php

function sl_display_latest_post($social){


$social .="<div class='sl_wrap_social_latest_posts'>";

$social.="<div class='sl_header'> 
<img src='".plugin_dir_url(__FILE__)."assets/images/instagram200.png'>
<p class='instagram'>Follow us on Instagram <br><a href=''>@intercotradingco</a></p>

<div class='most_recent_post'>
<h3> Most Recent Instagram Post</h3>
</div>

<div class='sl_footer'>
<img src='".plugin_dir_url(__FILE__)."assets/images/intercotradingco.jpg'>
<a href='#'> Display the first two lines of text included in the post</a>
</div>";
$social.="</div>";
//End Instagram
$social.="<div class='sl_header'> 
<img src='".plugin_dir_url(__FILE__)."assets/images/twitter200.png'>
<p class='twitter'>Follow us on Twitter <br><a href=''>@intercotradingco</a></p>

<div class='most_recent_post'>

<a class='twitter-timeline' href='https://twitter.com/ZaheerAbbas66' data-tweet-limit='1' data-width='100%'></a>
<script async src='http://platform.twitter.com/widgets.js' charset='utf-8'></script>

</div>

<div class='sl_footer'>
<img src='".plugin_dir_url(__FILE__)."assets/images/intercotradingco.jpg'>
<a href='#'> Display the first two lines of text included in the post</a>
</div>";
$social.="</div>";
//End Twitter

$social.="<div class='sl_header'> 
<img src='".plugin_dir_url(__FILE__)."assets/images/facebook.png'>
<p class='facebook'>Follow us on Facebook <br><a href=''>@intercotradingco</a></p>

<div class='most_recent_post'>";

$page_name = 'IntercoTradingCo'; // Example: http://facebook.com/{PAGE_NAME}
$page_id = '183707678315489'; // can get form Facebook page settings
$app_id = '2590298711225134'; // can get form Developer Facebook Page
$app_secret = '59b9b43c425d4b41c39bef93ad9fe191'; // can get form Developer Facebook Page
$limit = 5;

function load_face_posts($page_id, $page_name, $app_id, $app_secret, $limit, $message_len) {
    $access_token = "https://graph.facebook.com/oauth/access_token?client_id=$app_id&client_secret=$app_secret&grant_type=client_credentials";
    $access_token = file_get_contents($access_token); // returns 'accesstoken=APP_TOKEN|APP_SECRET'
    $access_token = str_replace('access_token=', '', $access_token);
    $limit = 5;
    $data  = file_get_contents("https://graph.facebook.com/$page_name/posts?limit=$limit&access_token=$access_token");
    $data = json_decode($data, true);
    $posts = $data[data];
    //echo sizeof($posts);

    for($i=0; $i<sizeof($posts); $i++) {
        //echo $posts[$i][id];
        $link_id = str_replace($page_id."_", '', $posts[$i][id]);
        $message = $posts[$i][message];

        echo ($i+1).". <a target='_blank' href='https://www.facebook.com/AqualinkMMC/posts/".$link_id."'>".$message."</a><br>";
    }
}

load_face_posts($page_id, $page_name, $app_id, $app_secret, $limit, $message_len);

/*function curl_get_file_contents($URL) {
$ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $URL);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
 curl_setopt($ch, CURLOPT_TIMEOUT, 400);
 $contents = curl_exec($ch);
 $err  = curl_getinfo($ch,CURLINFO_HTTP_CODE);
 curl_close($ch);
$contents=json_decode($contents,true);
if ($contents) return $contents;
else return FALSE;
}

$access_token = '2590298711225134|KvJFVxsE4OPdWXzeK4zyphgsdQk';
$url = "https://graph.facebook.com/100269614875527/feed?access_token=$access_token";
$posts = curl_get_file_contents($url);*/



$social.="</div>

<div class='sl_footer'>
<img src='".plugin_dir_url(__FILE__)."assets/images/intercotradingco.jpg'>
<a href='#'> Display the first two lines of text included in the post</a>
</div>";




/*$access_str = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id=2590298711225134&client_secret=59b9b43c425d4b41c39bef93ad9fe191&grant_type=client_credentials');

parse_str($access_str);
$access_token = "2590298711225134|KvJFVxsE4OPdWXzeK4zyphgsdQk";
//Request the public posts. Replace "YOUR-PROFILE-NAME" with your profile name.

$json_str = file_get_contents('https://graph.facebook.com/100001084818574/feed?access_token='.$access_token);

//decode json string into array

$data = json_decode($json_str);*/


$social.="</div>";
//End Facebook


$social .="</div>";

return $social;
}
add_shortcode("social-latest", "sl_display_latest_post" );