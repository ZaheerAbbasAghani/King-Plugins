<?php 

function tks_show_product_list($tickets){

$tickets = "";

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1
    );
$loop = new WP_Query( $args );
if ( $loop->have_posts() ) {
	$tickets .= "<ul class='tksWrapper'>";
    while ( $loop->have_posts() ) : $loop->the_post();

    $price = get_post_meta( get_the_ID(), '_price', true );

    $tks_color_picker = get_post_meta( get_the_ID(), 'tks_color_picker', true ) == "" ? "#8b0000" : get_post_meta( get_the_ID(), 'tks_color_picker', true );

    $tickets .= "<li class='TKSBOX' style='background:".$tks_color_picker.";'><a href='".get_site_url().'/?add-to-cart='.get_the_ID()."&quantity=1'>

    	<h3>".get_the_title()."</h3>
    	<p>".$price.' '.get_woocommerce_currency_symbol()."</p>
    </a>
    </li>";

    endwhile;
    $tickets .= "</ul>";
} else {
    echo __( 'No products found' );
}
wp_reset_postdata();


return $tickets;


}
add_shortcode("product_list","tks_show_product_list");