<?php 

function king_flipbox_boxes_display($atts){


	extract(shortcode_atts(array(
      'post' => '',
    ), $atts));



	$box = "";
	$args = array("post_type" => "flipboxes", "posts_per_page" => -1, "p" => $post);
	$query = new WP_Query( $args );
	$box .= "<div class='KingBoxWrapper'>";
	if($query->have_posts()): while($query->have_posts()): $query->the_post();

		$Firstimage = get_the_post_thumbnail_url(get_the_ID());
		$ImgId = get_post_meta(get_the_ID(), 'second_featured_image', true);
		$SecondImage = wp_get_attachment_image_src($ImgId, 'full-size');

		$redirect_url = get_post_meta(get_the_ID(),"redirect_url", true);

		$box .= "<div class='box'>
				<a href='".$redirect_url."' target='_blank'>
					<div class='defaultImg bx' style='background-image:url(".$Firstimage.");'></div>
					<div class='hoverImg' style='background-image:url(".$SecondImage[0].");'></div> 
					<p>".get_the_title()."</p>
				</a>
		</div>";

	endwhile;
	endif;
	$box .= "</div>";

	return $box;

}
add_shortcode("flipBox","king_flipbox_boxes_display");