<?php 

add_action( 'wp_ajax_dix_update_category', 'dix_update_category' );
add_action( 'wp_ajax_nopriv_dix_update_category', 'dix_update_category' );
function dix_update_category() {


	//print_r($_POST);


	global $wpdb; 
	$table_name = $wpdb->base_prefix.'dix_imported_data';
	$id = $_POST['id'];
	$val = $_POST['val'];
	
	$update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET item_type='$val' WHERE id='$id' "));

	if($update == 1){
		echo "Updated Successfully";
	}
	else
	{
		echo "Something went wrong!";
	}



	wp_die();
}