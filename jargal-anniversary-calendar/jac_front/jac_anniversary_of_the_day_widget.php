<?php 
// Creating the widget 
class wpb_widget extends WP_Widget {
  
function __construct() {
	parent::__construct(
	  
	// Base ID of your widget
	'wpb_widget', 
	  
	// Widget name will appear in UI
	__('Anniversay of the day', 'jac_widget_domain'), 
	  
	// Widget description
	array( 'description' => __( 'Display anniversay of the day post.', 'jac_widget_domain' ), ) 
	);
}
  
// Creating widget front-end
  
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
  
// before and after widget arguments are defined by themes
//echo $args['before_widget'];
//if ( ! empty( $title ) )
//echo $args['before_title'] . $title . $args['after_title'];
  
// This is where you run the code and display the output
//echo __( 'Hello, World!', 'jac_widget_domain' );

echo '<div id="post-wrapper" class="post-wrapper clearfix jac_widget_posts">';
$args = array(
	'post_type' => 'post',
	/*'meta_query' => array(
        array(
            'key'     => 'rudr_noindex',
            'value'   => 'on',
        ),
    ),*/
	'posts_per_page' =>-1
);
$i = 0;
$event_query = new WP_Query($args);


if($event_query->have_posts()): while($event_query->have_posts()):
	$event_query->the_post();
	$enable_disable_field = get_post_meta(get_the_ID(), 'rudr_noindex',false);
		
		//print_r($enable_disable_field);


	if($enable_disable_field[0]=="on"){
		//unstick_post( get_the_ID() );
		echo "";
	}else{
		//print_r($enable_disable_field);

		$post_date = get_the_date("m-d");
		$current_date = date("m-d");
		//echo $enable_disable_field[0];
		if($current_date == $post_date ){  ?>

<div class="post-column clearfix">

	<article id="post-<?php the_ID(); ?>" <?php post_class(array('sticky')); ?>>
				<?php wellington_post_image_archives(); ?>
		<header class="entry-header">

			<?php wellington_entry_meta(); ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .entry-header -->



		
		<div class="entry-content entry-excerpt clearfix">
			<?php //the_excerpt(); ?>
			<?php wellington_more_link(); ?>
		</div><!-- .entry-content -->

		


	</article>

</div>

<?php 
		}
	}	
	$i++;
endwhile;
wp_reset_postdata();
endif;

echo '</div>';


//echo $args['after_widget'];
}
          
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'jac_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
      
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
 
// Class wpb_widget ends here
} 
 
 
// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );	

/*
$args = array(
	'post_type' => 'post',
	'posts_per_page' =>-1,
);
$i = 0;
$event_query = new WP_Query($args);
//print_r($event_query);
if($event_query->have_posts()): while($event_query->have_posts()):
	$event_query->the_post();
	$enable_disable_field = get_post_meta(get_the_ID(), 'rudr_noindex',false);
	if($enable_disable_field[$i]=="on"){
		unstick_post( get_the_ID() );
	}else{
		$post_date = get_the_date("m-d");
		$current_date = date("m-d");
		
		if($current_date == $post_date){
			stick_post( get_the_ID() );
		}else{
			unstick_post( get_the_ID() );
		}
	}	
	$i++;
endwhile;
wp_reset_postdata();
endif;

}
*/