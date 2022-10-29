<?php 
if (!session_id()) {
    session_start();
}

function my_org_logos_upload_dir( $arr ) {
    $folder = '/'.WC()->session->get( 'directory_name', 0 ); // No trailing slash at the end.

    $arr['path'] .= $folder;
    $arr['url'] .= $folder;
    $arr['subdir'] .= $folder;

    return $arr;
}

add_action('wp_ajax_wfu_file_upload', 'wfu_file_upload');
add_action('wp_ajax_nopriv_wfu_file_upload', 'wfu_file_upload');

function wfu_file_upload() {


 //   print_r($_POST);

	$wp_upload_dir =  wp_upload_dir();
    $dname = WC()->session->set('directory_name', $_POST['product_id']);

    $getDir = WC()->session->get( 'directory_name', 0 );
    $a = $wp_upload_dir['path']."/".$getDir;
    if(is_dir($a)){
        array_map('unlink', glob("$a/*.*"));
        rmdir($a);
        mkdir($a);
    }else{
        mkdir($a);
    }

    check_ajax_referer('file_upload', 'security');
    $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
    for($i = 0; $i < count($_FILES['file']['name']); $i++) {
        if (in_array($_FILES['file']['type'][$i], $arr_img_ext)) {
            	
			add_filter( 'upload_dir', 'my_org_logos_upload_dir' );

			$upload = wp_upload_bits($_FILES['file']['name'][$i], null, file_get_contents($_FILES['file']['tmp_name'][$i]));
			remove_filter( 'upload_dir', 'my_org_logos_upload_dir' );

        }
    }

    wp_die();
}