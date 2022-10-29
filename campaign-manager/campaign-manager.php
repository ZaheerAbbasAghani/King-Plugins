<?php
/*
Plugin Name: Campaign Manager
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: If for example an association needs to find new board members. The members that want to get votes  geta listed and then everyone in the association has the possibility to vote. login by telefone number / sms, create voting campagin (has to have a start time and a end time), create candidates, bulk add members with phone numbers to allow them to vote, when voting is finished there has to be graphs that show who is the favourit. Maybe bulk notify everyone that has voted with a link to see the result. 
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: campaign-manager
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");

require_once plugin_dir_path(__FILE__).'back/vendor/autoload.php';
use Twilio\Rest\Client;

class CampaignManager {

function __construct() {
	add_action('init', array($this, 'camp_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'camp_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'camp_enqueue_admin'));
	add_action( 'after_setup_theme', array($this,  'camp_automatically_login') );
	add_action( 'after_setup_theme', array($this,  'camp_automatically_login2') );
	add_filter( 'the_content', array($this, 'camp_campaign_details') );
	add_action('init', array($this, 'camp_create_table'));
	add_action( 'template_redirect',  array($this, 'mh_check_loggedin_redirect') );
}


function camp_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/camp_delete_user.php';
	require_once plugin_dir_path(__FILE__) . 'back/camp_options_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/camp_check_if_phone_exists.php';

	require_once plugin_dir_path(__FILE__) . 'front/campaign_votes.php';

}

// Enqueue Style and Scripts

function camp_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('camp-style-front', plugins_url('assets/css/camp.css', __FILE__),'', rand(0,1000),'all');

	wp_enqueue_script('camp-syotimer', plugins_url('assets/js/jquery.syotimer.js', __FILE__),array('jquery'),rand(0,1000), true);

	
	wp_enqueue_script('camp-chart', "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js",array('jquery'),'2.5.0', true);

	wp_enqueue_script('camp-script', plugins_url('assets/js/camp.js', __FILE__),array('jquery'),rand(0,1000), true);


	$query_results = "";
	if(is_singular("campaigns")){
		global $wpdb;
		$table_name = $wpdb->base_prefix.'campaign_votes';
		$query = "SELECT sum(vote_count) as vote, candidate, post_id FROM $table_name WHERE post_id=".get_the_ID()." GROUP BY candidate";
		$query_results = $wpdb->get_results($query, ARRAY_A);
	}


	wp_localize_script( 'camp-script', 'camp_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'Votes' => $query_results  ) );
}

function camp_enqueue_admin($hook) {
	//Style & Script
	wp_enqueue_style('camp-style', plugins_url('assets/css/inputTags.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('camp-autocomplete-script', plugins_url('assets/js/index.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_enqueue_script('camp-dashboard-script', plugins_url('assets/js/camp_dashboard.js', __FILE__),array('jquery'),rand(0,1000), true);

	wp_localize_script( 'camp-dashboard-script', 'camp_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}


function camp_automatically_login() {

	if(isset($_GET['cusername']) && isset($_GET['cpassword'])){
		$creds = array(
			'user_login'    => $_GET['cusername'],
			'user_password' => $_GET['cpassword'],
			'remember'      => true
		);
		$user = wp_signon( $creds, true );

		if ( is_wp_error( $user ) ) {
			echo $user->get_error_message();
			exit;
		}
		do_action('wp_login', $user->user_login, $user);
		wp_set_current_user($user->ID, $user->user_login);
		wp_set_auth_cookie($user->ID);
		$location = get_permalink(  $_GET['postid'] );
		wp_safe_redirect($location);
		exit();	
	}
}
 

function camp_automatically_login2() {

	if(isset($_GET['logit']) && isset($_GET['username'])){
		if(!is_user_logged_in()):


	
			if($user=get_user_by('login',$_GET['username'])){

				

		        clean_user_cache($user->ID);

		        wp_clear_auth_cookie();
		        wp_set_current_user( $user->ID );
		        wp_set_auth_cookie( $user->ID , true, false);

		        update_user_caches($user);

		    }

		endif;
	}
}


function camp_campaign_details($content){

	if ( is_singular( 'campaigns' ) ) {

		global $wpdb;
		$table_name = $wpdb->base_prefix.'campaign_votes';
  
		$startDate 	= get_post_meta(get_the_ID(), "start_date", true);
		$endDate 	= get_post_meta(get_the_ID(), "end_date", true);
		$candidates = get_post_meta(get_the_ID(), "candidates", true);
		$votable 	= get_user_meta(get_current_user_id(),'votable', false);
		$after = "";

		//print_r($votable);
		
		$date_now = date("Y-m-d h:i A"); 

		if (strtotime($endDate) > strtotime($date_now)) {
			if ($startDate > $date_now) {
		  		$after = '
		  		<p class="startDate">Voting starts: '.date("d-m-Y h:i A",strtotime($startDate)).'</p> 
		  		<p class="startDate">Voting ends: '.date("d-m-Y h:i A",strtotime($endDate)).'</p> '; 

		  		$after .= "<h3>List of Candidates: </h3> <ul class='candidate_list' data-id='".get_the_ID()."'>";
		  		foreach (explode(",", $candidates) as $key => $value) {
		  			$after .= "<li><p>".$value."</p>";
		  			$after .="</li>";
		  		}
		  		$after .= "</ul>";


		  		$content = $content . $after;
		  	}else{
		  		$after = '
		  				<p class="startDate">Voting starts: '.date("d-m-Y h:i A",strtotime($startDate)).'</p> 
		  		<p class="startDate">Voting ends: '.date("d-m-Y h:i A",strtotime($endDate)).'</p> '; 

		  		$after .= "<h3>List of Candidates:  </h3> <ul class='candidate_list' data-id='".get_the_ID()."'>";

		  			$query = "SELECT * FROM $table_name WHERE user_id='".get_current_user_id()."' AND post_id='".get_the_ID()."'";
		  			$query_results = $wpdb->get_results($query);

		  		foreach (explode(",", $candidates) as $key => $value) {

			  		if(!empty($query_results)){
			  			if($query_results[0]->candidate == $value){
			  				$after .= "<li  class='voted'><p>".$value."</p>";
			  			}else{
			  				$after .= "<li><p>".$value."</p>";	
			  			}
			  		}else{
			  				$after .= "<li><p>".$value."</p>";
			  		}

		  			if(!empty($votable)){
			  			if(in_array(get_the_ID(), $votable)){
			  				$user_id = get_current_user_id();
			  				$post_id = get_the_ID();
			  			if(!empty($query_results)){
							if($query_results[0]->candidate == $value){
			  					$after .= "<a href='#' class='campVote'> Voted </a>";
			  				}else{
			  					$after .= "<a href='#' class='campVoteDone'> Not Voted </a>";
			  				}
			  			}else{
		  					$after .= "<a href='#' class='campVote'> Vote </a>";
		  				}


			  			}
			  		}


		  			$after .="</li>";
		  		}
		  		$after .= "</ul>";
		  		$content = $content . $after;
		  		return $content;
		  	}
		}else{
			$after .="<h3> Camapaign Ends </h3> <br> <canvas id='campaignCanvas' style='width:100%;max-width:600px'></canvas>";

		  
			$notification=get_post_meta( get_the_ID(),'campaign_notification', false);
			$sid = get_option('camp_account_sid');
			$token = get_option('camp_auth_token');
			$twilio = new Client($sid, $token);

			//delete_post_meta(get_the_ID(), 'campaign_notification');
			

			if(!in_array(1, $notification)){
				
			    $voter_phone = unserialize(get_post_meta(get_the_ID(), 'camp_voter_phone', true));
			    $j=0;
			    foreach ($voter_phone as $key => $value) {
			    	$string = str_replace('+', '', $value);
			    	$loginUrl= "?username=$string&logit=1";
				   	$message = $twilio->messages
		            ->create($value, // to
		                   ["body" => "Campaign finished for now: \n\n".get_the_title()." \n\nYou can access campaign results here\n\n". get_the_permalink(get_the_ID()).'/'.$loginUrl, "from" => get_option('camp_phone_number') ]
		            );
			    }
			    add_post_meta(get_the_ID(), 'campaign_notification', 1);

			}

			$content = $content . $after;
			return $content;
		}

	  	
	} 

	return $content;
} 



function camp_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'campaign_votes';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          post_id int(10) NOT NULL,
          candidate varchar(100) NOT NULL,
          user_id int(10) NOT NULL,
          vote_count int(255) NOT NULL,
          created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
/*
     $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);*/
}



function mh_check_loggedin_redirect()
{
    if( is_page( 'Campagin list' ) && ! is_user_logged_in() )
    {
        wp_redirect(wp_login_url());

        die;
    }
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('CampaignManager')) {
$obj = new CampaignManager();
	require_once plugin_dir_path(__FILE__) . 'back/camp_create_post_type.php';
}