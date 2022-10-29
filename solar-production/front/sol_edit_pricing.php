<?php
add_action( 'wp_ajax_nopriv_sol_edit_pricing', 'sol_edit_pricing' );
add_action( 'wp_ajax_sol_edit_pricing', 'sol_edit_pricing' );
function sol_edit_pricing() {
	global $wpdb; 
	$table_name = $wpdb->base_prefix.'sol_pricing';

	$kwp_from  = $_POST['kwp_from'];
	$kwp_m2 = $_POST['kwp_m2'];
	$pricing  = $_POST['pricing'];
	$id  = $_POST['id'];

//	print_r($_POST);

	$sol_code = $wpdb->query($wpdb->prepare("UPDATE $table_name SET kwp_from='$kwp_from',kwp_m2='$kwp_m2',pricing='$pricing' WHERE id=$id"));

	echo "Update Successfully.";

	wp_die(); // this is required to terminate immediately and return a proper response
}