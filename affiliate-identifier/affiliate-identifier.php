<?php
/*
Plugin Name: Affiliate Identifier
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Get referer name and store in users information. Create multiple custom fields and get user information through them using shortcode.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: AffiliateIdentifier
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class AffiliateIdentifier {

function __construct() {
	require_once plugin_dir_path(__FILE__) . 'front/afi_cookie_maker.php';

    add_action('init', array($this, 'afi_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'afi_enqueue_script_front'));
    add_action('admin_enqueue_scripts', array($this, 'afi_enqueue_script_admin'));
	add_action('user_register',array($this, 'afi_registration_save'),10,1 );
    add_action('init',array($this,'afi_create_table'));
    add_action( 'show_user_profile', array($this,'afi_extra_user_profile_fields') );
    add_action( 'edit_user_profile', array($this,'afi_extra_user_profile_fields') );
    add_action( 'personal_options_update', array($this,'afi_save_extra_user_profile_fields') );
	add_action( 'edit_user_profile_update', array($this,'afi_save_extra_user_profile_fields') );
}



function afi_start_from_here() {


global $current_user;

if(!empty(get_user_meta($current_user->ID, 'afi_reffered_by', true )) || is_admin()){

//echo "<h1> HELLO WORLD </h1>";

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) {

$ref = get_user_meta($user->ID, 'afi_reffered_by', true )

 ?>
    <h3><?php _e("Reffered By Information", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="reffered_by"><?php _e("Reffered By:"); ?></label></th>
        <td>
            <input type="text" name="afi_reffered_by" id="afi_reffered_by" value="<?php echo $ref; ?>" class="regular-text" 

            <?php if(is_admin()){} else{ ?>
                disabled readonly <?php } ?> /><br />
        </td>
    </tr>
    </table>
<?php }


} // check if user refered by someone


/*
	Include Files
*/

require_once plugin_dir_path(__FILE__) . 'back/afi_user_fields_maker_settings.php';	
require_once plugin_dir_path(__FILE__) . 'back/afi_insert_fields.php'; 
require_once plugin_dir_path(__FILE__) . 'back/afi_delete_fields.php'; 
require_once plugin_dir_path(__FILE__) . 'back/afi_field_shortcode_maker.php'; 
require_once plugin_dir_path(__FILE__) . 'front/afi_update_field.php'; 
require_once plugin_dir_path(__FILE__) . 'front/afi_referrer_shortcode_maker.php'; 

}

// Enqueue Style and Scripts

function afi_enqueue_script_front() {
//Style & Script
wp_enqueue_style('afi-style', plugins_url('assets/css/afi.css', __FILE__),'1.0.0','all');
wp_enqueue_script('afi-script', plugins_url('assets/js/afi.js', __FILE__),array('jquery'),'1.0.0', true);

wp_localize_script('afi-script', 'ajax_afi', array('ajax_url' => admin_url('admin-ajax.php')));

}


function afi_enqueue_script_admin() {
//Style & Script


wp_enqueue_style('afi-datatable', 'https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css','1.10.25','all');

wp_enqueue_script('afi-datatable','https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js',array('jquery'),'1.10.25', true);

wp_enqueue_style('afi-admin', plugins_url('assets/css/afi_admin.css', __FILE__),'1.0.0','all');

wp_enqueue_script('afi-script-admin', plugins_url('assets/js/afi_admin.js', __FILE__),array('jquery'),'1.0.0', true);
wp_localize_script('afi-script-admin', 'ajax_afi', array('ajax_url' => admin_url('admin-ajax.php')));

}


 
function afi_registration_save( $user_id ) {
    if(isset($_COOKIE["hop"])) {
    	add_user_meta($user_id,"afi_reffered_by",$_COOKIE["hop"]);
    }
}


function afi_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'fields_list';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if (!$wpdb->get_var( $query ) == $table_name) {

        $charset_collate=$wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          field tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

}

/*
Showing Profile Fields
*/




function afi_extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Extra profile information", "blank"); ?></h3>

    <table class="form-table">
  
<?php 
    global $wpdb; 
    $table_name = $wpdb->base_prefix.'fields_list';
    $query = "SELECT * FROM $table_name";
    $query_results = $wpdb->get_results($query);

   // $count = count($query_results);

    foreach ($query_results as $result) {
?>
    <tr>
        <th><label for="$result"><?php _e(strtoupper($result->field)); ?></label></th>
        <?php $field_name = $result->field; ?>
        <td>
            <input type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php echo esc_attr( get_the_author_meta($field_name, $user->ID ) ); ?>" class="regular-text" /><br />	
        </td>
    </tr>

<?php } ?>

    </table>
<?php }





function afi_save_extra_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
     
    global $wpdb; 
    $table_name = $wpdb->base_prefix.'fields_list';
    $query = "SELECT * FROM $table_name";
    $query_results = $wpdb->get_results($query);
    foreach ($query_results as $result) {
    	echo $_POST[$result->field];
    	update_user_meta($user_id,$result->field, $_POST[$result->field] );
    }
  
}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('AffiliateIdentifier')) {
	$obj = new AffiliateIdentifier();
}