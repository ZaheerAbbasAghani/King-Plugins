<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_floor_plan_maker", "sqr_floor_plan_maker");
add_action("wp_ajax_nopriv_sqr_floor_plan_maker", "sqr_floor_plan_maker");

function sqr_floor_plan_maker(){


	global $wpdb;
	$table_name = $wpdb->prefix . 'sqr_squizz_tables';

	$startDate = $_POST['startDate'];
	$endDate 	= $_POST['endDate'];
	$booked_spot = 'a:68:{i:0;s:3:" 71";i:1;s:3:" 72";i:2;s:3:" 73";i:3;s:3:" 74";i:4;s:3:" 75";i:5;s:3:" 76";i:6;s:3:" 77";i:7;s:3:" 78";i:8;s:3:" 79";i:9;s:3:" 80";i:10;s:3:" 83";i:11;s:3:" 84";i:12;s:4:" 106";i:13;s:4:" 107";i:14;s:4:" 108";i:15;s:4:" 109";i:16;s:4:" 110";i:17;s:4:" 111";i:18;s:4:" 112";i:19;s:4:" 113";i:20;s:4:" 114";i:21;s:4:" 115";i:22;s:4:" 118";i:23;s:4:" 119";i:24;s:4:" 212";i:25;s:4:" 213";i:26;s:4:" 215";i:27;s:4:" 216";i:28;s:4:" 218";i:29;s:4:" 219";i:30;s:4:" 247";i:31;s:4:" 248";i:32;s:4:" 250";i:33;s:4:" 251";i:34;s:4:" 253";i:35;s:4:" 254";i:36;s:4:" 257";i:37;s:4:" 258";i:38;s:4:" 259";i:39;s:4:" 260";i:40;s:4:" 282";i:41;s:4:" 283";i:42;s:4:" 285";i:43;s:4:" 286";i:44;s:4:" 288";i:45;s:4:" 289";i:46;s:4:" 292";i:47;s:4:" 293";i:48;s:4:" 294";i:49;s:4:" 295";i:50;s:4:" 298";i:51;s:4:" 299";i:52;s:4:" 301";i:53;s:4:" 302";i:54;s:4:" 304";i:55;s:4:" 305";i:56;s:4:" 317";i:57;s:4:" 318";i:58;s:4:" 320";i:59;s:4:" 321";i:60;s:4:" 323";i:61;s:4:" 324";i:62;s:4:" 333";i:63;s:4:" 334";i:64;s:4:" 336";i:65;s:4:" 337";i:66;s:4:" 339";i:67;s:4:" 340";}';

	$period = new DatePeriod(
	     new DateTime($startDate),
	     new DateInterval('P1D'),
	     new DateTime($endDate)
	);

	$now = current_time('mysql');
	foreach ($period as $key => $value) {
  
		$dt = $value->format('Y-m-d');

		if ($checkIfExists == NULL) {

			$checkIfExists = $wpdb->get_var("SELECT choosen_date FROM $table_name WHERE choosen_date = '$dt'");

			$insert_array = array(
		        'choosen_date' 	=> $dt,
		        'spots' 		=> $booked_spot,
		        'created_at' 	=> $now
			);
			$success = $wpdb->insert($table_name,$insert_array);
			


		}


	}


	echo "New Floor Plan Created.";

	wp_die();
}