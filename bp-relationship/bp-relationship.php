<?php
/*
Plugin Name: BP Relationship
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin is used to create relationship between 2 buddypress profiles.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: covid-19
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class BpRelationship {

function __construct() {
	add_action('init', array($this, 'br_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'br_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'br_admin_enqueue_script'));
	//register_activation_hook( __FILE__, 'br_create_table' );
	add_action('init', array($this, 'br_create_table'));
  	add_filter( 'bp_notifications_get_registered_components',array($this, 'custom_filter_notifications_get_registered_components') );
  	add_filter( 'bp_notifications_get_notifications_for_user', array($this,'bp_custom_format_buddypress_notifications'), 10, 5 );

  	add_shortcode("relations", array($this,"show_all_relations"));


}



function br_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/br_settings_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/br_insert_relationship.php';
  	require_once plugin_dir_path(__FILE__) . 'back/br_delete_relations.php';
  	require_once plugin_dir_path(__FILE__) . 'front/choose_relation_with_user.php';
  	require_once plugin_dir_path(__FILE__) . 'front/br_confirm_yes.php';
}

// Enqueue Style and Scripts

function br_enqueue_script_front() {
//Style & Script


wp_enqueue_style('br-style1',"http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css",'1.12.1','all');	
wp_enqueue_style('br-style', plugins_url('assets/css/br.css', __FILE__),'1.0.0','all');
wp_enqueue_script('br-script-front', plugins_url('assets/js/br.js', __FILE__),array('jquery'),'1.0.0', true);

//Recognize relation
global $wpdb;
$table_name1 = $wpdb->base_prefix.'br_relationship_confirm';
$relations = $wpdb->get_results( "SELECT * FROM $table_name1 WHERE br_status=1"); 

$relation_array = array();
foreach ($relations as $rel) {
	
	if(bp_displayed_user_id() == $rel->br_receiver){
		$usr = get_user_by('id', $rel->br_sender);
		array_push($relation_array, array("relation"=> $rel->br_relation, "display_name"=> bp_core_get_userlink($rel->br_sender) ));
	}
	if(bp_displayed_user_id() == $rel->br_sender){
		$usr = get_user_by('id', $rel->br_receiver);
		array_push($relation_array, array("relation"=> $rel->br_relation, "display_name"=> bp_core_get_userlink($rel->br_receiver) ));
	}
	
}
// Autocomplete
$array = array();
if ( bp_has_members( 'user_id=' . bp_displayed_user_id() ) ) : 
	while ( bp_members() ) : bp_the_member(); 
		$userId = bp_get_member_user_id();
		$user = new WP_User( $userId );
		foreach ($user as $u) {
			if(!empty( $u->display_name))
			array_push($array, $u->display_name);
		}
	endwhile;
endif;


wp_localize_script('br-script-front','ajax_object',array('ajax_url' => admin_url( 'admin-ajax.php' ), 'rela_array' => json_encode($array),'relatives' => json_encode( $relation_array ) ) );

wp_enqueue_script('jquery-ui-autocomplete');


}

function br_admin_enqueue_script() {
//Style & Script

wp_enqueue_script('zci-script-dtb','https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',array('jquery'),'1.10.21', true);

wp_enqueue_style('zci-style-dtb', "https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css",'1.10.21','all');

wp_enqueue_script('br-script', plugins_url('assets/js/br_admin.js', __FILE__),array('jquery'),'1.0.0', true);

/*global $wpdb;
$table_name = $wpdb->base_prefix.'br_relationship';
$relations = $wpdb->get_results( "SELECT * FROM $table_name"); 
$relate = json_encode($relations);*/


//print_r($array);


wp_localize_script('br-script','ajax_object',array('ajax_url' => admin_url( 'admin-ajax.php' ), 'rela_array' => json_encode($relate) ) );

}


function br_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'br_relationship';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          br_relation tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }


    $table_name1 = $wpdb->base_prefix.'br_relationship_confirm';
    
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name1 ) );
    if ( ! $wpdb->get_var( $query ) == $table_name1 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name1 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          br_sender tinytext NOT NULL,
          br_receiver tinytext NOT NULL,
          br_relation tinytext NOT NULL,
          br_status mediumint(10) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

//$delete = $wpdb->query("TRUNCATE TABLE $table_name1");

/*
$delete = $wpdb->query("TRUNCATE TABLE $table");
global $wpdb;
$mytables=$wpdb->get_results("SHOW TABLES");
foreach ($mytables as $mytable)
{
    foreach ($mytable as $t) 
    {       
        echo $t . "<br>";
    }
}*/



}

// Custom Component 1

function custom_filter_notifications_get_registered_components( $component_names = array() ) {
 
    // Force $component_names to be an array
    if ( ! is_array( $component_names ) ) {
        $component_names = array();
    }

    // Add 'custom' component to registered components array
    array_push( $component_names, 'custom' );
 
    // Return component's with 'custom' appended
    return $component_names;
}

// Formatting custom with respect to action
function bp_custom_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
global $wpdb; 
$table_name = $wpdb->base_prefix.'br_relationship_confirm';

$receiver = get_current_user_id();
$query = "SELECT * FROM $table_name WHERE br_receiver='$receiver' ";
$query_results = $wpdb->get_results($query);


    // New custom notifications
    if ( 'custom_action' === $action ) {

    	if(!empty($query_results)){

      foreach ($query_results as $result) {
        // WordPress Toolbar
        	if(!empty($result)){
	        	$user = get_user_by('ID', $result->br_sender);
	            $return = apply_filters( 'custom_filter',"<p relate='".$result->br_sender."'> User <b> ".$user->display_name.' </b> recognize you as a <b>'.$result->br_relation.'</b></p><button type="button" style="padding: 10px;margin: 10px 4px 0px 4px;" class="br_yes"> Yes </button>', $custom_text, $custom_link );
        	}
 		}
        

  	}
	else{
		$return = apply_filters( 'custom_filter',"Relationship request recognised.", $custom_text, $custom_link );
	}

	 return $return;

    }
   
}


function show_all_relations($result){

global $wpdb;
$table_name = $wpdb->base_prefix.'br_relationship';
$relations = $wpdb->get_results( "SELECT * FROM $table_name"); 


$result .= "<form method='post' action='' class='relations_form'>";
$result .= "<input type='text' name='search_user' class='br_field search_user' placeholder='Name of family member' id='list_relation'>";
$result .= "<select class='relations br_field'>";
foreach($relations as $value) {
	$result.= "<option>".$value->br_relation."</option>";
}
$result .= "</select><input type='button' id='submit_relation' value='Submit'> 
</form> <hr>";


if ( bp_has_members( 'user_id=' . bp_displayed_user_id() ) ) :  


  do_action( 'bp_before_directory_members_list' );
 

  $result .= '<ul id="members-list" class="item-list" role="main">';
 
  while ( bp_members() ) : bp_the_member(); 
 
 $result .= '<li>
      <div class="item-avatar">
         <a href="'.bp_get_member_permalink().'"> '. bp_get_member_avatar().'</a>
      </div>
 		<div class="item">
        	<div class="item-title">
           		<a href="'.bp_get_member_permalink().'">'.bp_get_member_name().'</a>';
 	$id = bp_get_member_user_id();
 	$table_name1 = $wpdb->base_prefix.'br_relationship_confirm';
 	$relay = $wpdb->get_results( "SELECT * FROM $table_name1 WHERE br_receiver='$id' OR br_sender='$id' "); 

 	foreach ($relay as $rel) {
 		$result .= "<p>".$rel->br_relation."</p>";
 	}
           	
  $result .= '</div></li>';
 
 endwhile; 
 
 $result .= '</ul>';
 
 //do_action( 'bp_after_directory_members_list' ); 
 
 
 

 else: 
 
   $result .= '<div id="message" class="info">
      <p>'. _e( "Sorry, no members were found.", 'buddypress' ).'</p>
   </div>';
 

endif;

/*$table_name1 = $wpdb->base_prefix.'br_relationship_confirm';
$user_id = bp_displayed_user_id();
$relate = $wpdb->get_results( "SELECT * FROM $table_name1 WHERE br_receiver='".$user_id."' "); 


foreach ($relate as $rela) {
	echo $rela->br_sender;
}
*/

return $result;

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('BpRelationship')) {
	$obj = new BpRelationship();
}