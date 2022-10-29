<?php
/*
Plugin Name: Simple Affiliate Plugin
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Get referer name and store in users information. Create multiple custom fields and get user information through them using shortcode.
Version: 1.2
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: SimpleAffiliatePlugin
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class SimpleAffiliatePlugin {

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

	add_action( 'personal_options_update', array($this,'afi_save_refer_user_profile_fields') );
	add_action( 'edit_user_profile_update', array($this,'afi_save_refer_user_profile_fields') );
	add_action( 'show_user_profile', array($this,'afi_refer_user_profile_fields') );
	add_action( 'edit_user_profile',  array($this,'afi_refer_user_profile_fields') );
	/*add_action( 'personal_options_update', array($this,'afi_registration_save') );
	add_action( 'edit_user_profile_update', array($this,'afi_registration_save') );
	add_action( 'show_user_profile', array($this,'afi_registration_save') );
	add_action( 'edit_user_profile',  array($this,'afi_registration_save') );*/
	

}

/*
	Included Files
*/
function afi_start_from_here () {
require_once plugin_dir_path(__FILE__) . 'back/afi_user_fields_maker_settings.php';	
require_once plugin_dir_path(__FILE__) . 'back/afi_insert_fields.php'; 
require_once plugin_dir_path(__FILE__) . 'back/afi_delete_fields.php'; 
require_once plugin_dir_path(__FILE__) . 'back/afi_field_shortcode_maker.php'; 
require_once plugin_dir_path(__FILE__) . 'front/afi_update_field.php'; 
require_once plugin_dir_path(__FILE__) . 'front/afi_referrer_shortcode_maker.php'; 
require_once plugin_dir_path(__FILE__) . 'front/afi_fetch_custom_field.php'; 


}


function afi_refer_user_profile_fields( $user ) {

$ref = get_user_meta($user->ID, 'afi_reffered_by', true );
$refby = esc_attr(get_the_author_meta('refby',$user->ID));
 ?>
    <h3><?php _e("Referred By Information", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="reffered_by"><?php _e("Referred By:"); ?></label></th>
        <td>
        	
           <input type="text" name="refby" id="refby" value="<?php echo $refby; ?>" class="regular-text" 

           <?php if(!current_user_can('administrator') ) { ?>
                readonly
            <?php } ?>
           /><br />

        </td>
    </tr>
    </table>
<?php }



function afi_save_refer_user_profile_fields($user_id) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }

    $refby = str_replace(' ', '', strtolower(sanitize_text_field($_POST['refby'])));
    update_user_meta($user_id,'refby',strtolower($refby));
    
  
}




// Enqueue Style and Scripts

function afi_enqueue_script_front() {
//Style & Script

	

wp_enqueue_style('afi-style', plugins_url('assets/css/datatables.min.css', __FILE__),'1.0.0','all');


/*wp_enqueue_script('afi-script-datatable', plugins_url('assets/js/datatables.min.js', __FILE__),array('jquery'),'1.11.3', true);
wp_enqueue_script('afi-sort-datatable', 'https://cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js',array('jquery'),'1.10.11', true);
*/

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
    $cookie = get_option("cookie_name");
    if(isset($_COOKIE[$cookie])) {
        add_user_meta($user_id,"refby",strtolower($_COOKIE[$cookie]));
    }
	//$ccuser = get_user_meta($u->ID, 'wizz_user', true);
	$user = get_user_by( 'id', $user_id);
	$new_str = str_replace(' ', '', $user->user_login);
	update_user_meta( $user_id ,"wizz_user",strtolower($new_str));
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

$current_user = get_user_by( 'id', $user->ID );
$string = str_replace(' ', '', $current_user->display_name);

$wizz_user =esc_attr(get_the_author_meta('wizz_user',$user->ID));

if(!empty($wizz_user)){
?>
<tr> <th><label>Custom User</label></th>
<td><input type="text" name="wizz_user" id="wizz_user" value="<?php echo $wizz_user; ?>"></td>
</tr>

<?php } else { ?>

<tr> <th><label>Custom User</label></th>
<td><input type="text" name="wizz_user" id="wizz_user" value="<?php echo $current_user->user_login ?>"></td>
</tr>
<?php update_user_meta($user->ID, 'wizz_user', $meta_value, $current_user->user_login); ?>
<?php } ?>

<?php 
    foreach ($query_results as $result) {
?>
    <tr>
        <th><label for="$result"><?php _e(strtoupper($result->field)); ?></label></th>
        <?php $field_name = $result->field;
    
        
        ?>
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
    $usr = str_replace(' ', '', strtolower(sanitize_text_field($_POST['wizz_user'])));
 
    update_user_meta($user_id,'wizz_user',strtolower($usr));
     
    global $wpdb; 
    $table_name = $wpdb->base_prefix.'fields_list';
    $query = "SELECT * FROM $table_name";
    $query_results = $wpdb->get_results($query);
    foreach ($query_results as $result) {
    	echo $_POST[$result->field];
        $string = str_replace(' ', '', $_POST[$result->field]);
    	update_user_meta($user_id,$result->field,strtolower($string));
    }

  
}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('SimpleAffiliatePlugin')) {
	$obj = new SimpleAffiliatePlugin();
}