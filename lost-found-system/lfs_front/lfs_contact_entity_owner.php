<?php

add_action('wp_ajax_lfs_contact_entity_owner','lfs_contact_entity_owner' );
add_action('wp_ajax_nopriv_lfs_contact_entity_owner','lfs_contact_entity_owner' );
function lfs_contact_entity_owner() {


$myname = $_POST['myname'];
$mymessage = $_POST['mymessage'];
$myemail = $_POST['myemail'];
$myphone = $_POST['myphone'];
$owner_email = $_POST['owner_email'];
$headers = array('Content-Type: text/html; charset=UTF-8');

$message = "<p> Name:  $myname </p> <p> Message: $mymessage </p> <p> Phone: $myphone </p><p> Email: $myemail </p>";

wp_mail($owner_email, "Contact regarding missing pet.", $message, $headers );

echo "Message Sent. Thanks";

wp_die();
}