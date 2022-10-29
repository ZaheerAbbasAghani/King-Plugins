<?php 

add_action( 'wp_ajax_vw_delete_file', 'vw_delete_file' );
add_action( 'wp_ajax_nopriv_vw_delete_file', 'vw_delete_file' );

function vw_delete_file() {
	global $wpdb; // this is how you get access to the database

	$image_name = $_POST['image_name'];
	$folder = $_POST['status'];
	$post_id = $_POST['post_id'];

	if($folder == "before"){
		$upload_dir = wp_upload_dir();
		$directory=$upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/'.$folder.'/'.$image_name;
		unlink($directory);
	}else{
		$upload_dir = wp_upload_dir();
		$directory=$upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/'.$folder.'/'.$image_name;
		unlink($directory);
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}