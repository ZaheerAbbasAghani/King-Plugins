<?php 

add_action( 'wp_ajax_vw_file_loader_before', 'vw_file_loader_before' );
add_action( 'wp_ajax_nopriv_vw_file_loader_before', 'vw_file_loader_before' );

function vw_file_loader_before() {
	global $wpdb; // this is how you get access to the database


	$id = $_POST['post_id'];

	$upload_dir = wp_upload_dir();
	$directory = $upload_dir['basedir'].'/vehicle-before-after/'.$id.'/before';
	$images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);
	$fileList = [];

	foreach($images as $image)
	{
		$imgName = basename($image);
		$img = get_site_url().'/wp-content/uploads/vehicle-before-after/'.$id.'/before/'.$imgName;

		if(!empty($image)){
			$size = filesize($image);
			$fileList[] = ['name'=>basename($image), 'size'=>$size, 'path'=>$img];
		}
	}

	echo json_encode($fileList);

	wp_die(); // this is required to terminate immediately and return a proper response
}