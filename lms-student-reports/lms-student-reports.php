<?php
/*
Plugin Name: LMS Student Reports
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: It would create a report that tells me the student's name, their professional license number, the course they took, the date they passed the exam and my Professional number. I want to be able to export this information on an excel spreadsheet
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: lms-course-reports
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class LMSStudentReports {

function __construct() {
	add_action('init', array($this, 'lsr_start_from_here'));
	//add_action('wp_enqueue_scripts', array($this, 'lsr_enqueue_script_front'));
	add_action('admin_enqueue_scripts',array($this,'lsr_enqueue_script_dashboard'));
	//add_action( 'add_meta_boxes', array($this,'lsr_sfwd_courses_metaboxes') );
	//add_action( 'save_post_sfwd-courses', array($this, 'lsr_sfwd_courses_save_post') );   


	add_action( 'add_meta_boxes', array($this, 'course_id_details_meta_box') );
	add_action( 'save_post', array($this, 'save_global_notice_meta_box_data') );
}



function lsr_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/lsr_options_page.php';

}

// Enqueue Style and Scripts

function lsr_enqueue_script_dashboard() {

	echo $hook;
	
	//Style & Script
	wp_enqueue_style('lsr-style', plugins_url('assets/css/lsr.css', __FILE__),'1.0.0','all');
		
	wp_enqueue_style('lsr-datatables', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css','1.11.5','all');

    wp_enqueue_style('lsr-dt', 'https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css','1.1.2','all');

    wp_enqueue_script('lsr-datatables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',array('jquery'),'1.11.5', true);

    wp_enqueue_script('lsr-moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js',array('jquery'),'2.18.1', true);

    wp_enqueue_script('lsr-dateTime', 'https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js',array('jquery'),'1.1.2', true);

	wp_enqueue_script('lmswr-btn', "https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js",array('jquery'),'1.6.2', true);

	wp_enqueue_script('lmswr-jszip', "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",array('jquery'),'3.1.3', true);

	wp_enqueue_script('lmswr-jszip', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",array('jquery'),'0.1.53', true);

	wp_enqueue_script('lmswr-vfs_fonts', "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",array('jquery'),'0.1.53', true);

	wp_enqueue_script('lmswr-btn-html5', "https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js",array('jquery'),'1.6.2', true);
	
	
	wp_enqueue_script('lsr-script', plugins_url('assets/js/lsr.js', __FILE__),array('jquery'),'1.0.0', true);


}


function lsr_sfwd_courses_metaboxes( ) {
   global $wp_meta_boxes;
   add_meta_box('postfunctiondiv', __('Enter Course ID'), array($this,'lsr_sfwd_courses_metaboxes_html'), 'sfwd-courses', 'side', 'high');


  // add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null )
}



function course_id_details_meta_box() {

    add_meta_box(
        'course-num',
        __( 'Enter Course ID', 'lms-course-reports' ),
        array($this, 'course_id_details_meta_box_callback'),
        'sfwd-courses',
        'side',
        'high'
    );
}

function course_id_details_meta_box_callback($post){

	wp_nonce_field( 'lms_course_id_nonce', 'lms_course_id_nonce' );
	$course_id = get_post_meta( $post->ID, '_course_id', true );

	echo '<div class="row mb-3">
        <label for="CourseID" class="col-sm-2 col-form-label">Course ID</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="CourseID" name="course_id" value="'.esc_attr( $course_id ).'" placeholder="Course ID" style="width:100%;">
        </div>
    </div>';

}

function save_global_notice_meta_box_data( $post_id ) {


    // Check if our nonce is set.
    if ( ! isset( $_POST['lms_course_id_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['lms_course_id_nonce'], 'lms_course_id_nonce' ) && ! wp_verify_nonce( $_POST['duration_nonce'], 'duration_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
  
    // Make sure that it is set.
    if ( ! isset( $_POST['course_id']  ) ) {
        return;
    }

    // Sanitize user input.
    $course_id = sanitize_text_field( $_POST['course_id'] );
  
    // Update the meta field in the database.
    update_post_meta( $post_id, '_course_id', $course_id );
}





} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('LMSStudentReports')) {
	$obj = new LMSStudentReports();
}