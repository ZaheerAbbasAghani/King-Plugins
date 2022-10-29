<?php
function wru_approve_user_product() {
//print_r($_POST);
$post_id = $_POST['post_id'];
$access_date = strtotime($_POST['access_date']);
$string = $_POST['user'];
$email = explode(')', (explode('(', $string)[1]))[0];
$user = get_user_by( 'email', $email );
$userid =  $user->ID;
$select_variaton = $_POST['select_variaton'];

$selected_variation_user_id = array();
foreach ($select_variaton as $val) {
	//echo $val;
    $new_variation = $val.'-'.$userid;
    array_push($selected_variation_user_id, $new_variation);
}

//print_r($selected_variation_user_id);

$List = implode(', ', $selected_variation_user_id); 
//print_r($List);
$already_approve = get_post_meta($post_id,"wru_approve_user");

//Array with all data

$array_all = array();
array_push($array_all, array("product_id"=>$post_id,"access_date"=>$access_date, "user_id"=>$userid, "variation" => $select_variaton));

//print_r($array_all);


if ( ! in_array(  $userid, $already_approve ) ) {
	$user_info = get_userdata($userid);
	$full_name = $user_info->first_name.' '.$user_info->last_name;
   
	
//   	add_post_meta( $post_id,"wru_is_approved_product",$post_id);
	add_post_meta( $post_id,"wru_approve_user",$userid);
	add_post_meta( $post_id,"wru_till_dt",$access_date);
	add_post_meta( $post_id,"wru_approve_variation",$List);
	add_post_meta( $post_id,"wru_all_approved_data",$array_all);

	$subject = "A Product had created on site pharmacy.pa9e.com";

	$product = wc_get_product( $post_id );
  	$product_name = $product->get_name();
  	$product_url = get_permalink($post_id);
  	$headers = array('Content-Type: text/html; charset=UTF-8');

  	$author_obj = get_user_by('id', $userid);
//  	$password = 'HelloWorld';

	$password = '';

	//Initialize a random desired length
	$desired_length = rand(8, 12);

	for($length = 0; $length < $desired_length; $length++) {
	//Append a random ASCII character (including symbols)
	$password .= chr(rand(32, 126));
	}

  	wp_set_password( $password, $userid);

	$message = "
	<p>You must login to purchase below product first login and then click on product link to purchase product.</p>
	<b>Login Username:".$author_obj->user_login."</b>
	<b>Login Password:".$password."</b>
	<p> Product <a href=".$product_url."> ".$product_name." </a> had created for you. You can click button add to cart to purchase your product. You can purchase product until date: ".date("d m, Y",$access_date)."</p>";
	wp_mail( $email, $subject, $message,$headers);


	echo $full_name;
}else{
	echo "User Already Approved for Product.";
}

die();
}

add_action( 'wp_ajax_wru_approve_user_product', 'wru_approve_user_product' );
add_action( 'wp_ajax_nopriv_wru_approve_user_product', 'wru_approve_user_product' );