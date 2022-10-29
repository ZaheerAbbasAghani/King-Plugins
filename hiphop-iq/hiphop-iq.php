<?php
/*
Plugin Name: Hip Hop IQ
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Dynamic Quiz create plugin
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: hiphop-iq
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class HipHopIq {

function __construct() {
    add_action('init', array($this, 'hhi_start_from_here'));
    add_action('wp_enqueue_scripts', array($this, 'hhi_enqueue_script_front'));
    add_action('admin_enqueue_scripts', array($this, 'hhi_admin_enqueue_script'));
    add_action( 'init', array($this,'hhi_create_faq_post_type'), 0 );
    add_action( 'init', array($this,'faq_hierarchical_taxonomy'), 0 );
    add_action( 'add_meta_boxes', array($this, 'add_faq_metaboxes' ));
  //  add_action( 'add_meta_boxes', array($this, 'add_woocomerce_answers_metaboxes' ));
    
    add_action( 'save_post', array($this,'hhi_save_faq_meta'), 1, 2 );
    //add_action( 'save_post', array($this,'hhi_extra_information_box_save' ));
    add_action('admin_footer', array($this,'hhi_admin_image_uploader'), 1, 2 );
    add_action( 'wp', array($this,'hhi_free_checkout_fields') );

    add_filter( 'woocommerce_add_to_cart_redirect', array($this,'hhi_redirect_checkout_add_cart') );
    add_action('woocommerce_thankyou',array($this,'hhi_gift_done'));
    

}



function hhi_start_from_here() {
    require_once plugin_dir_path(__FILE__).'frontend/faq_section.php';
    require_once plugin_dir_path(__FILE__).'frontend/hhi_store_and_response.php';
    require_once plugin_dir_path(__FILE__).'backend/faq_settings.php';
    require_once plugin_dir_path(__FILE__).'backend/hhi_delete_all_records.php';
}

// Enqueue Style and Scripts

function hhi_enqueue_script_front() {

if ( is_page( 'test-page' ) ) {    
//Style & Script
wp_enqueue_style('hhi-style', plugins_url('assets/css/hhi.css', __FILE__),'1.0.0','all');
//wp_enqueue_style('hhi-demo', plugins_url('assets/css/demo.css', __FILE__),'1.0.0','all');
wp_enqueue_style('hhi-component', plugins_url('assets/css/component.css', __FILE__),'1.0.0','all');

wp_enqueue_script('hhi-modernizr', plugins_url('assets/js/modernizr.custom.js', __FILE__),array('jquery'),'2.8.3', false);

wp_enqueue_script('hhi-classie', plugins_url('assets/js/classie.js', __FILE__),array('jquery'),'1.0.0', true);

wp_enqueue_script('hhi-fullscreenForm', plugins_url('assets/js/fullscreenForm.js', __FILE__),array('jquery'),'1.0.0', true);

wp_enqueue_script('hhi-script', plugins_url('assets/js/hhi.js', __FILE__),array('jquery'),'1.0.0', true);

$faq_banner_title = esc_attr( get_option('faq_banner_title') );
$faq_banner_sub_title = esc_attr( get_option('faq_banner_sub_title') );
$faq_button_text = esc_attr( get_option('faq_button_text') );
$faq_banner_image = esc_attr( get_option('faq_banner_image') );

wp_localize_script( 'hhi-script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'banner_title' => $faq_banner_title,'banner_sub_title' => $faq_banner_sub_title,  'banner_button' => $faq_button_text, 'banner_image' => $faq_banner_image) );


wp_enqueue_style('hhi-fontawesome-front', "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css",'4.7.0','all');

wp_enqueue_style('hhi-fontFamily', "https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap");

}

}

function hhi_admin_enqueue_script(){


    wp_enqueue_style('hhi-fontawesome', "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css",'4.7.0','all');

    wp_enqueue_style('hhi-admincss',plugins_url('assets/css/hhi_admin.css', __FILE__),'1.0.0','all');

    //if possible try not to queue this all over the admin by adding your settings GET page val into next
    if( empty( $_GET['page'] ) || "faq_settings" !== $_GET['page'] ) { 
        return; 
    }
    
    wp_enqueue_media();

    wp_enqueue_style('jquery-ui','https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' ,'1.12.1','all');
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_script('hhi-script-admin', plugins_url('assets/js/hhi_admin.js', __FILE__),array('jquery'),'1.0.0', true);

    wp_localize_script( 'hhi-script-admin', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
    
}


//add_action('admin_footer', function() { 
function hhi_admin_image_uploader(){
/*
if possible try not to queue this all over the admin by adding your settings GET page val into next
if( empty( $_GET['page'] ) || "my-settings-page" !== $_GET['page'] ) { return; }
*/

?>

<script>
    jQuery(document).ready(function($){

        var custom_uploader
          , click_elem = jQuery('.wpse-228085-upload')
          , target = jQuery('.wrap input[name="logo"]')

        click_elem.click(function(e) {
            e.preventDefault();
            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });
            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function() {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                target.val(attachment.url);
                jQuery("#faqBanner").val(attachment.url);
            });
            //Open the uploader dialog
            custom_uploader.open();
        });      
    });
</script>

<?php
}




/*
* Creating a function to create our CPT
*/
 
function hhi_create_faq_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Faqs', 'Post Type General Name', 'jam-faq' ),
        'singular_name'       => _x( 'Faq', 'Post Type Singular Name', 'jam-faq' ),
        'menu_name'           => __( 'Faqs', 'jam-faq' ),
        'parent_item_colon'   => __( 'Parent Faq', 'jam-faq' ),
        'all_items'           => __( 'All Faqs', 'jam-faq' ),
        'view_item'           => __( 'View Faq', 'jam-faq' ),
        'add_new_item'        => __( 'Add New Faq', 'jam-faq' ),
        'add_new'             => __( 'Add New', 'jam-faq' ),
        'edit_item'           => __( 'Edit Faq', 'jam-faq' ),
        'update_item'         => __( 'Update Faq', 'jam-faq' ),
        'search_items'        => __( 'Search Faq', 'jam-faq' ),
        'not_found'           => __( 'Not Found', 'jam-faq' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'jam-faq' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'faq', 'jam-faq' ),
        'description'         => __( 'Faq news and reviews', 'jam-faq' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'types' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
          'menu_icon'   => 'dashicons-lightbulb',
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'faq', $args );
 
}
 


 
function faq_hierarchical_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Type' ),
    'popular_items' => __( 'Popular Type' ),
    'all_items' => __( 'All Type' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Topic' ), 
    'update_item' => __( 'Update Topic' ),
    'add_new_item' => __( 'Add New Topic' ),
    'new_item_name' => __( 'New Topic Name' ),
    'separate_items_with_commas' => __( 'Separate types with commas' ),
    'add_or_remove_items' => __( 'Add or remove types' ),
    'choose_from_most_used' => __( 'Choose from the most used types' ),
    'menu_name' => __( 'Type' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('types','faq',array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'faq-type' ),
  ));
}


/**
 * Adds a metabox to the right side of the screen under the 
 */
function add_faq_metaboxes() {
    add_meta_box(
        'hhi_answer_box',
        'List of Answers',
        array($this,'hhi_answer_box'),
        'faq',
        'normal',
        'default'
    );
}


/**
 * Output the HTML for the metabox.
 */
function hhi_answer_box() {
    global $post;

    // Nonce field to validate form request came from current site
    wp_nonce_field( basename( __FILE__ ), 'answer_field_1' );
    wp_nonce_field( basename( __FILE__ ), 'answer_field_2' );
    wp_nonce_field( basename( __FILE__ ), 'answer_field_3' );
    wp_nonce_field( basename( __FILE__ ), 'answer_field_4' );
    wp_nonce_field( basename( __FILE__ ), 'answer_field_5' );


    // Get the answer1 data if it's already been entered
    $answer1 = get_post_meta( $post->ID, 'answer1', true );
    $answer2 = get_post_meta( $post->ID, 'answer2', true );
    $answer3 = get_post_meta( $post->ID, 'answer3', true );
    $answer4 = get_post_meta( $post->ID, 'answer4', true );
    $answer5 = get_post_meta( $post->ID, 'answer5', true );


    // Output the field
    echo '<input type="text" name="answer1" value="' . esc_textarea( $answer1 )  . '" class="widefat" style="margin-bottom:10px;"    placeholder="Answer 1">';


    echo '<input type="text"  name="answer2" value="' . esc_textarea( $answer2 )  . '" class="widefat" style="margin-bottom:10px;" placeholder="Answer 2">';

    echo '<input type="text" name="answer3" value="' . esc_textarea( $answer3 )  . '" class="widefat" style="margin-bottom:10px;" placeholder="Answer 3">';

    echo '<input type="text" name="answer4" value="' . esc_textarea( $answer4 )  . '" class="widefat" style="margin-bottom:10px;" placeholder="Answer 4">';

    echo '<input type="text" name="answer5" value="' . esc_textarea( $answer5 )  . '" class="widefat" style="margin-bottom:10px;" placeholder="Answer 5">';



}


/**
 * Save the metabox data
 */
function hhi_save_faq_meta( $post_id, $post ) {

    // Return if the user doesn't have edit permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;

    }

    // Verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times.
    if ( ! isset( $_POST['answer1'] ) || ! wp_verify_nonce( $_POST['answer_field_1'], basename(__FILE__) ) ) {
        return $post_id;
    }

    if ( ! isset( $_POST['answer2'] ) || ! wp_verify_nonce( $_POST['answer_field_2'], basename(__FILE__) ) ) {
        return $post_id;
    }

    if ( ! isset( $_POST['answer3'] ) || ! wp_verify_nonce( $_POST['answer_field_3'], basename(__FILE__) ) ) {
        return $post_id;
    }

    if ( ! isset( $_POST['answer4'] ) || ! wp_verify_nonce( $_POST['answer_field_4'], basename(__FILE__) ) ) {
        return $post_id;
    }

    if ( ! isset( $_POST['answer5'] ) || ! wp_verify_nonce( $_POST['answer_field_5'], basename(__FILE__) ) ) {
        return $post_id;
    }
    // Now that we're authenticated, time to save the data.
    // This sanitizes the data from the field and saves it into an array $events_meta.
    $events_meta['answer1'] = esc_textarea( $_POST['answer1'] );
    $events_meta['answer2'] = esc_textarea( $_POST['answer2'] );
    $events_meta['answer3'] = esc_textarea( $_POST['answer3'] );
    $events_meta['answer4'] = esc_textarea( $_POST['answer4'] );
    $events_meta['answer5'] = esc_textarea( $_POST['answer5'] );

    //print_r( $events_meta);


    // Cycle through the $events_meta array.
    // Note, in this example we just have one item, but this is helpful if you have multiple.
    foreach ( $events_meta as $key => $value ) :

        // Don't store custom data twice
        if ( 'revision' === $post->post_type ) {
            return;
        }

        if ( get_post_meta( $post_id, $key, false ) ) {
            // If the custom field already has a value, update it.
            update_post_meta( $post_id, $key, $value );
        } else {
            // If the custom field doesn't have a value, add it.
            add_post_meta( $post_id, $key, $value);
        }

        if ( ! $value ) {
            // Delete the meta key if there's no value
            delete_post_meta( $post_id, $key );
        }

    endforeach;

}



/**
 * Removes coupon form, order notes, and several billing fields if the checkout doesn't require payment.
 *
 * REQUIRES PHP 5.3+
 *
 * Tutorial: http://skyver.ge/c
 */
function hhi_free_checkout_fields() {

    // first, bail if WC isn't active since we're hooked into a general WP hook
    if ( ! function_exists( 'WC' ) ) {
        return; 
    }

    // bail if the cart needs payment, we don't want to do anything
    if ( WC()->cart && WC()->cart->needs_payment() ) {
        return;
    }

    // now continue only if we're at checkout
    // is_checkout() was broken as of WC 3.2 in ajax context, double-check for is_ajax
    // I would check WOOCOMMERCE_CHECKOUT but testing shows it's not set reliably
    if ( function_exists( 'is_checkout' ) && ( is_checkout() || is_ajax() ) ) {

        // remove coupon forms since why would you want a coupon for a free cart??
        remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

        // Remove the "Additional Info" order notes
        add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

        // Unset the fields we don't want in a free checkout
        add_filter( 'woocommerce_checkout_fields', function( $fields ) {

            // add or remove billing fields you do not want
            // fields: http://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/#section-2
            $billing_keys = array(
                'billing_company',
                'billing_phone',
                'billing_address_1',
                'billing_address_2',
                'billing_city',
                'billing_postcode',
                'billing_country',
                'billing_state',
            );

            // unset each of those unwanted fields
            foreach( $billing_keys as $key ) {
                unset( $fields['billing'][ $key ] );
            }

            return $fields;
        } );
    }

}


function hhi_redirect_checkout_add_cart() {
   return wc_get_checkout_url();
}

function hhi_empty_cart_notice(){
    global $woocommerce;
    if( is_cart() && WC()->cart->cart_contents_count != 0){
        wp_safe_redirect( wc_get_checkout_url() );
    }
}


/**
 * When an item is added to the cart, check total cart quantity
 */
function hhi_limit_cart_quantity( $valid, $product_id, $quantity ) {

    $max_allowed = 1;
    $current_cart_count = WC()->cart->get_cart_contents_count();

    if( ( $current_cart_count > $max_allowed || $current_cart_count + $quantity > $max_allowed ) && $valid ){
        wc_add_notice( sprint( __( 'Whoa hold up. You can only have %d items in your cart', 'your-plugin-textdomain' ), $max ), 'error' );
        $valid = false;
    }

    return $valid;

}


function hhi_redirect_visitor(){
    if ( is_page( 'cart' ) || is_cart() ) {
         wp_safe_redirect( wc_get_checkout_url() );
        exit(); // Don't forget this one
    }
}

function hhi_gift_done(){
	echo '<script> localStorage.setItem("giftDone","1"); </script>';
}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('HipHopIq')) {
    $obj = new HipHopIq();
}