<?php

/*

Plugin Name: Woocommerce File Uploader

Plugin URI: https://www.fiverr.com/zaheerabbasagha

Description: Plugin is used to create functionality for persons who want to purchase products on rent.

Version: 1.0

Author: Zaheer Abbas Aghani

Author URI: https://profiles.wordpress.org/zaheer01/

License: GPLv3 or later

Text Domain: woo-file-uploder

Domain Path: /languages

*/



defined("ABSPATH") or die("No direct access!");

class WoocommerceFileUploader {



function __construct() {

	//register_activation_hook( __FILE__, array($this,'wfu_my_activation'));
	add_action('init', array($this, 'wfu_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'wfu_enqueue_script_front'));
	add_action('admin_enqueue_scripts', array($this, 'wfu_enqueue_script_admin'));
	add_action( 'woocommerce_before_add_to_cart_button', array($this, 'display_additional_product_fields'), 9 );
	add_action( 'woocommerce_after_cart_item_name', array($this,'wfu_action_woocommerce_after_item_name'), 10, 2 );

	add_filter( 'woocommerce_cart_item_name', array($this, 'wfu_product_image_on_checkout'), 10, 3 );

	add_filter('woocommerce_thankyou',array($this,'wfu_move_to_order_folder'), 1, 1 );

	add_filter('woocommerce_order_item_name', array($this, 'wfu_change_orders_items_names'), 10, 2);
	add_action('woocommerce_after_order_itemmeta', array($this,'wdm_add_values_to_order_item_meta'),1,2);



}



function wfu_start_from_here() {


	require_once plugin_dir_path(__FILE__).'front/wfu_file_upload.php';
	require_once plugin_dir_path(__FILE__).'front/wfu_remove_product_images.php';
	require_once plugin_dir_path(__FILE__).'back/wfu_show_images_list.php';
	

}



// Enqueue Style and Scripts



function wfu_enqueue_script_front() {

	//Style & Script
	wp_enqueue_style('wfu-style', plugins_url('assets/css/wfu.css', __FILE__),'1.0.0','all');

	wp_enqueue_script('wfu-script',  plugins_url('assets/js/wfu.js', __FILE__), array('jquery'), rand(0,1000), false);

	$script_data_array = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'file_upload' ),
    );
    wp_localize_script( 'wfu-script', 'wfu_object', $script_data_array );

}

function wfu_enqueue_script_admin($hook){

	if($hook != "post.php")
		return false;

	wp_enqueue_script('wfu-script-admin',  plugins_url('assets/js/wfu_admin.js', __FILE__), array('jquery'), rand(0,1000), false);

	$script_data_array = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'file_upload' ),
    );
    wp_localize_script('wfu-script-admin', 'wfu_object', $script_data_array );

}



function display_additional_product_fields(){
    ?>
    <p class="form-row validate-required" id="image" >
        <label for="file_field"><?php echo __("Upload Image") . ': '; ?>
            <input type="file" id="file" accept="image/*" multiple />
        </label>
    </p>
    <?php
}


function wfu_action_woocommerce_after_item_name( $item, $item_key ) {

	//echo "HELLO WORLD";
 	$wp_upload_dir =  wp_upload_dir();
 	$dname = $item['product_id'];
 	//echo $dname;
    $directory = $wp_upload_dir['path']."/".$dname;

	$images = glob($directory . "/*.{jpg,gif,png}", GLOB_BRACE);
	echo "<ul>";
	foreach($images as $image)
	{
		$url = $wp_upload_dir['url'].'/'.$dname.'/'.basename($image);
	  	echo "<li style='display:inline-block;'><img src='".$url."' style='width:50px;height:70px;margin-right:10px;'/></li>";
	}
	echo "</ul>";

}





function wfu_product_image_on_checkout( $name, $item, $item_key ) {  

    /* Return if not checkout page */
    if ( ! is_checkout() ) {
        return $name;
    }


 	$wp_upload_dir =  wp_upload_dir();
 	$dname = $item['product_id'];
    $directory = $wp_upload_dir['path']."/".$dname;

	$images = glob($directory . "/*.{jpg,gif,png}", GLOB_BRACE);
	$img = "";
	$img .= "<ul>";
	foreach($images as $image)
	{
		$url = $wp_upload_dir['url'].'/'.$dname.'/'.basename($image);
	  	$img .= "<li style='display:inline-block;'><img src='".$url."' style='width:50px;height:70px;margin-right:10px;'/></li>";
	}
	$img .= "</ul>";

    return $name."<br>".$img;

}

function wfu_move_to_order_folder( $order_id ){

   	$order = new WC_Order( $order_id );
   	$ordero = wc_get_order( $order_id );
	$total_images = array();
	$folders = array();
	$wp_upload_dir =  wp_upload_dir();
		$dir = $wp_upload_dir['path']."/".$order_id;

	if(!is_dir($dir)){
		
		if(!is_dir($dir)){
			mkdir($dir);
		}

		foreach ($order->get_items() as $item_key => $item ):

			$product_id =  $item->get_product_id();
			
			$dname = $product_id;
		    $directory = $wp_upload_dir['path']."/".$order_id.'/'.$dname;

		    if(!is_dir($directory)){
				mkdir($directory);
			}


			$files = scandir($wp_upload_dir['path']."/".$dname);

			$source = $wp_upload_dir['path']."/".$dname."/";
			$destination = $wp_upload_dir['path']."/".$order_id."/".$dname."/";
			$delete = array();
			foreach ($files as $file) {
			  if (in_array($file, array(".",".."))) continue;
			  // If we copied this successfully, mark it for deletion
			  if (copy($source.$file, $destination.$file)) {
			    $ordero->update_meta_data($order_id.'-'.$dname, $destination.$file);
				$ordero->save();
			    array_push($delete, $source.$file);
			  }
			}

			foreach ($delete as $d) {
		        //array_map('unlink', glob("$d/*.*"));
		        unlink($d);
			}
			rmdir($wp_upload_dir['path']."/".$dname);

			

		endforeach;
	}

}



function wfu_change_orders_items_names( $name, $item){


	//print_r($item);

	$wp_upload_dir =  wp_upload_dir();
 	$dname = $item['order_id'];
    $directory = $wp_upload_dir['path']."/".$dname."/".$item['product_id'];

	$images = glob($directory . "/*.{jpg,gif,png}", GLOB_BRACE);
	$img = "";
	$img .= "<ul>";
	foreach($images as $image)
	{
		$url=$wp_upload_dir['url'].'/'.$dname.'/'.$item['product_id']."/".basename($image);
	  	$img .= "<li style='display:inline-block;'><img src='".$url."' style='width:50px;height:70px;margin-right:10px;'/></li>";
	}
	$img .= "</ul>";

    return $name."<br>".$img;

}



function wdm_add_values_to_order_item_meta($item_id, $item){	
	//print_r($item['product_id']);

 	$wp_upload_dir =  wp_upload_dir();
 	$pid = $item['product_id'];
 	$oid = $item['order_id'];
 	//echo $dname;
    $directory = $wp_upload_dir['path']."/".$oid."/".$pid;

	$images = glob($directory . "/*.{jpg,gif,png}", GLOB_BRACE);
	echo "<ul>";
	foreach($images as $image)
	{
		$url = $wp_upload_dir['url']."/".$oid."/".$pid.'/'.basename($image);
	  	echo "<li style='display:inline-block;'><a href='".$url."' target='_blank'> <img src='".$url."' style='width:50px;height:70px;margin-right:10px;'/></a></li>";
	}
	echo "</ul>";


}



} // class ends



// CHECK WETHER CLASS EXISTS OR NOT.



if (class_exists('WoocommerceFileUploader')) {

	$obj = new WoocommerceFileUploader();

}