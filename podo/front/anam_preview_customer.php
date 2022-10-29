<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_anam_preview_customer', 'anam_preview_customer' );
add_action( 'wp_ajax_anam_preview_customer', 'anam_preview_customer' );
function anam_preview_customer() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);
$info = "";
$info .= "
	<div class='rightSideInner1'>
		<div class='customer_logo'>
			<i class='fa fa-user'></i>
		</div>
		<div class='customer_name'>
			<h3>".$results[0]->first_name.' '.$results[0]->last_name."</h3>
		</div>
		<div class='customer_edit_btn'>
			<a href='#' class='button edit_customer' data-id='".$results[0]->id."'>Patienten bearbeiten</a>
		</div>
	</div>
	<div class='rightSideInner2'>
		<div class='doctitle'>
			<h4>Anamnese </h4>
		</div>";

	
  global $wpdb;
  $table_name2 = $wpdb->base_prefix.'anam_document_info';
  $id2 = get_current_user_id();
  $query2 = "SELECT * FROM $table_name2 WHERE user_id='".$results[0]->id."' ORDER BY created_at DESC";
  $documents = $wpdb->get_results($query2);

if(empty($documents)){

	$u = wp_get_current_user();
	if(in_array('mitarbeiter',(array) $u->roles)) {

		$info .= "<div class='docbtncreate'>
			<a href='#' class='button create_doc' data-id='".$results[0]->id."'>Neue Anamnese erstellen </a>
		</div>";
	}
}else{
		$info .= "<div class='docbtncreate'>
			<a href='#' class='button create_dokument' data-id='".$results[0]->id."'>Neue Dokumentation </a>
		</div>";

}


$info .= '</div> <!-- 2-->';

	//$info.= '<div id="anam_accordion">';
    $i=1;

	if(!empty($documents)){
		$info .= '<div id="anam_accordion" class="anam_bx doclist" >';
		foreach ($documents as $document) {
		    $info .= '<h3 class="doc"><span style="float:left !important;"><i class="fa fa-book"></i><a href="#" data-id="'.$document->id.'" user-id="'.$results[0]->id.'" class="doc_title"> Erstellt am '.date("d.m.Y H:i",strtotime($document->created_at));
		    if(strtotime($document->created_at) != strtotime($document->updated_at) ){
		    	$info .=' - '.date("d.m.Y H:i",strtotime($document->updated_at));
		    }
		    $info .= '</a></span> <a href="#" class="edit_document" data-id="'.$document->id.'" ><i class="fa fa-edit"></i> Editieren </a></h3>';
		}
		$info .= '</div>';

	} else{
	  	$info .= "<p style='text-align:center;'>Keine frühere Anamnese gefunden!</p>";
	}


  $table_name3 = $wpdb->base_prefix.'anam_dokument_info';
  $id3 = get_current_user_id();
  $query3 = "SELECT * FROM $table_name3 WHERE customer_id='".$id."'  ORDER BY created_at DESC";
  $doks = $wpdb->get_results($query3);
 
  
if(!empty($doks)){
$info .= '<div id="anam_accordion" class="doks"><h4>Dokumentationen </h4><table border="1">';
foreach ($doks as $dok) {
	$info .= '<tr data-id="'.$dok->id.'">';
		if(strtotime($dok->created_at) != strtotime($dok->updated_at)){
			$info .= '<td><a href="#" data-id="'.$dok->id.'"  user-id="'.$dok->customer_id.'" class="dok_title"> Updated at '.date("d.m.Y H:i",strtotime($dok->updated_at)).' </a></span></td>';
		}else{

			$info .= '<td><span><a href="#" data-id="'.$dok->id.'" user-id="'.$dok->customer_id.'"  class="dok_title"> Created '.date("d.m.Y",strtotime($dok->created_at)).' </a></span></td>';
		}
		$info .='<td><span><a href="#" data-id="'.$dok->id.'" user-id="'.$dok->customer_id.'"  class="dok_title">'.$dok->treatment_name.'</a></span></td>';
		//$info .= '<td><span>'.$dok->treatment_name.'</span></td>';

		if($dok->status == "paid"){
			$info .= '<td><span> Status: <span class="dashicons dashicons-yes" style="color:#4ab516;"></span> </span></td>';
		}else{
			$info .= '<td><span> Status: <span class="dashicons dashicons-clock" style="color:#f7c631;font-size: 20px;margin-left: 7px;line-height: 26px;"></span> </span></td>';
		}

		$info .='<td><a href="#" class="edit_dokument" data-id="'.$dok->id.'" ><i class="fa fa-edit"></i> Editieren </a></td>';

		$enroll_date = date("d-m-Y",strtotime($dok->created_at));
		$date1 = new DateTime($enroll_date);
		$date2 = new DateTime(date("d-m-Y"));
		$interval = $date1->diff($date2);
		if($interval->y == 10):
			$info .= '<td><a href="#" class="delete_dokument" data-id="'.$dok->id.'" ><i class="fa fa-trash"></i> Löschen </a></td>';
		endif;


		$info .= '</tr>';
}
$info.= '</div></table>';

} else{
$info .= "<p style='text-align:center;margin-top:50px;'>Keine Dokumentationen gefunden!	</p>";
}
$info .= "</div>";



echo $info;

	wp_die();
}