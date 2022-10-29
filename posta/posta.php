<?php
/*
Plugin Name: Posta
Plugin URI: https://alnetid.fo
Description: Posta plugin ger tað møguligt at knýta woocommerce til Posta.
Version: 1.0
Author: Alnetið
Author URI: Alnetid.fo/samband
License: GPLv3 or later
Text Domain: posta
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class Posta {

function __construct() {
	add_action('init', array($this, 'pcol_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'pcol_enqueue_script_front'));
    add_action('admin_enqueue_scripts', array($this, 'pcol_enqueue_script_dashboard'));
    add_action( 'add_meta_boxes', array($this, 'select_box_add_meta_box') );
    add_action( 'save_post_shop_order', array($this, 'select_box_save_postdata') );

}



function pcol_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/pcol_settings_page.php';

}

// Enqueue Style and Scripts

function pcol_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('pcol-style', plugins_url('assets/css/pcol.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('pcol-script', plugins_url('assets/js/pcol.js', __FILE__),array('jquery'),'1.0.0', true);
}


// Enqueue Style and Scripts

function pcol_enqueue_script_dashboard($hook) {
    //Style & Script
    //echo $hook;
    if($hook != "toplevel_page_pcol_posta_settings")
        return 0;

    wp_enqueue_style('pcol-style-admin', plugins_url('assets/css/inputTags.css', __FILE__),'1.0.0','all');
    wp_enqueue_script('pcol-script-admin-tags', plugins_url('assets/js/index.js', __FILE__),array('jquery'),'1.0.0', true);
    wp_enqueue_script('pcol-script-admin', plugins_url('assets/js/pcol.js', __FILE__),array('jquery'),'1.0.0', true);
}

    

// Create the meta box
function select_box_add_meta_box() {
      add_meta_box(
          'pcol_package_selector',
          'Package Selector',
          array($this,'pcol_select_box_content'),
          'shop_order',
          'side',
      );
}

// Create the meta box content
function pcol_select_box_content($post) {
    
    wp_nonce_field( 'pcol_meta_box', 'pcol_meta_box_nonce' );
    $value = get_post_meta( $post->ID, '_delivery_option', true);
    $packages = explode(",",get_option('pcol_packages'));

    foreach ($packages as $package) {
     
?>
    <label style="margin:10px 10px;display:block;"> <input type="radio" name="delivery_option" value="<?php echo $package; ?>" <?php checked($value, $package); ?>> <?php echo $package; ?> </label>

<?php 
    }


}

// Save the selection
function select_box_save_postdata($post_id) {    
      
    
       // Check if our nonce is set.
        if ( !isset( $_POST['pcol_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['pcol_meta_box_nonce'], 'pcol_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }


        // Sanitize user input.
        $delivery_option = ( isset( $_POST['delivery_option'] ) ?  $_POST['delivery_option']  : '' );

        // Update the meta field in the database.
        update_post_meta( $post_id, '_delivery_option', $delivery_option );


}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('Posta')) {
	$obj = new Posta();
}