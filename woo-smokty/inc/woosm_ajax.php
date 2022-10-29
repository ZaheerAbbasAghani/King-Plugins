<?php 

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {
	global $woocommerce;
            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);
            $custom_width = $_POST['custom_width'];
            $custom_height = $_POST['custom_height'];
            $price = $_POST['price'];
      		//row 1
/*            if($custom_height >=48 && $custom_height <=59){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 80;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 89;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 111;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 135;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 161;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 182;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 198;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 245;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 282;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 324;
                }
                if($custom_width == 144){
                	$custom_price = 373;
                }

            }

            //row 2
            if($custom_height >=60 && $custom_height <=71){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 87;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 97;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 131;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 156;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 178;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 200;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 228;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 289;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 318;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 366;
                }
                if($custom_width == 144){
                	$custom_price = 420;
                }

            }

            //row 3
            if($custom_height >=72 && $custom_height <=83){
            	//$custom_price = SUCCESS";
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 96;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 107;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 142;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 178;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 200;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 231;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 259;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 325;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 358;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 411;
                }
                if($custom_width == 144){
                	$custom_price = 473;
                }

            }

            //row 4
            if($custom_height >=84 && $custom_height <=95){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 110;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 122;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 156;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 195;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 226;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 260;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 298;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 363;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 399;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 459;
                }
                if($custom_width == 144){
                	$custom_price = 528;
                }

            }

            //row 5
            if($custom_height >=96 && $custom_height <=107){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 125;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 139;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 175;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 218;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 254;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 290;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 328;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 398;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 438;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 503;
                }
                if($custom_width == 144){
                	$custom_price = 579;
                }

            }

            //row 6
            if($custom_height >=108 && $custom_height <=119){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 136;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 151;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 195;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 235;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 282;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 323;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 363;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 424;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 466;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 536;
                }
                if($custom_width == 144){
                	$custom_price = 617;
                }

            }

            //row 7
            if($custom_height >=120 && $custom_height <=131){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 151;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 168;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 216;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 261;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 313;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 359;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 403;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 471;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 518;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 595;
                }
                if($custom_width == 144){
                	$custom_price = 685;
                }

            }
            //row 8
            if($custom_height >=132 && $custom_height <=143){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 186;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 207;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 267;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 321;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 386;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 442;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 496;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 580;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price =638;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 734;
                }
                if($custom_width == 144){
                	$custom_price = 844;
                }

            }
            //row 9
            if($custom_height ==144){
                if($custom_width >=24 && $custom_width <=35){
                    $custom_price = 186;
                }
                if($custom_width >=36 && $custom_width <=47){
                    $custom_price = 207;
                }
                if($custom_width >=48 && $custom_width <=59){
                    $custom_price = 267;
                }
                if($custom_width >=60 && $custom_width<=71){
                	$custom_price = 321;
                }
                if($custom_width >=72 && $custom_width<=83){
                	$custom_price = 386;
                }
                if($custom_width >=84 && $custom_width<=95){
                	$custom_price = 442;
                }
                if($custom_width >=96 && $custom_width<=107){
                	$custom_price = 496;
                }
                if($custom_width >=108 && $custom_width<=119){
                	$custom_price = 580;
                }
                if($custom_width >=120 && $custom_width<=131){
                	$custom_price = 638;
                }
                if($custom_width >=132 && $custom_width<=143){
                	$custom_price = 734;
                }
                if($custom_width == 144){
                	$custom_price = 844;
                }

            }
*/


$cart_item_data = array('custom_price' => $price); 
if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id,"",$cart_item_data) && 'publish' === $product_status) {

$woocommerce->cart->calculate_totals();
// Save cart to session
$woocommerce->cart->set_session();
// Maybe set cart cookies
$woocommerce->cart->maybe_set_cart_cookies();
//echo $custom_price;


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