<?php
/*
Plugin Name: Liav Product Carousel
Plugin URI: 
Description: Display a particular list of products in owl carousel with backend control.
Version: 1.0
Author: Zah
Author URI: Zah
License: GPLv3 or later
Text Domain: liav-product-carousel
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class LiavProductCarousel {

function __construct() {
	add_action('init', array($this, 'lpc_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'bpem_enqueue_script_front'));
	add_action( 'woocommerce_product_options_advanced', array($this,'lpc_product_options'));
	add_action( 'woocommerce_process_product_meta', array($this,'lpc_save_fields'), 10, 2 );	
}



function lpc_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'lpc_front/lpc_shortcode.php';

}

// Enqueue Style and Scripts

function bpem_enqueue_script_front() {
//Style & Script
wp_enqueue_style('lpc-style', plugins_url('assets/css/lpc.css', __FILE__),'1.0.0','all');
//wp_enqueue_style('j-carousel', plugins_url('assets/css/jcarousel.responsive.css', __FILE__),'1.0.0','all');
wp_enqueue_style('lpc-owl-carousel', "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css");



wp_enqueue_script('lpc-script', plugins_url('assets/js/lpc.js', __FILE__),array('jquery'),'1.0.0', true);
//$lps_show_hide_product = get_post_meta(get_the_ID(), 'lpc_show_hide_product',false);
//wp_localize_script( $handle, $object_name, $l10n );


}


function lpc_product_options(){
 
	echo '<div class="options_group">';
	woocommerce_wp_checkbox( array(
		'id'      => 'lpc_show_hide_product',
		'value'   => get_post_meta( get_the_ID(), 'lpc_show_hide_product', true ),
		'label'   => 'Show/Hide Product',	
	));
	echo '</div>';
 
}

function lpc_save_fields( $id, $post ){
 	update_post_meta( $id, 'lpc_show_hide_product', $_POST['lpc_show_hide_product'] );	
}



} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('LiavProductCarousel')) {
	$obj = new LiavProductCarousel();
}