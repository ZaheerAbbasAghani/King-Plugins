<?php
/*
Plugin Name: Woo CSV Product Uploader
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Upload products that are present on the csv file and not present on the site (new products), Update the products that have the same code, Delete the products that are not in the csv file I am uploading
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: woo-csv-product-uploader
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class WooCSVProductUploader {

function __construct() {
	add_action('init', array($this, 'wcpu_start_from_here'));
	add_action('admin_enqueue_scripts', array($this, 'wcpu_enqueue_script_admin'));
	add_action( 'admin_init', array($this,'wcpu_if_woocommerce_not_active' ));

		// Schedule 10 min

	register_activation_hook( __FILE__,array($this,'wcpu_schedule_thirty_min_cron'));
	add_filter('cron_schedules',array($this,'wcpu_thirty_min_cron_schedules'));
	add_action('wcpu_thirty_min_cron', array($this,'wcpu_thirty_min_cron'));
	register_deactivation_hook(__FILE__,array($this,'wcpu_thirty_min_deactivation'));
	
	
}


function wcpu_if_woocommerce_not_active($message) {
	if (!is_plugin_active('woocommerce/woocommerce.php')) {
	
		$message = "<div class='notice notice-error is-dismissible'><p> <b> Woocommerce </b> Plugin Activation Required for <b> Woo CSV Product Uploader</b>.</p>
		</div>";
		echo $message;
		deactivate_plugins('/woo-csv-product-uploader/woo-csv-product-uploader.php');
		

	}
}


function wcpu_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'back/wcpu_settings_page.php';
	require_once plugin_dir_path(__FILE__) . 'back/wcpu_csv_upload.php';

}

function wcpu_schedule_thirty_min_cron(){
    wp_schedule_event(time(), '30min', 'wcpu_thirty_min_cron');
}

function wcpu_thirty_min_cron_schedules($schedules){
    if(!isset($schedules["30min"])){
        $schedules["30min"] = array(
            'interval' => 3*60,
            'display' => __('Once every 30 minutes'));
    }
    
    return $schedules;
}



function wcpu_thirty_min_deactivation() {
    wp_clear_scheduled_hook( 'wcpu_thirty_min_cron' );
}


function wcpu_thirty_min_cron(){


$csv_file = glob(plugin_dir_path( __FILE__ )."*.csv");

if(!is_dir_empty($csv_file)){

global $wpdb;

// All Products
$all_ids = get_posts( array(
    'post_type' => 'product',
    'numberposts' => -1,
    'post_status' => 'publish',
    'fields' => 'ids',
));

$all_products = array();
foreach ( $all_ids as $id ) {
    array_push($all_products, $id);
}


$available_products = array();


$row = 1;
$flag = true;
if (($handle = fopen($csv_file[0], "r")) !== FALSE) {
    while (($value = fgetcsv($handle, 1000, ",")) !== FALSE) {

     if($flag) {  $flag = false;  continue;  }

		$BothCategories = explode(">", $value[0]);

	    $check_post = get_page_by_title($value[2], OBJECT, 'product');
	    if($check_post){
	        //echo "Exists <br>";
	        array_push($available_products, $check_post->ID);

	        $post = [

	            'post_author' => get_current_user_id(),
	            'post_title' => wp_strip_all_tags($value[2]),
	            'post_name' => $value[2],
	            'post_excerpt' => $value[3],
	            'post_content' => $value[4],
	            'post_status' => "publish",
	            'post_type' => "product",
	        ];

	        //Create Post

	        //$product_id = wp_insert_post($post);

	        $product_id = wp_update_post($post);

	        update_post_meta($product_id, '_stock_status', 'instock');
	        update_post_meta($product_id, '_stock', $value[7]);
	        update_post_meta($product_id, '_price', $value[5]);
	        update_post_meta( $product_id, '_regular_price', $value[5] );
	        update_post_meta( $product_id, '_sku', $value[1] );



	    }else{
	        //echo "Not Exists <br>";

	      //  echo $BothCategories[0]


	        $post = [

	            'post_author' => get_current_user_id(),
	            'post_title' => wp_strip_all_tags($value[2]),
	            'post_name' => $value[2],
	            'post_excerpt' => $value[3],
	            'post_content' => $value[4],
	            'post_status' => "publish",
	            'post_type' => "product",
	        ];

	        //Create Post

	        $product_id = wp_insert_post($post);

	        // Creating Category

	        $cat_name = utf8_encode($BothCategories[0]); // category name we want to assign the post to 

	        $taxonomy = 'product_cat'; // category by default for posts for other custom post types like woo-commerce it is product_cat

	        $append = true ;// true means it will add the cateogry beside already set categories. false will overwrite

	        //get the category to check if exists

	        $cat  = get_term_by('name', $cat_name , $taxonomy);
	        //check existence

	        if($cat == false){

	            //cateogry not exist create it 
	            $cat = wp_insert_term($cat_name, $taxonomy);
	            $child_cat = wp_insert_term(utf8_encode($BothCategories[1]), 'product_cat', array('description'=> '', 'slug' => sanitize_title(utf8_encode($BothCategories[1])),'parent' => $cat['term_id']));

	            $cid = $child_cat['term_id'];

	        }else{

	           $cid = $BothCategories[1];
	        }



	        //setting post category 

	        wp_set_object_terms($product_id, array($cid), 'product_cat');
	        wp_set_object_terms($product_id, array($value[6]), 'product_tag');

	        update_post_meta($product_id, '_stock_status', 'instock');
	        update_post_meta($product_id, '_stock', $value[7]);
	        update_post_meta($product_id, '_price', $value[5]);
	        update_post_meta( $product_id, '_regular_price', $value[5] );
	        update_post_meta( $product_id, '_sku', $value[1] );


	        // only need these if performing outside of admin environment

	        require_once(ABSPATH . 'wp-admin/includes/media.php');
	        require_once(ABSPATH . 'wp-admin/includes/file.php');
	        require_once(ABSPATH . 'wp-admin/includes/image.php');

	        // example image
	        $image = $value[8];
	        // magic sideload image returns an HTML image, not an ID
	        $media = media_sideload_image($image, $product_id);

	        // therefore we must find it so we can set it as featured ID

	        if(!empty($media) && !is_wp_error($media)){

	            $args = array(
	                'post_type' => 'attachment',
	                'posts_per_page' => -1,
	                'post_status' => 'any',
	                'post_parent' => $product_id
	            );

	            // reference new image to set as featured

	            $attachments = get_posts($args);

	            if(isset($attachments) && is_array($attachments)){

	                foreach($attachments as $attachment){

	                    // grab source of full size images (so no 300x150 nonsense in path)

	                    $image = wp_get_attachment_image_src($attachment->ID, 'full');

	                    // determine if in the $media image we created, the string of the URL exists

	                    if(strpos($media, $image[0]) !== false){

	                        // if so, we found our image. set it as thumbnail

	                        set_post_thumbnail($product_id, $attachment->ID);

	                        // only want one image

	                        break;
	                    }
	                }
	            }
	        }


	    } // if not exists
    }
}

}


}

// Enqueue Style and Scripts

function wcpu_enqueue_script_admin($hook) {

	if($hook != "toplevel_page_wcpu_product_uploader")
		return;
	//Style & Script
	wp_enqueue_script('wcpu-script-cookie', plugins_url('assets/js/jquery.cookie.js', __FILE__),array('jquery'),'1.4.1', true);

	wp_enqueue_script('wcpu-script', plugins_url('assets/js/adminScript.js', __FILE__),array('jquery'),'1.0.0', true);

		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'wcpu-script', 'wcpu_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('WooCSVProductUploader')) {
	$obj = new WooCSVProductUploader();
}