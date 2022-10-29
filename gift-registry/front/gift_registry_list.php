<?php

function gift_registry_list_process($list){


if(is_user_logged_in()):

	$list .= "<div class='gift_registry_list'>";

	global $wpdb;
	$table_name = $wpdb->base_prefix.'gft_gift_registry_records';

	$query = "SELECT * FROM $table_name where user_id='".get_current_user_id()."'";
	$query_results = $wpdb->get_results($query);

	if(!empty($query_results)){

		$list .= "<table border='1' style='text-align:center;border: 1px solid #eee;box-shadow: 1px 1px 5px #ddd;'>";

			$list .= "<tr> 
				<th> # </th>
				<th> Product Image </th>
				<th> Product Name </th>
				<th> Product Price </th>
				<th> Purchase Button </th>
			 </tr>";

			$i = 1;
			foreach ($query_results as $results) {
				    
				    $pro = wc_get_product($results->product_id);
					$thumb=get_the_post_thumbnail_url($results->product_id,'full');

					$list .= "<tr data-id='".$results->product_id."'> 
						<td> ".$i." </td>";
					

					if ($pro->get_type() == "simple") {
				       
				       $list .= "<td><img src='".$thumb."'></td>";
				    }
				    elseif($pro->get_type() == "variation"){

				    	$variation=new WC_Product_Variation( $results->product_id );
						$image_id = $variation->get_image_id();

						$image_array = wp_get_attachment_image_src($image_id, 'thumbnail');
							
						$image_src = $image_array[0];


					    
					   $list .= "<td><img src='".$image_src."'></td>";
				    }else{
				    	 $list .= '<td></td>';
				    }


					$list .= "<td> ".get_the_title($results->product_id)." </td>";
						
					if ($pro->get_type() == "simple") {
				        $sale_price     =  $pro->get_sale_price();
				        $regular_price  =  $pro->get_regular_price();

				        $list .= '<td>'.get_woocommerce_currency_symbol().''.$regular_price.'</td>';
				    }
				    elseif($pro->get_type() == "variation"){
					    
					    $sale_price     =  $pro->get_sale_price();
				        $regular_price  =  $pro->get_regular_price();
				        $list .= '<td>'.get_woocommerce_currency_symbol().''.$regular_price.'</td>';
				    }else{
				    	 $list .= '<td></td>';
				    }

					$list .= "<td> <a href='#' data-id='".$results->product_id."' class='button deleteProduct'> Delete </a> </td>
					 </tr>";
				$i++;
			}

		$list .= "</table>";
		$list .= "<a href='#' class='send_gift_registry_link'> Send Gift Registry Link </a> <br>";

	}else{
		//$list.="<p class='no_gift_registry'> No product added in gift registry.</p>";
	}

	$list .= "</div>";

else:

//$list .= "<a href='".get_site_url()."/my-account' class='loginUrlMessage'> Please login to view results </a>";

endif;




//if(is_user_logged_in()):

	$list .= "<div class='gift_registry_list'>";

	global $wpdb;
	$table_name2 = $wpdb->base_prefix.'gft_gift_registry_users';

	$user = get_user_by( 'id', get_current_user_id());

	//echo $user->id;

	$billing_email = $_GET['email'];
	$billing_phone = str_replace(' ', '',"+".$_GET['phone']);

	$query = "SELECT * FROM $table_name2 where user_email='$billing_email' AND user_phone='$billing_phone' ";
	$query_results = $wpdb->get_results($query);



	if(!empty($query_results)){

	$list .= "<table border='1' style='text-align:center;border: 1px solid #eee;box-shadow: 1px 1px 5px #ddd;'>";

			$list .= "<tr> 
				<th> # </th>
				<th> Product Image </th>
				<th> Product Name </th>
				<th> Product Price </th>
				<th> Purchase Button </th>
			 </tr>";

			$i = 1;
			foreach ($query_results as $results) {
				    
				    $pro = wc_get_product($results->product_id);
					$thumb=get_the_post_thumbnail_url($results->product_id,'full');

					$list .= "<tr data-id='".$results->product_id."'> 
						<td> ".$i." </td>";
					

					if ($pro->get_type() == "simple") {
				       
				       $list .= "<td><img src='".$thumb."'></td>";
				    }
				    elseif($pro->get_type() == "variation"){

				    	$variation=new WC_Product_Variation( $results->product_id );
						$image_tag = $variation->get_image();
					    
					   $list .= "<td>".$image_tag."</td>";
				    }else{
				    	 $list .= '<td></td>';
				    }


					$list .= "<td> ".get_the_title($results->product_id)." </td>";
						
					if ($pro->get_type() == "simple") {
				        $sale_price     =  $pro->get_sale_price();
				        $regular_price  =  $pro->get_regular_price();

				        $list .= '<td>'.get_woocommerce_currency_symbol().''.$regular_price.'</td>';
				    }
				    elseif($pro->get_type() == "variation"){
					    
					   $sale_price     =  $pro->get_sale_price();
				        $regular_price  =  $pro->get_regular_price();
				        $list .= '<td>'.get_woocommerce_currency_symbol().''.$regular_price.'</td>';
				    }else{
				    	 $list .= '<td></td>';
				    }


				    if(is_user_logged_in()): 

						$list .= "<td> <a href='".get_site_url()."/cart/?add-to-cart=".$results->product_id."&quantity=1' data-id='".$results->product_id."' class='button purchaseIt'> Purchase </a> </td>
						 </tr>";

					else:

						$list .= "<td> <a href='".get_site_url()."/my-account' class='button'> Purchase </a> </td>
						 </tr>";

					endif;

				$i++;
			}

		$list .= "</table>";
	
	}else{
		//$list.="<p class='no_gift_registry'> No product added in gift registry.</p>";
	}

	$list .= "</div>";

/*else:

$list .= "<a href='".get_site_url()."/my-account' class='loginUrlMessage'> Please login to view results </a>";

endif;*/



return $list;

}
add_shortcode( "gift_registry_list", "gift_registry_list_process");