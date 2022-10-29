<?php 

//Update Events
add_action( 'wp_ajax_bpem_event_update_response', 'bpem_event_update_response' );
add_action( 'wp_ajax_nopriv_bpem_event_update_response', 'bpem_event_update_response' );

function bpem_event_update_response() {

//$image_url     = sanitize_text_field($_POST['ev_image']);

$ev_id 	    	 = sanitize_text_field($_POST['ev_id']); 
$title 	    	 = sanitize_text_field($_POST['ev_title']); 
$content 	     = $_POST['ev_desc'];
//$image_url     	 = sanitize_text_field($_POST['ev_image']);
$location 	     = sanitize_text_field($_POST['ev_location']);
$start_date      = sanitize_text_field($_POST['ev_start_date']);
$start_time      = sanitize_text_field($_POST['ev_start_time']);
$end_date 	     = sanitize_text_field($_POST['ev_end_date']);
$end_time 	     = sanitize_text_field($_POST['ev_end_time']);
$ev_organizer    = sanitize_text_field($_POST['ev_organizer']);
$ev_organizer_url= sanitize_text_field($_POST['ev_organizer_url']);

//$group 		     = sanitize_text_field($_POST['ev_group']);

$post_id = wp_update_post(array (

'ID'	 		=> 	$ev_id,
'post_title' 	=> 	$title,
'post_content' 	=> 	$content,
//'post_status' 	=> 'publish'

));

update_post_meta( $post_id, 'evn_location', $location );
update_post_meta( $post_id, 'evn_startDate', $start_date);
update_post_meta( $post_id, 'evn_startTime', $start_time);
update_post_meta( $post_id, 'evn_endDate', $end_date);
update_post_meta( $post_id, 'evn_endTime', $end_time);
update_post_meta( $post_id, 'evn_organizer', $ev_organizer);
update_post_meta( $post_id, 'evn_organizer_url', $ev_organizer_url);

/*update_post_meta( $post_id, 'evn_group', $group);

update_post_meta( $post_id, 'evn_group_slug', sanitize_title($group));*/



/*require_once(ABSPATH . 'wp-admin/includes/media.php');

require_once(ABSPATH . 'wp-admin/includes/file.php');

require_once(ABSPATH . 'wp-admin/includes/image.php');



$image = media_sideload_image($image_url, $post_id,"Image",'id');

set_post_thumbnail( $post_id, $image );

echo "Success";*/





wp_die();

}