<?php 
// Creating the widget
class dp_widgets extends WP_Widget {
  
function __construct() {
parent::__construct(
  
// Base ID of your widget
'dp_widget',
  
// Widget name will appear in UI
__('Daily Posts Widget', 'dp_widget_domain'),
  
	// Widget description
	array( 'description' => __( 'You can set post and number to show post on top', 'dp_widget_domain' ), )
	);
}
  
// Creating widget front-end
  
public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );

	// before and after widget arguments are defined by themes
	echo $args['before_widget'];
	if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];
	//echo "HELLO WORLD";
?>

<form method="post" name="searchform" id="dp_searchForm">
	
	<input type="number" name="dp_search_number">
	<input type="submit" name="" value="Submit">

</form>

<div class="dp_response"></div>

<?php 
	echo $args['after_widget'];



}
          
// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
	$title = $instance[ 'title' ];
}
else {
	$title = __( 'Enter Widget Title', 'dp_widget_domain' );
}
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
 
// Class dp_widgets ends here
}
 
 
// Register and load the widget
function dp_load_widget() {
    register_widget( 'dp_widgets' );
}
add_action( 'widgets_init', 'dp_load_widget' );