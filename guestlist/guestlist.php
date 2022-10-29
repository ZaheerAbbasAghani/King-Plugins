<?php
/*
Plugin Name: Guestlist
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Backend: A person can create events for for DJs and company employees to add their guests (how many, must be customisable) to the guestlist. Frontend: The DJ or employee is given a password to log into the page from where they can add their guest to the guest list. Finally, there needs to be a third page where you can see all the people on the guest list so that the door managers can check who is on the list.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: guest-list
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class GuestList {

function __construct() {
	add_action('init', array($this, 'gstl_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'gstl_enqueue_script_front'));
	add_action('init',array($this,'gstl_register_events_custom_post_type'), 0 );
	add_action( 'admin_menu', array($this,'gstl_add_meta_box') );
	add_action( 'admin_menu', array($this,'gstl_add_meta_box_members') );
	add_action( 'save_post',  array($this,'gstl_save_meta'), 10, 2 );
	add_action('admin_enqueue_scripts', array($this, 'gstl_enqueue_admin_script'));
	add_filter( 'the_content', array($this,'my_content_filter') );

//	delete_post_meta(665, 'gstl_join_event');
}

function gstl_start_from_here() {

	// Backend Files
	require_once plugin_dir_path(__FILE__) . 'back/gstl_register_events_post_type.php';
	require_once plugin_dir_path(__FILE__) . 'back/gstl_roles.php';
	require_once plugin_dir_path(__FILE__) . 'back/gstl_add_dj_in_list.php';
	require_once plugin_dir_path(__FILE__) . 'back/gstl_remove_dj.php';

	require_once plugin_dir_path(__FILE__) . 'front/gstl_add_members_in_list.php';
	require_once plugin_dir_path(__FILE__) . 'front/gstl_remove_members_in_list.php';
	require_once plugin_dir_path(__FILE__) . 'front/gstl_list_of_events.php';
	require_once plugin_dir_path(__FILE__) . 'front/gstl_add_remove_members.php';
}

// Enqueue Style and Scripts

function gstl_enqueue_script_front() {
//Style & Script
wp_enqueue_style('gstl-style', plugins_url('assets/css/gstl.css', __FILE__),'1.0.0','all');
wp_enqueue_script('gstl-script', plugins_url('assets/js/gstl.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script( 'gstl-script', 'gstl_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

wp_enqueue_style('gstl-jquery-ui', "http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css",'1.12.1','all');

wp_enqueue_script('gstl-jquery-ui', "https://code.jquery.com/ui/1.12.1/jquery-ui.js",array('jquery'),'1.12.1', true);


}


function gstl_enqueue_admin_script(){
	
	wp_enqueue_style('gstl-admin', plugins_url('assets/css/gstl_admin.css', __FILE__),'1.0.0','all');

	wp_enqueue_style( 'gstl-jquery-ui-datepicker' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
	wp_enqueue_script('admin-gstl-script', plugins_url('assets/js/gstl_admin.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_localize_script( 'admin-gstl-script', 'gstl_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

	 wp_enqueue_script( 'jquery-ui-datepicker' );
}

/*
* Creating a function to create our CPT
*/
 
function gstl_register_events_custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'  => _x( 'Events', 'Post Type General Name', 'gstl' ),
        'singular_name' => _x( 'Event', 'Post Type Singular Name', 'gstl' ),
        'menu_name'  => __( 'Events', 'gstl' ),
        'parent_item_colon' => __('Parent Event','gstl'),
        'all_items'  => __( 'All Events', 'gstl' ),
        'view_item'  => __( 'View Event', 'gstl' ),
        'add_new_item' => __( 'Add New Event', 'gstl' ),
        'add_new'     => __( 'Add New', 'gstl' ),
        'edit_item'   => __( 'Edit Event', 'gstl' ),
        'update_item' => __( 'Update Event', 'gstl' ),
        'search_items' => __( 'Search Event', 'gstl' ),
        'not_found'    => __( 'Not Found', 'gstl' ),
        'not_found_in_trash' => __('Not found in Trash', 'gstl' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Events', 'gstl' ),
        'description'         => __( 'Event news and reviews', 'gstl' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'    => array( 'title', 'editor', 'thumbnail','revisions'),
        'taxonomies'          => array( 'type' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 50,
        'menu_icon'   => 'dashicons-megaphone',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'gstl_events', $args );
 
}

/*
	Meta Box
*/
function gstl_add_meta_box() {

	add_meta_box(
		'gstl_metabox', // metabox ID
		'Event Details', // title
		 array($this,'gstl_metabox_event_callback'), // callback function
		'gstl_events',
		'normal', // position (normal, side, advanced)
		'default' // priority (default, low, high, core)
	);

}

/*
	Meta Box HTML
*/

function gstl_metabox_event_callback( $post ) {
//	echo 'hey';

$gstl_location = get_post_meta($post->ID, 'gstl_location', true );
$gstl_start_date_time = get_post_meta($post->ID, 'gstl_start_date_time', true );
$gstl_end_date_time = get_post_meta($post->ID, 'gstl_end_date_time', true );

// nonce, actually I think it is not necessary here
wp_nonce_field( '_gstllocation', '_gstllocationnonce' );
wp_nonce_field( '_gstlstartdatetime', '_gstlsdtnonce' );
wp_nonce_field( '_gstlenddatetime', '_gstledtnonce' );

echo '<table class="form-table">
<tbody>
<tr>
<th><label for="gstl_location">Location </label></th>
<td><input type="text" id="gstl_location" name="gstl_location" value="' . esc_attr( $gstl_location ) . '" class="regular-text"></td>
</tr>

<tr>
<th><label for="gstl_start_date_time">Start Date </label></th>
<td><input type="text" id="gstl_start_date_time" name="gstl_start_date_time" value="' . esc_attr( $gstl_start_date_time ) . '" class="regular-text"></td>
</tr>


<tr>
<th><label for="gstl_end_date_time">End Date </label></th>
<td><input type="text" id="gstl_end_date_time" name="gstl_end_date_time" value="' . esc_attr( $gstl_end_date_time ) . '" class="regular-text"></td>
</tr>

 
</tbody>
</table>';
}

/*
	Save Meta box data
*/

function gstl_save_meta( $post_id, $post ) {

	// nonce check
	if ( ! isset( $_POST[ '_gstllocationnonce' ] ) || ! wp_verify_nonce( $_POST[ '_gstllocationnonce' ], '_gstllocation' ) ) {
		return $post_id;
	}

	if ( ! isset( $_POST[ '_gstlsdtnonce' ] ) || ! wp_verify_nonce( $_POST[ '_gstlsdtnonce' ], '_gstlstartdatetime' ) ) {
		return $post_id;
	}

	if ( ! isset( $_POST[ '_gstledtnonce' ] ) || ! wp_verify_nonce( $_POST[ '_gstledtnonce' ], '_gstlenddatetime' ) ) {
		return $post_id;
	}

	// check current use permissions
	$post_type=get_post_type_object( $post->post_type );

	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Do not save the data if autosave
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// define your own post type here
	if( $post->post_type != 'gstl_events' ) {
		return $post_id;
	}
	
	if( isset( $_POST[ 'gstl_location' ] ) ) {
		update_post_meta( $post_id, 'gstl_location', sanitize_text_field( $_POST[ 'gstl_location' ] ) );
	} else {
		delete_post_meta( $post_id, 'gstl_location' );
	}

	if( isset( $_POST[ 'gstl_start_date_time' ] ) ) {
		update_post_meta( $post_id, 'gstl_start_date_time', sanitize_text_field( $_POST[ 'gstl_start_date_time' ] ) );
	} else {
		delete_post_meta( $post_id, 'gstl_start_date_time' );
	}

	if( isset( $_POST[ 'gstl_end_date_time' ] ) ) {
		update_post_meta( $post_id, 'gstl_end_date_time', sanitize_text_field( $_POST[ 'gstl_end_date_time' ] ) );
	} else {
		delete_post_meta( $post_id, 'gstl_end_date_time' );
	}

	return $post_id;
}

/*
	Meta Box
*/
function gstl_add_meta_box_members() {

	add_meta_box(
		'gstl_metabox2', // metabox ID
		'DJ/Employee Details', // title
		 array($this,'gstl_metabox_members_callback'), // callback function
		'gstl_events',
		'normal', // position (normal, side, advanced)
		'default' // priority (default, low, high, core)
	);

}


function gstl_metabox_members_callback($post){
	
echo '<div class="form-table members_details">';

$blogusers = get_users(array('role__in'=>array( 'dj-employee')));
// Array of WP_User objects.
foreach ( $blogusers as $user ) {
	echo '<div class="box"><img src="'.esc_url( get_avatar_url( $user->ID ) ).'" style="border-radius: 20em;width: 60px;"><h4 for="gstl_location">'. esc_html( $user->display_name ).'</h4>';
	$response = get_post_meta($post->ID,'gstl_dj_event', false);
	$limit = get_post_meta($post->ID,'djlimit_'.$user->ID,true);

	if(empty($limit)){
		echo "<input type='number' class='member_limit' placeholder='Member Limit'>";
	}else{
		echo "<input type='number' class='member_limit' placeholder='Member Limit' value='".$limit."' readonly>";
	}

	if(!in_array($user->ID, $response)){
		echo '<a href="#" class="button button-primary gstl_member" member-id="'.$user->ID.'" post-id="'.$post->ID.'"> Manage This Event </a></div>';
	}else{
		echo '<a href="#" class="button button-default gstl_member_delete" member-id="'.$user->ID.'" post-id="'.$post->ID.'" limit="'.$limit.'"> Remove  </a></div>';
	}
}
	echo ' </div>';


}


function my_content_filter($content){

  	if(is_singular( 'gstl_events' )){
  	 $post_id = get_the_ID();
  	 $gstl_location = get_post_meta($post_id, 'gstl_location', true );
	 $gstl_start_date_time = get_post_meta($post_id, 'gstl_start_date_time', true );
	 $gstl_end_date_time = get_post_meta($post_id, 'gstl_end_date_time', true );

	 $response = get_post_meta($post_id, 'gstl_join_event', false);

	 $after .= '<p><b> Location: </b>'.$gstl_location.'</p>'.'<p><b> Start Date/Time: </b>'.$gstl_start_date_time.'</p>'.'<p><b> End Date/Time: </b>'.$gstl_end_date_time.'</p>'; 
	 $after .= "<h3> Members: </h3><div class='members_details'>";
	 foreach ($response as $user) {
	 	// $user_info = get_userdata($user);

	 	// print_r($user_info);

	 	 $after .= '<div class="box"><img src="'.esc_url( get_avatar_url( $user ) ).'" style="border-radius: 20em;width: 60px;"><h4 for="gstl_location">'. esc_html($user).'</h4></div>';
	 }
	$after .= "</div>";

	  $content =  $content .'<p>'. $after."</p>";
	}

  return $content; 
} 



 

} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('GuestList')) {
	$obj = new GuestList();
}