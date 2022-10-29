<?php 

function gg_product_list($product){


$product .= "<div class='gg_list_products'>";

$the_query = new WP_Query( array('posts_per_page'=>-1,
 'post_type'=>'products', 'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
); 
                            ?>
<?php while ($the_query -> have_posts()) : $the_query -> the_post(); 
$image =   $src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full', false );
//$desc = wp_trim_words(get_the_content(), 20, null );
$price = get_post_meta( get_the_ID(), '_product_price', true);
$product .= '<div class="gg-col"><img src="'.$image[0].'">';
$product .= '<a href="#" class="gg-product-title" target="_blank"> '.get_the_title().' </a>

<div class="gg-product-price"><span>Price:  '.$price.'</span></div>';


if(!is_user_logged_in()):
	$product .= ' <a href="'.get_site_url().'/account'.'" class="gg_buy_now NotloginDone">Buy Now </a>';
else:
	$product .= ' <a href="#" class="gg_buy_now loginDone" data-id="'.get_the_ID().'">Buy Now </a>';
endif;

$product .= '</div>';
endwhile;

$product .= "</div>";

return $product;

}
add_shortcode("product_list", "gg_product_list");