<?php
add_action( 'wp_ajax_jac_subscribe_now', 'jac_subscribe_now' );
add_action( 'wp_ajax_nopriv_jac_subscribe_now', 'jac_subscribe_now' );

function jac_subscribe_now() {
    global $wpdb;
    $table_name = $wpdb->base_prefix.'jac_subscribe';
    $email 		= $_POST['email'];
    $post_id 	= $_POST['post_id'];

	$results=$wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE user_email= '$email' AND post_id='$post_id'"));
	
	//echo $post_id;
	if($results == null) {

		$data_array = array("user_email" => "$email", "post_id" => "$post_id");
		$rowResult=$wpdb->insert($table_name, $data_array,array("%s","%s"));
		
		echo "Inscription enregistrée !";

		$admin_email = get_option("admin_email");
		$headers=array('Content-Type: text/html; charset=UTF-8');
		$subject = "Subscription alert!";
		if($post_id == "All"){
			$message = "A user with $email subscribe on your site.  <br> \n Post title: <b> All </b>.";	
		}else{
			$message = "A user with $email subscribe on your site. <br> \n  Post title: <b>".get_the_title($post_id)."</b>.";	
		}
		
    	wp_mail($admin_email, $subject, $message, $headers);


	}else{
		echo "Vous êtes déjà enregistré(e)";
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}