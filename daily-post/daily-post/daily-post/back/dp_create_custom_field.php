<?php 

add_action( 'add_meta_boxes_post','dp_day_of_year_number_metaboxes');

	add_action( 'save_post', 'dp_day_of_year_number_save_post');  



function dp_day_of_year_number_metaboxes( ) {

   global $wp_meta_boxes;

   add_meta_box('dp_number', __('Day of Year Number'), 'dp_day_of_year_number_metaboxes_html','post','normal', 'high');

}



function dp_day_of_year_number_metaboxes_html()

{

    global $post;

    $custom = get_post_custom($post->ID);

    $dp_day_of_year_number = isset($custom["dp_day_of_year_number"][0])?$custom["dp_day_of_year_number"][0]:'';

?>

    <input type="number" name="dp_day_of_year_number" value="<?php echo $dp_day_of_year_number; ?>" placeholder="Day of Year Number" style="width: 100%;border: 1px solid #ddd;padding: 6px;font-size: 16px;" id="dp_day_of_year_number" data-id="<?php echo $post->ID; ?>">

<?php



 //echo date('z');



}



function dp_day_of_year_number_save_post()

{


    if(empty($_POST)) return; 
    global $post;
    $lan = pll_get_post_language($post->ID);

    $posts_with_meta = get_posts( array(
        'posts_per_page' => 1, 
        'meta_key' => 'dp_day_of_year_number',
        'meta_value' => $_POST["dp_day_of_year_number"],
        'fields' => 'ids', // we don't need it's content, etc.
    ));

    if ( count( $posts_with_meta ) ) {
            
        if($posts_with_meta[0] != $post->ID){
            wp_die("<h4> The (Day of Year Number) already exists.<a href='".$_SERVER['HTTP_REFERER']."'> Go Back </a></h4>");
        }elseif($_POST["dp_day_of_year_number"] > 365){
            wp_die("<h4> There are 365 days in year. <a href='".$_SERVER['HTTP_REFERER']."'> Go Back </a></h4>");
        }
        else{
            update_post_meta($post->ID, "dp_day_of_year_number", $_POST["dp_day_of_year_number"]);
        }

    }else{
        update_post_meta($post->ID, "dp_day_of_year_number", $_POST["dp_day_of_year_number"]);
    }


}   

