<?php 

add_action( 'wp_ajax_vw_move_images_to_folder', 'vw_move_images_to_folder' );
add_action( 'wp_ajax_nopriv_vw_move_images_to_folder', 'vw_move_images_to_folder' );

function vw_move_images_to_folder() {
	global $wpdb; // this is how you get access to the database

	//print_r($_FILES);

$post_id = $_POST['post_id'];
$status = $_POST['status'];
$upload_dir = wp_upload_dir();

if($status == "before"){

	if ( ! is_dir( $upload_dir['basedir'].'/vehicle-before-after/'.$post_id ) ) {
		
		wp_mkdir_p($upload_dir['basedir'].'/vehicle-before-after/'.$post_id );
		wp_mkdir_p($upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/before' );

		$directory = $upload_dir['basedir'].'/vehicle-before-after';
		$images = glob($directory . "/*.{jpg,png,jpeg,gif}",GLOB_BRACE);

		foreach($images as $image)
		{
			rename($upload_dir['basedir'].'/vehicle-before-after/'.basename($image), $upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/before/'.basename($image));
		}

	}else{

		if ( ! is_dir( $upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/before' ) ) {
			wp_mkdir_p($upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/before' );
		}

		$directory = $upload_dir['basedir'].'/vehicle-before-after';
		$images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);

		foreach($images as $image)
		{
			rename($upload_dir['basedir'].'/vehicle-before-after/'.basename($image), $upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/before/'.basename($image));
		}
	}

}else{


	if ( ! is_dir( $upload_dir['basedir'].'/vehicle-before-after/'.$post_id ) ) {
		
		wp_mkdir_p($upload_dir['basedir'].'/vehicle-before-after/'.$post_id );
		wp_mkdir_p($upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/after' );

		$directory = $upload_dir['basedir'].'/vehicle-before-after';
		$images = glob($directory . "/*.{jpg,png,jpeg,gif}",GLOB_BRACE);

		foreach($images as $image)
		{
			rename($upload_dir['basedir'].'/vehicle-before-after/'.basename($image), $upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/after/'.basename($image));
		}

	}else{

		if ( ! is_dir( $upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/after' ) ) {
			wp_mkdir_p($upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/after' );
		}

		$directory = $upload_dir['basedir'].'/vehicle-before-after';
		$images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);

		foreach($images as $image)
		{
			rename($upload_dir['basedir'].'/vehicle-before-after/'.basename($image), $upload_dir['basedir'].'/vehicle-before-after/'.$post_id.'/after/'.basename($image));
		}
	}

}


	wp_die(); // this is required to terminate immediately and return a proper response
}