<?php

/*

Plugin Name: Pirani App
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin is used to get some information regarding product.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: pirani-app
Domain Path: /languages

*/



defined("ABSPATH") or die("No direct access!");

class PiraniApp {

function __construct() {
	session_start();
	add_action('init', array($this, 'pa_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'bpem_enqueue_script_front'));
	add_action( "admin_enqueue_scripts", array($this,"pa_enqueue_admin_scripts"));

	add_action( 'woocommerce_product_options_advanced', array($this,'pa_adv_product_options'));
	add_action( 'woocommerce_process_product_meta', array($this,'pa_save_fields'), 10, 2 );
	add_action( 'woocommerce_before_calculate_totals', array($this,'woocommerce_custom_price_to_cart_item'), 99 );

	add_filter('woocommerce_add_cart_item_data',array($this,'wdm_add_item_data'),1,2);
	add_filter('woocommerce_get_cart_item_from_session', array($this,'wdm_get_cart_items_from_session'), 1, 3 );
	//add_filter('woocommerce_admin_order_data_after_order_details',array($this,'wdm_add_user_custom_option_from_session_into_cart'),1,3);  
	add_filter('woocommerce_cart_item_name',array($this,'wdm_add_user_custom_option_from_session_into_cart'),1,3);
    add_action('woocommerce_checkout_create_order',array($this, 'wdm_add_values_to_order_item_meta_save'), 10, 4 );
	add_action('woocommerce_admin_order_data_after_order_details',array($this, 'wdm_add_values_to_order_item_meta'), 10, 4 );
	add_action('woocommerce_before_cart_item_quantity_zero',array($this, 'wdm_remove_user_custom_data_options_from_cart'),1,1);

}



function pa_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'pa_front/pa_process.php';
	require_once plugin_dir_path(__FILE__) . 'pa_inc/pa_text_replacement.php';
	require_once plugin_dir_path(__FILE__) . 'pa_inc/pa_thread_color.php';
	require_once plugin_dir_path(__FILE__) . 'pa_inc/pa_font_style.php';
	require_once plugin_dir_path(__FILE__) . 'pa_inc/pa_img_price_fields.php';
	require_once plugin_dir_path(__FILE__) . 'pa_inc/pa_custom_add_to_cart.php';
}


// Enqueue Style and Scripts

function bpem_enqueue_script_front() {
//Style & Script
wp_enqueue_style('pa-style', plugins_url('assets/css/pirani.css', __FILE__),'1.0.0','all');
wp_enqueue_style('pa-d-script', "https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap");

wp_enqueue_script( "validation", "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js");

wp_enqueue_script('pa-script', plugins_url('assets/js/pirani.js', __FILE__),array('jquery'),'1.0.0', true);

if (function_exists('is_product') && is_product()) {
        wp_enqueue_script('woocommerce-ajax-add-to-cart', plugin_dir_url(__FILE__) . 'assets/js/ajax-add-to-cart.js', array('jquery'), '', true);
}


}

function  pa_enqueue_admin_scripts(){
	wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');

	wp_enqueue_script('pa-admin-enqueue', plugin_dir_url(__FILE__) .'assets/js/pirani-admin.js',array('jquery'),'1.0.0', false);
}

 


function pa_adv_product_options(){
 
	echo '<div class="options_group">';
 
	woocommerce_wp_checkbox( array(
		'id'      => 'pa_enable_embroidery',
		'value'   => get_post_meta( get_the_ID(), 'pa_enable_embroidery', true ),
		'label'   => 'Enable/Disable Embroidery',	
	));
 
	echo '</div>';
 
}
 
 

function pa_save_fields( $id, $post ){
 	update_post_meta( $id, 'pa_enable_embroidery', $_POST['pa_enable_embroidery'] );	
}


 function woocommerce_custom_price_to_cart_item( $cart_object ) {  
    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if( isset( $value["custom_price"] ) ) {
                $value['data']->set_price($value["custom_price"]);
                
            }
        }  
    }  
}



    function wdm_add_item_data($cart_item_data,$product_id)
    {
        /*Here, We are adding item in WooCommerce session with, pa_product_custom_data_value name*/
        global $woocommerce;
        session_start();    
        if (isset($_SESSION['pa_product_custom_data'])) {
            $option = $_SESSION['pa_product_custom_data'];       
            $new_value = array('pa_product_custom_data_value' => $option);
        }
        if(empty($option))
            return $cart_item_data;
        else
        {    
            if(empty($cart_item_data))
                return $new_value;
            else
                return array_merge($cart_item_data,$new_value);
        }
        unset($_SESSION['pa_product_custom_data']); 
        //Unset our custom session variable, as it is no longer needed.
    }




    function wdm_get_cart_items_from_session($item,$values,$key)
    {
        if (array_key_exists( 'pa_product_custom_data', $values ) )
        {
        	$item['pa_product_custom_data'] = $values['pa_product_custom_data'];
        }       
        return $item;
    }





 function wdm_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key )
    {
        /*code to add custom data on Cart & checkout Page*/    
        if(count($values['pa_product_custom_data_value']) > 0)
        {
            $return_string = $product_name . "</a>";
            $return_string .= "<div class='wdm_options_table' id='" . $values['product_id'] . "'>";
            //$return_string .= "<h3>Embroidery</h3>";
            $i=1;
            $j=0;
            $labels = array("First Line:","Second Line:","Position:","Color:","Font:");
            foreach ($values['pa_product_custom_data_value'] as $value) {
            	$return_string .= "<div class='box'>";
            	if($i == 1){	
            		$return_string .= "<img src='" . $value . "'/>";
            	}else{

            		$return_string .= "<span>".$labels[$j]." ".$value."</span>";
            		$j++;
            	}
            	$i++;

            	$return_string .= "</div>";
            }
            $return_string .= "</div>"; 
            return $return_string;
        }
        else
        {
            return $product_name;
        }
    }



  function wdm_add_values_to_order_item_meta_save($order)
  {     session_start();
        $extra_info = $_SESSION['pa_product_custom_data'];
        $order->update_meta_data("pa_extra_info", $extra_info);    
  }



  function wdm_add_values_to_order_item_meta($order)
  {
        $extra_information = get_post_meta( $order->get_id(), 'pa_extra_info', false );
        
        $i=1;
        echo "<div class='emb_panel' style='padding-top:5px;clear:both;'><h3 style='margin-bottom:10px;'>Embroidery</h3>";
        echo "<table border='1' style='width:100%; text-align:center;'>";
            foreach ($extra_information as $value) {
                echo "<tr>
                    <th>Image</th>
                    <th>First Line</th>
                    <th>Second Line</th>
                    <th>Placement</th>
                    <th>Color</th>
                    <th>Font Style</th>
                <tr>";
                echo "<tr>";
                foreach ($value as $info) {
                    if($i==1){
                        echo "<td><img src='".$info."' style='width:80px; height:90px;'/></td>";
                    }else{
                        echo "<td>".$info."</td>";
                    }
                    $i++;
                }
                echo "</tr>";
            }
        echo "</table></div>";

  }



function wdm_remove_user_custom_data_options_from_cart($cart_item_key)
{
    global $woocommerce;
    // Get cart
    $cart = $woocommerce->cart->get_cart();
    // For each item in cart, if item is upsell of deleted product, delete it
    foreach( $cart as $key => $values)
    {
    if ( $values['pa_product_custom_data_value'] == $cart_item_key )
        unset( $woocommerce->cart->cart_contents[ $key ] );
    }
}



} // class ends



// CHECK WETHER CLASS EXISTS OR NOT.
if (class_exists('PiraniApp')) {
	$obj = new PiraniApp();
}