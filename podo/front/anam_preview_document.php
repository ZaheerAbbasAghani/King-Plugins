<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_anam_preview_document', 'anam_preview_document' );
add_action( 'wp_ajax_anam_preview_document', 'anam_preview_document' );
function anam_preview_document() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_document_info';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ORDER BY created_at DESC";
$documents = $wpdb->get_results($query, ARRAY_A);
$user = "";


if(!empty($documents)){
$user .= '<div id="anam_accordion" class="anamnese_box">';
$i=1;
$user .= '<ul class="patienten_anamnese">';
foreach ($documents[0] as $key => $value) {

  if($key != "id" && $key != "user_id" && $key != "doctor_id" && $key != "created_at" && $key != "updated_at"){
    if($value == "on"){
      $string = str_replace('_', ' ', ucwords($key));
      $user .= '<li data-sort="'.$value.'"> '.$string.': Ja </li>';
    }else{
       $string = str_replace('_', ' ', ucwords($key));
       $user .= '<li data-sort="'.$value.'"> '.$string.':'.$value.'</li>';
    }
  }

$i++;
}

$user .= ' </ul><!-- deformation -->';

} else{
  $user .= "<p style='text-align:center;'> No document found! </p>";
}
$user .= '</div>';

echo $user;


wp_die();

}