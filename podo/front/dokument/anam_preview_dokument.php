<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

function get_customer_last_name($id){

  global $wpdb;
  $table_name = $wpdb->base_prefix.'anam_customer_info';
  $fetch = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$id'");

  return $fetch[0]->last_name;

}



add_action( 'wp_ajax_nopriv_anam_preview_dokument', 'anam_preview_dokument' );
add_action( 'wp_ajax_anam_preview_dokument', 'anam_preview_dokument' );
function anam_preview_dokument() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_dokument_info';
$id = $_POST['id'];
$user_id = $_POST['user_id'];

$query = "SELECT * FROM $table_name WHERE id='".$id."' AND customer_id='".$user_id."' ORDER BY created_at ASC";
$documents = $wpdb->get_results($query);


$dok .= "<div class='patienten_dok'> 

<label> Treatment Name: <span>".$documents[0]->treatment_name."</span></label>

<label> Price <span>".$documents[0]->price."</span></label>

<label> Additional Information <span>".$documents[0]->addition_information."</span></label>

<label> Payment Method <span>".$documents[0]->payment_methods."</span></label>

<label> Email to receive PDF Dokumentation
 <span>".$documents[0]->email_pdf."</span></label>

<label> Status  <span>".$documents[0]->status."</span></label>


<label> Erstellt am  <span>".date("d.m.Y H:i",strtotime($documents[0]->created_at))."</span></label>";

$inv = wp_upload_dir();

//print_r($inv);
$d = date("d.m.Y",strtotime($documents[0]->created_at));
$url = $inv['baseurl'].'/Invoice-'.$d.'-'.get_customer_last_name($documents[0]->customer_id).'.pdf';

$dok .= "<label> Invoice <span><a href='".$url."' download='dokument-".get_customer_last_name($documents[0]->customer_id)."'> Download File </a></span></label>";


$wp_upload_dir =  wp_upload_dir();
$lastid = $wpdb->insert_id;
$custom_upload_folder= $wp_upload_dir['basedir'].'/anam_users/'.$id;

$images = glob($custom_upload_folder. "/*.{jpg,png,gif}",GLOB_BRACE);
if(!empty($images )){
	$dok .= '<label>Behandlungs-Bilder</label><br><div class="parent-container">';
}
foreach($images as $image)
{
  $image_name = basename($image);
  $img=$wp_upload_dir['baseurl'].'/anam_users/'.$id.'/'.$image_name;

  $dok .= "<a href='".$img."'><img src='".$img."'></a>";

}

$dok .= "</div>";





$user = get_user_by('id', $documents[0]->doctor_id);
if(!empty($user->display_name)){

	$dok .= "<p style='text-align:center;margin-top:50px;'>  Diese Dokumentation wurde erstellt von <span style='font-size:30px; text-align:center;display:block;'>".$user->display_name."</span></p>";
}else{
		$dok .= "<p style='text-align:center;margin-top:50px;'>  Diese Dokumentation wurde erstellt von <span style='font-size:30px; text-align:center;display:block;'>".$user->first_name . ' ' . $user->last_name."</span></p>";
}



$dok .= "</div>";

echo $dok;


wp_die();

}