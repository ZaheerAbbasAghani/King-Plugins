<?php
/*
Plugin Name: BML Uploader
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: The plugin allow to upload files.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: bml-uploader
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class BMLUploader {

function __construct() {

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	add_action('init', array($this, 'bml_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'bml_enqueue_script_front'));
	add_action( 'woocommerce_before_add_to_cart_button', array($this, 'addon_uploads_section' ) );

	add_filter( 'woocommerce_add_cart_item_data', array($this,  'bml_add_cart_item_data' ), 10, 2 );
	add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'bml_get_cart_item_from_session' ), 10, 2 );

	add_filter('woocommerce_get_item_data',array( $this,'bml_get_item_data'), 10, 2 );

	add_action( 'woocommerce_checkout_create_order_line_item', array($this, 'bml_add_item_meta_url' ), 10, 4 );

	add_action( 'woocommerce_cart_item_removed', array($this, 'bml_remove_cart_action' ), 10, 2 );


}



function bml_start_from_here() {
	//require_once plugin_dir_path(__FILE__) . 'bml_front/bml_shortcode.php';

}

// Enqueue Style and Scripts

function bml_enqueue_script_front() {
	//Style & Script
	wp_enqueue_style('bml-style', plugins_url('assets/css/bml.css', __FILE__),'1.0.0','all');
	wp_enqueue_script('bml-script', plugins_url('assets/js/bml.js', __FILE__),array('jquery'),'1.0.0', true);
}

function addon_uploads_section() {

		
	$upload_label = __( 'Upload an image: ', 'bml-uploader' );
	$file_upload_template = '<div class="bml_wrapper_div">
			<label for="bml_file_addon">' . $upload_label . '</label>
			<input type="file" name="bml_file_addon[]" id="bml_file_addon" accept="image/*" class="bml-auto-width bml-files" multiple/>
		</div>';
	echo $file_upload_template;

}


function bml_add_cart_item_data( $cart_item_meta, $product_id ){

	$images_id = array();



	$files = $_FILES["bml_file_addon"];

    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            $file = array(
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            );
            $_FILES = array("upload_file" => $file);
            $attachment_id = media_handle_upload("upload_file", 0);

            if (is_wp_error($attachment_id)) {
                // There was an error uploading the image.
                echo "Error adding file";
            } else {
     
				$images_id['media_id'] = $attachment_id;
				$images_id['media_url'] = wp_get_attachment_url( esc_attr( $attachment_id ) );
				$cart_item_meta['bml_images_ids'][] = $images_id;


            }
        }
    }



	return $cart_item_meta;
}


function bml_get_cart_item_from_session( $cart_item, $values ){

	if( isset( $values['bml_images_ids'] ) ){
		$cart_item['bml_images_ids'] = $values['bml_images_ids'];
	}

	return $cart_item;
}



function bml_get_item_data( $other_data, $cart_item ) {
	if ( isset( $cart_item['bml_images_ids'] ) ) {

		print_r($cart_item['bml_images_ids'] );


		foreach ( $cart_item['bml_images_ids'] as $addon_id ) {
			$name    = __( 'Uploaded File', 'bml-uploader' );
			$display = $addon_id['media_id'];

			$other_data[] = array(
				'name'    => $name,
				'display' => wp_get_attachment_image( $display, 'thumbnail', 'true', '' )
			);
		}
	}
	return $other_data;
}


function bml_add_item_meta_url( $item, $cart_item_key, $values, $order ) {

		if ( empty( $values['bml_images_ids'] ) ) {
			return;
		}

		foreach ( $values['bml_images_ids'] as $addon_key => $addon_id ) {
			$media_url = wp_get_attachment_url( esc_attr( $addon_id['media_id'] ) );

			$item->add_meta_data( __( 'Uploaded Media', 'bml-uploader' ), $media_url );
		}
}


function bml_remove_cart_action( $cart_item_key, $cart ) {
	$removed_item = $cart->removed_cart_contents[ $cart_item_key ];

	if ( isset( $removed_item['bml_images_ids'] ) && isset( $removed_item['bml_images_ids'][0] ) &&
			isset( $removed_item['bml_images_ids'][0]['media_id'] ) && $removed_item['bml_images_ids'][0]['media_id'] !== '' ) {

		$media_id = $removed_item['bml_images_ids'][0]['media_id'];

		$delete_status = wp_delete_attachment( $media_id, true );
	}
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('BMLUploader')) {
	$obj = new BMLUploader();
}