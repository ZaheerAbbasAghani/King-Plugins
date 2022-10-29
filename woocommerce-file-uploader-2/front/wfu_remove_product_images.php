<?php 
/*if (!session_id()) {
    session_start();
}*/
add_action('wp_ajax_wfu_remove_product_images', 'wfu_remove_product_images');
add_action('wp_ajax_nopriv_wfu_remove_product_images', 'wfu_remove_product_images');

function wfu_remove_product_images() {

	$wp_upload_dir =  wp_upload_dir();
    $a = $wp_upload_dir['path']."/".$_POST['product_id'];
    if(is_dir($a)){
        array_map('unlink', glob("$a/*.*"));
        rmdir($a);
        echo "Directory Deleted";
    }

  //  print_r($_POST);

    wp_die();
}