<?php
/*
Plugin Name: Gift Finder
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Gift Finder plugin help visitors to find correct gift for them or there loved ones.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: gift-finder
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class GiftFinder {

function __construct() {
	add_action('init', array($this, 'gf_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'gf_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'gf_enqueue_admin'));
	add_action('init', array($this, 'gf_create_fields_table'));
	add_action( 'woocommerce_cart_calculate_fees',array($this, 'gf_discount_price') ); 
	add_action( 'woocommerce_thankyou', array($this,  'gf_delete_cookie'));
/*	if(isset($_SESSION['gft_discount'])){
		unset($_SESSION['gft_discount']);
	}*/

	//echo "HELLO ".$_SESSION['gft_discount'];
    
}



function gf_start_from_here() {
	require_once plugin_dir_path(__FILE__) .'back/gf_check_fields_availability.php';
	require_once plugin_dir_path(__FILE__) . 'back/gf_store_form_data.php';
	require_once plugin_dir_path(__FILE__) . 'back/gf_remove_field_db.php';
	require_once plugin_dir_path(__FILE__) . 'back/gf_options_page.php';

	require_once plugin_dir_path(__FILE__) . 'front/gf_gift_finder_show.php';
	require_once plugin_dir_path(__FILE__) . 'front/wep_submit_form.php';


}


function gf_enqueue_admin($hook){

	if($hook != "toplevel_page_gf_gift_finder")
			return 0;
		wp_enqueue_script("jquery-ui");
		wp_enqueue_script("jquery-ui-sortable");
       /* wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_script("jquery-ui-droppable");*/
		wp_enqueue_script('gf-form-builder', 'https://formbuilder.online/assets/js/form-builder.min.js',array('jquery'),'3.8.2', true);
		wp_enqueue_script('gf-form-render', 'https://formbuilder.online/assets/js/form-render.min.js',array('jquery'),'3.8.2', true);

        wp_enqueue_script('gf-depends-setup', plugins_url('assets/js/depends_setup.js', __FILE__),array('jquery'),'', true);

		wp_enqueue_script('gf-script-admin', plugins_url('assets/js/gf-script-admin.js', __FILE__),array('jquery'),'1.0.0', true);

		wp_localize_script('gf-script-admin', 'gf_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}

// Enqueue Style and Scripts

function gf_enqueue_script_front() {
	//Style & Script
	
	wp_enqueue_style('gf-simpleform', plugins_url('assets/css/simpleform.css', __FILE__),'','all');

	wp_enqueue_style('gf-owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css','2.3.4','all');

	wp_enqueue_style('gf-owl-owl-theme-default', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css','2.3.4','all');

	wp_enqueue_style('gf-style', plugins_url('assets/css/gf.css', __FILE__),'1.0.0','all');

	wp_enqueue_script('gf-script-validate','https://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js',array('jquery'),'1.12.0', true);

	wp_enqueue_script('gf-script-simpleform', plugins_url('assets/js/simpleform.js', __FILE__),array('jquery'),'', true);

	wp_enqueue_script('gf-owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',array('jquery'),'2.3.4', true);

    

	wp_enqueue_script('gf-script', plugins_url('assets/js/gf-script.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_localize_script('gf-script', 'gf_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

}


function gf_create_fields_table(){
    global $wpdb;
    $table_name = $wpdb->base_prefix.'gf_fields_table';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          type text NOT NULL,
          required text NOT NULL,
          label text NOT NULL,
          subtype text NOT NULL,
          inline text NOT NULL,
          name text NOT NULL,
          className text NOT NULL,
          multiple text NOT NULL,
          valuess text NOT NULL,
          selected text NOT NULL,
          dependson_type text NOT NULL,
          dependson_code text NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
}




function gf_discount_price($cart) 
{ 
	if(isset($_COOKIE['gft_discount']) && isset($_COOKIE['discountDone'])){
		global $woocommerce; //Set the price for user role. 
		$discount_price = $_COOKIE['gft_discount']; 

		if($cart->cart_contents_total >=50){
			$cart_total = $cart->cart_contents_total - $discount_price; 
			$woocommerce->cart->add_fee( 'Discounted Price', -$discount_price, true, 'standard' ); 

			$cookie_name = "discountDone";
			$cookie_value = 1;

			if(!isset($_COOKIE[$cookie_name])){
				setcookie($cookie_name, $cookie_value,  time() + (10 * 365 * 24 * 60 * 60), "/");
			}
		}else{
			setcookie("gft_discount", "", time()-3600,'/');
		}
	}
} 




function gf_delete_cookie( $order_id ) {

	setcookie("gft_discount", "", time()-3600,'/');


}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('GiftFinder')) {
	$obj = new GiftFinder();
	//require_once plugin_dir_path(__FILE__) . 'back/gift_finder_cpt.php';
}