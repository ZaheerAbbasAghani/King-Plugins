<?php

/*

Plugin Name: LMS Weekly Report

Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html

Description:  Plugin reflect all of the time activity the student has done while in the course ( ex: each log in time, each log out time, total time for each duration) on a weekly basis to be sent via email to the admin ( and the report to be able to be pulled when necessary by the admin)

Version: 1.0

Author: Zaheer Abbas Aghani

Author URI: https://www.fiverr.com/zaheerabbasagha

License: GPLv3 or later

Text Domain: lms-weekly-report

Domain Path: /languages

*/



defined("ABSPATH") or die("No direct access!");

class LMSWeeklyReport {



function __construct() {

	add_action('init', array($this, 'lmswr_start_from_here'));

	add_action('wp_enqueue_scripts', array($this, 'lmswr_enqueue_script_front'));

	add_action('admin_enqueue_scripts', array($this, 'lmswr_enqueue_admin_script'));

	add_action( "wp_login", array($this,"lmswr_last_login_attempt"), 10, 2 );

	add_action( "admin_init", array($this,"lmswr_create_table"), 10, 2 );

	add_action('clear_auth_cookie', array($this,'lmswr_last_logout_attempt'), 10,2);

	add_action( 'show_user_profile', array($this,'custom_user_profile_fields' ));

	add_action( 'edit_user_profile', array($this,'custom_user_profile_fields' ));

	add_action('wp', array($this,'lms_schedule_event'));
	add_action ('mycronjob', array($this,'lms_send_weekly_report')); 
	

}







function lmswr_start_from_here() {

	require_once plugin_dir_path(__FILE__) . 'lmswr_front/lmswr_shortcode.php';
	require_once plugin_dir_path(__FILE__) . 'lmswr_back/lmswr_students_list_page.php';
	require_once plugin_dir_path(__FILE__) . 'lmswr_front/lmswr_process_in_out_accumulate.php';



}



// Enqueue Style and Scripts



function lmswr_enqueue_script_front() {

	//Style & Script

	wp_enqueue_style('lmswr-style', plugins_url('assets/css/lms.css', __FILE__),'1.0.0','all');


	wp_enqueue_script('lmswr-moment', plugins_url('assets/js/moment.js', __FILE__),array('jquery'),'2.27.0', true);

	wp_enqueue_script('lmswr-script', plugins_url('assets/js/lmswr.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_localize_script( 'lmswr-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
	



}




function lmswr_enqueue_admin_script(){

	// DataTable

	wp_enqueue_style('lmswr-admin', plugins_url('assets/css/lms-admin.css', __FILE__),'1.0.0','all');


	wp_enqueue_style('lmswr-dataTable', "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css",'1.10.21','all');

	wp_enqueue_script('lmswr-script', "https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js",array('jquery'),'1.10.21', true);



	wp_enqueue_script('lmswr-btn', "https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js",array('jquery'),'1.6.2', true);

	wp_enqueue_script('lmswr-jszip', "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",array('jquery'),'3.1.3', true);

	wp_enqueue_script('lmswr-jszip', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",array('jquery'),'0.1.53', true);

	wp_enqueue_script('lmswr-vfs_fonts', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",array('jquery'),'0.1.53', true);

	wp_enqueue_script('lmswr-btn-html5', "https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js",array('jquery'),'1.6.2', true);

	wp_enqueue_script('jquery-ui-accordion');



	wp_enqueue_script('lmswr-admin-script', plugins_url('assets/js/lmswr_admin.js', __FILE__),array('jquery'),'1.0.0', true);



}





//Creating Table In Database

function lmswr_create_table(){



	global $wpdb;

	$table_name = $wpdb->base_prefix.'student_info';

	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

	if ( ! $wpdb->get_var( $query ) == $table_name ) {



		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (

		  id mediumint(20) NOT NULL AUTO_INCREMENT,
		  user_id mediumint(20) NOT NULL,
		  user_name tinytext NOT NULL,
		  clock_in_time varchar(200) DEFAULT '' NOT NULL,
		  clock_out_time varchar(200) DEFAULT '' NOT NULL,
		  time_accumulation varchar(200) DEFAULT '' NOT NULL,
		  course_id varchar(50) DEFAULT '' NOT NULL,

		  PRIMARY KEY  (id)

		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $sql );



	}



}



// Store Clock In Information

function lmswr_last_login_attempt($user_login, $user){

	if (!session_id()) {

    	session_start();

	}



	if ( in_array( 'subscriber', (array) $user->roles ) ) {



	    $courses = get_user_meta($user->ID);
	 	$user_id = $user->ID;
	    $user_full_name = $courses['first_name'][0].' '.$courses['last_name'][0];
	    $clock_in_time = $courses['learndash-last-login'][0];

		global $wpdb;
		$table = $wpdb->prefix.'student_info';
		$data = array('user_id' => $user_id, 'user_name' => $user_full_name,'clock_in_time' => $clock_in_time);
		$format = array('%d','%s','%s');
		$wpdb->insert($table,$data,$format);

		//$_SESSION['clock_in_time']=$clock_in_time;

		//$my_id = $wpdb->insert_id;





	}

}



// Store Clock Out Information

function lmswr_last_logout_attempt(){



	$userinfo = wp_get_current_user();



	//check role

	$user = get_userdata( $userinfo->ID );

	

	if ( in_array( 'subscriber', (array) $user->roles ) ) {



	    $courses = get_user_meta($userinfo->ID);



	 	$user_id = $userinfo->ID;
	    $user_full_name = $courses['first_name'][0].' '.$courses['last_name'][0];
	    $clock_in_time_pre = $courses['learndash-last-login'][0];
	    $clock_in_time = new DateTime (date("Y-m-d H:i:s",$courses['learndash-last-login'][0]));
	    $clock_out_time = new DateTime (date("Y-m-d H:i:s"));
	    $difference = $clock_in_time->diff($clock_out_time);
	    $time_accumulation = $difference->format('%h')." Hours ".$difference->format('%i')." Minutes";
	    $clock_out_time = $clock_out_time->format('Y-m-d H:i:s');

	    

		global $wpdb;
		$table_name = $wpdb->prefix.'student_info';
		$clockintime = $wpdb->get_var( "SELECT clock_in_time FROM $table_name WHERE user_id=$user_id  ORDER BY id DESC LIMIT 1");


		$result = $wpdb->query($wpdb->prepare("UPDATE $table_name SET clock_out_time='$clock_out_time',time_accumulation='$time_accumulation' WHERE clock_in_time='".$clockintime."' AND user_id='".$user_id."' "));



	}

}





function custom_user_profile_fields($user){

  ?>

<h3>Student Report</h3><hr>

<table id="student_reports" class="display" style="width:100%; text-align: center;">
        <thead>
            <tr>
                <th>User Name</th>
				<th>Clock In Time</th>
				<th>Clock Out Time</th>
				<th>Time Accumulation</th>
            </tr>
        </thead>
        <tbody>
            
<?php 



global $wpdb;
$table_name = $wpdb->base_prefix.'student_info';
$user_id =  $user->ID;
$results = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id=$user_id",OBJECT );

if(!empty($results)){
	foreach ($results as $result) {
		echo "<tr>";
			echo "<td>".$result->user_name."</td>";
			echo "<td>".date("Y-m-d H:i:s",$result->clock_in_time)."</td>";
			echo "<td>".$result->clock_out_time."</td>";
			echo "<td>".$result->time_accumulation."</td>";
		echo "</tr>";
	}
}else{

		echo "<tr><td> Report Not Available.</td></tr>";	
}

?>

        </tbody>
        <tfoot>
            <tr>
             	<th>User Name</th>
				<th>Clock In Time</th>
				<th>Clock Out Time</th>
				<th>Time Accumulation</th>
            </tr>
        </tfoot>
    </table>


<?php 
}

// create a scheduled event (if it does not exist already)
function lms_schedule_event() {
    if( !wp_next_scheduled( 'mycronjob' ) ) {  
       wp_schedule_event( 1476118800, 'weekly', 'mycronjob' );  
    	//wp_schedule_event( time(), 'daily', 'mycronjob' );  
    }
}

function lms_send_weekly_report(){

global $wpdb;

$table_name = $wpdb->base_prefix.'student_info';

$date = date('Y-m-d H:i:s',time()-(7*86400)); // 7 days ago
//$today = date('Y-m-d H:i:s');
$week_before = strtotime($date);
$today = strtotime(date('Y-m-d H:i:s'));

//$sql = "SELECT * FROM $table_name WHERE date('Y-m-d H:i:s') <='$date' ";
$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE clock_in_time between '$week_before' and '$today' ", OBJECT );

$to = get_option('admin_email');
$subject = "Weekly Student Report";
$message .= "<table border='1' style='text-align:center;width:100%;' cellpadding='10'>";
$i = 1;
$message .= "<tr>";
    $message .= "<th>#</th>";
    $message .= "<th>Username</th>";
    $message .= "<th>Clock In Time</th>";
    $message .= "<th>Clock Out Time</th>";
    $message .= "<th>Time Accumulation</th>";
$message .= "</tr>";
foreach ($results as $result) {
    $message .= "<tr>";
    $message .= "<td>".$i."</td>";
    $message .= "<td>".$result->user_name."</td>";
    $message .= "<td>".date('Y-m-d H:i:s', $result->clock_in_time)."</td>";
    $message .= "<td>".$result->clock_out_time."</td>";
    $message .= "<td>".$result->time_accumulation."</td>";
    $message .= "</tr>";
    $i++;
}
$message .= "</table>";
$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail( $to, $subject, $message, $headers);



}





} // class ends



// CHECK WETHER CLASS EXISTS OR NOT.



if (class_exists('LMSWeeklyReport')) {
	$obj = new LMSWeeklyReport();
}