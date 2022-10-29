<?php 

add_action( 'wp_ajax_dix_edit_product_name', 'dix_edit_product_name' );
add_action( 'wp_ajax_nopriv_dix_edit_product_name', 'dix_edit_product_name' );
function dix_edit_product_name() {

	global $wpdb; 
	$table_name = $wpdb->base_prefix.'dix_imported_data';
	$id = $_POST['id'];
	$query = "SELECT * FROM $table_name WHERE id='$id'";
	$query_results = $wpdb->get_results($query);


	echo "<div class='popUpBox'> 

		<form method='post' action='' name='dix_update_name' id='dix_update_name'>
			<label style='display: block;text-align: left;'> Enter New Name <input type='text' name='editName' id='editName' value='".$query_results[0]->item_name."' style='display: block;margin: 10px auto;width: 100%;'>
			 <input type='hidden' name='product_id' id='product_id' value='".$id."'>
			</lable>
			<input type='submit' class='button button-primary' value='Update Now'>
		</form>

	</div>";

	wp_die();
}