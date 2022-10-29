<?php 

add_action( 'wp_ajax_wep_submit_form', 'wep_submit_form' );
add_action( 'wp_ajax_nopriv_wep_submit_form', 'wep_submit_form' );
function wep_submit_form() {
	global $wpdb;

	parse_str($_POST['formData'], $data);


$names = explode("_", $data['names']);

// First Person Data
$Key = 'names';
$firstPerson = [];

foreach($data as $key => $value) 
{   
  if($key == $Key) break;
  $firstPerson[$key] = $value;
}

// Second Person Data
$secondPerson = [];
foreach ($data as $key => $value) {
	if(!in_array($value, $firstPerson)){
		array_push($secondPerson, $value);
	}
}

// All Woocommerce Tags
$alltags = array();
$terms = get_terms( 'product_tag' );
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
    		array_push($alltags, $term->slug);
    }
}

// First User

$TagsUserSelected1 = array();

// Match Woocommerce Tags with User Selected Tags
foreach ($firstPerson as $key => $person) {
		if(is_array($person)){
			foreach($person as $p){
				if(in_array($p, $alltags)){
        		array_push($TagsUserSelected1, $p);
        }
			}

		}else{
				if(in_array($person, $alltags)){
	        array_push($TagsUserSelected1, $person);
	      }
		} // end else
}


//print_r($TagsUserSelected1);


if(!empty($TagsUserSelected1)){

	echo "<div class='searchWrap'><div class='message'><h4> <b> Congratulations </b> <br> We want to offer you a $5 discount on the total cost of $50 or more just for trying out our Gift finder. We’ve gathered the following items based off your Geek Passions Gifts Gift Finder answers you’ve submitted </h4></div>
		<h3> Gift Finder Results for: [".$names[0]."] </h3>";

		echo '<div id="owl-example-1" class="owl-carousel owl-theme owl-centered">';
			
			$args = array( 
					'post_type' => 'product', 
					'posts_per_page' => -1,
					'product_tag' => $TagsUserSelected1,
					'orderby' =>'date',
					'order' => 'ASC' 
			);

			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
			global $product; 

			$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 

			echo '<div>
				<a href="'.get_the_permalink().'"> 
				<div class="featuredImage"> <img src="'.$featured_img_url.'"> </div>
				<p>'.get_the_title().'</p>
				</a>';

				$product = wc_get_product(get_the_ID());

				if( $product->is_type( 'simple' ) ){
				   
				   	echo '<a href="'.get_site_url().'/cart/?add-to-cart='.get_the_ID().'&quantity=1" class="button">Add to cart</a>';

				   	echo '<a href="#" class="button fa-gift" data-id="'.get_the_ID().'">Add to gift registry</a>';

				   	echo do_shortcode('[yith_wcwl_add_to_wishlist link_classes="button" icon="" product_id="'.get_the_ID().'"]');

				   	echo '<a href="'.get_site_url().'/checkout/?add-to-cart='.get_the_ID().'&quantity=1" class="button">Pay Now</a>';


				} elseif( $product->is_type( 'variable' ) ){
					
					$variations = $product->get_available_variations();
					$variations_id = wp_list_pluck( $variations, 'variation_id' );
					$pid = $variations_id == "" ? get_the_ID() : $variations_id;

				  
				  echo '<a href="'.get_site_url().'/cart/?add-to-cart='.$pid[0].'&quantity=1" class="button">Add to cart</a>	';

				  echo '<a href="#" class="button fa-gift" data-id="'.$pid[0].'">Add to gift registry</a>	';

				  echo do_shortcode('[yith_wcwl_add_to_wishlist link_classes="button" icon="" product_id="'.$pid[0].'"]');

				  echo '<a href="'.get_site_url().'/checkout/?add-to-cart='.$pid[0].'&quantity=1" class="button">Pay Now</a>';

				}
				

			echo '</div>';

			endwhile;
			wp_reset_query(); 

		echo "</div>";
		echo "</div>";

}


// Second User

$TagsUserSelected2 = array();

// Match Woocommerce Tags with User Selected Tags
foreach ($secondPerson as $key => $person2) {
		if(is_array($person2)){
			foreach($person2 as $p2){
				if(in_array($p2, $alltags)){
        		array_push($TagsUserSelected2, $p2);
        }
			}

		}else{
				if(in_array($person2, $alltags)){
	        array_push($TagsUserSelected2, $person2);
	      }
		} // end else
}


if(!empty($TagsUserSelected2)){

	echo "<div class='searchWrap'><h3> Gift Finder Results for: [".$names[1]."] </h3>";

		echo '<div id="owl-example-2" class="owl-carousel owl-theme owl-centered">';
			
			$args = array( 
					'post_type' => 'product', 
					'posts_per_page' => -1,
					'product_tag' => $TagsUserSelected2,
					'orderby' =>'date',
					'order' => 'ASC' 
			);

			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
			global $product; 

			$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 

			echo '<div>
				<a href="'.get_the_permalink().'"> 
				<div class="featuredImage"> <img src="'.$featured_img_url.'"> </div>
				<p>'.get_the_title().'</p>
				</a>';

			$product = wc_get_product(get_the_ID());

				if( $product->is_type( 'simple' ) ){
				   
				   	echo '<a href="'.get_site_url().'/cart/?add-to-cart='.get_the_ID().'&quantity=1" class="button">Add to cart</a>';

				   	echo '<a href="#" class="button fa-gift" data-id="'.get_the_ID().'">Add to gift registry</a>';

				   	echo do_shortcode('[yith_wcwl_add_to_wishlist link_classes="button" icon="" product_id="'.get_the_ID().'"]');

				   	echo '<a href="'.get_site_url().'/checkout/?add-to-cart='.get_the_ID().'&quantity=1" class="button">Pay Now</a>';


				} elseif( $product->is_type( 'variable' ) ){
					
					$variations = $product->get_available_variations();
					$variations_id = wp_list_pluck( $variations, 'variation_id' );
					$pid = $variations_id == "" ? get_the_ID() : $variations_id;

				  
				  echo '<a href="'.get_site_url().'/cart/?add-to-cart='.$pid[0].'&quantity=1" class="button">Add to cart</a>	';

				  echo '<a href="#" class="button fa-gift" data-id="'.$pid[0].'">Add to gift registry</a>	';

				  echo do_shortcode('[yith_wcwl_add_to_wishlist link_classes="button" icon="" product_id="'.$pid[0].'"]');

				  echo '<a href="'.get_site_url().'/checkout/?add-to-cart='.$pid[0].'&quantity=1" class="button">Pay Now</a>';

				}


			echo '</div>';

			endwhile;
			wp_reset_query(); 

		echo "</div>";
		echo "</div>";

}

$cookie_name = "gft_discount";
$cookie_value = 5;

if(!isset($_COOKIE[$cookie_name]) && !isset($_COOKIE['discountDone'])){
	setcookie($cookie_name, $cookie_value,  time() + (10 * 365 * 24 * 60 * 60), '/');
}


	wp_die();
}