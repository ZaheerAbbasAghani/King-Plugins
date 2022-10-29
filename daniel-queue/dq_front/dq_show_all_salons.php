<?php 



function dq_show_all_salons($data){

$args = array(
'post_type' => 'salons',
'post_status'=>'publish', 
'posts_per_page'=>-1
);



$query = new WP_Query($args);

if($query->have_posts()): while($query->have_posts()): $query->the_post();

	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 

	$address = get_post_meta(get_the_ID(),'salon_address',true);

	$data .= "<div class='dq_my_salon'><div class='my_mini_salon'>

				<img src='".$featured_img_url."'> 

				<p><b>Salon Name: </b><a href='".get_the_permalink()."'> ".get_the_title()."</a></p>

				<p><b>Description: </b>".get_the_content()."</p>

				<p><b>Address: </b>".$address."</p></div>";

				$user_queue = get_post_meta(get_the_ID(),"user_in_queue",false);

				if(is_user_logged_in()){

					if(in_array(get_current_user_id(), $user_queue)){

						$data .= "<div class='delete_queue'><button class='delete_queue_btn' user_id='".get_current_user_id()."' salon_id='".get_the_id()."'> Leave Queue </button></div>";

					}else{

						$data .= "<div class='join_queue'><button class='join_queue_btn' user_id='".get_current_user_id()."' salon_id='".get_the_id()."'> Join Queue </button></div>";

					}

				}else{

					$data .= "<div class='join_queue'><a href='".get_site_url()."/signin' id='login' class='search-submit'> Login </a></div>";
					$data .= "<div class='join_queue'><a href='".get_site_url()."/registration' id='login' class='search-submit'> Register </a></div>";

				}

				

			$data .="</div>";



endwhile;

else:

	$data .= "<p>No salon found!</p>";

endif;





return $data;



}



add_shortcode( "all_salons", "dq_show_all_salons");