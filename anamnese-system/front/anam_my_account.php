<?php 
function anam_my_account_page($user){

if(is_user_logged_in()):
	$user .= "<div class='anam_wrapper'> 
	<div class='leftSide'>
	<h5>".__("All Customers")." </h5> <hr>
	<div class='frmSearch'>";
		
	$u = wp_get_current_user();
	if ( in_array( 'doctor', (array) $u->roles ) ) {
	$user .= "<div class='docbtncreatecustomer'>
	        <a href='#' class='new_customer'><i class='fa fa-user-plus' aria-hidden='true'></i><br> Add new customer </a>
	      </div>";
	}


	$user .= "<form method='post' action=''>
			<input type='search' name='search_customer' id='search_customer' placeholder='Search Customer'/>
		</form>
		<div id='suggesstion-box'></div>
	</div>
		";
	
	global $wpdb;
    $table_name = $wpdb->base_prefix.'anam_customer_info';
    $id = get_current_user_id();
    $query = "SELECT * FROM $table_name ORDER BY first_name ASC";
    $results = $wpdb->get_results($query);

   /* echo "<pre>";
    print_r($results);
    echo "</pre>";*/

    $user .= "<ul class='customer_list'>";

    $alpha = range("A", "Z");
    $i=0;
    $valGrab = array();
    foreach ($results as $value) {
    	$first_letter=substr($value->first_name, 0, 1);
    	if(in_array($first_letter,  $alpha)){
    		if(!in_array($first_letter, $valGrab)){
				$user .= "<div><h5>".$first_letter."</h5></div>";
				array_push($valGrab, $first_letter);
			}
    		$user.="<li data-id='".$value->id."'><a href='#'>".$value->first_name.' '.$value->last_name."</a></li>";
    	}
    }

    $user .= "</ul>";

	$user .= "</div>";



	global $wpdb;
	$table_name1 = $wpdb->base_prefix.'anam_customer_info';
	$id = get_current_user_id();
	$query = "SELECT * FROM $table_name1 WHERE first_name!='' ORDER BY first_name ASC LIMIT 1";
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
				<a href='#' class='button edit_customer' data-id='".$results[0]->id."'>Edit Customer</a>
			</div>
		</div>

		<div class='rightSideInner2'>
			<div class='doctitle'>
				<h4>Documentations</h4>
			</div>";

			$u = wp_get_current_user();
      if ( in_array( 'doctor', (array) $u->roles ) ) {

      			$user .= "<div class='docbtncreate'>
      				<a href='#' class='button create_doc' data-id='".$results[0]->id."'>Create new document </a>
      			</div>";

      }
	 $user .= '<div class="delete_customer"><a href="#" class="button" data-id="'.$results[0]->id.'"> Delete Customer </a></div>';
		$user .= '</div> <!-- 2-->';

	  global $wpdb;
	  $table_name2 = $wpdb->base_prefix.'anam_document_info';
	  $query2 = "SELECT * FROM $table_name2 WHERE user_id='".$results[0]->id."' ORDER BY created_at ASC";
	  $documents = $wpdb->get_results($query2);

	if(!empty($documents)){
		$user .= '<div id="anam_accordion">';
		foreach ($documents as $document) {
		    $user .= '<h3><span style="float:left !important;"><i class="fa fa-book"></i><a href="#" data-id="'.$document->id.'" user-id="'.$results[0]->id.'" class="doc_title"> Namnesis of the ['.date("d.m.Y H:i",strtotime($document->created_at)).'] </a></span> <a href="#" class="edit_document" data-id="'.$document->id.'" ><i class="fa fa-edit"></i> Edit </a></h3>';
		}

	} else{
	  	$user .= "<p style='text-align:center;'>No document found!</p>";
	}





  $user .= '</div>';

	$user .= "</div>";
else:
	echo wp_login_form();
endif;

return $user;
}
add_shortcode("my-account", "anam_my_account_page");