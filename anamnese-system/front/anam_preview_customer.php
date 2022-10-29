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

$info .= "
	<div class='rightSideInner1'>
		<div class='customer_logo'>
			<i class='fa fa-user'></i>
		</div>
		<div class='customer_name'>
			<h3>".$results[0]->first_name.' '.$results[0]->last_name."</h3>
		</div>
		<div class='customer_edit_btn'>
			<a href='#' class='button edit_customer' data-id='".$results[0]->id."'>Edit Customer</a>
		</div>
	</div>
	<div class='rightSideInner2'>
		<div class='doctitle'>
			<h4>Documentations</h4>
		</div>";

		$u = wp_get_current_user();
      	if(in_array('doctor',(array) $u->roles)) {

      		$info .= "<div class='docbtncreate'>
      			<a href='#' class='button create_doc' data-id='".$results[0]->id."'>Create new document </a>
      		</div>";
      	}
	 	$info .= '<div class="delete_customer"><a href="#" class="button" data-id="'.$results[0]->id.'"> Delete Customer </a></div>';
		$info .= '</div> <!-- 2-->';

  global $wpdb;
  $table_name2 = $wpdb->base_prefix.'anam_document_info';
  $id2 = get_current_user_id();
  $query2 = "SELECT * FROM $table_name2 WHERE user_id='".$results[0]->id."' ORDER BY created_at ASC";
  $documents = $wpdb->get_results($query2);

	$info.= '<div id="anam_accordion">';
    $i=1;

if(!empty($documents)){
		$info .= '<div id="anam_accordion">';
		foreach ($documents as $document) {
		    $info .= '<h3><span style="float:left !important;"><i class="fa fa-book"></i><a href="#" data-id="'.$document->id.'" user-id="'.$results[0]->id.'" class="doc_title"> Namnesis of the ['.date("d.m.Y H:i",strtotime($document->created_at)).'] </a></span> <a href="#" class="edit_document" data-id="'.$document->id.'" ><i class="fa fa-edit"></i> Edit </a></h3>';
		}

	} else{
	  	$info .= "<p style='text-align:center;'>No document found!</p>";
	}


  $info.= '</div>';


	$info.= "</div>";

echo $info;

	wp_die();
}