<?php

function lps_show_list_of_products_in_carousel($product){
	
$args = array(
'post_type' => 'product',
'posts_per_page' =>-1,

);
$i = 1;
$j = 2;
$product .= "<div class='carousel-wrap'><div class='carousel_wrapper'><div class='jnav'><div class='leftLinks'><a href='#' class='jcarousel-control-prev'>&lsaquo;</a><a href='#' class='jcarousel-control-next'> &rsaquo;</a></div><div class='rightTitle'> <p>  מבצעי החודש     </p></div></div><ul class='owl-carousel owl-theme'>";
$product_query = new WP_Query($args);


if ($product_query->have_posts()): while ($product_query->have_posts()):
	$product_query->the_post();
	$pro = new WC_Product( get_the_ID() );
	$lps_show_hide_product = get_post_meta(get_the_ID(), 'lpc_show_hide_product',false);

	if($lps_show_hide_product[0] == "yes"){
	//	echo max($sum);
		$product_img = get_the_post_thumbnail_url(get_the_ID(),'full'); 
		if($i == 2 || $i==6 || $i==10 || $i == 14 || $i==18 || $i==22 || $i == 26 || $i==30 || $i==34 || $i == 38 || $i==42 || $i==46 || $i == 50 || $i==54 || $i==58 || $i == 62 || $i==66 || $i==70 || $i==74 || $i==78 || $i == 82 || $i==86 || $i==90 || $i == 94 || $i==98 || $i==102){
			$product .='<div class="small_product">';
		}
		$product .= '<li class="item"><a href="'.get_the_permalink().'" rel="lightbox"><img src="'.$product_img.'" ><div id="jmeta"><p>'.get_the_title().'</p><span id="myprice">'.$pro->get_price_html().'</span><a href="'.get_site_url().'/cart/?add-to-cart='.get_the_ID().'" class="single_add_to_cart_button button alt">  הוספה לסל  </a></div></a></li>';
		if($i == 3 || $i==7 || $i==11 || $i == 15 || $i==19 || $i==23 || $i == 27 || $i==31 || $i==35 || $i == 39 || $i==43 || $i==47 || $i == 51 || $i==55 || $i==59 || $i == 63 || $i==67 || $i==71 || $i==75 || $i==79 || $i == 83 || $i==87 || $i==91 || $i == 95 || $i==99 || $i==103){
			$product .= "</div>";
		}
		$i++;
	}else{
		echo "";
	}
	

endwhile;
wp_reset_postdata();
endif;



//print_r( $data );
$product .= '</ul></div>';
//$product .= '</ul></div></div></div>';
	return $product;
}
add_shortcode("liavCarousel","lps_show_list_of_products_in_carousel");