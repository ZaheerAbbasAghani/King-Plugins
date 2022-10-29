<?php

add_action('wp_ajax_lfs_create_advertisement_process','lfs_create_advertisement_process' );
add_action('wp_ajax_nopriv_lfs_create_advertisement_process','lfs_create_advertisement_process' );
function lfs_create_advertisement_process() {
	global $wpdb; // this is how you get access to the database

	$lfs_user_name = sanitize_text_field($_POST['lfs_user_name']);
	$lfs_user_email = sanitize_text_field($_POST['lfs_user_email']);
	$lfs_phone_number = sanitize_text_field($_POST['lfs_phone_number']);
	$lfs_lost_or_found_date = sanitize_text_field($_POST['lfs_lost_or_found_date']);
	$lfs_lost_or_found_place = sanitize_text_field($_POST['lfs_lost_or_found_place']);
	$lfs_state = sanitize_text_field($_POST['lfs_state']);
	$lfs_animal_type = sanitize_text_field($_POST['lfs_animal_type']);
	$lfs_animal_breed = sanitize_text_field($_POST['lfs_animal_breed']);
	$lfs_special_mark = sanitize_text_field($_POST['lfs_special_mark']);
	$lfs_birth_Day = sanitize_text_field($_POST['lfs_birth_Day']);
	$lfs_micro_chip = sanitize_text_field($_POST['lfs_micro_chip']);
	$lfs_description = sanitize_text_field($_POST['lfs_description']);
	

	//$item_to_win = sanitize_text_field($_FILES['item_to_win']);
	
	$post_id = wp_insert_post(array(
		'post_type' => 'advertisement',
		'post_title' => $lfs_user_name,
		'post_content' => $lfs_description,
		'post_status' => 'publish',
	));
	
	add_post_meta($post_id, 'lfs_user_email', $lfs_user_email);
	add_post_meta($post_id, 'lfs_phone_number', $lfs_phone_number);
	add_post_meta($post_id, 'lfs_lost_or_found_date', $lfs_lost_or_found_date);
	add_post_meta($post_id, 'lfs_lost_or_found_place', $lfs_lost_or_found_place);
	add_post_meta($post_id, 'lfs_state', $lfs_state);
	add_post_meta($post_id, 'lfs_animal_type', $lfs_animal_type);
	add_post_meta($post_id, 'lfs_animal_breed', $lfs_animal_breed);
	add_post_meta($post_id, 'lfs_special_mark', $lfs_special_mark);
	add_post_meta($post_id, 'lfs_birth_Day', $lfs_birth_Day);
	add_post_meta($post_id, 'lfs_micro_chip', $lfs_micro_chip);
	
	

	//require the needed files

	require_once ABSPATH . "wp-admin" . '/includes/image.php';
	require_once ABSPATH . "wp-admin" . '/includes/file.php';
	require_once ABSPATH . "wp-admin" . '/includes/media.php';

	//then loop over the files that were sent and store them using  media_handle_upload();

	$meta_keys = array('second_featured_image','third_featured_image','fourth_featured_image','fifth_featured_image');
	$i=0;
	if ($_FILES) {
		foreach ($_FILES as $file => $array) {
			//echo $i;
			if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
				echo "upload error : " . $_FILES[$file]['error'];
				die();
			}

			$attach_id = media_handle_upload($file, $post_id);
			
			if($i==0){
				update_post_meta($post_id, '_thumbnail_id', $attach_id);	
			}else{
				update_post_meta($post_id, "$meta_keys[$i]", $attach_id);	
			}
			$i++;
		}
	}

	
	_e('Advertisement Created Successfully');

	wp_die(); // this is required to terminate immediately and return a proper response
}