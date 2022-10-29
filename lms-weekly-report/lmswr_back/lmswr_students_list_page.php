<?php

// create custom plugin settings menu

add_action('admin_menu', 'my_cool_plugin_create_menu');



function my_cool_plugin_create_menu() {



	//create new top-level menu

	add_menu_page('Students Report', 'Students Report', 'manage_options', "lmswr_students_reports", 'lmswr_students_reports_page' , 'dashicons-admin-users',35 );



	//call register settings function

	add_action( 'admin_init', 'register_my_cool_plugin_settings' );

}





function register_my_cool_plugin_settings() {

	//register our settings

	/*register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );

	register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );

	register_setting( 'my-cool-plugin-settings-group', 'option_etc' );*/

}



function lmswr_students_reports_page() {

?>

<div class="wrap">

<h1>Students Reports</h1><hr>



<?php

$argsTwo = array(
    'post_type' => 'sfwd-courses',
    'post_status' => 'publish',
    'order' => 'ASC',
    'orderby' => 'ID',
);

$query = new WP_Query($argsTwo);

global $wpdb;
$table_name = $wpdb->base_prefix.'student_info';

echo '<div id="lmswr_accordion">';
$i=1;
if($query->have_posts()): while($query->have_posts()): $query->the_post();

//echo get_the_title().'<br>';

echo '<h3>'.get_the_title().'</h3>';
$id = get_the_ID();

$results=$wpdb->get_results( "SELECT * FROM $table_name WHERE course_id='$id' ",OBJECT);

echo '<div><table id="student_reports_'.$i.'" class="display" style="width:100%">
		<thead>
            <tr>
                <th>Name</th>
                <th>Clock In Time</th>
                <th>Clock Out Time</th>
                <th>Time Accumulation</th>
            </tr>
        </thead>
        <tbody>';
foreach ($results as $result) {
	echo "<tr>";
	echo "<td>".$result->user_name."</td>";
	echo "<td>".$result->clock_in_time."</td>";
	echo "<td>".$result->clock_out_time."</td>";
	echo "<td>".$result->time_accumulation."</td>";
	echo "</tr>";
}
echo "</tbody>
</table></div>";
$i++;
endwhile;
endif;
echo "</div>";
/*echo "<pre>";
    print_r($query);
echo "</pre>";*/





//[uo_time_course_completed course-id="30152"]
//echo do_shortcode('[uo_time course-id="30152"]');
//echo do_shortcode('[uo_time_course_completed course-id="30152"]');

/*global $wpdb;

$table_name = $wpdb->base_prefix.'student_info';

//$date = date('Y-m-d H:i:s',time()-(7*86400)); // 7 days ago
//$today = date('Y-m-d H:i:s');
//$week_before = strtotime($date);
//$today = strtotime(date('Y-m-d H:i:s'));

//$sql = "SELECT * FROM $table_name WHERE date('Y-m-d H:i:s') <='$date' ";
$results = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT );

foreach ($results as $result) {
	echo $result->user_id;
}
*/
//$to = get_option('admin_email');
/*$to = "aghanizaheer@gmail.com";
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
echo wp_mail( $to, $subject, $message, $headers);*/


/*$results = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT );



echo "<pre>";

    print_r($results);

echo "</pre>";
*/


/*$args = array(

    'role'    => 'subscriber',
    'orderby' => 'user_nicename',
    'order'   => 'ASC'

);

$users = get_users( $args );


echo '<ul>';

foreach ( $users as $user ) {

    

    echo '<li>' . esc_html( $user->display_name ) . '[' . esc_html( $user->user_email ) . ']</li>';



    $courses = get_user_meta ( $user->ID);
echo "<pre>";
    print_r($courses);
echo "</pre>";

}

echo '</ul>';
*/


?>



</div>

<?php } ?>