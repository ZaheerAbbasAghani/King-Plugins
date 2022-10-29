<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_rss_update_now', 'rss_update_now' );
add_action( 'wp_ajax_nopriv_rss_update_now', 'rss_update_now' );

function rss_update_now() {

$wp_request_url =  esc_attr( get_option('rss_api_url') );
$wp_request_headers = array(
  'ApiKey' => get_option('rss_api_key')
);
$wp_response = wp_remote_request(
  $wp_request_url,
  array(
      'method'    => 'GET',
      'headers'   => $wp_request_headers
  )
);

$json = $wp_response['body'];
$recods = json_decode($json)->myDivisions;

$time_period = get_option('rss_update_how_often');
$time = $time_period['rss_time_period'];

if($time == 72){
	set_transient('rss_divisions', $recods, $time * HOUR_IN_SECONDS );	
  echo "Data Updated Successfully.";
}
elseif ($time == 24) {
	set_transient('rss_divisions', $recods, $time * HOUR_IN_SECONDS );
  echo "Data Updated Successfully.";
}
elseif ($time == 7) {
	set_transient('rss_divisions', $recods, $time * DAY_IN_SECONDS );
  echo "Data Updated Successfully.";
}else{
	echo "Please first choose <b> How often update data? </b>";
}



wp_die();
}