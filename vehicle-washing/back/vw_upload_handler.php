<?php 

add_action( 'wp_ajax_vw_upload_handler', 'vw_upload_handler' );
add_action( 'wp_ajax_nopriv_vw_upload_handler', 'vw_upload_handler' );

function vw_upload_handler() {
	global $wpdb; // this is how you get access to the database


	if(isset($_FILES)){

		$file = $_FILES['file'];
		$name = $file['name'];

		$upload_dir = wp_upload_dir();

		$path = $upload_dir['basedir'].'/vehicle-before-after/'. basename($name);
		if (move_uploaded_file($file['tmp_name'], $path)) {
		    echo "Move succeed";
		} else {
		 echo " Move failed. Possible duplicate?";
		}
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}