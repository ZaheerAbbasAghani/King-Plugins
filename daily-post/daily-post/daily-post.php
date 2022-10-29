<?php
/*
Plugin Name: Daily Post
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: The plugin install creates a custom field in posts to enter a Day of Year Number. The object is to enter a day of year number (1 - 365) to assign a post to a particular day.When first loading up, the plugin should calculate todays "Day of Year Number" and show that post on the front page. There should also be a sidebar widget to allow a lookup and display of a particular post by "Day of Year Number .I should still be able to have normal menus and pages.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: daily-post
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class DailyPost {

function __construct() {
	add_action('init', array($this, 'dp_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'dp_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'dp_enqueue_script_admin'));
	add_filter('post_limits',array($this,'dp_filter_main_search_post_limits'), 10, 2 );
	add_action("init", array($this,"make_the_post_sticky"));
	add_action( 'save_post_post',  array($this,'set_default_terms'), 5,3);
	add_action( 'update_option_dp_select_category',  array($this,'dp_select_category_save'), 5,3);
	add_action( 'update_option_dp_select_tag',  array($this,'dp_select_tag_save'), 5,3);
}


function dp_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/dp_create_custom_field.php';
	require_once plugin_dir_path(__FILE__) . 'back/dp_settings_panel.php';
	require_once plugin_dir_path(__FILE__) . 'front/dp_get_post_by_value.php';
}

// Enqueue Style and Scripts

function dp_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('dp-style', plugins_url('assets/css/dp.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('dp-script', plugins_url('assets/js/dp.js', __FILE__),array('jquery'),'1.0.0', true);
	wp_localize_script('dp-script', 'dp_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

// Enqueue Style and Scripts admin
function dp_enqueue_script_admin() {
	//Style & Script
	//wp_enqueue_style('dp-style', plugins_url('assets/css/dp.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('dp-script-admin', plugins_url('assets/js/dp_admin.js', __FILE__),array('jquery'),'1.0.0', true);
	wp_localize_script( 'dp-script-admin', 'dp_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}



function dp_filter_main_search_post_limits( $limit, $query ) {

    if ( ! is_admin() && $query->is_main_query() && ($query->is_search() || $query->is_home()) ){
        return 'LIMIT 0, 0';
    }
    return $limit;
}
//dp_day_of_year_number

function make_the_post_sticky(){

	$d = date("z") + 1;
	//echo $d;
	$query_meta = array(
	    'post_type' => 'post',
	    'meta_key' => 'dp_day_of_year_number',
	    'meta_value' => $d
	);
	$today_post = new WP_Query( $query_meta );

	//echo $today_post->found_posts;

	if($today_post->found_posts == 0){

		$query_meta1 = array(
		    'post_type' => 'post'
		);
		$today_post1 = new WP_Query( $query_meta1 );

		if($today_post1->have_posts()): while($today_post1->have_posts()): $today_post1->the_post();
				unstick_post(get_the_ID());
		endwhile;
		endif;


	}else{
		if($today_post->have_posts()): while($today_post->have_posts()): $today_post->the_post();
			if(!is_sticky(get_the_ID())){
				stick_post(get_the_ID());
			}
		endwhile;
		endif;
	}
}


	
function set_default_terms($post_id, $post, $update) {
  // Check the post-type of the post being saved is our type
	 if ( 'post' !== $post->post_type ) {
        return;
     }
	
	// Creating Category
  	$suggestion_cat = get_option('dp_select_category');
  	wp_set_post_categories( $post_id, array($suggestion_cat) );

	// Creating Tags
	if (!has_tag()){
	  	$suggestion_tag= get_option('dp_select_tag');
	 	$taxonomy = 'post_tag'; 
	  	wp_set_post_terms( $post_id, array($suggestion_tag), $taxonomy );
	}

}

// Creating By Default Category
function dp_select_category_save(){

	$args = array("post_type" => "post", "posts_per_page" => -1);
	$query = new WP_Query($args);

	if($query->have_posts()): while($query->have_posts()): $query->the_post();
	$suggestion_cat= get_option('dp_select_category');
	$taxonomy = 'category'; 
	if(!empty($suggestion_cat)){
		wp_set_post_terms(get_the_ID(), array($suggestion_cat), $taxonomy );
	}else{
		wp_set_post_terms(get_the_ID(), "", $taxonomy );
	}

	endwhile;
	endif;


}

// Creating By Default Tags
function dp_select_tag_save(){

	$args = array("post_type" => "post", "posts_per_page" => -1);
	$query = new WP_Query($args);

	if($query->have_posts()): while($query->have_posts()): $query->the_post();

	  	$suggestion_tag= get_option('dp_select_tag');
	 	$taxonomy = 'post_tag'; 
	 	if(!empty($suggestion_tag)){
	  		wp_set_post_terms(get_the_ID(), array($suggestion_tag), $taxonomy );
	  	}else{
	  		wp_set_post_terms(get_the_ID(), "", $taxonomy );
	  	}

	endwhile;
	endif;
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('DailyPost')) {
	$obj = new DailyPost();
	require_once plugin_dir_path(__FILE__) . 'back/dp_widget.php';
}