<?php
/*
Plugin Name: Jargal Anniversary Calendar
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: Display Anniversary Dates of Articles In Calendar.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: jargal-anniversary-calendar
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class JargalAnniversayCalendar {

function __construct() {
	add_action('init', array($this, 'jac_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'jac_enqueue_script_front'));
    add_filter('manage_post_posts_columns',array($this,'misha_price_and_featured_columns'));
    add_action('manage_posts_custom_column',array($this,'misha_populate_both_columns'),10,2);
    add_action('quick_edit_custom_box',array($this,'misha_quick_edit_fields'), 10, 2);
    add_action( 'save_post', array($this,'misha_quick_edit_save' ));
    add_action( 'admin_enqueue_scripts', array($this,'misha_enqueue_quick_edit_population' ));
    add_action( "init",array($this,"stick_anniversay_post_on_top"));
}



function jac_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'jac_front/jac_shortcode.php';
	require_once plugin_dir_path(__FILE__) . 'jac_front/jac_enable_disable_post.php';
 
}

// Enqueue Style and Scripts

function jac_enqueue_script_front() {


wp_enqueue_style('jsc-main-css',plugins_url('assets/animated-event-calendar/dist/simple-calendar',__FILE__),'','all');

wp_enqueue_script('jsc-main-js',plugins_url('assets/animated-event-calendar/dist/jquery.simple-calendar.min',__FILE__),array('jquery'),'', false);


//Style & Script
wp_enqueue_style('jac-style', plugins_url('assets/css/jac.css', __FILE__),'1.0.0','all');
wp_enqueue_script('jac-script', plugins_url('assets/js/jac.js', __FILE__),array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),'1.0.0', true);
}


/*
 * New columns
 */

// the above hook will add columns only for default 'post' post type, for CPT:
// manage_{POST TYPE NAME}_posts_columns
function misha_price_and_featured_columns( $column_array ) {
    $column_array['hidePost'] = 'Anniversary Disabled';
    
    return $column_array;
}
 
/*
 * Populate our new columns with data
 */

function misha_populate_both_columns( $column_name, $id ) {
 
    // if you have to populate more that one columns, use switch()
    switch( $column_name ) :
        case 'hidePost': {
            if( get_post_meta($id,'rudr_noindex',true) == 'on') {
                echo 'Yes';
            }else{
                echo 'No';
            }
            break;
        }
    endswitch;
 
}


function misha_quick_edit_fields( $column_name, $post_type ) {
 
    // you can check post type as well but is seems not required because your columns are added for specific CPT anyway
 
    switch( $column_name ) :
        case 'hidePost': {
            wp_nonce_field( 'misha_q_edit_nonce', 'misha_nonce' );
            echo '<label class="alignleft">
                    <input type="checkbox" name="rudr_noindex">
                    <span class="checkbox-title">Check this to hide post from anniversairy calendar.</span>
                </label>';
            echo '</div></div></fieldset>';
 
            break;
 
        }
 
    endswitch;
 
}


/*
 * Quick Edit Save
 */

 
function misha_quick_edit_save( $post_id ){
 
    // check user capabilities
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 
    // check nonce
    if ( !wp_verify_nonce( $_POST['misha_nonce'], 'misha_q_edit_nonce' ) ) {
        return;
    }
 
    // update checkbox
    if ( isset( $_POST['rudr_noindex'] ) ) {
        update_post_meta( $post_id, 'rudr_noindex', $_POST['rudr_noindex'] );
    } else {
        update_post_meta( $post_id, 'rudr_noindex', '' );
    }
 
 
}


function misha_enqueue_quick_edit_population( $pagehook ) {
 
    // do nothing if we are not on the target pages
    if ( 'edit.php' != $pagehook ) {
        return;
    }
 
    
    wp_enqueue_script( 'my_custom_script', plugins_url('/assets/js/admin_edit.js', __FILE__),false, null, true );
 
}


function stick_anniversay_post_on_top(){

$args = array(
	'post_type' => 'post',
	'posts_per_page' =>-1,
);
$i = 0;
$event_query = new WP_Query($args);
//print_r($event_query);
if($event_query->have_posts()): while($event_query->have_posts()):
	$event_query->the_post();
	$enable_disable_field = get_post_meta(get_the_ID(), 'rudr_noindex',false);
	if($enable_disable_field[$i]=="on"){
		unstick_post( get_the_ID() );
	}else{
		$post_date = get_the_date("m-d");
		$current_date = date("m-d");
		
		if($current_date == $post_date){
			stick_post( get_the_ID() );
		}else{
			unstick_post( get_the_ID() );
		}
	}	
	$i++;
endwhile;
wp_reset_postdata();
endif;

}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('JargalAnniversayCalendar')) {
	$obj = new JargalAnniversayCalendar();
}