<?php 

add_action( 'wp_ajax_dix_update_product_name', 'dix_update_product_name' );
add_action( 'wp_ajax_nopriv_dix_update_product_name', 'dix_update_product_name' );
function dix_update_product_name() {

	global $wpdb; 
	$table_name = $wpdb->base_prefix.'dix_imported_data';
	$id = $_POST['id'];
	$val = $_POST['val'];
	$label = $_POST['label'];


	$update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET $label='$val' WHERE id='$id' "));

	if($update == 1){
		echo "Updated Successfully";
	}
	else
	{
		echo "Something went wrong!";
	}



	wp_die();
}