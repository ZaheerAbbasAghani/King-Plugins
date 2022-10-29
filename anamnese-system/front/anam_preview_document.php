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
$query = "SELECT * FROM $table_name WHERE id='".$id."' ORDER BY created_at ASC";
$documents = $wpdb->get_results($query);

if(!empty($documents)){
$user .= '<div id="anam_accordion">';
$i=1;
foreach ($documents as $document) {
$user .= '<div>
  <div class="foot_deformation">
    <h4> Foot Deformations </h4><hr>
    <label>Senkfuss:<span>'.$document->senkfuss.'</span></label>
    <label>Spreizfuss:<span>'.$document->spreizfuss.'</span></label>
    <label>Knickfuss nach innen:<span>'.$document->knickfuss_nach_innen.'</span></label>
    <label>Knickfuss nach aussen:<span>'.$document->Knickfuss_nach_aussen.'</span></label>
    <label>Hohlfuss:<span>'.$document->hohlfuss.'</span></label>
    <label>Plattfuss:<span>'.$document->plattfuss.'</span></label>
    <label>Fusschwellung:<span>'.$document->fusschwellung.'</span></label>
    <label>Other foot deformation:<span>'.$document->other_foot_deformation.'</span></label>
  </div><!-- deformation -->


  <div class="foot_deformation">
    <h4> Krampfadern </h4><hr>

    <label> Oberschenkel: <span>'.$document->oberschenkel.'</span></label>


    <label> Unterschenkel: <span>'.$document->unterschenkel.'</span></label>

    <h4> Einlagen </h4><hr>

    <label> Konfektion: <span>'.$document->konfektion.'</span></label>

    <label> Nach Mass: <span>'.$document->nach_mass.'</span></label>

    <h4> Risks </h4><hr>
    <div style="margin-bottom: 15px;"><label>';
    $risks = unserialize($document->risks);

//    print_r($risks);

    if(!empty($risks)){
      foreach ($risks as $risk) {
        $user.= "<span>".$risk.'</span>';
      }
    }
    $user .='</label></div>
    <label> Infektionskrankheiten: <span>'.$document->infektionskrankheiten.'</span></label>

  </div><!-- Krampfadern -->

    <div class="foot_deformation">
    <h4> Findings </h4><hr>

    <label>';
    $findings = unserialize($document->findings);
    if(!empty($findings)){
      foreach ($findings as $finding) {
        $user.= '<span>'.$finding.'</span>';
      }
    }
    $user.='</label>

    <label> Wunden: <span>'.$document->wunden.'</span></label>
    <label> Huhneraugen auf Zehen: <span>'.$document->huhneraugen_auf_zehen.'</span></label>

    <label> Hammerzehen: <span>'.$document->hammerzehen.'</span></label>

    <label> Nagelpilz: <span>'.$document->nagelpilz.'</span></label>

    <label> Eingewachsene Nagel: <span>'.$document->eingewachsene_nagel.'</span></label>

    <label> Zustand de Nagel: <span>'.$document->zustand_de_nagel.'</span></label>';

$wp_upload_dir =  wp_upload_dir();
$custom_upload_folder= $wp_upload_dir['basedir'].'/anam_users/'.$_POST['user_id'].'/'.$id;

$images = glob($custom_upload_folder."/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
 $user.= "<div class='doc_images parent-container'>";
 $i =1;
foreach($images as $image)
{
     $user.= "<a href='".$wp_upload_dir['baseurl'].'/anam_users/'.$_POST['user_id'].'/'.$id.'/'.basename($image)."'><img src='".$wp_upload_dir['baseurl'].'/anam_users/'.$_POST['user_id'].'/'.$id.'/'.basename($image)."' alt='".basename($image)."' title='".basename($image)."'/></a>";
     $i++;
}
 $user.= "</div>";

 $user.= '</div><!-- deformation -->


</div>';
$i++;
}

} else{
  $user .= "<p style='text-align:center;'> No document found! </p>";
}
$user .= '</div>';

echo $user;


wp_die();

}