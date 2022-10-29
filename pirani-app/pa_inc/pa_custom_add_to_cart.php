<?php
//session_start();
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
//add_action( 'woocommerce_before_calculate_totals', 'woocommerce_ajax_add_to_cart' );
function woocommerce_ajax_add_to_cart() {

    global $woocommerce;

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    $emb_image = $_POST['emb_image'];
    $emb_price = $_POST['emb_price'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $placement = $_POST['placement'];
    $color = $_POST['color'];
    $font = $_POST['font'];


    $cart_item_data = array('custom_price' => $emb_price);
    
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id,"",$cart_item_data) && 'publish' === $product_status) {

    $woocommerce->cart->calculate_totals();
    // Save cart to session
    $woocommerce->cart->set_session();
    // Maybe set cart cookies
    $woocommerce->cart->maybe_set_cart_cookies();
    //echo $custom_price;
    
    session_start();
    if(!empty($emb_image)){
        $_SESSION['pa_product_custom_data'] = array("emb_image"=>$emb_image, "firstline" =>$firstname, "listline" => $lastname, "placement" =>$placement, "color" => $color,"font" => $font);
    }
    

    /*foreach ( $cart_object->cart_contents as $value ) {
        if ( $value['product_id'] == $product_id ) {
            $value['data']->price = $emb_price;
        }
    }*/

    
    


    do_action('woocommerce_ajax_added_to_cart', $product_id);

    if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
        wc_add_to_cart_message(array($product_id => $quantity), true);
    }

    $data = array(
        'error' => true,
        'site_url' => site_url().'/cart');
        echo wp_send_json($data);
        WC_AJAX :: get_refreshed_fragments();



} else {

    $data = array(
        'error' => true,
        'site_url' => apply_filters('woocommerce_cart_redirect_after_error', site_url().'/cart', $product_id));
    echo wp_send_json($data);
}

    wp_die();
}