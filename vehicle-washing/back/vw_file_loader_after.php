<?php 

add_action( 'wp_ajax_vw_file_loader_after', 'vw_file_loader_after' );
add_action( 'wp_ajax_nopriv_vw_file_loader_after', 'vw_file_loader_after' );

function vw_file_loader_after() {
	global $wpdb; // this is how you get access to the database


	$id = $_POST['post_id'];

	$upload_dir = wp_upload_dir();
	$directory = $upload_dir['basedir'].'/vehicle-before-after/'.$id.'/after';
	$images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);
	$fileList = [];

	foreach($images as $image)
	{
		$imgName = basename($image);
	//	echo $imgName."\n";
		$img = get_site_url().'/wp-content/uploads/vehicle-before-after/'.$id.'/after/'.$imgName;

		if(!empty($image)){
			$size = filesize($image);
			$fileList[] = ['name'=>basename($image), 'size'=>$size, 'path'=>$img];
		}
	}

	echo json_encode($fileList);

	wp_die(); // this is required to terminate immediately and return a proper response
}