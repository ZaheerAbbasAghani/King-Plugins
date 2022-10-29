<?php
add_action( 'wp_ajax_jf_store_and_response', 'jf_store_and_response' );
add_action( 'wp_ajax_nopriv_jf_store_and_response', 'jf_store_and_response' );

function jf_store_and_response() {
//global $wpdb; // this is how you get access to the database


echo "<div id='faqProducts'><h1> CONGRATULATIONS! YOU WON! </h1><p> You were selected to win a FREE T-Shirt of your choice! </p>";
//foreach ($info_new as $value) {
	$params = array(
    'post_type' => 'product',
    'posts_per_page' => 3,
    'orderby' => 'rand'

);
$wc_query = new WP_Query($params);
//global $post, $product;

if( $wc_query->have_posts() ) {

    while( $wc_query->have_posts() ) {

      $wc_query->the_post(); 

      $thumb = get_the_post_thumbnail_url(get_the_ID()); 
      ?>

      <div class="jfcolumn">
  		<img src="<?php echo $thumb; ?>">
     		<?php echo "<h3>".get_the_title()."</h3>"; ?>
     		<p><?php echo wp_trim_words(get_the_content(),40,'...'); ?></p>
     	<a href="<?php echo get_the_permalink().'checkout/?add-to-cart='.get_the_ID().' '; ?>" class="jf_view_more">Choose One</a>
  	</div>

    <?php 

    } // end while

} // end if
 else 
{
    echo "Sorry No result Found!";
}


	wp_die(); // this is required to terminate immediately and return a proper response
}