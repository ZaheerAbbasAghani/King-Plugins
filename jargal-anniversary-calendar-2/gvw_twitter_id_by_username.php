<?php

$url = "https://api.twitter.com/labs/2/users/by?usernames=ZaheerAbbas66&user.fields=created_at,description,pinned_tweet_id";
$token = "AAAAAAAAAAAAAAAAAAAAAJFhGwEAAAAADyCrd1B2gPwZz17SnwrXI7Qpiec%3DHfxgncV8VhNMaIrXhfwBJTApT8NK5gZYJJr6Av1hmhky7g1Lh2";
$response = wp_remote_get($url, array(
  'headers' => array(
  'Authorization' => ' Bearer '.$token
),
));
$flagJson = wp_remote_retrieve_body($response);
$res = json_decode( $flagJson );


