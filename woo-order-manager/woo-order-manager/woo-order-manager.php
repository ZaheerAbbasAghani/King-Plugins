<?php
/*
Plugin Name: Woo Order Manager
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: We will create a new user role called "Order Manager"   We will limit the ability of this user to not be able to do anything other than view WooCommerce orders and change their status.  These users will not have access to pricing information in the order table or in the order details page (including cost, total, and amount paid).
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: woo-order-manager
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WooOrderManager {

function __construct() {
	//register_activation_hook(__FILE__ , array($this,'woo_plugin_activation') );
	add_action('init', array($this, 'wom_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'wom_enqueue_script_front'));
	add_action( 'admin_init', array($this,  'wom_remove_menu_pages') );
  add_action('admin_enqueue_scripts', array($this, 'wom_enqueue_script_admin'));
  add_action( 'admin_menu' , array($this, 'wpdocs_remove_post_custom_fields') );

  add_action( 'show_user_profile', array($this, 'wom_extra_user_profile_fields') );
  add_action( 'edit_user_profile', array($this,'wom_extra_user_profile_fields') );

  add_action( 'personal_options_update', array($this, 'save_wom_extra_user_profile_fields') );
  add_action( 'edit_user_profile_update', array($this,'save_wom_extra_user_profile_fields') );
  add_action( 'pre_get_posts', array($this, 'zipcode_wise_post_filter') );

  add_action( 'admin_footer', array($this, 'wom_footer_block') );


}



function wom_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/wom_role_maker.php';
}

// Enqueue Style and Scripts

function wom_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('wom-style', plugins_url('assets/css/wom.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('wom-script', plugins_url('assets/js/wom.js', __FILE__),array('jquery'),'1.0.0', true);
}





function wom_remove_menu_pages() {

   global $user_ID;

   if ( current_user_can( 'order_manager' ) ) {
     remove_menu_page('tools.php');
     remove_menu_page('users.php');
     remove_menu_page('edit-comments.php');
     remove_menu_page('upload.php');
     remove_menu_page('edit.php');
     //remove_menu_page( 'edit.php?post_type=product' );
     remove_menu_page( 'edit.php?post_type=page' );
      remove_submenu_page('edit.php?post_type=product' ,'edit.php?post_type=wcpa_pt_forms' );
     remove_menu_page( 'order_delivery_date' );
     remove_menu_page( 'xt-plugins' );
     remove_submenu_page( 'woocommerce', 'wc-order-export');
     remove_submenu_page( 'woocommerce', 'wc-reports');
     remove_submenu_page( 'woocommerce', 'wc-settings');
     remove_submenu_page( 'woocommerce', 'wc-status');
     remove_submenu_page( 'woocommerce', 'wc-addons');

   }


    if ( current_user_can( 'regional_manager' ) ) {

    $currentScreen = get_current_screen();
     
    remove_menu_page('tools.php');
    remove_menu_page('users.php');
    remove_menu_page('edit-comments.php');
    remove_menu_page('upload.php');
    remove_menu_page('edit.php');
    //remove_menu_page( 'edit.php?post_type=product' );
    remove_menu_page( 'edit.php?post_type=page' );
    remove_submenu_page('edit.php?post_type=product' ,'edit.php?post_type=wcpa_pt_forms' );
    remove_menu_page( 'order_delivery_date' );
    remove_menu_page( 'xt-plugins' );
     
     remove_submenu_page( 'woocommerce', 'wc-order-export');
     remove_submenu_page( 'woocommerce', 'wc-reports');
     remove_submenu_page( 'woocommerce', 'wc-settings');
     remove_submenu_page( 'woocommerce', 'wc-status');
     remove_submenu_page( 'woocommerce', 'wc-addons');
     remove_submenu_page( 'woocommerce', 'wc-coupons');
     remove_submenu_page( 'woocommerce' ,'edit.php?post_type=shop_coupon' );
     remove_submenu_page( 'woocommerce' ,'wgact' );


   }


}

function wom_enqueue_script_admin($hook){

    if ( current_user_can( 'order_manager' ) ) {

    	  wp_enqueue_script('wom-script-admin', plugins_url('assets/js/wom_admin.js', __FILE__),array('jquery'),'1.0.0', false);
    }

   

        wp_enqueue_style('wom-style-admin', plugins_url('assets/css/inputTags.css', __FILE__),'1.0.0','all');
        wp_enqueue_script('wom-script-admin-tags', plugins_url('assets/js/index.js', __FILE__),array('jquery'),'1.0.0', true);

        wp_enqueue_script('wom-script-admin2', plugins_url('assets/js/wom_admin2.js', __FILE__),array('jquery'),'1.0.0', true);

  

}

function wom_footer_block(){

    if ( current_user_can( 'regional_manager' ) ) {
          echo '<script> jQuery(".post-type-product table.wp-list-table").find("a").attr("href","#"); 

            var displaying_num = jQuery(".post-type-shop_order .displaying-num").eq(0).text();
                jQuery("ul.subsubsub li.all").html("<a href='."#".'> All ("+displaying_num+")</a>");

            jQuery("#woocommerce-order-data ._billing_email_field").remove();
            jQuery("#woocommerce-order-data ._billing_phone_field").remove();
            jQuery("#woocommerce-order-data .order_data_column .address p").eq(1).remove();
            jQuery("#woocommerce-order-data .order_data_column .address p:last-child").remove();
            jQuery(".post-type-product table.wp-list-table .row-actions span.inline").remove();
            jQuery(".post-type-product table.wp-list-table .row-actions span.trash").remove();
            jQuery(".post-type-product table.wp-list-table .row-actions span.duplicate").remove();

          </script>';
    }
}




function wom_extra_user_profile_fields( $user ) {


    //echo $user->ID;

 if ( current_user_can( 'administrator' ) || current_user_can( 'regional_manager' )) {
 ?>
    <h3><?php _e("Order Manager Zip Codes List", "woo-order-manager"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="zip_codes"><?php _e("Zip Codes"); ?></label></th>
        <td>
            <input type="text" name="zip_codes" id="zip_codes" value="<?php echo esc_attr( get_the_author_meta( 'zip_codes', $user->ID ) ); ?>" class="regular-text" />
        </td>
    </tr>
   
    </table>
<?php 

}else{
    ?>

<?php if ( current_user_can( 'regional_manager' )) 

    { ?>

    <h3><?php _e("Order Manager Zip Codes List", "woo-order-manager"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="zip_codes"><?php _e("Zip Codes"); ?></label></th>
        <td>
           <?php echo esc_attr( get_the_author_meta( 'zip_codes', $user->ID ) ); ?>
        </td>
    </tr>
    </table>

<?php } ?>

    <?php 
}


}





function zipcode_wise_post_filter( $query ) {
    
    if( !$query->is_main_query() ) return;

    if ( in_array ( $query->get('post_type'), array('shop_order') ) ) {
        if(isset(wp_get_current_user()->roles[0]) && wp_get_current_user()->roles[0] == 'regional_manager'){
            $user_id = get_current_user_id();
            $zip_code = esc_attr( get_the_author_meta( 'zip_codes', $user_id ) );
            $zipArray = explode(",", $zip_code);

            $meta_query = $query->get("meta_query");
            $meta_query = array(
                "relation" => "AND",
            );
            $query->set('post_type', 'shop_order' );

              $meta_query[]  =   array(
                  'key'=>'_billing_postcode',
                  'value'=> $zipArray,
                  'compare' => 'IN',
              );


            add_filter( 'views_edit-shop_order', function( $views ) {

                $views['all'] = sprintf("<a href='%s'>All (".$query['post_count'].")", $url_to_redirect, $all_count );
                $views['wc-processing'] = sprintf("<a href='%s'>Processing (%d)", $url_to_redirect, $processing_count );
                $views['wc-completed'] = sprintf("<a href='%s'>Completed (%d)", $url_to_redirect, $completed_count );
                $views['wc-failed'] = sprintf("<a href='%s'>Failed (%d)", $url_to_redirect, $failed_count );

                unset( $views['wc-failed'] );
                unset( $views['wc-refunded'] );
                unset( $views['wc-cancelled'] );
                unset( $views['wc-completed'] );
                unset( $views['wc-processing'] );

            return $views;

            });
                


            $query->set('meta_query',$meta_query ); 

        } 
    }

  return $query; 
}



function save_wom_extra_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'zip_codes', $_POST['zip_codes'] );
  
}


function wom_reports_get_order_report_query_filter($args) {

    $user_id = get_current_user_id();
    $zip_code = esc_attr( get_the_author_meta( 'zip_codes', $user_id ) );
    $zipArray = explode(",", $zip_code);


    $args['where_meta'] = array(
        'meta_key' => '_billing_postcode',
        'meta_value' => array(11435),
        'operator' => 'in'
    );
    return $args;
}


/**
 * Remove Custom Fields meta box
 */
function wpdocs_remove_post_custom_fields() {
    remove_meta_box( 'order-delivery-date', 'shop_order' , 'normal' ); 
}




} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WooOrderManager')) {
	$obj = new WooOrderManager();
}