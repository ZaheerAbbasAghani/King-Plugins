<?php

function dix_items_slider($slider){
global $wpdb; 
$table_name = $wpdb->base_prefix.'dix_imported_data';
$slider = "";
$query = "SELECT * FROM $table_name";
$query_results = $wpdb->get_results($query, ARRAY_A);

$slider = "";
if(count($query_results) != 0) {



	$slider .= '<nav class="owl-filter-bar">
	   
	    <a href="#" class="item" id="newbestSeller" data-owl-filter=".'.sanitize_title("New & Best Seller").'">SALES & BEST SELLERS</a>
	    <a href="#" class="item" data-owl-filter=".'.sanitize_title("GOLD").'">GOLD</a>
	    <a href="#" class="item" data-owl-filter=".'.sanitize_title("SILVER").'">SILVER</a>
	    <a href="#" class="item" data-owl-filter=".'.sanitize_title("PLATINUM").'">PLATINUM</a>
	    <a href="#" class="item" data-owl-filter=".'.sanitize_title("COLLECTIBLES").'">COLLECTIBLES</a>
	    <a href="#" class="item" data-owl-filter=".'.sanitize_title("RARE EARTHS").'">RARE EARTHS</a>

	    ';

	$slider .= '</nav>';



	$slider .= '<div class="owl-carousel">';
	foreach ($query_results as $key => $value) {


		$slider .= ' <div class="dixBox '.sanitize_title($value['item_type']).'"> <div class="buyNow"><a href="'.$value['item_url'].'" class="buttonbtn">Buy Now</a></div><img src="'.$value['item_image_url'].'"><div class="detail-box"><a href="'.$value['item_url'].'">'.$value['item_name'].'</a><h2 id="'.$value['item_id'].'" class="item_price"></h2> <p>'.$value['item_type'].'</p>  </div>';

			if(!empty($value['item_badge']) && $value['item_badge'] != "-"){

				if($value['item_badge'] == "Sale"){
					$slider	.= '<div class="arrow-right" style="background-color:#c00000;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "New"){
					$slider	.= '<div class="arrow-right" style="background-color:#bf9000;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "Best Price"){
					$slider	.= '<div class="arrow-right" style="background-color:#002060;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "Top Seller"){
					$slider	.= '<div class="arrow-right" style="background-color:#548235;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}

				elseif($value['item_badge'] == "Low Stock"){
					$slider	.= '<div class="arrow-right" style="background-color:#767171;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}else{
					$slider	.= '<div class="arrow-right" style="background-color:#c00000;">
						<span>'.$value['item_badge'].'</span>
					</div>';
				}
			}

		$slider	.= '</div>';
	}
	$slider .= '</div>';

}

return $slider;
}
add_shortcode("dixSlider", "dix_items_slider");