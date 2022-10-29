<?php 

function dq_create_salon_on_site(){

	//print_r($_POST);


$salon_name = sanitize_text_field($_POST['salon_name']);
$salon_address = sanitize_textarea_field($_POST['salon_address']);
$user_id =  sanitize_textarea_field($_POST['user_id']);
$salon_description = sanitize_text_field($_POST['salon_description']);

$post_id = wp_insert_post(array(
	'post_type' => 'salons',
	'post_title' => $salon_name,
	'post_content' => $salon_description,
	'post_status' => "publish",
));

add_post_meta($post_id, 'salon_address', $salon_address);
add_post_meta($post_id, 'salon_master', $user_id);
//require the needed files

require_once ABSPATH . "wp-admin" . '/includes/image.php';
require_once ABSPATH . "wp-admin" . '/includes/file.php';
require_once ABSPATH . "wp-admin" . '/includes/media.php';



//then loop over the files that were sent and store them using  media_handle_upload();

if ($_FILES) {
	foreach ($_FILES as $file => $array) {
		if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
			echo "upload error : " . $_FILES[$file]['error'];
		die();
	}

		$attach_id = media_handle_upload($file, $post_id);
	}
}

//and if you want to set that image as Post  then use:
update_post_meta($post_id, '_thumbnail_id', $attach_id);
_e('Salon Created Successfully', 'daniel-queue'); 







wp_die();
}

add_action( "wp_ajax_dq_create_salon_on_site", "dq_create_salon_on_site");
add_action( "wp_ajax_nopriv_dq_create_salon_on_site", "dq_create_salon_on_site");
