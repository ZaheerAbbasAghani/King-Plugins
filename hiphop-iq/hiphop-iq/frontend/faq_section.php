<?php 
function show_faq_section($atts){
$faq="";

$types = get_terms( 'types', array('hide_empty' => false) );
extract(shortcode_atts(array(
  'category' => $types[0]->slug,
), $atts));

$faq .= '<div class="fs-form-wrap clearfix" id="fs-form-wrap">
		
		<form id="myform" class="fs-form fs-form-full clearfix" autocomplete="off" method="post" action="">
			<ol class="fs-fields clearfix">';
				
$args = array('post_type' =>'faq','posts_per_page' => -1/*'tax_query' => array(
  array( 'taxonomy' => 'types', 'field' => 'slug', 'terms' => array( $category ) )
) */);
$the_query = new WP_Query( $args ); 

$j=1;
if ($the_query->have_posts()) :
while ($the_query->have_posts()) : $the_query->the_post();


//$answer1 = get_post_meta( $post->ID, 'answer1', true );
$faq.='<li data-input-trigger>
	<label class="fs-field-label fs-anim-upper" for="q" data-info="This will help us know what kind of service you need">'.get_the_title().'</label>
	<div class="fs-radio-group fs-radio-custom clearfix fs-anim-lower">';

	for ($i=0; $i <= 10; $i++) { 

		$answers = get_post_meta( get_the_ID(), "answer$i", true );
		$answer_icon_field = get_post_meta(get_the_ID(),"answer".$i."_icon_field",true); 
		if(!empty($answers)){
			//$diff = $j."_".$i;
			$faq.='<span><input id="q'.$j.'b" name="'.get_the_title().'" type="radio" value="'.$answers.'"/><label for="q'.$j.'b" class="radio-conversion"><i class="fa '.$answer_icon_field.'"></i> '.$answers.'</label></span>';
		}
	
	}


	$faq .= '</div>
</li>';
	
$j++;
endwhile;
endif;

/*$faq.='<li><label class="fs-field-label fs-anim-upper" for="q2" data-info="We won\'t send you spam, we promise...">What\'s your email address?</label><input class="fs-anim-lower" id="q2" name="email_field" type="email" placeholder="dean@road.us" required/></li>';*/


$faq.='</ol><!-- /fs-fields -->
			<button class="fs-submit" type="submit">Send answers</button>
		</form><!-- /fs-form -->
	</div><!-- /fs-form-wrap -->
';


global $woocommerce;
if(WC()->cart->cart_contents_count != 0){
    wp_safe_redirect( wc_get_checkout_url() );
}else{
	return $faq;
}	

}
add_shortcode("FAQ", "show_faq_section");

?>