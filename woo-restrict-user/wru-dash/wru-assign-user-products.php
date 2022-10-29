<?php 

function wru_search_user_by_name_email() {
   add_meta_box(
       'search_user_by_name_email',       // $id
       'Search User',                  // $title
       'wru_search_user_by_name_email_process',  // $callback
       'product',                 // $page
       'normal',                  // $context
       'high'                     // $priority
   );
}
add_action('add_meta_boxes', 'wru_search_user_by_name_email');

//showing custom form fields
function wru_search_user_by_name_email_process() {
    global $post;
    $handle=new WC_Product_Variable($post->ID);
    $variations1=$handle->get_children();
   

    echo '<div class="search_wrapper"><form method="post" action=""> <label>Search User: <input type="text" name="search_user" id="search_user" placeholder="Enter Username or email to search" post-id="'.$_GET['post'].'"></label>'; 
    echo "<label>Select Variation: <select id='select_variaton' name='select_variation' multiple>";
    foreach ($variations1 as $value) {
    $single_variation=new WC_Product_Variation($value);
    echo '<option  value="'.$value.'">'.implode(" / ", $single_variation->get_variation_attributes()).'-'.get_woocommerce_currency_symbol().$single_variation->price.'</option>';
    }
    echo "</select></label>";
    echo '<label>Select Access Date:<input type="text" id="select_access_date" name="select_access_date" placeholder="Select Date" ></label>';
    echo '</div></form>';
    echo "<h3>Approved User List for Product <hr></h3>";
    echo "<table id='UserListing'>
    <tr>
    	<th>ID</th><th>Name</th><th>Variation</th><th>Date</th><th>Action</th>
    <tr>";
  
    
    $post_id = $_GET['post'];

    $post = get_post_meta( $post->ID,'wru_approve_user', false );
    $access_date = get_post_meta( $post_id,'wru_till_dt', false );
    $variations = get_post_meta( $post_id,'wru_approve_variation', false );
	   $all_approved_data = get_post_meta( $post_id,'wru_all_approved_data', false );

	//print_r($access_date);

    $i=0;
    foreach ($post as $value) {
    //echo $access_date[0];
    $user_info = get_userdata($value);
    $full_name = $user_info->first_name.' '.$user_info->last_name;
    //echo $full_name;
    	echo "<tr><td>".$i."</td><td>".$full_name."</td><td id='vary'>";
      if(!empty($variations)){
      	//print_r($variations);
      	
      	//$variations = str_replace( "-", " ", $variations );
      	//print_r($repl);
      	$myArray = explode(',', $variations[$i]);
      	$list = implode(', ', $myArray);
      	// /echo $list;
      	$count = count($myArray);
    	for ($j=0; $j < $count; $j++) { 
      		$repl = preg_replace('/-[^-]*$/', '', $myArray[$j]);
      		$vary = wc_get_product($repl);
            echo '<p class="variations_list"> '.$vary->get_formatted_name().'</p>';
      	}
      }
      echo "</td><td>".date("d M, Y",$access_date[$i])."</td><td><a href='#' class='button button-default dlt_button' id='".$_GET['post']."' user-id='".$value."' approve-date='".$access_date[$i]."' variations='".$list."' all-user-data=''> Delete </a></td></tr>";
    $i++;
    }
    echo "</table>";

}