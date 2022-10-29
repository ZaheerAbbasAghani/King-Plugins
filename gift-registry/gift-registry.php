<?php
/*
Plugin Name: Gift Registry
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: when user goes to registry cart they can see all their items and send out one link that user B can select which gift they are going to buy or some gifts or all gifts on registry list and when they purchase an item or items they disappear from the list… so user C no longer sees full list because user B brought a item.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: gift-registry
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class GiftRegistry {

function __construct() {
	add_action('init', array($this, 'gft_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'gft_enqueue_script_front'));

	register_activation_hook( __FILE__, array($this, 'gft_create_registry_page') );

	add_action( 'admin_init', array($this,'gft_if_woocommerce_not_active' ));
	add_filter( 'woocommerce_get_price_html', array($this,'append_icon_after_product_price'), 10, 2 );

	add_action( 'woocommerce_register_form',  array($this, 'gft_extra_register_fields') );
	add_action('woocommerce_register_post',array($this,'gft_validate_extra_register_fields'),10,3);
	add_action( 'woocommerce_created_customer', array($this,'gft_save_extra_register_fields') );

	add_action( 'woocommerce_thankyou', array($this, 'gft_check_if_gift_registry_product'), 4 );
	
}

// Check if woocommerce not active
function gft_if_woocommerce_not_active($message) {
	if (!is_plugin_active('woocommerce/woocommerce.php')) {
		echo $message .= "<div class='notice notice-error is-dismissible'><h4> Woocommerce Plugin Activation Required for Gift Registry Plugin.</h4>
		</div>";
		deactivate_plugins('/gift-registry/gift-registry.php');
	}
}


// Before Plugin Installation
function gft_create_registry_page() { 
    
    // Create Gift Registry Page

    //if( ! is_page('Gift Registry') ):
    if ( get_page_by_title('Gift Registry') == null ):
   	
	   	$page_details = array(
		  'post_title'    => 'Gift Registry',
		  'post_content'  => '[gift_registry_list]',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_type' => 'page'
	    );
	    wp_insert_post( $page_details );

	endif;

    // Creating Table in database
    global $wpdb;
    $table_name = $wpdb->base_prefix.'gft_gift_registry_records';
    $table_name2 = $wpdb->base_prefix.'gft_gift_registry_users';
   
    $query=$wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like($table_name ));
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          product_id text NOT NULL,
          user_id int(30) NOT NULL,
          created_at datetime NOT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    $query=$wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like($table_name2 ));
    if ( ! $wpdb->get_var( $query ) == $table_name2 ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name2 (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          product_id text NOT NULL,
          user_id int(30) NOT NULL,
          user_email varchar(50) NOT NULL,
          user_phone varchar(15) NOT NULL,
          status int(1) NOT NULL,
          created_at datetime NOT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }


   // $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

    //$now = current_time('mysql', false);
    //gmdate('Y-m-d H:i:s'),

}

// Add Gift Icon to Each Product
function append_icon_after_product_price( $price, $product ) {
    if(is_singular( 'product' )){
    	$price .= '<span class="gwrap"> <i class="fa fa-gift" data-id="'.$product->get_ID().'"></i> Add to Gift Registry </span>';
    }else{
    	$price .= '<i class="fa fa-gift" data-id="'.$product->get_ID().'"></i>';
    }
    return $price;
}

// Adding Plugin Files
function gft_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'front/gift_registry_process.php';
	require_once plugin_dir_path(__FILE__) . 'front/gift_registry_list.php';
	require_once plugin_dir_path(__FILE__) . 'front/gift_registry_item_delete.php';
	require_once plugin_dir_path(__FILE__) . 'front/gift_registry_receiver_records.php';
	//require_once plugin_dir_path(__FILE__) . 'front/gift_registry_product_purchasing.php';

	require_once plugin_dir_path(__FILE__) . 'back/gift_registry_settings_page.php';

}

// Enqueue Style and Scripts
function gft_enqueue_script_front() {
	//Style & Script

	wp_enqueue_style('gft-toastr', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css','2.1.3','all');

	wp_enqueue_style('gft-style', plugins_url('assets/css/gft.css', __FILE__),'1.0.0','all');

	wp_enqueue_script('gft-toastr', "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js",array('jquery'),'2.1.3', true);

	wp_enqueue_script('gft-sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.4.9/dist/sweetalert2.all.min.js',array('jquery'),'11.4.9', true);
	wp_enqueue_script('gft-script', plugins_url('assets/js/gft.js', __FILE__),array('jquery'),'1.0.0', true);

	wp_localize_script( 'gft-script', 'gft_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}

// Add Phone Number
function gft_extra_register_fields() {?>
    
       <p class="form-row form-row-wide">
	       <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?><span class="required"> * </span></label>
	       <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>"/>
       </p>
    
       <div class="clear"></div>
       <?php
}

function gft_validate_extra_register_fields( $username, $email, $validation_errors ) {
      if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
             $validation_errors->add( 'billing_phone_error', __( 'Phone Number is required!', 'woocommerce' ) );
      }
    
      return $validation_errors;
}


/**
* Below code save extra fields.
*/
function gft_save_extra_register_fields( $customer_id ) {
	if ( isset( $_POST['billing_phone'] ) ) {
	     // Phone input filed which is used in WooCommerce
	     update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	}
}




function gft_check_if_gift_registry_product( $order_id ) {

global $wpdb; 
$table_name = $wpdb->base_prefix.'gft_gift_registry_users';

 $order = wc_get_order($order_id);
 $order_data = $order->get_data();

 $billing_email = $order_data['billing']['email'];
 $billing_phone = $order_data['billing']['phone'];

 foreach ($order->get_items() as $item_key => $item ):

 	$product = $item->get_product();
 	$pid  = $item->get_product_id();


 	if ($product->get_type() == "simple") {

	 	$query = "SELECT * FROM $table_name where user_email='".$billing_email."' AND user_phone='".$billing_phone."' AND product_id='".$pid."'";
		$query_results = $wpdb->get_results($query);

		if(!empty($query_results)){

			$wpdb->delete( $table_name, array( 'user_email' => $billing_email, 'user_phone' => $billing_phone, "product_id" => $pid ), array( '%s','%s','%s' ) );

		}

 	}else{

 		//$variable_product = new WC_Product_Variation($pid);
		$vid = $item->get_variation_id();

		/*echo $vid.'<br>';
		echo $billing_email.'<br>';
		echo $billing_email.'<br>';
*/
		$query = "SELECT * FROM $table_name where user_email='".$billing_email."' AND user_phone='".$billing_phone."'AND product_id='".$vid."'";
		$query_results = $wpdb->get_results($query);

		if(!empty($query_results)){

			$wpdb->delete( $table_name, array( 'user_email' => $billing_email, 'user_phone' => $billing_phone,  "product_id" => $vid ), array( '%s','%s','%s' ) );

		}


 	}

 	

 /*	echo $pid.'<br>';*/

 

 endforeach;


}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('GiftRegistry')) {
	$obj = new GiftRegistry();
}