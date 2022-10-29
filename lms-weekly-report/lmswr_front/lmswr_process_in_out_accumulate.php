<?php 

add_action( 'wp_ajax_lmswr_process_in_out_accumulate','lmswr_process_in_out_accumulate');
add_action( 'wp_ajax_nopriv_lmswr_process_in_out_accumulate','lmswr_process_in_out_accumulate' );

function lmswr_process_in_out_accumulate() {
	global $wpdb; // this is how you get access to the database

	$clock_in_time = $_POST['clock_in_time'][0];
	$course_id = $_POST['course_id'];
	$clock_out_time = $_POST['clock_out_time'];
	$user_id = get_current_user_id();
	$author_obj = get_user_by('id', $user_id);
	$user_full_name = $author_obj->first_name.' '.$author_obj->last_name;;

	$clock_in_time_num = strtotime($_POST['clock_in_time'][0]);
	$clock_out_time_num = strtotime($_POST['clock_out_time']);
	$diff = abs($clock_out_time_num - $clock_in_time_num);  
 
	$years = floor($diff / (365*60*60*24));  
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));  
	$days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24)); 
	$hours = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24) 
	                               / (60*60));  
	$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24  
	                      - $hours*60*60)/ 60);  
	$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 
	            - $hours*60*60 - $minutes*60));  
	$time_accumulation = "Hours: ".$hours." Minutes: ".$minutes." Seconds: ".$seconds;


	$table = $wpdb->prefix.'student_info';
	$data = array('user_id' => $user_id, 'user_name' => $user_full_name,'clock_in_time' => $clock_in_time,"clock_out_time" => $clock_out_time,"time_accumulation" => $time_accumulation, "course_id" => $course_id);
	$format = array('%d','%s','%s','%s','%s','%d');
	$wpdb->insert($table,$data,$format);
	$lastid = $wpdb->insert_id;
	$mylink = $wpdb->get_row( "SELECT course_id FROM $table WHERE id=$lastid ");
	echo $mylink->course_id; 

	wp_die(); // this is required to terminate immediately and return a proper response
}