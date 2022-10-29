<?php 

function dix_search_results($results){


	global $wpdb; 


	$table_name = $wpdb->base_prefix.'dix_imported_data';

	$search_text = "%" . $_GET['dixQuery'] . "%";

    $sql = $wpdb->prepare( 
        "SELECT * FROM $table_name WHERE item_name LIKE ('%s') OR item_type LIKE ('%s') OR item_badge LIKE ('%s') ", 
        $search_text,
        $search_text,
        $search_text
    );


    $query_results = $wpdb->get_results( $sql , ARRAY_A );

	$results = "";

	$language = $_COOKIE['selected_language'];
		
	$results .= "<div class='dixGridWrapper'>";

	if(!empty($query_results)){

		
		if($language == "USDC"){
			foreach ($query_results as $key => $value) {

			$results .= ' <div class="dixBox '.sanitize_title($value['item_type']).'"> <div class="buyNow"><a href="'.$value['item_url'].'" class="buttonbtn">Buy Now</a></div><img src="'.$value['item_image_url'].'"><div class="detail-box"><a href="'.$value['item_url'].'">'.$value['item_name'].'</a><h2 id="'.$value['item_id'].'" class="item_price"></h2> <p>'.$value['item_type'].'</p>  </div>';

			if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

				if($value['item_badge'] == "Sale"){
					$results	.= '<div class="arrow-right" style="background-color:#c00000;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "New"){
					$results	.= '<div class="arrow-right" style="background-color:#bf9000;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "Best Price"){
					$results	.= '<div class="arrow-right" style="background-color:#002060;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "Top Seller"){
					$results	.= '<div class="arrow-right" style="background-color:#548235;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "Low Stock"){
					$results	.= '<div class="arrow-right" style="background-color:#767171;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}else{
					$results	.= '<div class="arrow-right" style="background-color:#c00000;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}
			}

		$results	.= '</div>';
				
			/*	$results .= "<div class='dixBoxSearch'> 
					
						<div class='dixLeftSide' style='background-image:url(".$value['item_image_url']."); height:150px; width:150px; background-size:100% 100%;float:left;margin-top:40px;'>
							<div class='buyNow'>
								<a href='".$value['item_url']."' class='buttonbtn'>Buy Now</a>
							</div>
						</div>
						<a href='".$value['item_url']."' target='_blank'> 
							<div class='dixRightSide' style='float:left;width:78%;margin-top:60px;'> <ul> 
								<li> ".$value['item_name']." </li>
								<li id='".$value['item_id']."' class='item_price' style='opacity: 1;'>".$value['item_price']."</li>
								<li> ".$value['item_type']." </li>";

						if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

							if($value['item_badge'] == "Sale"){
								$results .= '<div class="arrow-right" style="background-color:#c00000;">
									<span>'.$value['item_badge'].'</span>
								</div>';
							}

							elseif($value['item_badge'] == "New"){
								$results .= '<div class="arrow-right" style="background-color:#bf9000;">
									<span>'.$value['item_badge'].'</span>
								</div>';
							}

							elseif($value['item_badge'] == "Best Price"){
								$results .= '<div class="arrow-right" style="background-color:#002060;">
									<span>'.$value['item_badge'].'</span>
								</div>';
							}

							elseif($value['item_badge'] == "Top Seller"){
								$results .= '<div class="arrow-right" style="background-color:#548235;">
									<span>'.$value['item_badge'].'</span>
								</div>';
							}

							elseif($value['item_badge'] == "Low Stock"){
								$results .= '<div class="arrow-right" style="background-color:#767171;">
									<span>'.$value['item_badge'].'</span>
								</div>';
							}else{
								$results .= '<div class="arrow-right" style="background-color:#c00000;">
									<span>'.$value['item_badge'].'</span>
								</div>';
							}
						}



				$results .= "</ul></div>
						</a>
				</div>";*/

			}
			
		}
		
		elseif ($language == "EUROC") {
			$url = "https://eur.indigopreciousmetals.com";
			foreach ($query_results as $key => $value) {
				$d = explode("/", $value['item_url']);

				$results .= ' <div class="dixBox '.sanitize_title($value['item_type']).'"> <div class="buyNow"><a href="'.$value['item_url'].'" class="buttonbtn">Buy Now</a></div><img src="'.$value['item_image_url'].'"><div class="detail-box"><a href="'.$value['item_url'].'">'.$value['item_name'].'</a><h2 id="'.$value['item_id'].'" class="item_price"></h2> <p>'.$value['item_type'].'</p>  </div>';

				if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

					if($value['item_badge'] == "Sale"){
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "New"){
						$results	.= '<div class="arrow-right" style="background-color:#bf9000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Best Price"){
						$results	.= '<div class="arrow-right" style="background-color:#002060;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Top Seller"){
						$results	.= '<div class="arrow-right" style="background-color:#548235;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Low Stock"){
						$results	.= '<div class="arrow-right" style="background-color:#767171;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}else{
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}
				}

				$results	.= '</div>';


			}
		}

		elseif ($language == "SGDC") {
			$url = "https://sgd.indigopreciousmetals.com";
			foreach ($query_results as $key => $value) {

				$d = explode("/", $value['item_url']);

				$results .= ' <div class="dixBox '.sanitize_title($value['item_type']).'"> <div class="buyNow"><a href="'.$value['item_url'].'" class="buttonbtn">Buy Now</a></div><img src="'.$value['item_image_url'].'"><div class="detail-box"><a href="'.$value['item_url'].'">'.$value['item_name'].'</a><h2 id="'.$value['item_id'].'" class="item_price"></h2> <p>'.$value['item_type'].'</p>  </div>';

				if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

					if($value['item_badge'] == "Sale"){
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "New"){
						$results	.= '<div class="arrow-right" style="background-color:#bf9000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Best Price"){
						$results	.= '<div class="arrow-right" style="background-color:#002060;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Top Seller"){
						$results	.= '<div class="arrow-right" style="background-color:#548235;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Low Stock"){
						$results	.= '<div class="arrow-right" style="background-color:#767171;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}else{
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}
				}

				$results	.= '</div>';
			}
		}

		elseif ($language == "MYRC") {
			$url = "https://myr.indigopreciousmetals.com";
			foreach ($query_results as $key => $value) {

				$d = explode("/", $value['item_url']);

				$results .= ' <div class="dixBox '.sanitize_title($value['item_type']).'"> <div class="buyNow"><a href="'.$value['item_url'].'" class="buttonbtn">Buy Now</a></div><img src="'.$value['item_image_url'].'"><div class="detail-box"><a href="'.$value['item_url'].'">'.$value['item_name'].'</a><h2 id="'.$value['item_id'].'" class="item_price"></h2> <p>'.$value['item_type'].'</p>  </div>';

				if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

					if($value['item_badge'] == "Sale"){
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "New"){
						$results	.= '<div class="arrow-right" style="background-color:#bf9000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Best Price"){
						$results	.= '<div class="arrow-right" style="background-color:#002060;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Top Seller"){
						$results	.= '<div class="arrow-right" style="background-color:#548235;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Low Stock"){
						$results	.= '<div class="arrow-right" style="background-color:#767171;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}else{
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}
				}

				$results	.= '</div>';
			}
		}

		elseif ($language == "GBPC") {
			$url = "https://gbp.indigopreciousmetals.com";
			foreach ($query_results as $key => $value) {

				$d = explode("/", $value['item_url']);

				
				$results .= ' <div class="dixBox '.sanitize_title($value['item_type']).'"> <div class="buyNow"><a href="'.$value['item_url'].'" class="buttonbtn">Buy Now</a></div><img src="'.$value['item_image_url'].'"><div class="detail-box"><a href="'.$value['item_url'].'">'.$value['item_name'].'</a><h2 id="'.$value['item_id'].'" class="item_price"></h2> <p>'.$value['item_type'].'</p>  </div>';

				if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

					if($value['item_badge'] == "Sale"){
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "New"){
						$results	.= '<div class="arrow-right" style="background-color:#bf9000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Best Price"){
						$results	.= '<div class="arrow-right" style="background-color:#002060;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Top Seller"){
						$results	.= '<div class="arrow-right" style="background-color:#548235;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Low Stock"){
						$results	.= '<div class="arrow-right" style="background-color:#767171;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}else{
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}
				}

				$results	.= '</div>';
			}
		}

		else{
			foreach ($query_results as $key => $value) {
			
				$results .= ' <div class="dixBox '.sanitize_title($value['item_type']).'"> <div class="buyNow"><a href="'.$value['item_url'].'" class="buttonbtn">Buy Now</a></div><img src="'.$value['item_image_url'].'"><div class="detail-box"><a href="'.$value['item_url'].'">'.$value['item_name'].'</a><h2 id="'.$value['item_id'].'" class="item_price"></h2> <p>'.$value['item_type'].'</p>  </div>';

				if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

					if($value['item_badge'] == "Sale"){
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "New"){
						$results	.= '<div class="arrow-right" style="background-color:#bf9000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Best Price"){
						$results	.= '<div class="arrow-right" style="background-color:#002060;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Top Seller"){
						$results	.= '<div class="arrow-right" style="background-color:#548235;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}

					elseif($value['item_badge'] == "Low Stock"){
						$results	.= '<div class="arrow-right" style="background-color:#767171;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}else{
						$results	.= '<div class="arrow-right" style="background-color:#c00000;">
							<span>'.$value['item_badge'].'</span>
						</div>';
					}
				}

				$results	.= '</div>';
			}
		}
	}else{
		$results .= "<p style='font-size: 20px;'>  Try again! No result found for <b>".$_GET['dixQuery']."</b> </p>";
	}



	$results .= "</div>";

	return $results;
}
add_shortcode("dixSearchReults", "dix_search_results");