<?php 

add_action( 'add_meta_boxes_post','dp_week_of_year_number_metaboxes');
add_action( 'save_post_post', 'dp_week_of_year_number_save_post');  



function dp_week_of_year_number_metaboxes( ) {

   global $wp_meta_boxes;
   add_meta_box('dp_number', __('Week of Year Number'), 'dp_week_of_year_number_metaboxes_html','post','normal', 'high');

}



function dp_week_of_year_number_metaboxes_html()

{

    global $post;
    $custom = get_post_custom($post->ID);
    $dp_week_of_year_number = isset($custom["dp_week_of_year_number"][0])?$custom["dp_week_of_year_number"][0]:''; 
    $myRes = explode('-', $dp_week_of_year_number );

?>

    <input type="text" name="dp_week_of_year_number" value="<?php echo $myRes[0]; ?>" placeholder="Week of Year Number" style="width: 100%;border: 1px solid #ddd;padding: 6px;font-size: 16px;" id="dp_week_of_year_number" data-id="<?php echo $post->ID; ?>">

<?php
//echo $dp_week_of_year_number;

}



function dp_week_of_year_number_save_post()

{

    if(empty($_POST)) return; 
    global $post;
    $lan = pll_get_post_language($post->ID);

    $posts_with_meta = get_posts( array(
        'posts_per_page' => 1, 
        'meta_key' => 'dp_week_of_year_number',
        'meta_value' => $_POST["dp_week_of_year_number"].'-'.$lan,
        'fields' => 'ids', // we don't need it's content, etc.
    ));

    if ( count( $posts_with_meta ) ) {
            
        if($posts_with_meta[0] != $post->ID){
            wp_die("<h4> The (Week of Year Number) already exists.<a href='".$_SERVER['HTTP_REFERER']."'> Go Back </a></h4>");
        }else{
            update_post_meta($post->ID, "dp_week_of_year_number", $_POST["dp_week_of_year_number"].'-'.$lan);
        }

    }else{
        update_post_meta($post->ID, "dp_week_of_year_number", $_POST["dp_week_of_year_number"].'-'.$lan);
    }


    //update_post_meta($post->ID, "dp_week_of_year_number", $_POST["dp_week_of_year_number"].'-'.$lan);

}   

