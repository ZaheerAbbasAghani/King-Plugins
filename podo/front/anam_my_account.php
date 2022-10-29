<?php 
function anam_my_account_page($user){

if(is_user_logged_in()):
	$id = get_current_user_id();
	$u = get_user_by('id', $id);


	$user .= "<div class='anam_wrapper'> 

	<div class='leftSide'>
	<h5>".__("Alle Patienten")." </h5> <hr>
	<div class='frmSearch'>";
		
	//$id = wp_get_current_user();
	if ( in_array( 'mitarbeiter', (array) $u->roles ) ) {
	$user .= "<div class='docbtncreatecustomer'>
	        <a href='#' class='new_customer'><i class='fa fa-user-plus' aria-hidden='true'></i><br> Neuen Patienten hinzufügen </a>

	     
	      </div>";
	}


	$user .= "<form method='post' action=''>
			<input type='search' name='search_customer' id='search_customer' placeholder='Suche'/>
		</form>
		<div id='suggesstion-box'></div>
	</div>
		";
	
	global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_customer_info';
    $id = get_current_user_id();
    $query = "SELECT * FROM $table_name ORDER BY last_name ASC";
    $results = $wpdb->get_results($query);

/*    echo "<pre>";
    print_r($results);
    echo "</pre>";*/

    //$nm = substr($u->display_name, 0, 6);
    $nm = strlen($u->display_name) > 6 ? substr($u->display_name,0,6)."..." : $u->display_name;
    $user .= "<div class='final_section'>
		<span style='font-size:17px;'>".$nm."</span><br>
		<a href='".wp_logout_url(get_permalink())."' class='logout'>Ausloggen</a>

	</div>";


    $user .= "<ul class='customer_list'>";

    $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","Ä","Ü","Ö");

    //$i=0;
    $valGrab = array();
    foreach ($results as $value) {
    	
    	$last_name=mb_substr($value->last_name, 0, 1, 'utf-8');
    	
    	//	$last_name=substr(utf8_encode($value->last_name), 0, 1);
    	//$user .= utf8_encode($last_name);
    	if(in_array($last_name,  $alpha)){
    		if(!in_array($last_name, $valGrab)){
				$user .= "<div><h5>".$last_name."</h5></div>";
				array_push($valGrab,$last_name);
			}
    		$user.="<li data-id='".$value->id."'><a href='#'>".$value->first_name.' '.$value->last_name."</a></li>";
    	}
    }

    $user .= "</ul>";

	$user .= "</div>";



	global $wpdb;
	$table_name1 = $wpdb->base_prefix.'anam_customer_info';
	$id = get_current_user_id();
	$query = "SELECT * FROM $table_name1 WHERE first_name!='' ORDER BY last_name ASC LIMIT 1";
	$results = $wpdb->get_results($query);

	/*echo "<pre>";
	print_r($results);
	echo '</pre>';*/


	$user .= "<div class='rightSide'> 
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
	  $query2 = "SELECT * FROM $table_name2 WHERE user_id='".$results[0]->id."'";
	  $documents = $wpdb->get_results($query2);

	if(empty($documents)){
		$u = wp_get_current_user();
	      if ( in_array( 'mitarbeiter', (array) $u->roles ) ) {

	      			$user .= "<div class='docbtncreate'>
	      				<a href='#' class='button create_doc' data-id='".$results[0]->id."'>Neue Anamnese erstellen </a>
	      			</div>";

	    }
	} 
	else{
		$user .= "<div class='docbtncreate'>
			<a href='#' class='button create_dokument' data-id='".$results[0]->id."'>Neue Dokumentation </a>
		</div>";

		/*$user .= "<div class='vwa'>
			<a href='#' class='button view_anamnesen'>Anamnese anzeigen </a>
		</div>";*/
	}


	
	$user .= '</div> <!-- 2-->';




	if(!empty($documents)){
		$user .= '<div id="anam_accordion" class="anam_bx doclist" >';
		foreach ($documents as $document) {
		    $user .= '<h3 class="doc"><span style="float:left !important;"><i class="fa fa-book"></i><a href="#" data-id="'.$document->id.'" user-id="'.$results[0]->id.'" class="doc_title"> Erstellt am '.date("d.m.Y H:i",strtotime($document->created_at)).'';
		    if(strtotime($document->created_at) != strtotime($document->updated_at) ){
		    	$user .='- '.date("d.m.Y H:i",strtotime($document->updated_at)).'';
		    }
		    $user .= '</a></span> <a href="#" class="edit_document" data-id="'.$document->id.'" ><i class="fa fa-edit"></i> Editieren </a></h3>';
		}
		$user .= '</div>';

	} else{
	  	$user .= "<p style='text-align:center;'>Keine frühere Anamnese gefunden! </p>";
	}

$table_name3 = $wpdb->base_prefix.'anam_dokument_info';
$id3 = get_current_user_id();
$query3 = "SELECT * FROM $table_name3 WHERE customer_id='".$results[0]->id."' ORDER BY created_at DESC";
$doks = $wpdb->get_results($query3);


//print_r($doks);

if(!empty($doks)){
$user .='<div id="anam_accordion" class="doks"><h4>Dokumentationen </h4><table border="1">';
foreach ($doks as $dok) {
	$user .='<tr user-id="'.$dok->customer_id.'">';
		if(strtotime($dok->created_at) != strtotime($dok->updated_at)){
			$user .='<td><span><a href="#" data-id="'.$dok->id.'" user-id="'.$dok->customer_id.'" class="dok_title">Updated - '.date("d.m.Y H:i",strtotime($dok->updated_at)).' </a></span></td>';
		}else{

			$user .='<td><span><a href="#" data-id="'.$dok->id.'" user-id="'.$dok->customer_id.'" class="dok_title"> Created '.date("d.m.Y",strtotime($dok->created_at)).' </a></span></td>';
		}

		$user .= '<td><span><a href="#" data-id="'.$dok->id.'"  user-id="'.$dok->customer_id.'" class="dok_title">'.$dok->treatment_name.'</a></span></td>';
		
		if($dok->status == "paid"){
			$user .= '<td><span> Status: <span class="dashicons dashicons-yes" style="color:#4ab516;"></span> </span></td>';
		}else{
			$user .= '<td><span> Status: <span class="dashicons dashicons-clock" style="color:#f7c631;font-size: 20px;margin-left: 7px;line-height: 26px;"></span> </span></td>';
		}


		$user .='<td><a href="#" class="edit_dokument" data-id="'.$dok->id.'"><i class="fa fa-edit"></i> Editieren </a></td>';

		$enroll_date = date("d-m-Y",strtotime($dok->created_at));
		$date1 = new DateTime($enroll_date);
		$date2 = new DateTime(date("d-m-Y"));
		$interval = $date1->diff($date2);
		if($interval->y == 10):

			$user .= '<td><a href="#" class="delete_dokument" data-id="'.$dok->id.'" ><i class="fa fa-trash"></i> Löschen </a></td>';
		endif;


		$user .= '</tr>';
}
$user.='</div></table>';

} else{
$user .="<p style='text-align:center;margin-top:50px;'>Keine Dokumentationen gefunden!	</p>";
}
$user .="</div>";

//print_r($doks);

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$query = "SELECT * FROM $table_name WHERE id='".$results[0]->id."'";
$res = $wpdb->get_results($query);
 


//echo $res[0]->created_at;
$register_date = date("d-m-Y",strtotime($res[0]->created_at));

$date1 = new DateTime($register_date);
$date2 = new DateTime(date("d-m-Y"));
$interval = $date1->diff($date2);

if($interval->y == 10):
	$user .= " <div class='delete_customer'><a href='#' class='button' data-id='".$results[0]->id."'> Patienten löschen </a></div>";
endif;


else:
	echo wp_login_form();
endif;

return $user;
}
add_shortcode("my-account", "anam_my_account_page");