<?php 

add_action('wp_ajax_wfu_show_images_list', 'wfu_show_images_list');
add_action('wp_ajax_nopriv_wfu_show_images_list', 'wfu_show_images_list');

function wfu_show_images_list() {

    $wp_upload_dir =  wp_upload_dir();

    $names = $_POST['names'];
    $order_id = $_POST['order_id'];

    $i=1;
    foreach ($names as $name) {
        $product = get_page_by_title($name,OBJECT,'product');
        
        print_r($product);

      /*  $directory = $wp_upload_dir['path']."/".$order_id."/".$product->ID;
        $url = $wp_upload_dir['url']."/".$order_id."/".$product->ID;
        echo $url;*/

    }

    wp_die();
}